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
        header('Location: manage-event-type.php');
        exit();
    }

    // Retrieve existing table information
    $sql = "SELECT * FROM tbleventtype WHERE ID = :editid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':editid', $editid, PDO::PARAM_INT);
    $query->execute();
    $table = $query->fetch(PDO::FETCH_OBJ);

    // Check if the table exists
    if (!$table) {
        header('Location: manage-event-type.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $event_id = $_POST['event_id'];
        $event_type = $_POST['event_type'];
        $event_status = $_POST['event_status'];


        // Update table details in the database
        $sql = "UPDATE tbleventtype SET EventType = :event_type, EventStatus= :event_status WHERE ID = :event_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $query->bindParam(':event_type', $event_type, PDO::PARAM_STR);
        $query->bindParam(':event_status', $event_status, PDO::PARAM_STR);

        $query->execute();

        if ($query) {
            echo "<script>alert('Table details updated successfully');</script>";
            echo "<script>window.location.href = 'manage-event-type.php'</script>";
        } else {
            echo "<script>alert('Failed to update table details');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book Cafe - Edit Tables</title>
    <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Edit Table</h2>

                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Table Details</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <form method="post" action="">
                            <input type="hidden" name="event_id" value="<?php echo $table->ID; ?>">
                            <div class="form-group">
                                <label for="event_type">Table Type</label>
                                <input type="text" id="event_type" name="event_type" class="form-control" value="<?php echo $table->EventType; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="event_type">Table Status</label>
                                <!-- <input type="text" id="event_type" name="event_type" class="form-control" value="<?php echo $table->EventStatus; ?>" required> -->
                                <select name="event_status" id="event_status" class="form-control" required>
                               <option value="1" <?php if ($event_status == "1") { echo "selected"; } ?>>Active</option>
                                 <option value="0">Inactive</option>
			</select>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Update Table</button>
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
