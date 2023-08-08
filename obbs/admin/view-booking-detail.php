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
          //   if ($currentAvailability == 0) {
          //     echo '<script>alert("Book is unavailable")</script>';
          //     echo "<script>window.location.href ='all-booking.php'</script>";
          //     exit; // Stop further execution
          // }
          
            $newAvailability = $currentAvailability - 1;

            // Update the availability in the database
            $sql3 = "UPDATE tblservice SET SerAvailable=:newAvailability WHERE ID=:serviceid";
            $query3 = $dbh->prepare($sql3);
            $query3->bindParam(':newAvailability', $newAvailability, PDO::PARAM_INT);
            $query3->bindParam(':serviceid', $serviceid, PDO::PARAM_INT);
            $query3->execute();


////table ko
            // Get the EventType (table name) from tblbooking for the selected booking
            $sql_get_event_type = "SELECT EventType FROM tblbooking WHERE ID = :eid";
            $query_get_event_type = $dbh->prepare($sql_get_event_type);
            $query_get_event_type->bindParam(':eid', $eid, PDO::PARAM_INT);
            $query_get_event_type->execute();
            $row_get_event_type = $query_get_event_type->fetch();
        
            if ($row_get_event_type) {
                $eventType = $row_get_event_type['EventType'];
        
                // Update the status of the selected table (EventType) in the tbleventtype table to "inactive"
                $sql_update_event_type_status = "UPDATE tbleventtype SET EventStatus = '0' WHERE EventType = :eventType";
                $query_update_event_type_status = $dbh->prepare($sql_update_event_type_status);
                $query_update_event_type_status->bindParam(':eventType', $eventType, PDO::PARAM_STR);
                $query_update_event_type_status->execute();
            }
        }
    

        
        
        echo '<script>alert("Remark has been updated")</script>';
        echo "<script>window.location.href ='all-booking.php'</script>";
    }

  }
?>


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

// $sql="SELECT tbluser.FullName,tbluser.MobileNumber,tbluser.Email,tblbooking.BookingID,tblbooking.BookingDate,tblbooking.BookingFrom,tblbooking.BookingTo,tblbooking.EventType,tblbooking.Numberofguest,tblbooking.Message, tblbooking.Remark,tblbooking.Status,tblbooking.UpdationDate,tblservice.ServiceName,tblservice.SerDes,tblservice.ServicePrice from tblbooking join tblservice on tblbooking.ServiceID=tblservice.ID join tbluser on tbluser.ID=tblbooking.UserID  where tblbooking.ID=:eid";
  //  $sql="SELECT tbluser.FullName,tbluser.MobileNumber,tbluser.Email,tblbooking.BookingID,tblbooking.BookingDate,tblbooking.BookingFrom,tblbooking.BookingTo,tblbooking.EventType,tblbooking.ItemID,tblbooking.Numberofguest,tblbooking.Message, tblbooking.Remark,tblbooking.Status,tblbooking.UpdationDate,tblservice.ServiceName,tblservice.SerDes,tblservice.ServicePrice from tblbooking join tblservice on tblbooking.ServiceID=tblservice.ID join tbluser on tbluser.ID=tblbooking.UserID  where tblbooking.ID=:eid";
  $sql = "SELECT tbluser.FullName, tbluser.MobileNumber, tbluser.Email, tblbooking.BookingID, tblbooking.BookingDate, tblbooking.BookingFrom, tblbooking.BookingTo, tblbooking.EventType, tblbooking.ItemID, tblbooking.Numberofguest, tblbooking.Message, tblbooking.Remark, tblbooking.Status, tblbooking.UpdationDate, tblservice.ServiceName, tblservice.SerDes, tblservice.ServicePrice, tblitem.ItemName, tblitem.ItemPrice
  FROM tblbooking
  LEFT JOIN tblservice ON tblbooking.ServiceID = tblservice.ID
  LEFT JOIN tblitem ON tblbooking.ItemID = tblitem.ID
  JOIN tbluser ON tbluser.ID = tblbooking.UserID
  WHERE tblbooking.ID=:eid";


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
   <th>Booking Time</th>
    <td><?php  echo $row->BookingTo;?></td>
    <th>Number of Guest</th>
    <td><?php  echo $row->Numberofguest;?></td>
  </tr>
 
  <tr>
    
    <th>Table Type</th>
    <td><?php  echo $row->EventType;?></td>
    <th>Message</th>
    <td><?php  echo $row->Message;?></td>
  </tr>

  <tr>
    <th>Book Name</th>
    <td><?php  echo $row->ServiceName;?></td>
    <th>Book Price</th>
    <td>$<?php  echo $row->ServicePrice;?></td>
    <!-- <th>Book Description</th>
    <td><?php  echo $row->SerDes;?></td> -->
  </tr>

   <tr>
    <th>Table Price</th>
    <td>$100</td>
    <th>Apply Date</th>
    <td><?php  echo $row->BookingDate;?></td>
  </tr>


  
<tr>
  <th> Item Name</th>
<td>
    <?php
    // ItemID is directly accessible from the $row object
    $itemid = $row->ItemID;
    $categorySql = "SELECT ItemName FROM tblitem WHERE ID = '$itemid'";
    $categoryQuery = $dbh->prepare($categorySql);
    $categoryQuery->execute();
    $categoryResult = $categoryQuery->fetch(PDO::FETCH_OBJ);
    if ($categoryQuery->rowCount() > 0) {
        echo $categoryResult->ItemName;
    }
    ?>
</td>
  <th>Item Price</th>
    <td>$<?php echo $row->ItemPrice;?></td>
  </tr>


  

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
     <th >Admin Remark</th>
    <?php if($row->Status==""){ ?>

                     <td><?php echo "Not Updated Yet"; ?></td>
<?php } else { ?>                  <td><?php  echo htmlentities($row->Remark);?>
                  </td>
                  <?php } ?>
  </tr>



<tr>
 <th>Total Price</th>
 <?php
 // Calculate the Total Price (Item Price + Service Price)
 $totalPrice = $row->ItemPrice + $row->ServicePrice +100;
 ?>
 <td colspan="3">$<?php echo $totalPrice; ?></td>
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