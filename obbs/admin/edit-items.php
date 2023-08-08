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
        header('Location: manage-items.php');
        exit();
    }

    // Retrieve existing book information
    $sql = "SELECT * FROM tblitem WHERE ID = :editid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':editid', $editid, PDO::PARAM_INT);
    $query->execute();
    $item = $query->fetch(PDO::FETCH_OBJ);

    // Check if the book exists
    if (!$item) {
        header('Location: manage-items.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $itemid = $_POST['itemid'];
        $itemname = $_POST['itemname'];
        $itemdes = $_POST['itemdes'];
        $itemprice = $_POST['itemprice'];
        // $book_aval = $_POST['book_aval'];
        // $book_description = $_POST['book_description'];

        // Update book details in the database
        $sql = "UPDATE tblitem SET ItemName = :itemname, ItemDes = :itemdes, ItemPrice = :itemprice WHERE ID = :itemid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':itemid', $itemid, PDO::PARAM_INT);
        $query->bindParam(':itemname', $itemname, PDO::PARAM_STR);
        $query->bindParam(':itemdes', $itemdes, PDO::PARAM_STR);
        $query->bindParam(':itemprice', $itemprice, PDO::PARAM_STR);
        // $query->bindParam(':book_aval', $book_aval, PDO::PARAM_STR);
        // $query->bindParam(':book_description', $book_description, PDO::PARAM_STR);
        $query->execute();

        if ($query) {
            echo "<script>alert('Item details updated successfully');</script>";
            echo "<script>window.location.href = 'manage-items.php'</script>";
        } else {
            echo "<script>alert('Failed to update item details');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book Cafe - Edit Item</title>
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Edit Item</h2>

                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Item Details</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <form method="post" action="">
                            <input type="hidden" name="book_id" value="<?php echo $item->ID; ?>">
                            <div class="form-group">
                                <label for="book_name">Item Name</label>
                                <input type="text" id="book_name" name="itemname" class="form-control" value="<?php echo $item->ItemName; ?>" required>
                            </div>


                            <div class="form-group">
                                <label for="book_description">Item Description</label>
                                <textarea id="book_description" name="itemdes" class="form-control" required><?php echo $item->ItemDes; ?></textarea>
                            </div>

                            
                            <div class="form-group">
                                <label for="book_author">Item Price</label>
                                <input type="text" id="book_author" name="itemprice" class="form-control" value="<?php echo $item->ItemPrice; ?>" required>
                            </div>

                            

                            <button type="submit" name="submit" class="btn btn-primary">Update Item</button>
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
