
<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');


if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['editid'];
        $bookingid = $_GET['bookingid'];
        $serviceid = $_GET['serviceid'];
        $tablestatus = $_GET['tablestatus'];

        $status = $_POST['status'];
        $remark = $_POST['remark'];

        $sql = "UPDATE tblbooking SET Status=:status, Remark=:remark WHERE ID=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':remark', $remark, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();

   
         if ($status === "Approved") {
        $sql2 = "SELECT SerAvailable FROM tblservice WHERE ID=:serviceid";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':serviceid', $serviceid, PDO::PARAM_INT);
        $query2->execute();
        $result2 = $query2->fetch(PDO::FETCH_ASSOC);

        if ($result2) {
            $currentAvailability = $result2['SerAvailable'];
  
          
            $newAvailability = $currentAvailability - 1;

            // Update the availability in the database
            $sql3 = "UPDATE tblservice SET SerAvailable=:newAvailability WHERE ID=:serviceid";
            $query3 = $dbh->prepare($sql3);
            $query3->bindParam(':newAvailability', $newAvailability, PDO::PARAM_INT);
            $query3->bindParam(':serviceid', $serviceid, PDO::PARAM_INT);
            $query3->execute();

          }

                // Fetch the TableType and AvailableDate associated with the booking
                $sqlBookingTable = "SELECT TableType, BookingFrom FROM tblbooking WHERE ID=:eid";
                $queryBookingTable = $dbh->prepare($sqlBookingTable);
                $queryBookingTable->bindParam(':eid', $eid, PDO::PARAM_STR);
                $queryBookingTable->execute();
                $resultBookingTable = $queryBookingTable->fetch(PDO::FETCH_ASSOC);

                if ($resultBookingTable) {
                    $tableType = $resultBookingTable['TableType'];
                    $bookingDate = $resultBookingTable['BookingFrom'];

                //     // Update the availability status of the table for the specific date
                //     $sqlUpdateTableStatus = "UPDATE tbltableavailability SET AvailableStatus = '0' WHERE TableType = :tableType AND AvailableDate = :bookingDate";
                //     $queryUpdateTableStatus = $dbh->prepare($sqlUpdateTableStatus);
                //     $queryUpdateTableStatus->bindParam(':tableType', $tableType, PDO::PARAM_STR);
                //     $queryUpdateTableStatus->bindParam(':bookingDate', $bookingDate, PDO::PARAM_STR);
                //     $queryUpdateTableStatus->execute();
                // }
                // Fetch AvailableTime and AvailableEndTime from tbltableavailability
    $sqlFetchAvailableTime = "SELECT AvailableTime, AvailableEndTime FROM tbltableavailability WHERE TableType = :tableType AND AvailableDate = :bookingDate";
    $queryFetchAvailableTime = $dbh->prepare($sqlFetchAvailableTime);
    $queryFetchAvailableTime->bindParam(':tableType', $tableType, PDO::PARAM_STR);
    $queryFetchAvailableTime->bindParam(':bookingDate', $bookingDate, PDO::PARAM_STR);
    $queryFetchAvailableTime->execute();
    $resultAvailableTime = $queryFetchAvailableTime->fetch(PDO::FETCH_ASSOC);

    if ($resultAvailableTime) {
        $availableTime = $resultAvailableTime['AvailableTime'];
        $availableEndTime = $resultAvailableTime['AvailableEndTime'];

        // Update the availability status of the table for the specific date and time range
        $sqlUpdateTableStatus = "UPDATE tbltableavailability SET AvailableStatus = '0' WHERE TableType = :tableType AND AvailableDate = :bookingDate AND AvailableTime = :availableTime AND AvailableEndTime = :availableEndTime";
        $queryUpdateTableStatus = $dbh->prepare($sqlUpdateTableStatus);
        $queryUpdateTableStatus->bindParam(':tableType', $tableType, PDO::PARAM_STR);
        $queryUpdateTableStatus->bindParam(':bookingDate', $bookingDate, PDO::PARAM_STR);
        $queryUpdateTableStatus->bindParam(':availableTime', $availableTime, PDO::PARAM_STR);
        $queryUpdateTableStatus->bindParam(':availableEndTime', $availableEndTime, PDO::PARAM_STR);
        $queryUpdateTableStatus->execute();
    }
  }

    
    

        
        
        echo '<script>alert("Remark has been updated")</script>';
        echo "<script>window.location.href ='all-booking.php'</script>";
        //  Approved
    }

  }
?>



<!doctype html>
 <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
 <title>Book Cafe- View Booking</title>
<link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">

</head>
    <body>
        <div id="page-container" class="sidebar-o sidebar-inverse side-scroll page-header-fixed main-content-narrow">
     

             <?php include_once('includes/sidebar.php');?>

          <?php include_once('includes/header.php');?>

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="content">
                
                    <!-- Register Forms -->
                    <h2 class="content-heading">View Booking</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Register -->
                            <div class="block block-themed">
                                <div class="block-header bg-gd-emerald">
                                    <h3 class="block-title">View Booking</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                   
                                    <?php
                  $eid=$_GET['editid'];

//  $sql = "SELECT tbluser.FullName, tbluser.MobileNumber, tbluser.Email,tblbooking.TableType,tbltable.TableCapacity,tbltable.tableType, tblbooking.BookingID, tblbooking.BookingDate, tblbooking.BookingFrom, tblbooking.BookingTo, tblbooking.TableType, tblbooking.ServiceStatus, tblbooking.Numberofguest, tblbooking.Message, tblbooking.Remark, tblbooking.Status, tblbooking.Payment, tblbooking.UpdationDate, tblservice.ServiceName, tblservice.SerDes, tblservice.ServicePrice, tblitem.ItemName, tblitem.ItemPrice
//   FROM tblbooking
//   LEFT JOIN tblservice ON tblbooking.ServiceID = tblservice.ID
//   LEFT JOIN tblitem ON tblbooking.ItemID = tblitem.ID
//   LEFT JOIN tbltable ON tblbooking.TableType = tbltable.TableType
//   JOIN tbluser ON tbluser.ID = tblbooking.UserID
//   WHERE tblbooking.ID=:eid";
$sql="SELECT tbluser.FullName,tbluser.MobileNumber,tbluser.Email,tblbooking.TableType,tbltable.TableCapacity,tbltable.tableType,tblbooking.BookingID,tblbooking.BookingDate,tblbooking.BookingFrom,tblbooking.BookingTo,tblbooking.TableType,tblbooking.Numberofguest,tblbooking.Message, tblbooking.Remark,tblbooking.Status,tblbooking.Payment,tblbooking.UpdationDate,tblbooking.ServiceStatus,tblservice.ServiceName,tblservice.SerDes,tblservice.ServicePrice, tbltableavailability.AvailableTime, tbltableavailability.AvailableEndTime from tblbooking join tblservice on tblbooking.ServiceID=tblservice.ID join tbluser on tbluser.ID=tblbooking.UserID 
join tbltable on tblbooking.TableType = tbltable.TableType
   join tbltableavailability on tbltableavailability.TableType = tblbooking.TableType 
   and tblbooking.BookingFrom = tbltableavailability.AvailableDate
where tblbooking.ID=:eid GROUP BY tblbooking.BookingID" ;


$query = $dbh -> prepare($sql);
$query-> bindParam(':eid', $eid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                            <table border="1" class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                                <tr>
                                    <th colspan="5" style="text-align: center;font-size: 20px;color: blue;">Booking Number: <?php  echo $row->BookingID;?>
                                        
                                    </th>
                                </tr>
                                            <tr>
    <th>Client Name</th>
    <td><?php  echo $row->FullName;?></td>
     <th>Mobile Number</th>
    <td><?php  echo $row->MobileNumber;?></td>
  </tr>
 

  <tr>
   <th>Email</th>
    <td><?php  echo $row->Email;?></td>
    <th>Booking Date</th>
    <td><?php  echo $row->BookingFrom;?></td>
    
  </tr>

   <tr>
   <th>Start Time</th>
   <td><?php echo date('h:i A', strtotime($row->AvailableTime));?></td>
    <th>End Time</th>
    <td><?php echo date('h:i A', strtotime($row->AvailableEndTime));?></td>  </tr>
 
  <tr>
    
    <th>Table Type</th>
    <td><?php  echo $row->TableType;?></td>
    <th>Table Capacity</th>
    <td><?php  echo $row->TableCapacity;?></td>
   
  </tr>

  <tr>
    <th>Book Name</th>
    <td><?php  echo $row->ServiceName;?></td>
    <th>Book Price</th>
    <td>Rs.<?php  echo $row->ServicePrice;?></td>
  
  </tr>

   <tr>
    <th>Apply Date</th>
    <td><?php  echo $row->BookingDate;?></td>
    <th>Message</th>
    <td><?php  echo $row->Message;?></td>
  </tr>


  
<tr>
  <th> Item Name</th>
<td>
 
    <?php
$eid = $_GET['editid'];

// Fetch and display ItemIDs separately
$sqlSelectItemIDs = "SELECT ItemID, Quantity FROM tblorder WHERE BID = :eid";

$querySelectItemIDs = $dbh->prepare($sqlSelectItemIDs);
$querySelectItemIDs->bindParam(':eid', $eid, PDO::PARAM_STR);
$querySelectItemIDs->execute();
$resultItemIDs = $querySelectItemIDs->fetchAll(PDO::FETCH_ASSOC);

echo '<ul>'; // Start an unordered list to display items and quantities

// Iterate through the ItemIDs and fetch and display their names, quantities, and prices
foreach ($resultItemIDs as $itemData) {
    $itemID = $itemData['ItemID'];
    $quantity = $itemData['Quantity'];

    // Fetch the ItemName and ItemPrice for each ItemID
    $sqlSelectItemData = "SELECT ItemName, ItemPrice FROM tblitem WHERE ID = :itemID";
    $querySelectItemData = $dbh->prepare($sqlSelectItemData);
    $querySelectItemData->bindParam(':itemID', $itemID, PDO::PARAM_INT);
    $querySelectItemData->execute();
    $itemResult = $querySelectItemData->fetch(PDO::FETCH_OBJ);

    // Display the ItemName, Quantity, and ItemPrice
    if ($querySelectItemData->rowCount() > 0) {
        echo '<li>';
        echo  htmlentities($itemResult->ItemName) . '<br>';
        echo 'Quantity: ' . htmlentities($quantity) . '<br>';
        echo 'Per Price: Rs.' . htmlentities($itemResult->ItemPrice);
        echo '</li>';
        echo '<br>';
        $totalPrice += ($itemResult->ItemPrice * $quantity); // Update the total price
    }
}

echo '</ul>'; // End the unordered list
?>

</td>

  <th>Total Price</th>
 <?php
 // Calculate the Total Price (Item Price + Service Price)
//  $totalPrice = $row->ItemPrice + $row->ServicePrice +100;
 $totalPrice += $row->ServicePrice; // Add the book price
        
 ?>
 <td colspan="3">Rs.<?php echo $totalPrice; ?></td>
  </tr>


  <tr>
  <th>Number of Guest</th>
    <td><?php  echo $row->Numberofguest;?></td>
    <th >Admin Remark</th>
    <?php if($row->Status==""){ ?>

                     <td><?php echo "Not Updated Yet"; ?></td>
<?php } else { ?>                  <td><?php  echo htmlentities($row->Remark);?>
                  </td>
                  <?php } ?>

  <tr>
     <th>Order Final Status</th>

    <td> <?php  $status=$row->Status;
    
if($row->Status=="Approved")
{
  echo "Approved";
}

if($row->Status=="Cancelled")
{
 echo "Cancelled";
}


if($row->Status=="")
{
  echo "Not Response Yet";
}


     ;?></td>

<th>Service Status</th>
    <td> 
     <?php $ServiceStatus = trim($row->ServiceStatus); // Trim any leading/trailing whitespace

      if ($ServiceStatus === '1' || $ServiceStatus === 1) {
          echo 'Returned';
      }  if ($ServiceStatus === '0' || $ServiceStatus === 0) {
          echo 'Not Returned';
      }?></td>
   
  </tr>



  <tr>
 
    <th>Payment Status</th>
    <td>
        <?php  
            $paymentStatus = trim($row->Payment); // Trim any leading/trailing whitespace

            if ($paymentStatus === '1' || $paymentStatus === 1) {
                echo 'Paid';
            }  if ($paymentStatus === '0' || $paymentStatus === 0) {
                echo 'Unpaid';
            }
        ?>
    </td>


</tr>





<?php $cnt=$cnt+1;}} ?>

</table> 


















<?php 

if ($status==""){
?> 
<p align="center"  style="padding-top: 20px">                            
 <button class="btn btn-primary waves-effect waves-light w-lg" data-toggle="modal" data-target="#myModal">Take Action</button></p>  

<?php } ?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
     <div class="modal-content">
      <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Take Action</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                <table class="table table-bordered table-hover data-tables">

                                <form method="post" name="submit">

                                
                               
     <tr>
    <th>Remark :</th>
    <td>
    <textarea name="remark" placeholder="Remark" rows="12" cols="14" class="form-control wd-450" required="true"></textarea></td>
  </tr> 
   
 
  <tr>
    <th>Status :</th>
    <td>

   <select name="status" class="form-control wd-450" required="true" >
     <option value="Approved" selected="true">Approved</option>
     <option value="Cancelled">Cancelled</option>
   </select></td>
  </tr>
</table>
</div>
<div class="modal-footer">
 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
 <button type="submit" name="submit" class="btn btn-primary">Update</button>
  
  </form>



                                </div>
                            </div>
                            <!-- END Bootstrap Register -->
                        </div>
                        
                       </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

          <?php include_once('includes/footer.php');?>
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
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
<?php }  ?>