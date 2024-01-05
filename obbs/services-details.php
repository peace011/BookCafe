<?php
session_start();
error_reporting(0);


include('includes/dbconnection.php');

// Get the service ID from the URL
$serviceId = $_GET['bookid'];

// Retrieve book details
$sql = "SELECT * FROM tblservice WHERE ID = :serviceId";
$query = $dbh->prepare($sql);
$query->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
$query->execute();
$bookDetails = $query->fetch(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Book Cafe|| About </title>

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




<style>

        .container{
            width:1400px;
            
        }

        .row {
                    display: flex;
                    flex-wrap: wrap;
                }

        .col-md-4 {
            width: 33.3333%;
            box-sizing: border-box;
        }

        .book-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
        }
        /* Basic Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: 2px solid #3498db; /* Border color */
            border-radius: 5px; /* Rounded corners */
            color: #3498db; /* Text color */
            background-color: #d4d8db; /* Background color */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition for hover effects */
        }

        /* Hover State */
        .btn:hover {
            background-color: #3498db; /* Hover background color */
            color: #fff; /* Hover text color */
        }

    </style>



</head>
<body>
	<!-- banner -->
	<div class="banner jarallax">
		<div class="agileinfo-dot">
		<?php include_once('includes/header.php');?>
			<div class="wthree-heading">
				<h2>Services</h2>
			</div>
		</div>
	</div>
	<!-- //banner -->
	<!-- about -->
	<!-- about-top -->


    <div class="container " style="color: #555; margin-left:40px; display:flex;">
    <div class="image">
        <h2><?php echo $bookDetails->ServiceName; ?></h2>
        <p class="font-w600"><?php echo '<img src="' . $bookDetails->ServiceImage . '" alt="Service Image">'; ?></p>
    </div>
        <div class="book" style=" margin-left:100px; margin-top:80px;">
            <p>Name: <?php echo htmlentities($bookDetails->ServiceName); ?></p>
            <p>Author: <?php echo htmlentities($bookDetails->ServiceAuthor); ?></p>
            <p>Description: <?php echo htmlentities($bookDetails->SerDes); ?></p>
            <p>Availability: <?php echo htmlentities($bookDetails->SerAvailable); ?></p>
            <p>Price: $<?php echo htmlentities($bookDetails->ServicePrice); ?></p>

            <p><a href="book-services.php" class="btn">Book Services</a></p>     
            
            <div style="display: flex; align-items: center; margin: 10px 0; font-size: 24px; color: #e74c3c;">
                <?php for ($i = 5; $i >= 1; $i--) { ?>
                    <span onclick="setRating(<?php echo $i; ?>)" style="cursor: pointer; hover:#fff; <?php echo $i === 1 ? 'margin-right: 0;' : ''; ?>">&#9733;</span>
                <?php } ?>
            </div>
         </div>
   
   

                                 




         <script>
        function setRating(rating) {
            // You can handle the rating selection here (e.g., send it to the server)
            alert('Selected Rating: ' + rating);
        }
    </script>



 

      
 


    </div>
    </div>
    </div>
    </div>
    </div>

    </div>
    </div>
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

</body>	
</html> 

