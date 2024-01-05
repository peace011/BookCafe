<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Code for deleting product from cart
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblitem WHERE ID = :rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_INT);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage-items.php'</script>";
    }
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book Cafe - Manage Items</title>
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>
<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Manage Items</h2>

                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Manage Items</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th>Item Name</th>
                                    <th>Item ID</th>
                                    <th class="d-none d-sm-table-cell">Item Price</th>
                                    <!-- <th class="d-none d-sm-table-cell">Availability</th> -->
                                    <th class="d-none d-sm-table-cell">Creation Date</th>
                                    <th class="d-none d-sm-table-cell" style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tblitem";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($cnt); ?></td>
                                            <td class="font-w600"><?php echo htmlentities($row->ItemName); ?></td>
                                            <td class="font-w600"><?php echo htmlentities($row->ID); ?></td>
                                            <td class="d-none d-sm-table-cell">$<?php echo htmlentities($row->ItemPrice); ?></td>
                                            <!-- <td class="d-none d-sm-table-cell">
                                            <?php  $book_aval=$row->BookAval;
    
    // if( $book_aval=="1")
    // {
    //   echo "Active";
    // }
    
    // if( $book_aval=="0")
    // {
    //  echo "Inactive";
    // }
    // ?>
                                            </td> -->
                                            <td class="d-none d-sm-table-cell">
                                                <span class="badge badge-primary"><?php echo htmlentities($row->CreationDate); ?></span>
                                            </td>
                                            <td class="d-none d-sm-table-cell">
                                                <a href="manage-items.php?delid=<?php echo ($row->ID); ?>" onclick="return confirm('Do you really want to Delete?');">
                                                    <i class="fa fa-trash fa-delete" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td class="d-none d-sm-table-cell">
                                                <a href="edit-items.php?editid=<?php echo ($row->ID); ?>"><button>Edit</button></a>
                                            </td>
                                            <td class="d-none d-sm-table-cell">
                                                <a href="itemdetails.php?itemsid=<?php echo ($row->ID); ?>" class="btn btn-primary">Details</a>
                                            </td>
                                        </tr>
                                <?php
                                        $cnt++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
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

<?php } ?>
