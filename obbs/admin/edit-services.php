<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['editid'])) {
        $editid = intval($_GET['editid']);
    } else {
        header('Location: manage-services.php');
        exit();
    }

    // Retrieve existing service information
    $sql = "SELECT * FROM tblservice WHERE ID = :editid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':editid', $editid, PDO::PARAM_INT);
    $query->execute();
    $service = $query->fetch(PDO::FETCH_OBJ);

    // Check if the service exists
    if (!$service) {
        header('Location: manage-services.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $service_id = $_POST['service_id'];
        $service_name = $_POST['service_name'];
        $service_author = $_POST['service_author'];
        $service_category = $_POST['service_category'];
        $service_des = $_POST['service_des'];
        $service_price = $_POST['service_price'];
        $service_aval = $_POST['service_aval'];

        $targetFile = '';

        if (isset($_FILES["service_image"]) && $_FILES["service_image"]["size"] > 0) {
            $targetDirectory = "uploads/";
            $targetFile = $targetDirectory . basename($_FILES["service_image"]["name"]);

            // Check if the file is an actual image
            $check = getimagesize($_FILES["service_image"]["tmp_name"]);
            if ($check !== false) {
                // File is an image
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $targetFile)) {
                    // File uploaded successfully
                } else {
                    echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
                }
            } else {
                echo '<script>alert("File is not an image.");</script>';
            }
        }

        // Update service details in the database
        $sql = "UPDATE tblservice SET ServiceName = :service_name, ServiceAuthor = :service_author, ServiceImage = :service_image, SerDes = :service_des, ServicePrice = :service_price, SerAvailable = :service_aval, CategoryID = :service_category WHERE ID = :service_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':service_id', $service_id, PDO::PARAM_INT);
        $query->bindParam(':service_name', $service_name, PDO::PARAM_STR);
        $query->bindParam(':service_author', $service_author, PDO::PARAM_STR);
        $query->bindParam(':service_image', $targetFile, PDO::PARAM_STR);
        $query->bindParam(':service_des', $service_des, PDO::PARAM_STR);
        $query->bindParam(':service_price', $service_price, PDO::PARAM_STR);
        $query->bindParam(':service_aval', $service_aval, PDO::PARAM_STR);
        $query->bindParam(':service_category', $service_category, PDO::PARAM_STR);

        $query->execute();

        if ($query) {
            echo "<script>alert('Service details updated successfully');</script>";
            echo "<script>window.location.href = 'manage-services.php'</script>";
        } else {
            echo "<script>alert('Failed to update service details');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book Cafe - Edit Services</title>
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Edit Book | Services</h2>

                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Book Details</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <form method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="service_id" value="<?php echo $service->ID; ?>">
                            <div class="form-group">
                                <label for="book_name">Book Name</label>
                                <input type="text" id="book_name" name="service_name" class="form-control" value="<?php echo $service->ServiceName; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="service_image">Service Image</label>
                                <input type="file" id="service_image" name="service_image" class="form-control" required>
                                <br>
                                <!-- <label>Current Image:</label> -->
                                <?php
                                    if (!empty($service->ServiceImage)) {
                                        echo '<img src="' . $service->ServiceImage . '" alt="Service Image" style="max-width: 200px; max-height: 200px;">';
                                    } else {
                                        echo 'No image available';
                                    }
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="book_author">Book Author</label>
                                <input type="text" id="book_author" name="service_author" class="form-control" value="<?php echo $service->ServiceAuthor; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="book_description">Book Description</label>
                                <textarea id="book_description" name="service_des" class="form-control" required><?php echo $service->SerDes; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="book_description"> Category</label>
                                <select type="text" class="form-control" name="service_category" required="true">
                                    <option value="">Choose Book category</option>
                                    <?php 
                                    $sql2 = "SELECT * FROM tblcategory";
                                    $query2 = $dbh->prepare($sql2);
                                    $query2->execute();
                                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                    foreach($result2 as $row) { ?>
                                        <option value="<?php echo htmlentities($row->ID);?>" <?php if ($row->ID == $service->CategoryID) echo 'selected'; ?>><?php echo htmlentities($row->CategoryName);?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="book_aval">Book Availability</label>
                                <input type="text" id="book_aval" name="service_aval" class="form-control" value="<?php echo $service->SerAvailable; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="book_price">Book Price</label>
                                <input type="text" id="book_price" name="service_price" class="form-control" value="<?php echo $service->ServicePrice; ?>" required>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Update Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/core/jquery.slimscroll.min.js"></script>
    <script src="assets/js/core/jquery.scrollLock.min.js"></script>
    <script src="assets/js/core/jquery.appear.min.js"></script>
    <script src="assets/js/core/jquery.countTo.min.js"></script>
    <script src="assets/js/core/js.cookie.min.js"></script>
    <script src="assets/js/codebase.js"></script>
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/pages/be_tables_datatables.js"></script>
</body>
</html>
