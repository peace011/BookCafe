
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['odmsaid']==0)) {
  header('location:logout.php');
  } 


 





?>
<!doctype html>
 <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
 <title>Book Cafe - View Services</title>
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
                    <h2 class="content-heading"> View Books | Services</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Register -->
                            <div class="block block-themed">
                                <div class="block-header bg-gd-emerald">
                                    <h3 class="block-title">View Books | Services</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                   
                                    <?php
                 
                 $serviceid=$_GET['serviceid'];
                 $sql="SELECT * from tblservice where ID='$serviceid'";
// $sql="SELECT tblservice.ServiceName,tblservice.ServiceAuthor,tblservice.SerDes,tblservice.ServicePrice,tblservice.SerAvailable,tblcategory.CategoryName,tblcategory.ID from tblservice where ID='$booksid' join tblcategory on tblservice.CategoryID=tblcategory.ID where tblservice.ID=:booksid";
// $sql="SELECT * from tblservice  join tblcategory on tblservice.CategoryID=tblcategory.ID where tblservice.ID=:booksid";


                 $query = $dbh -> prepare($sql);
                 $query->execute();
                 $results=$query->fetchAll(PDO::FETCH_OBJ);
                 
                 
                 $cnt=1;
                 if($query->rowCount() > 0)
                 {
                 foreach($results as $row)
                 {               ?>
                                             <table border="1" class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                                                 <tr>
                                                     <th colspan="5" style="text-align: center;font-size: 20px;color: blue;">Book ID: <?php  echo $row->ID;?>
                                                         
                                                     </th>
                                                 </tr>
                                                             <tr>
                     <th>Book Name</th>
                     <td><?php  echo $row->ServiceName;?></td>
                      <th>BookAuthor</th>
                     <td><?php  echo $row->ServiceAuthor;?></td>
                   </tr>
                   
                 
                   <tr>
                     
                    <th>Book Description</th>
                     <td><?php  echo $row->SerDes;?></td>
                      <th>Book Price</th>
                     <td>$<?php  echo $row->ServicePrice;?></td>
                   </tr>
                 
                    <tr>
                    <th>Book Available</th>
                     <td><?php  echo $row->SerAvailable;?></td>
                     <th> Category</th>
                     <td>
                        <?php
                     $categoryID = $row->CategoryID; // Retrieve the CategoryID from $row
        $categorySql = "SELECT CategoryName FROM tblcategory WHERE ID = '$categoryID'";
        $categoryQuery = $dbh->prepare($categorySql);
        $categoryQuery->execute();
        $categoryResult = $categoryQuery->fetch(PDO::FETCH_OBJ);
        if ($categoryQuery->rowCount() > 0) {
            echo $categoryResult->CategoryName;
        }
        ?>
                    </td>
                    
                   </tr>

  
 
<?php $cnt=$cnt+1;}} ?>

</table> 





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
