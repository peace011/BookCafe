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


// Assuming $books is an array of book names retrieved from the database
// Your actual array of books

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
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->



<!-- For searching algorith Tria -->
<!-- <script type="text/javascript">
    $(document).ready(function () {
        const trie = <?php echo json_encode($trie->root); ?>; // Convert PHP Trie object to JavaScript object

        // Function to perform auto-complete and display suggestions
        function autoComplete(searchTerm) {
            const suggestions = [];
            let node = trie;

            for (let char of searchTerm) {
                if (!node.children[char]) {
                    return suggestions; // Prefix not found, return empty array
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

            // Display suggestions in the auto-complete container
            const autoCompleteContainer = $('#autoCompleteSuggestions');
            autoCompleteContainer.empty();

            for (let suggestion of suggestions) {
                autoCompleteContainer.append('<p>' + suggestion + '</p>');
            }

            // Filter and display books based on the search term
            if (searchTerm === '') {
                $('#bookList tbody tr').show(); // Show all books when search term is empty
            } else {
                $('#bookList tbody tr').each(function () {
                    const bookName = $(this).find('td:nth-child(2)').text().toLowerCase();
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
</script> -->

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
                    return suggestions; // Prefix not found, return empty array
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
                $('#bookList tbody tr').show(); // Show all books when search term is empty
            } else {
                $('#bookList tbody tr').each(function () {
                    const bookName = $(this).find('td:nth-child(2)').text().toLowerCase();
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
	

			<div class="wthree-services-bottom-grids">
				
			
				<p class="wow fadeInUp animated" data-wow-delay=".5s">List of services which is prvided by us.</p>
					<div class="bs-docs-example wow fadeInUp animated" data-wow-delay=".5s">
					<?php if (!empty($books)) { ?>
						<table class="table table-bordered" id="bookList">
							<thead>
								<tr>
									<th>S.N</th>
									<th>Books Name</th>
									<th>Description</th>
									<th>Available</th>

									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							
								<?php

                                $cnt=1;

                                foreach($books as $row)
                                    { ?>
								
								


									<tr>
									<td><?php echo htmlentities($cnt);?></td>
									<td><?php  echo htmlentities($row->ServiceName);?></td>
									<td><?php  echo htmlentities($row->SerDes);?></td>
									<td><?php  echo htmlentities($row->SerAvailable);?></td>
									<td><?php  echo htmlentities($row->ServicePrice);?></td>

                                    <!-- //disabled -->
                                    <?php if ($row->SerAvailable == 0) { ?>
                                     <td><button class="btn btn-default" disabled>Book Services</button></td>
                                    <?php } else { ?>
                                        <!-- dis -->



									 <?php if($_SESSION['obbsuid']==""){?>
										<td><a href="login.php" class="btn btn-default">Book Services</a></td>
									<?php } else {?>
									<td><a href="book-services.php?bookid=<?php echo $row->ID;?>" class="btn btn-default">Book Services</a></td><?php }?>



                                    <!-- dis -->
                                    <?php } ?>
                                    <?php  ?>
                                    
                                    <!-- dis -->



								</tr>
                                 <?php $cnt=$cnt+1;}?> 



							</tbody>
						</table>

						<?php } else { ?>
                        <p>No results found.</p>
                    <?php } ?>

					</div>

		

				<div class="clearfix"> </div>
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

