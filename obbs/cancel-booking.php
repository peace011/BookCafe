<?php



session_start();
include('includes/dbconnection.php');

if (isset($_GET['bookingid'])) {
    $bookingID = $_GET['bookingid'];

    try {
        // First, fetch the booking details
        $fetchSql = "SELECT * FROM tblbooking WHERE BookingID = :bookingid";
        $fetchQuery = $dbh->prepare($fetchSql);
        $fetchQuery->bindParam(':bookingid', $bookingID, PDO::PARAM_STR);
        $fetchQuery->execute();
        $bookingDetails = $fetchQuery->fetch(PDO::FETCH_ASSOC);

        // Check if the booking exists
        if ($bookingDetails) {

            // Update the status to "inactive" in tbltable
            $tabletype = $bookingDetails['TableType'];
            $updateTableSql = "UPDATE tbltable SET TableStatus = 1 WHERE TableType = :tabletype";
            $updateTableQuery = $dbh->prepare($updateTableSql);
            $updateTableQuery->bindParam(':tabletype', $tabletype, PDO::PARAM_STR);
            $updateTableQuery->execute();


            // Delete the entire booking record
            $deleteSql = "DELETE FROM tblbooking WHERE BookingID = :bookingid";
            $deleteQuery = $dbh->prepare($deleteSql);
            $deleteQuery->bindParam(':bookingid', $bookingID, PDO::PARAM_STR);
            $deleteQuery->execute();

            // You can add additional logic here if needed

            // Redirect the user to the booking history page after cancellation
            header('Location: booking-history.php');
            exit();
        } else {
            // Handle the case where the booking does not exist
            echo "Booking not found.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle invalid request, redirect to a proper page
    header('Location: index.php');
    exit();
}
?>
