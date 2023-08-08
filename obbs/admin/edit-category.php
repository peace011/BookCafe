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
        header('Location: manage-category.php');
        exit();
    }

    // Retrieve existing book information
    $sql = "SELECT * FROM tblcategory WHERE ID = :editid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':editid', $editid, PDO::PARAM_INT);
    $query->execute();
    $category = $query->fetch(PDO::FETCH_OBJ);

    // Check if the book exists
    if (!$category) {
        header('Location: manage-category.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $categoryid = $_POST['categoryid'];
        $categoryname = $_POST['categoryname'];
   
        // Update book details in the database
        $sql = "UPDATE tblcategory SET CategoryName = :categoryname WHERE ID = :categoryid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':categoryid', $categoryid, PDO::PARAM_INT);
        $query->bindParam(':categoryname', $categoryname, PDO::PARAM_STR);
  

        $query->execute();

        if ($query) {
            echo "<script>alert('Category details updated successfully');</script>";
            echo "<script>window.location.href = 'manage-category.php'</script>";
        } else {
            echo "<script>alert('Failed to update category details');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book Cafe - Edit Category</title>
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Edit Category</h2>

                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Category Details</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <form method="post" action="">
                            <input type="hidden" name="categoryid" value="<?php echo $category->ID; ?>">
                            <div class="form-group">
                                <label for="book_name">Category Name</label>
                                <input type="text" id="book_name" name="categoryname" class="form-control" value="<?php echo $category->CategoryName; ?>" required>
                            </div>


                          

                            

                            <button type="submit" name="submit" class="btn btn-primary">Update Category</button>
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
