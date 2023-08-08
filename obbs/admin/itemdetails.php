
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
 <title>Book cafe - View Items</title>
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
                    <h2 class="content-heading">View Items</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Register -->
                            <div class="block block-themed">
                                <div class="block-header bg-gd-emerald">
                                    <h3 class="block-title">View Items</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                   
                                    <?php
                 
                 $itemsid=$_GET['itemsid'];
                 $sql="SELECT * from tblitem where ID='$itemsid'";
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
                                                     <th colspan="5" style="text-align: center;font-size: 20px;color: blue;">Item ID: <?php  echo $row->ID;?>
                                                         
                                                     </th>
                                                 </tr>
                                                             <tr>
                     <th>Item Name</th>
                     <td><?php  echo $row->ItemName;?></td>
                
                   </tr>
                   
                 
                   <tr>
                     
                    <th>Item Description</th>
                     <td><?php  echo $row->ItemDes;?></td>
                      <th>Item Price</th>
                     <td><?php  echo $row->ItemPrice;?></td>
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
