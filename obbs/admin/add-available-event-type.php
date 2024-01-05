<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbconnection.php');

if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $selectedTableType = $_POST['selectedTableType'];
        $estatus = $_POST['estatus'];
        $availableDate = $_POST['availableDate'];
        $formattedTime = $_POST['availableTime'];
        $formattedEndTime = $_POST['availableEndTime'];


        // Convert time to 12-hour format with AM/PM
        // $formattedTime = date('h:i A', strtotime($availableTime));
        // $formattedEndTime = date('h:i A', strtotime($availableEndTime));


        // Get the TableID based on the selected TableType
        $sqlGetTableID = "SELECT ID, TableType FROM tbltable WHERE TableType = :selectedTableType";
        $queryGetTableID = $dbh->prepare($sqlGetTableID);
        $queryGetTableID->bindParam(':selectedTableType', $selectedTableType, PDO::PARAM_STR);
        $queryGetTableID->execute();
        $result = $queryGetTableID->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $tableID = $result['ID'];
            $tableType = $result['TableType'];


            // Insert into tbltableavailability
            $sqlAvailability = "INSERT INTO tbltableavailability (TableID,TableType, AvailableDate, AvailableTime,AvailableEndTime, AvailableStatus) VALUES (:tableID, :tableType, :availableDate, :formattedTime, :formattedEndTime, :estatus)";
            $queryAvailability = $dbh->prepare($sqlAvailability);
            $queryAvailability->bindParam(':tableID', $tableID, PDO::PARAM_INT);
            $queryAvailability->bindParam(':tableType', $tableType, PDO::PARAM_STR);
            $queryAvailability->bindParam(':availableDate', $availableDate, PDO::PARAM_STR);
            $queryAvailability->bindParam(':formattedTime', $formattedTime, PDO::PARAM_STR);
            $queryAvailability->bindParam(':formattedEndTime', $formattedEndTime, PDO::PARAM_STR);
            $queryAvailability->bindParam(':estatus', $estatus, PDO::PARAM_STR);
            $queryAvailability->execute();

            $LastInsertId = $dbh->lastInsertId();

            if ($LastInsertId > 0) {
                echo '<script>alert("Availability has been added.")</script>';
                echo "<script>window.location.href ='add-available-event-type.php'</script>";
            } else {
                echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }
        } else {
            echo '<script>alert("Table Type not found. Please select a valid Table Type.")</script>';
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <title>Book cafe - Add Availability</title>
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>
<body>
    <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
        <?php include_once('includes/sidebar.php');?>
        <?php include_once('includes/header.php');?>
        <main id="main-container">
            <div class="content">
                <h2 class="content-heading">Add Availability</h2>
                <div class="row">
                    <div class="col-md-12">
                        <div class="block block-themed">
                            <div class="block-header bg-gd-emerald">
                                <h3 class="block-title">Add Availability</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                        <i class="si si-refresh"></i>
                                    </button>
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                </div>
                            </div>
                            <div class="block-content">
                                <form method="post">
                                    <div class="form-group row">
                                        <label class="col-12" for="selectedTableType">Select Table Type:</label>
                                        <div class="col-12">
                                            <!-- Add a dropdown to select the TableType -->
                                            <select name="selectedTableType" class="form-control" required>
                                                <?php
                                                // Fetch TableTypes from tbltable
                                                $sqlTableTypes = "SELECT DISTINCT TableType FROM tbltable";
                                                $queryTableTypes = $dbh->prepare($sqlTableTypes);
                                                $queryTableTypes->execute();
                                                while ($row = $queryTableTypes->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['TableType'] . '">' . $row['TableType'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12" for="register1-email">Available Date:</label>
                                        <div class="col-12">
                                             <input type="date" class="form-control" name="availableDate" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12" for="register1-email">Available Start Time :</label>
                                        <div class="col-12">
                                             <input type="time" class="form-control" name="availableTime" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12" for="register1-email">Available End Time :</label>
                                        <div class="col-12">
                                             <input type="time" class="form-control" name="availableEndTime" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12" for="register1-email">Table Status:</label>
                                        <div class="col-12">
                                            <select name="estatus" id="book_aval" class="form-control" required>
                                               <option value="1">Active</option>
                                               <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-alt-success" name="submit">
                                                <i class="fa fa-plus mr-5"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include_once('includes/footer.php');?>
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
</body>
</html>
