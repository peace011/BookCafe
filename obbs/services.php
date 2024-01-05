<?php
session_start();
error_reporting(0);

include('includes/dbconnection.php');

$categoryName = $_GET['category'];
$searchTerm = $_GET['search']; 
// Retrieve the categories from the tblcategory table
$sql = "SELECT * FROM tblcategory";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);



//all ra alphaet
// Modify the SQL query based on the selected category or search term
if ($categoryName == 'all') {
    // If 'All' category selected, retrieve all books in alphabetical order
    $sql = "SELECT * FROM tblservice ORDER BY ServiceName ASC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $books = $query->fetchAll(PDO::FETCH_OBJ);
} elseif ($categoryName || $searchTerm) {
    // If specific category or search term is selected
    // Retrieve the books based on the selected category or search term
    if ($categoryName) {
        $sql = "SELECT * FROM tblservice WHERE CategoryID = :categoryName ORDER BY ServiceName ASC";
        $query = $dbh->prepare($sql);
        $query->bindParam(':categoryName', $categoryName, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM tblservice WHERE ServiceName LIKE :searchTerm ORDER BY ServiceName ASC";
        $query = $dbh->prepare($sql);
        $query->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    }
    $query->execute();
    $books = $query->fetchAll(PDO::FETCH_OBJ);
} else {
    // If no category is selected and no search term, retrieve all books in alphabetical order
    $sql = "SELECT * FROM tblservice ORDER BY ServiceName ASC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $books = $query->fetchAll(PDO::FETCH_OBJ);
}




// Trie Class for Auto-complete
class TrieNode {
    public $children = [];
    public $isEndOfWord;

    public function __construct() {
        $this->isEndOfWord = false;
    }
}

class Trie {
    public $root;

    public function __construct() {
        $this->root = new TrieNode();
    }

    public function insert($word) {
        $node = $this->root;
        for ($i = 0; $i < strlen($word); $i++) {
            $char = strtolower($word[$i]);
            if (!isset($node->children[$char])) {
                $node->children[$char] = new TrieNode();
            }
            $node = $node->children[$char];
        }
        $node->isEndOfWord = true;
    }
}




$trie = new Trie(); // Create a new Trie object

// Insert all lowercased book names into the trie
foreach ($books as $book) {
    $trie->insert(strtolower($book->ServiceName)); // Insert lowercased book names
}



// / Retrieve the categories from the tblcategory table
$sql = "SELECT * FROM tblcategory";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);


// if (isset($_SESSION['obbsuid'])) {
//     echo "<p>User ID: " . $_SESSION['obbsuid'] . "</p>";
// } else {
//     echo "<p>User ID not set</p>";
// }

// Example PHP code for recommendation generation
// $userID = $_SESSION['obbsuid'];
// echo "<p>User ID: " . $_SESSION['obbsuid'] . "</p>";
// // $bookID = $_GET['bookID'];

function calculateSimilarity($service1, $service2)
{
    $similarity = 0;

    // Add more similarity criteria as needed
    $author1 = trim(mb_strtolower($service1->ServiceAuthor, 'UTF-8'));
    $author2 = trim(mb_strtolower($service2->ServiceAuthor, 'UTF-8'));

    if ($author1 === $author2) {
        $similarity += 1;
        // echo "Author Matched: +1\n";
    }
  if ($service1->CategoryID == $service2->CategoryID) {
        $similarity += 1;
        // echo "Category ID Matched: +1\n";
    }
    return $similarity;
}


function getUserBookingHistory($userId)
{
    global $dbh;

    $sql = "SELECT DISTINCT s.* 
            FROM tblbooking b 
            JOIN tblservice s ON b.ServiceID = s.ID
            WHERE b.UserID = :userId";

    try {
        $query = $dbh->prepare($sql);
        $query->bindParam(':userId', $userId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
          // Debugging statements
        // echo "<pre>User Booking History Query: " . $sql . "</pre>";
        // echo "<pre>User Booking History Result: ";
        // print_r($result);
        // echo "</pre>";
        return $result;
    } catch (PDOException $e) {
        // Handle the exception appropriately, e.g., log the error
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array in case of an error
    }
}

// Retrieve all services from the tblservice table
$sql = "SELECT * FROM tblservice";
$query = $dbh->prepare($sql);
$query->execute();
$services = $query->fetchAll(PDO::FETCH_OBJ);

// Simulate a user making a booking
$userId = $_SESSION['obbsuid'];

// Calculate similarity for each service based on the user's booking history
$userBookingHistory = getUserBookingHistory($userId);

// Extract booked service IDs from the user's booking history
$bookedServiceIds = array_map(function ($booking) {
    // echo "Booked Service ID: {$booking->ID}\n"; // Add this line to print the booked service ID
    return $booking->ID;
    return $booking->ID;
}, $userBookingHistory);

// Filter out booked services from the list
$services = array_filter($services, function ($service) use ($bookedServiceIds) {
    return !in_array($service->ID, $bookedServiceIds);
});

foreach ($services as $service) {
    $totalSimilarity = 0;

    foreach ($userBookingHistory as $booking) {
        $totalSimilarity += calculateSimilarity($service, $booking);
        
        // Print statements for debugging
        // echo "Similarity between {$service->ServiceName} and Booking ID {$booking->ID}: $totalSimilarity\n";
    }
    // / Print total similarity for the current service
    // echo "Total Similarity for {$service->ServiceName}: $totalSimilarity\n";

    $service->totalSimilarity = $totalSimilarity;
}

// Sort services by total similarity in descending order
usort($services, function ($a, $b) {
    return $b->totalSimilarity - $a->totalSimilarity;
});

// Get top recommended services
$numberOfRecommendations = 15;
$topRecommendations = array_slice($services, 0, $numberOfRecommendations);

// Display recommended services
// echo "<h3>Recommended Services:</h3>";
foreach ($topRecommendations as $recommendedService) {
    // echo '<p>' . $recommendedService->ServiceName . ' by ' . $recommendedService->ServiceAuthor . '</p>';
}
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


<!-- Add the following JavaScript after including jQuery -->
<script type="text/javascript">
    $(document).ready(function () {
        const trie = <?php echo json_encode($trie->root); ?>; // Convert PHP Trie object to JavaScript object

        // Function to perform auto-complete and display suggestions
        function autoComplete(searchTerm) {
            const suggestions = [];
            let node = trie;

            for (let char of searchTerm) {
                if (!node.children[char]) {
                    return suggestions; // Prefix not found, return an empty array
                }
                node = node.children[char];
            }

            // Traverse the Trie to find all words with the given prefix
            findWordsWithPrefix(node, searchTerm, suggestions);
            return suggestions;
        }

        function findWordsWithPrefix(node, prefix, suggestions) {
            if (node.isEndOfWord) {
                suggestions.push(prefix);
            }

            for (let char in node.children) {
                findWordsWithPrefix(node.children[char], prefix + char, suggestions);
            }
        }

        // Event listener for the search input field
        $('#searchInput').on('input', function () {
            const searchTerm = $(this).val().trim().toLowerCase(); // Trim whitespace from the search term
            const suggestions = autoComplete(searchTerm);

            // Clear the autoCompleteContainer to remove previous suggestions
            const autoCompleteContainer = $('#autoCompleteSuggestions');
            autoCompleteContainer.empty();

            // Display suggestions in the auto-complete container if searchTerm is not empty
            if (searchTerm !== '') {
                for (let suggestion of suggestions) {
                    autoCompleteContainer.append('<p>' + suggestion + '</p>');
                }
            }

            // Filter and display books based on the search term
            if (searchTerm === '') {
                $('.book-item').show(); // Show all books when the search term is empty
            } else {
                $('.book-item').each(function () {
                    const bookName = $(this).find('.book-title').text().toLowerCase();
                    // Check if the book name starts with the search term
                    if (bookName.startsWith(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
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

        .book-item{
            width: 25%;
            box-sizing: border-box;
         
            
        }
        .recom-book-item{
            box-sizing: border-box;
            margin-right: 3%; 
            margin-left: 3%; 
            width:100%; 
        }

        .book-info {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 25px; 
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;

        }
        .recom-book-info {
          border: 1px solid #ddd;
            padding: 10px;
            margin: 6px;  
            width:210px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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

        .slider-container {
            overflow: hidden;
            position: relative;
        }

        .slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;

        }

        .recom-row {
                    display: flex;
                }

            .prev-btn,
            .next-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                padding: 10px;
                background-color: #3498db;
                color: #fff;
                border: none;
                cursor: pointer;
            }

            .prev-btn {
                left: 0;
            }

            .next-btn {
                right: 0;
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

	<div class="content-side content-side-full">

	<form action="" method="get">
        <input type="text" name="search" id="searchInput" placeholder="Search Books">
        <input type="submit" value="Search">
		<!-- <div id="search-suggestions"></div> -->
		<div id="autoCompleteSuggestions"></div>
		

    </form>



                            <ul class="nav-main">
                            
                                    <h2>Categories</h2>
									<li><a href="?category=all">All</a></li>
						



								<?php
            // Display the categories
            foreach ($categories as $cat) {
                echo "<li><a href='?category=" . $cat->ID. "'>" . $cat->CategoryName . "</a></li>";
            }
			?>
			
                                   
                                       
                                  
                              
</div>


<div class="about-top">
        <div class="container">
        <?php
        if (isset($categoryName) && $categoryName != 'all') {
            echo '<h2> Category: ' . getCategoryName($dbh, $categoryName) . '</h2>';
        }
        ?>
            <div class="wthree-services-bottom-grids">
                <p class="wow fadeInUp animated" data-wow-delay=".5s">List of services which is provided by us.</p>
                <div class="bs-docs-example wow fadeInUp animated " data-wow-delay=".5s">
                    <?php if (!empty($books)) { ?>
                        <div id="bookList" class="row">
                            <?php
                            $cnt = 1;
                            foreach ($books as $row) {
                                ?>
                                <div class="book-item">
                                    <div class="book-info">
                                    <a href="services-details.php?bookid=<?php echo $row->ID; ?>"><?php echo '<img src="' . $row->ServiceImage . '" alt="Service Image" style="max-width: 200px; max-height: 200px;  margin-bottom: 11px; ">'; ?></a>
                                        <p class="book-title" style="font-weight: bold; font-size: larger; "><a href="services-details.php?bookid=<?php echo $row->ID;?>" style="color:black; text-decoration: none;"><?php echo htmlentities($row->ServiceName);?></a></p>
                                        <p class="book-author">By: <?php echo htmlentities($row->ServiceAuthor); ?></p>
                                        <p class="book-availability">Availability:<?php echo htmlentities($row->SerAvailable); ?></p>
                                        <p class="book-price" style="font-weight: bold; ">Price:Rs.<?php echo htmlentities($row->ServicePrice); ?></p>
                                   
                                    <!-- //disabled -->
                                    <?php if ($row->SerAvailable == 0) { ?>
                                        <button class="btn btn-default" disabled>Book Services</button>
                                    <?php } else { ?>
                                        <!-- dis -->
                                        <?php if ($_SESSION['obbsuid'] == "") { ?>
                                            <a href="login.php" class="btn btn-default">Book Services</a>
                                        <?php } else { ?>
                                            <a href="book-services.php?bookid=<?php echo $row->ID; ?>"
                                                class="btn btn-default">Book Services</a>
                                        <?php } ?>
                                        <!-- dis -->
                                    <?php } ?>
                                    <!-- dis -->
                                    </div>
                                </div>
                                <?php
                                $cnt++;
                            }
                            ?>
                        </div>
                    <?php } else { ?>
                        <p>No results found.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

        <!-- Displaying selected category -->
        <?php
            function getCategoryName($dbh, $categoryId)
            {
                $sql = "SELECT CategoryName FROM tblcategory WHERE ID = :categoryId";
                $query = $dbh->prepare($sql);
                $query->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);

                return ($result) ? htmlentities($result['CategoryName']) : 'Unknown Category';
            }
            ?>



				<div class="clearfix"> </div>
                
			</div>
		</div>




    <!-- Book Recommendations                         -->
    <div class="about-top">
    <div class="container">
        <div class="wthree-services-bottom-grids">
            <h3 style="font-weight: bold;">Recommended Books</h3>
            <div class="bs-docs-example wow fadeInUp animated" data-wow-delay=".5s">
                <?php if (!empty($books)) { ?>
                    <div id="bookList" class="row">
                        <div class="slider-container">
                        
                            <!-- Slider Track -->
                            <div class="slider-track recom-row">
                                <?php foreach ($topRecommendations as $row) { ?>
                                    <div class="recom-book-item">
                                        <div class="recom-book-info" style="font-size: medium;">
                                            <a href="services-details.php?bookid=<?php echo $row->ID; ?>">
                                                <?php echo '<img src="' . $row->ServiceImage . '" alt="Service Image" style="max-width: 200px; max-height: 200px; margin-bottom: 13px; ">'; ?>
                                            </a>
                                            <p class="book-title" style="font-weight: bold; font-size: larger; font-weight: bold;">
                                                <a href="services-details.php?bookid=<?php echo $row->ID;?>" style="color:black; text-decoration: none;">
                                                    <?php echo htmlentities($row->ServiceName);?>
                                                </a>
                                            </p>
                                            <p class="book-author">By: <?php echo htmlentities($row->ServiceAuthor); ?></p>
                                            <p class="book-availability">Availability: <?php echo htmlentities($row->SerAvailable); ?></p>
                                            <p class="book-price" style="font-weight: bold;">Price: Rs.<?php echo htmlentities($row->ServicePrice); ?></p>
                                            <!-- //disabled -->
                                            <?php if ($row->SerAvailable == 0) { ?>
                                                <button class="btn btn-default" disabled>Book Services</button>
                                            <?php } else { ?>
                                                <!-- dis -->
                                                <?php if ($_SESSION['obbsuid'] == "") { ?>
                                                    <a href="login.php" class="btn btn-default">Book Services</a>
                                                <?php } else { ?>
                                                    <a href="book-services.php?bookid=<?php echo $row->ID; ?>" class="btn btn-default">Book Services</a>
                                                <?php } ?>
                                                <!-- dis -->
                                            <?php } ?>
                                            <!-- dis -->
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                                <!-- Previous Button -->
                            <button class="prev-btn" onclick="moveSlider('prev')">&#10094; </button>

                            <!-- Next Button -->
                            <button class="next-btn" onclick="moveSlider('next')"> &#10095;</button>
                        </div>
                    </div>
                <?php } else { ?>
                    <p>No results found.</p>
                <?php } ?>
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


<!-- For next and dprevious slider recommendation -->
<!-- Add the following JavaScript after including jQuery  -->
<script>
    var currentPosition = 0;
    const bookItemWidth = $('.recom-book-item').outerWidth(true);
    const totalBooks = <?php echo count($topRecommendations); ?>;

    function moveSlider(direction) {
        const maxPosition = (totalBooks - 5) * bookItemWidth; // Display 5 books at a time

        if (direction === 'next' && currentPosition > -maxPosition) {
            currentPosition -= bookItemWidth;
        } else if (direction === 'prev' && currentPosition < 0) {
            currentPosition += bookItemWidth;
        }

        $('.slider-track').css('transform', 'translateX(' + currentPosition + 'px)');
    }
</script>

</body>	
</html> 

