<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['odmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {


$itemname=$_POST['itemname'];
$itemdes=$_POST['itemdes'];
$itemprice=$_POST['itemprice'];



$sql="insert into tblitem(ItemName,ItemDes,ItemPrice)values(:itemname,:itemdes,:itemprice)";
$query=$dbh->prepare($sql);
$query->bindParam(':itemname',$itemname,PDO::PARAM_STR);
$query->bindParam(':itemdes',$itemdes,PDO::PARAM_STR);
$query->bindParam(':itemprice',$itemprice,PDO::PARAM_STR);



 $query->execute();

   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Items has been added.")</script>';
echo "<script>window.location.href ='add-items.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
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
                    <h2 class="content-heading">Add Items</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Register -->
                            <div class="block block-themed">
                                <div class="block-header bg-gd-emerald">
                                    <h3 class="block-title"> Items</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                   
                                    <form method="post">

                                    <div class="form-group row">
                                            <!-- <label class="col-12" for="register1-email">Service Type:</label> -->
                                            <!-- <div class="col-12">
                                            <select name="bookname" class="form-control" required='true' >
                                                
                                                        <option value="books">Books</option>
                                                      
                                                    </select> 
                                                   

                                            </div> -->
                                        <!-- </div> -->
                                        
                                        <div class="form-group row">
                                                 <label class="col-12" for="register1-email">Item Name:</label>
                                                <div class="col-12">
                                                <input type="text" class="form-control" name="itemname" value="" required='true'> 
                                                   

                                            </div>
                                        </div>
                                        <br/><br/>

                                        <!-- <label class="col-12" for="register1-email"> Author:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="serauthor" value="" required='true'> 
                                                   

                                            </div>
                                        </div> -->

                                        <div class="form-group row">
                                            <label class="col-12" for="register1-email">Item Description:</label>
                                            <div class="col-12">
                                                 <textarea type="text" class="form-control" name="itemdes" value="" required='true'></textarea>
                                            </div>
                                        </div><br/><br/>

                                        <div class="form-group row">
                                            <label class="col-12" for="register1-password">Item Price:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="itemprice" value="" required='true'>
                                              
                                            </div>
                                        </div><br/><br/>
                                        <!-- <div class="form-group row">
                                            <label class="col-12" for="register1-password">Book Available:</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="seravailable" value="" required='true'>
                                              
                                            </div>
                                        </div> -->

                                       
                                       
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