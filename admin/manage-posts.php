<?php 
session_start();
$role = $_SESSION['role']; 
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{
// Soft delete (pindah ke Trash)
if(isset($_GET['action']) && $_GET['action']=='del'){
    $postid = intval($_GET['pid']);
    $query = mysqli_query($con,"UPDATE tblposts SET Is_Active=3 WHERE id='$postid'");
    if($query){
        $msg = "Post moved to Trash";
    } else {
        $error = "Something went wrong. Please try again.";    
    } 
}

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    
        <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
        <!-- App title -->
        <title>Cakrawala | Kelola Artikel</title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="../plugins/morris/morris.css">

        <!-- jvectormap -->
        <link href="../plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="../plugins/switchery/switchery.min.css">
        <link rel="stylesheet" href="../plugins/datatables/jquery.dataTables.min.css">

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
           <?php include('includes/topheader.php');?>

            <!-- ========== Left Sidebar Start ========== -->
           <?php include('includes/leftsidebar.php');?>


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Kelola Artikel </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Cakrawala</a>
                                        </li>
                                      
                                        <li class="active">
                                            Kelola Artikel 
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->

                        <div class="row">
                            
                                <div class="card-box">
                                    <div class="table-responsive">
                                        <table id="managePostsTable" class="table table-colored table-centered table-inverse m-0">
                                        <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Kategori</th>
                                            <th>Sub Kategori</th>
                                            <th>Penulis</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $role = $_SESSION['role'];
                                        $userId = $_SESSION['id']; // id admin yg login (simpan waktu login)
                                        $sql = "
                                            SELECT 
                                                tblposts.id as postid,
                                                tblposts.PostTitle as title,
                                                tblcategory.CategoryName as category,
                                                tblsubcategory.Subcategory as subcategory,
                                                tblposts.Is_Active as status,
                                                tblposts.PostingDate as date,
                                                tbladmin.AdminUserName as author
                                            FROM tblposts
                                            LEFT JOIN tblcategory ON tblcategory.id=tblposts.CategoryId
                                            LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId=tblposts.SubCategoryId
                                            LEFT JOIN tbladmin ON tbladmin.id=tblposts.PostedBy
                                            WHERE tblposts.Is_Active != 3
                                        ";

                                        if($role == 3){
                                            $sql .= " AND tblposts.PostedBy = '$userId' ";
                                        }

                                        $sql .= " ORDER BY tblposts.id DESC";
                                        $query = mysqli_query($con, $sql);

                                        $rowcount=mysqli_num_rows($query);
                                        if($rowcount==0)
                                        {
                                        ?>
                                        <tr>
                                        <td colspan="6" align="center"><h3 style="color:red">No record found</h3></td>
                                        </tr>
                                        <?php 
                                        } else {
                                        while($row=mysqli_fetch_array($query))
                                        {
                                        ?>
                                        <tr>
                                        <td><b><?php echo htmlentities($row['title']);?></b></td>
                                        <td><?php echo htmlentities($row['category']);?></td>
                                        <td><?php echo htmlentities($row['subcategory']);?></td>
                                        <td><?php echo htmlentities($row['author']);?></td>
                                        <td>
                                            <?php 
                                            if($row['status']==1) {
                                                echo "<span class='badge badge-success'>Disetujui</span>";
                                            } elseif($row['status']==0) {
                                                echo "<span class='badge badge-warning'>Menunggu</span>";
                                            } elseif($row['status']==2) {
                                                echo "<span class='badge badge-danger'>Ditolak</span>";
                                            } elseif($row['status']==3) {
                                                echo "<span class='badge badge-danger'>Terhapus</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                                <a href="edit-post.php?pid=<?php echo htmlentities($row['postid']);?>">
                                                <i class="fa fa-pencil" style="color: #29b6f6;"></i>
                                                </a> 
                                                &nbsp;
                                                <a href="manage-posts.php?pid=<?php echo htmlentities($row['postid']);?>&action=del" 
                                                onclick="return confirm('Do you really want to delete ?')">
                                                <i class="fa fa-trash-o" style="color: #f05050"></i>
                                                </a>

                                            <?php 
                                            // Hanya tampil kalau post belum di-approve/reject
                                            if($row['status'] == 0) {
                                                // Role 1 (Admin) dan Role 2 (Staff) bisa approve/reject
                                                if($_SESSION['role'] == 1 || $_SESSION['role'] == 2){ ?>
                                                    &nbsp;
                                                    <a href="approve-post.php?id=<?php echo htmlentities($row['postid']);?>" 
                                                    class="btn btn-xs btn-success">Setuju</a>
                                                    &nbsp;
                                                    <a href="reject-post.php?id=<?php echo htmlentities($row['postid']);?>" 
                                                    class="btn btn-xs btn-danger">Tolak</a>
                                            <?php 
                                                }
                                            } 
                                            ?>
                                        </td>

                                        </tr>
                                        <?php } } ?>
                                                                                
                                        </tbody>
                                        </table>
                                        </div>

                                </div>
                            
                        </div>



                    </div> <!-- container -->

                </div> <!-- content -->

       <?php include('includes/footer.php');?>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->



        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="../plugins/switchery/switchery.min.js"></script>

        <!-- CounterUp  -->
        <script src="../plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="../plugins/counterup/jquery.counterup.min.js"></script>

        <!--Morris Chart-->
		<script src="../plugins/morris/morris.min.js"></script>
		<script src="../plugins/raphael/raphael-min.js"></script>

        <!-- Load page level scripts-->
        <script src="../plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="../plugins/jvectormap/gdp-data.js"></script>
        <script src="../plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>


        <!-- Dashboard Init js -->
		<script src="assets/pages/jquery.blog-dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../plugins/datatables/dataTables.bootstrap.js"></script>

        <script>
            $(document).ready(function () {
                $('#managePostsTable').DataTable({
                    responsive: true,
                    order: [],
                    columnDefs: [{ targets: [5], orderable: false }],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        emptyTable: "Tidak ada data yang tersedia",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });
            });
        </script>

    </body>
</html>
