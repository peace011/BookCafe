<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['odmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        // Check if the "serimage" key exists in the $_FILES array
        if (isset($_FILES["serimage"])) {
            $sername = $_POST['sername'];
            $serauthor = $_POST['serauthor'];
            $serdes = $_POST['serdes'];
            $serprice = $_POST['serprice'];
            $seravailable = $_POST['seravailable'];
            $category = $_POST['category'];

            $targetDirectory = "uploads/";
            $targetFile = $targetDirectory . basename($_FILES["serimage"]["name"]);

            // Check if the file is an actual image
            $check = getimagesize($_FILES["serimage"]["tmp_name"]);
            if ($check !== false) {
                // File is an image
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($_FILES["serimage"]["tmp_name"], $targetFile)) {
                    $sql = "INSERT INTO tblservice(ServiceName,ServiceAuthor,SerDes,ServicePrice,SerAvailable,CategoryID,ServiceImage)VALUES(:sername,:serauthor,:serdes,:serprice,:seravailable,:category,:imagepath)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':sername', $sername, PDO::PARAM_STR);
                    $query->bindParam(':serauthor', $serauthor, PDO::PARAM_STR);
                    $query->bindParam(':serdes', $serdes, PDO::PARAM_STR);
                    $query->bindParam(':serprice', $serprice, PDO::PARAM_STR);
                    $query->bindParam(':seravailable', $seravailable, PDO::PARAM_STR);
                    $query->bindParam(':category', $category, PDO::PARAM_STR);
                    $query->bindParam(':imagepath', $targetFile, PDO::PARAM_STR);

                    $query->execute();

                    $LastInsertId = $dbh->lastInsertId();
                    if ($LastInsertId > 0) {
                        echo '<script>alert("Books | Services has been added.")</script>';
                        echo "<script>window.location.href ='add-services.php'</script>";
                    } else {
                        echo '<script>alert("Something Went Wrong. Please try again")</script>';
                    }
                } else {
                    echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
                }
            } else {
                echo '<script>alert("File is not an image.")</script>';
            }
        } else {
            echo '<script>alert("Please select a file to upload.")</script>';
        }
    }

?>
<!doctype html>
 <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
 <title>Book Cafe - Add Services</title>
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
                    <h2 class="content-heading">Add Services</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Register -->
                            <div class="block block-themed">
                                <div class="block-header bg-gd-emerald">
                                    <h3 class="block-title">Books | Services</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                   
                                    <form method="post" enctype="multipart/form-data">

                                    <div class="form-group row">
                                          
                                        
                                        <div class="form-group row">
                                                 <label class="col-12" for="register1-email">Book Name:</label>
                                                <div class="col-12">
                                                <input type="text" class="form-control" name="sername" value="" required='true'> 
                                                   

                                            </div>
                                        </div>

                                        <!-- Add this inside your form -->
                                             <div class="form-group row">
                                            <label class="col-12" for="register1-password">Book Image:</label>
                                            <div class="col-12">
                                                <input type="file" class="form-control" name="serimage">
                                            </div>
                                        </div>


                                        <!-- <div class="form-group row"> -->
                                        <label class="col-12" for="register1-email">Book Author:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="serauthor" value="" required='true'> 
                                                   

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="register1-email">Book Description:</label>
                                            <div class="col-12">
                                                 <textarea type="text" class="form-control" name="serdes" value="" required='true'></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="register1-email">Book Category:</label>
                                            <div class="col-12">
                                       <select type="text" class="form-control" name="category" required="true" >
							 	<option value="">Choose Book category</option>
							 	<?php 

$sql2 = "SELECT * from   tblcategory ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);

foreach($result2 as $row)
{          
    ?>  
<option value="<?php echo htmlentities($row->ID);?>"><?php echo htmlentities($row->CategoryName);?></option>
 <?php } ?>
							 </select>
                                    </div>
</div>

                                        <div class="form-group row">
                                            <label class="col-12" for="register1-password">Book Price:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="serprice" value="" required='true'>
                                              
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-12" for="register1-password">Book Available:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="seravailable" value="" required='true'>
                                              
                                            </div>
                                        </div>

                                       
                                       
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-alt-success" name="submit">
                                                    <i class="fa fa-plus mr-5"></i> Add
                                                </button>
                                            </div>
                                        </div>
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