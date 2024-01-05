<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['odmsaid']==0)) {
  header('location:logout.php');
  } else{

// Code for deleting product from cart
if(isset($_GET['delid']))
{
$rid=intval($_GET['delid']);
$sql="delete from tbltableavailability where ID=:rid";
$query=$dbh->prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
 echo "<script>alert('Data deleted');</script>"; 
  echo "<script>window.location.href = 'manage-available-event-type.php'</script>";     


}

  ?>
<!doctype html>
<html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
        <title>Book Cafe - Manage Table</title>

        <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">

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
                    <h2 class="content-heading">Manage Available Table</h2>

                   

                    <!-- Dynamic Table Full Pagination -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Manage Available Table</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full-pagination class, functionality initialized in js/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                                <thead>
                                    <tr>
                                        <th class="text-center">S.N</th>
                                        <th> Available Table Type</th>
                                        <th> Available Table ID</th>
                                        <th> Available Table Date</th>
                                        <th>  Start Time</th>
                                        <th>  End Time</th>
                                        <th> Available Table Status</th>
                                       
                                        
                                        <th class="d-none d-sm-table-cell">Creation Date</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Action</th>
                                       </tr>
                                </thead>
                                <tbody>
                                    <?php
$sql="SELECT * from tbltableavailability";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                    <tr>
                                        <td class="text-center"><?php echo htmlentities($cnt);?></td>

                                        <td class="font-w600"><?php  echo htmlentities($row->TableType);?></td>
                                        <td class="font-w600"><?php  echo htmlentities($row->TableID);?></td>
                                        <td class="font-w600"><?php  echo htmlentities($row->AvailableDate);?></td>
                                        <!-- <td class="font-w600"><?php  echo htmlentities($row->AvailableTime);?></td>
                                        <td class="font-w600"><?php  echo htmlentities($row->AvailableEndTime);?></td> -->
                                        <td class="font-w600"><?php echo date('h:i A', strtotime($row->AvailableTime));?></td>
                                        <td class="font-w600"><?php echo date('h:i A', strtotime($row->AvailableEndTime));?></td>

                                        <td class="font-w600"> <?php  $tablestatus=$row->AvailableStatus;
    
    if( $tablestatus=="1")
    {
      echo "Active";
    }
    
    if( $tablestatus=="0")
    {
     echo "Inactive";
    }
    ?></td>

                                        <td class="d-none d-sm-table-cell"><?php  echo htmlentities($row->CreationDate);?></td>
                                       
                                         <td class="d-none d-sm-table-cell"><a href="manage-available-event-type.php?delid=<?php echo ($row->ID);?>" onclick="return confirm('Do you really want to Delete ?');"><i class="fa fa-trash fa-delete" aria-hidden="true"></i></a></td>
                                         <td class="d-none d-sm-table-cell">
                                             <a href="edit-available-tables.php?availabletableid=<?php echo ($row->ID);?>"><button>Edit</button></a>
                                        </td>
                                    </tr>
                                    <?php $cnt=$cnt+1;}} ?> 
                                
                                
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Dynamic Table Full Pagination -->

                    <!-- END Dynamic Table Simple -->
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

        <!-- Page JS Plugins -->
        <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page JS Code -->
        <script src="assets/js/pages/be_tables_datatables.js"></script>
    </body>
</html>
<?php }  ?>