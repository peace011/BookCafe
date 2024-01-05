<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['obbsuid']) == 0) {
    header('location: logout.php');
} else {
    if (isset($_POST['submit'])) {
        // Generate a random booking ID
        $bookingid = mt_rand(100000000, 999999999);

        // Retrieve user and booking details from the form
        $bid = $_GET['bookid'];
        $uid = $_SESSION['obbsuid'];
        $bookingfrom = $_POST['bookingfrom'];
        $bookingto = $_POST['bookingto'];
        $eventtype = $_POST['eventtype'];
        $nop = $_POST['nop'];
        $message = $_POST['message'];

        // Retrieve selected items as an array
        $selectedItems = $_POST['selected_items'];
          // Retrieve quantities as an array
    $quantities = $_POST['quantity'];

       



 
        // Insert booking details into the 'tblbooking' table
        $sql = "INSERT INTO tblbooking (BookingID, ServiceID, UserID, BookingFrom, BookingTo, TableType, Numberofguest, Message)
                VALUES (:bookingid, :bid, :uid, :bookingfrom, :bookingto, :eventtype, :nop, :message)";

        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingid', $bookingid, PDO::PARAM_STR);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);
        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
        $query->bindParam(':bookingfrom', $bookingfrom, PDO::PARAM_STR);
        $query->bindParam(':bookingto', $bookingto, PDO::PARAM_STR);
        $query->bindParam(':eventtype', $eventtype, PDO::PARAM_STR);
        $query->bindParam(':nop', $nop, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        // $query->bindParam(':selected_items', $selectedItemsList, PDO::PARAM_STR);

        // Start a database transaction
        $dbh->beginTransaction();

        try {
            // Execute the query to insert booking details
            $query->execute();

            // Retrieve the last insert ID
            $LastInsertId = $dbh->lastInsertId();

          // Loop through selected items and insert them into the 'BookingItems' table
        foreach ($selectedItems as $itemID) {
            // Get the quantity for this item
            $quantity = $quantities[$itemID];
        
            // Insert the item and quantity into the 'BookingItems' table
            $sqlItems = "INSERT INTO tblorder (BID, ItemID, Quantity) VALUES (:bookingid, :itemid, :quantity)";
            $queryItems = $dbh->prepare($sqlItems);
            $queryItems->bindParam(':bookingid', $LastInsertId, PDO::PARAM_INT);
            $queryItems->bindParam(':itemid', $itemID, PDO::PARAM_INT);
            $queryItems->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $queryItems->execute();
        }

            // Commit the database transaction
            $dbh->commit();



            echo '<script>alert("Your Booking Request Has Been Sent. We Will Contact You Soon")</script>';
            echo "<script>window.location.href ='services.php'</script>";
        } catch (PDOException $e) {
            // Rollback the transaction on error
            $dbh->rollBack();
            // echo '<script>alert("Something Went Wrong. Please try again")</script>';
            echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
        }

    }

    if (isset($_POST['bookingfrom'])) {
        $selectedDate = $_POST['bookingfrom'];

        // Fetch available tables for the selected date
        $sql = "SELECT t.*, a.AvailableTime, a.AvailableEndTime
                FROM tbltable t
                LEFT JOIN tbltableavailability a ON t.ID = a.TableID
                WHERE a.AvailableDate = :selectedDate  AND a.AvailableStatus = 1";

        $query = $dbh->prepare($sql);
        $query->bindParam(':selectedDate', $selectedDate, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Display available tables
        if (count($result) > 0) {
            echo '<h2 style="color: blue; font-size: 18px; font-style:bold">Available Tables for ' . $selectedDate . '</h2>';
            echo '<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">';
            echo '<thead>';
            echo '<tr>';
            echo '<th style="background-color: #ccc; text-align: center;">Table ID</th>';
            echo '<th style="background-color: #ccc; text-align: center;">Table Type</th>';
            echo '<th style="background-color: #ccc; text-align: center;">Table Capacity</th>';
            echo '<th style="background-color: #ccc; text-align: center;"> Start Time</th>';
            echo '<th style="background-color: #ccc; text-align: center;"> End Time</th>';

            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($result as $row) {
                echo '<tr>';
                echo '<td style="text-align: center;">' . $row['ID'] . '</td>';
                echo '<td style="text-align: center;">' . $row['TableType'] . '</td>';
                echo '<td style="text-align: center;">' . $row['TableCapacity'] . '</td>';
                // echo '<td style="text-align: center;">' . $row['AvailableTime'] . '</td>';
                echo '<td style="text-align: center;">' . date('h:i A', strtotime($row['AvailableTime'])) . '</td>';
                echo '<td style="text-align: center;">' . date('h:i A', strtotime($row['AvailableEndTime'])) . '</td>';
                // echo '<td style="text-align: center;">' . $row['AvailableEndTime'] . '</td>';

                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            
        } else {
            echo '<p>No available tables for the selected date.</p>';
        } 
         // Display table types dropdown
    echo '<div class="form-group row">';
    echo '<label class="col-form-label col-md-4">Type of Table:</label>';
    echo '<div class="col-md-10">';
    echo '<select type="text" class="form-control" name="eventtype" required="true">';
    echo '<option value="">Choose Table Type</option>';
    foreach ($result as $row) {
        echo '<option value="' . htmlentities($row['TableType']) . '">' . htmlentities($row['TableType']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';

        exit(); 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Cafe | Book Services</title>
    <script type="application/x-javascript">
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- bootstrap-css -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all"/>
    <!--// bootstrap-css -->
    <!-- css -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all"/>
    <!--// css -->
    <!-- font-awesome icons -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons -->
    <!-- font -->
    <link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i"
          rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300'
          rel='stylesheet' type='text/css'>
    <!-- //font -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();
                $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
            });
        });
    </script>
     <script type="text/javascript">
        $(document).ready(function () {
            // Event handler for date selection
            $('#bookDate').change(function () {
                // Get the selected date
                var selectedDate = $(this).val();

                // Fetch available tables for the selected date
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {
                        bookingfrom: selectedDate
                    },
                    success: function (response) {
                        // Display the response in a designated div or element
                        $('#availableTables').html(response);
                    }
                });
            });
        });
    </script>





</head>
<body>
<!-- banner -->
<div class="banner jarallax">
    <div class="agileinfo-dot">
        <?php include_once('includes/header.php'); ?>
        <div class="wthree-heading">
            <h2>Book Services</h2>
        </div>
    </div>
</div>
<!-- //banner -->
<!-- contact -->
<div class="contact">
    <div class="container">
        <div class="agile-contact-form">

            <div class="col-md-6 contact-form-right" style="width:55%">
                <div class="contact-form-top">
                    <h3>Book Services</h3>
                </div>
                <div class="agileinfo-contact-form-grid">
                    <form method="post">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Booking Date:</label>
                            <div class="col-md-10">
                                <input type="date" class="form-control" style="font-size: 20px" required="true"
                                       id="bookDate" name="bookingfrom">
                            </div>
                            
                        </div>

                        <div id="availableTables"></div>

                      



                        <script type="text/javascript">
                            // Get the current date and format it as "YYYY-MM-DD"
                            const currentDate = new Date().toISOString().split('T')[0];

                            // Get the date input field
                            const bookDateInput = document.getElementById('bookDate');

                            // Set the minimum date for the input field to the current date
                            bookDateInput.setAttribute('min', currentDate);
                        </script>

                        <!-- <div class="form-group row">
                            <label class="col-form-label col-md-4">Booking Time:</label>
                            <div class="col-md-10">
                                <?php
                                // Specify the start and end time for the time slots
                                // $start_time = strtotime('10:00 AM');
                                // $end_time = strtotime('5:00 PM');
                                $start_time = strtotime('10:00 AM');
                                $end_time = strtotime('5:00 PM');

                                // Specify the interval between time slots (in minutes)
                                $interval = 60;


                                // Create an array to store the time slots
                                $time_slots = array();

                                // Generate the time slots
                                $current_time = $start_time;
                                while ($current_time <= $end_time) {
                                    $time_slots[] = date('h:i A', $current_time);
                                    $current_time += $interval * 60;
                                }
                                ?>
                                <select class="form-control" name="bookingto" required="true" style="font-size: 20px">
                                    <?php foreach ($time_slots as $time_slot) : ?>
                                        <option value="<?php echo $time_slot; ?>"><?php echo $time_slot; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div> -->



                      



                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Select Item(s):</label>
                            <div class="col-md-8">
                                <?php
                                $sql2 = "SELECT * FROM tblitem";
                                $query2 = $dbh->prepare($sql2);
                                $query2->execute();
                                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                foreach ($result2 as $row) {
                                    echo '<div class="checkbox">';
                                    echo '<label>';
                                    echo '<input type="checkbox" name="selected_items[]" value="' . htmlentities($row->ID) . '"> ' . htmlentities($row->ItemName);
                                    echo ' - Price: $'  . htmlentities($row->ItemPrice); 
                                    echo '</label>';
                                    echo " -   " ;
                                    echo '<input type="number" name="quantity[' . htmlentities($row->ID) . ']" placeholder="Quantity" >';
        
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Number of People:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" style="font-size: 20px" required="true"
                                       name="nop">
                            </div>
                        </div>








                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Message (if any):</label>
                            <div class="col-md-10">
                                <textarea class="form-control" required="true" name="message"
                                          style="font-size: 20px"></textarea>
                            </div>
                        </div>

                        <div class="tp">
                            <button type="submit" class="btn btn-primary" name="submit">Book</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- //contact -->
<?php include_once('includes/footer.php'); ?>
<!-- jarallax -->
<script src="js/jarallax.js"></script>
<script src="js/SmoothScroll.min.js"></script>
<script type="text/javascript">
    /* init Jarallax */
    $('.jarallax').jarallax({
        speed: 0.5,
        imgWidth: 1366,
        imgHeight: 768
    })
</script>
<!-- //jarallax -->
<script src="js/SmoothScroll.min.js"></script>
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<!-- here stars scrolling icon -->
<script type="text/javascript">
    $(document).ready(function () {
        $().UItoTop({easingType: 'easeOutQuart'});
    });
</script>
<!-- //here ends scrolling icon -->
<script src="js/modernizr.custom.js"></script>

</body>
</html>
