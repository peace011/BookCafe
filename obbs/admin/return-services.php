<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('includes/dbconnection.php');

if (isset($_GET['bookingid'])) {
    $bookingID = $_GET['bookingid'];

    try {
        // Fetch booking details
        $fetchSql = "SELECT * FROM tblbooking WHERE BookingID = :bookingid";
        $fetchQuery = $dbh->prepare($fetchSql);
        $fetchQuery->bindParam(':bookingid', $bookingID, PDO::PARAM_STR);
        $fetchQuery->execute();
        $bookingDetails = $fetchQuery->fetch(PDO::FETCH_ASSOC);

        // Check if the booking exists
        if ($bookingDetails) {
            // Update ServiceStatus in tblbooking
            $updateBookingSql = "UPDATE tblbooking SET ServiceStatus = 1 WHERE BookingID = :bookingid";
            $updateBookingQuery = $dbh->prepare($updateBookingSql);
            $updateBookingQuery->bindParam(':bookingid', $bookingID, PDO::PARAM_STR);
            $updateBookingQuery->execute();

            // Increase the availability of the associated service in tblservice
            $serviceID = $bookingDetails['ServiceID'];

            $updateServiceSql = "UPDATE tblservice SET SerAvailable = SerAvailable + 1 WHERE ID = :serviceid";
            $updateServiceQuery = $dbh->prepare($updateServiceSql);
            $updateServiceQuery->bindParam(':serviceid', $serviceID, PDO::PARAM_STR);
            $updateServiceQuery->execute();

            // Redirect to booking history or a confirmation page
            header('Location: booking-history.php');
            exit();
        } else {
            // Handle the case where the booking does not exist
            echo "Booking not found.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        echo "An unexpected error occurred. Please try again later.";
    }
} else {
    // Handle invalid request, redirect to a proper page
    header('Location: index.php');
    exit();
}
?>
