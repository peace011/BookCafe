<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['obbsuid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {
  	$bid=$_GET['bookid'];
  	$uid=$_SESSION['obbsuid'];
 $bookingfrom=$_POST['bookingfrom'];
  $bookingto=$_POST['bookingto'];
 $eventtype=$_POST['eventtype'];
 $nop=$_POST['nop'];
 $message=$_POST['message'];
 $bookingid=mt_rand(100000000, 999999999);
 $itemid = $_POST['itemid'];


//          // Check for overlapping booking
//          $sql_check_overlap = "SELECT COUNT(*) AS count_overlap FROM tblbooking 
//          WHERE ServiceID = :bid AND BookingFrom <= :bookingto AND BookingTo >= :bookingfrom";
// $query_check_overlap = $dbh->prepare($sql_check_overlap);
// $query_check_overlap->bindParam(':bid', $bid, PDO::PARAM_STR);
// $query_check_overlap->bindParam(':bookingfrom', $bookingfrom, PDO::PARAM_STR);
// $query_check_overlap->bindParam(':bookingto', $bookingto, PDO::PARAM_STR);
// $query_check_overlap->execute();
// $overlap_result = $query_check_overlap->fetch(PDO::FETCH_ASSOC);
// $count_overlap = $overlap_result['count_overlap'];
// if ($count_overlap > 0) {
//     echo '<script>alert("The selected time slot is already booked. Please choose a different time slot.")</script>';
// } else {




$sql="insert into tblbooking(BookingID,ServiceID,UserID,BookingFrom,BookingTo,EventType,Numberofguest,Message,ItemID)values(:bookingid,:bid,:uid,:bookingfrom,:bookingto,:eventtype,:nop,:message,:itemid)";
$query=$dbh->prepare($sql);
$query->bindParam(':bookingid',$bookingid,PDO::PARAM_STR);
$query->bindParam(':bid',$bid,PDO::PARAM_STR);
$query->bindParam(':uid',$uid,PDO::PARAM_STR);
$query->bindParam(':bookingfrom',$bookingfrom,PDO::PARAM_STR);
$query->bindParam(':bookingto',$bookingto,PDO::PARAM_STR);
$query->bindParam(':eventtype',$eventtype,PDO::PARAM_STR);
$query->bindParam(':nop',$nop,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);
$query->bindParam(':itemid',$itemid,PDO::PARAM_STR);


 $query->execute();
   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Your Booking Request Has Been Send. We Will Contact You Soon")</script>';
echo "<script>window.location.href ='services.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}




  
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Book Cafe| Book Services</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!--// bootstrap-css -->
<!-- css -->
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<!--// css -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<!-- font -->
<link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300' rel='stylesheet' type='text/css'>
<!-- //font -->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script> 


</head>
<body>
	<!-- banner -->
	<div class="banner jarallax">
		<div class="agileinfo-dot">
			<?php include_once('includes/header.php');?>
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
				
				<div class="col-md-6 contact-form-right">
					<div class="contact-form-top">
						<h3>Book Services </h3>
					</div>
					<div class="agileinfo-contact-form-grid">
						<form method="post">
							 <div class="form-group row">
                                    <label class="col-form-label col-md-4">Booking Date:</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" style="font-size: 20px" required="true" id="bookDate" name="bookingfrom">
                                        
                                    </div>
                                </div>
                                <script type="text/javascript">
                                     // Get the current date and format it as "YYYY-MM-DD"
                                     const currentDate = new Date().toISOString().split('T')[0];

                                     // Get the date input field
                                     const bookDateInput = document.getElementById('bookDate');

                                     // Set the minimum date for the input field to the current date
                                     bookDateInput.setAttribute('min', currentDate);
                                </script>
                                           
                                    <div class="form-group row">
                                                                            <label class="col-form-label col-md-4">Booking Time:</label>
                                    <div class="col-md-10">
                                    <?php
                                // Specify the start and end time for the time slots
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
                                <select class="form-control" name="bookingto" required="true" style="font-size: 20px" >
                                    <?php foreach ($time_slots as $time_slot) : ?>
                                        <option value="<?php echo $time_slot; ?>"><?php echo $time_slot; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type of Table:</label>
                                    <div class="col-md-10">
                                       <select type="text" class="form-control" name="eventtype" required="true" >
							 	<option value="">Choose Table Type</option>
							 	<?php 

$sql2 = "SELECT * from   tbleventtype WHERE EventStatus='1' ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);

foreach($result2 as $row)
{          
    ?>  
<option value="<?php echo htmlentities($row->EventType);?>"><?php echo htmlentities($row->EventType);?></option>
 <?php } ?>
							 </select>
                                    </div>
                                </div>






<div class="form-group row">
    <label class="col-form-label col-md-4">Item Name:</label>
    <div class="col-md-10">
        <select type="text" class="form-control" name="itemid" required="true">
            <option value="">Choose item</option>
            <?php
            $sql2 = "SELECT * FROM tblitem";
            $query2 = $dbh->prepare($sql2);
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

            foreach ($result2 as $row) {
                ?>
                <option value="<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->ItemName); ?></option>
            <?php } ?>
        </select>
    </div>
</div>








                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Number of Guest:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" style="font-size: 20px" required="true" name="nop">
                                    </div>
                                </div>
                                                 <div class="form-group row">
                                    <label class="col-form-label col-md-4">Message(if any)</label>
                                    <div class="col-md-10">
                                        <textarea  class="form-control"  required="true" name="message" style="font-size: 20px"></textarea> 
                                    </div>
                                </div>
                                                
                                              <br>
                                                <div class="tp">
                                                    
                                                     <button type="submit" class="btn btn-primary" name="submit">Book</button>
                                                </div>
                                            </form>

					</div>
				</div>
				
				<div class="clearfix"> </div>
			</div>
			
		
		</div>
	</div>
	<!-- //contact -->
	<?php include_once('includes/footer.php');?>
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
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
<script src="js/modernizr.custom.js"></script>

</body>	
</html><?php }  ?>