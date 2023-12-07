<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['obbsuid']==0)) {
  header('location:logout.php');
  } else{
   

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Book Cafe|| View Booking </title>

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

	// Function to trigger print
	function printReceipt() {
            window.print();
        }

</script> 
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->


<script src="https://js.stripe.com/v3/"></script>

</head>
<body>
	<!-- banner -->
	<div class="banner jarallax">
		<div class="agileinfo-dot">
		<?php include_once('includes/header.php');?>
			<div class="wthree-heading">
				<h2>View Booking</h2>
			</div>
		</div>
	</div>
	<!-- //banner -->
	<!-- about -->
	<!-- about-top -->
	<div class="about-top">
		<div class="container">
			<div class="wthree-services-bottom-grids">
				
				<p class="wow fadeInUp animated" data-wow-delay=".5s">View Your Booking Details.</p>
					<div class="bs-docs-example wow fadeInUp animated" data-wow-delay=".5s">
						 <?php
          

$eid=$_GET['editid'];

$sql="SELECT tbluser.FullName,tbluser.MobileNumber,tbluser.Email,tblbooking.BookingID,tblbooking.BookingDate,tblbooking.BookingFrom,tblbooking.BookingTo,tblbooking.EventType,tblbooking.Numberofguest,tblbooking.Message, tblbooking.Remark,tblbooking.Status,tblbooking.UpdationDate,tblservice.ServiceName,tblservice.SerDes,tblservice.ServicePrice from tblbooking join tblservice on tblbooking.ServiceID=tblservice.ID join tbluser on tbluser.ID=tblbooking.UserID  where tblbooking.ID=:eid";
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
$totalPrice += 100; // Add the table price (assuming it's a fixed value of $100)

 ?>
 <td colspan="3">Rs.<?php echo $totalPrice; ?></td>
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
  
 
<?php $cnt=$cnt+1;}} ?>

</table> 
					</div>
				<div class="clearfix"> </div>



       <!-- Your payment form -->
       <form action="charge.php" method="post" id="payment-form">
  <!-- Other form fields -->
  <label for="amount">Amount (in cents):</label>
<input type="text" id="amount" name="amount" required>

  <!-- Stripe.js token element -->
  <div id="card-element"></div>

  <!-- Used to display form errors -->
  <div id="card-errors" role="alert"></div>

  <!-- Hidden input for the Stripe token -->
  <input type="hidden" name="stripeToken" id="stripeToken">
  <input type="hidden" name="stripeAmount" id="stripeAmount">

  <!-- Submit button -->
  <button type="submit">Submit Payment</button>
</form>







				<!-- Print Button -->
				<button onclick="printReceipt()">Print Receipt</button>

			</div>
		</div>
	</div>
	<!-- //about-top -->
	
	<!-- //about -->
	<!-- footer -->
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



<script>
// Set your Stripe public key
var stripe = Stripe('pk_test_51OI6ZrGnOza8XShbfZAVqHOLKYNTc2U46uZBP1wNTDtsGsJGMI5b4AUTgt3ffSpZRpBnxVuKC1SyepjQzLo5Z6AD00c2HfAgoz');

// Create an instance of Elements
var elements = stripe.elements();

// Create an instance of the card Element
var card = elements.create('card');

// Add an instance of the card Element into the `card-element` div
card.mount('#card-element');

// Handle form submission
var form = document.getElementById('payment-form');
var amountField = document.getElementById('amount');

form.addEventListener('submit', function (event) {
  event.preventDefault();

  // Fetch the amount from the form
  var amountValue = amountField.value;

  // Create a token using the card element
  stripe.createToken(card, { amount: amountValue }).then(function (result) {
    if (result.error) {
      // Inform the user if there was an error
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Log the token to the console (for debugging)
      console.log('Token:', result.token.id);

      // Set the token in the hidden input field
      document.getElementById('stripeToken').value = result.token.id;
      document.getElementById('stripeAmount').value = amountValue;
      // Submit the form
      form.submit();
    }
  });
});
</script>
</body>	
</html><?php }  ?>