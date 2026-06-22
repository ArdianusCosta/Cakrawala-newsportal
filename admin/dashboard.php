<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">
        <!-- App title -->
        <title>Cakrawala | Admin Dashboard</title>
		<link rel="stylesheet" href="../plugins/morris/morris.css">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="../plugins/switchery/switchery.min.css">
        <script src="assets/js/modernizr.min.js"></script>

    </head>

    <style>
    .post-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background: #fff;
    }
    .post-thumb {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
    }
    .post-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .badge-category {
        background: #dc3545;
        color: #fff;
        font-size: 12px;
        margin-bottom: 8px;
        display: inline-block;
        padding: 3px 6px;
        border-radius: 4px;
        font-weight: 400;
    }
    .post-meta {
        font-size: 13px;
        color: #666;
    }

    </style>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="index.html" class="logo"><span><span>Admin</span></span><i class="mdi mdi-layers"></i></a>
                    <!-- Image logo -->
                    <!--<a href="index.html" class="logo">-->
                        <!--<span>-->
                            <!--<img src="assets/images/logo.png" alt="" height="30">-->
                        <!--</span>-->
                        <!--<i>-->
                            <!--<img src="assets/images/logo_sm.png" alt="" height="28">-->
                        <!--</i>-->
                    <!--</a>-->
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
            <?php include('includes/topheader.php');?>
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <?php include('includes/leftsidebar.php');?>
            <!-- Left Sidebar End -->



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
                                    <h4 class="page-title">Dashboard</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Cakrawala</a>
                                        </li>
                                        <li class="active">
                                            Dashboard
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->

                        <div class="row">
                        <a href="manage-categories.php">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-newspaper widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="Statistics">Kategori</p>
                                        <?php $query=mysqli_query($con,"select * from tblcategory where Is_Active=1");
                                        $countcat=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countcat);?> <small></small></h2>
                                    
                                    </div>
                                </div>
                            </div>
                        </a><!-- end col -->

                        <a href="manage-subcategories.php">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-layers widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">Sub Kategori</p>
                                        <?php $query=mysqli_query($con,"select * from tblsubcategory where Is_Active=1");
                                        $countsubcat=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countsubcat);?> <small></small></h2>
                              
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </a>

                        <a href="manage-posts.php">                       
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-pencil widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">Artikel</p>
                                        <?php $query=mysqli_query($con,"select * from tblposts where Is_Active=1");
                                        $countposts=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countposts);?> <small></small></h2>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </a>
                
                  
                        </div>
                        <!-- end row -->
   
                    <div class="row">

                        <a href="author-list.php"> 
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-account widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">User Aktif</p>
                                        <?php $query=mysqli_query($con,"select * from tbladmin where Is_Active=1");
                                        $countposts=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countposts);?> <small></small></h2>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="manage-authors.php"> 
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-account-multiple widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">User Berlangganan</p>
                                        <?php $query=mysqli_query($con,"select * from tblauthors where status='approved'");
                                        $countposts=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countposts);?> <small></small></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    
                        <a href="trash-posts.php"> 
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card-box widget-box-one">
                                    <i class="mdi mdi-delete widget-one-icon"></i>
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">Artikel Non Aktif</p>
                                        <?php $query=mysqli_query($con,"select * from tblposts where Is_Active=0");
                                        $countposts=mysqli_num_rows($query);
                                        ?>
                                        <h2><?php echo htmlentities($countposts);?> <small></small></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="row mb-3"> 
                        <div class="col-md-8 themed-grid-col"> 
                            <div class="pb-3">
                                <div class="card-box widget-box-one">
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" 
                                        title="User This Month">Artikel dalam antrian</p>
                                 

                                            <?php
                                            $sql = "
                                                SELECT 
                                                    p.id,
                                                    p.PostTitle,
                                                    p.PostImage,
                                                    p.PostingDate,
                                                    p.views,
                                                    p.PostUrl,
                                                    c.CategoryName,
                                                    s.Subcategory,
                                                    a.AdminUserName as author
                                                FROM tblposts p
                                                LEFT JOIN tblcategory c ON c.id = p.CategoryId
                                                LEFT JOIN tblsubcategory s ON s.SubCategoryId = p.SubCategoryId
                                                LEFT JOIN tbladmin a ON a.id = p.PostedBy
                                                WHERE p.Is_Active = 0
                                                ORDER BY p.PostingDate DESC
                                            ";
                                            $query = mysqli_query($con, $sql);
                                            ?>

                                            <?php if(mysqli_num_rows($query) == 0): ?>
                                                <div class="alert alert-warning mt-2" style="margin-top:20px;">Belum ada artikel pending.</div>
                                            <?php else: ?>
                                                <?php while($row = mysqli_fetch_assoc($query)): ?>
                                                    <div class="post-card mb-3" style="margin-top:20px;">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <?php if($row['PostImage']): ?>
                                                                    <img src="uploads/<?php echo htmlentities($row['PostImage']); ?>" 
                                                                        class="post-thumb" alt="thumbnail">
                                                                <?php else: ?>
                                                                    <img src="assets/images/no-image.png" 
                                                                        class="post-thumb" alt="no image">
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <span class="badge-category">
                                                                    <?php echo htmlentities($row['CategoryName']); ?>
                                                                </span>
                                                                <div class="post-title">
                                                                    <?php echo htmlentities($row['PostTitle']); ?>
                                                                </div>
                                                                    <br>
                                                                <span>
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <br>
                                                                            <div class="post-meta mt-4">
                                                                                <i class="mdi mdi-account"></i> <?php echo htmlentities($row['author']); ?> | 
                                                                                <i class="mdi mdi-calendar"></i> <?php echo date("d M Y", strtotime($row['PostingDate'])); ?> | 
                                                                                <i class="mdi mdi-eye"></i> <?php echo htmlentities($row['views']); ?> views | 
                                                                                <i class="mdi mdi-tag"></i> <?php echo htmlentities($row['Subcategory']); ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 text-right">
                                                                            <div class="mt-2">
                                                                                <a href="approve-post.php?id=<?php echo $row['id']; ?>" 
                                                                                class="btn btn-success btn-sm">Approve</a>
                                                                                <a href="reject-post.php?id=<?php echo $row['id']; ?>" 
                                                                                class="btn btn-danger btn-sm">Reject</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <div class="col-md-4 themed-grid-col">
                                <div class="pb-3">
                                    <div class="card-box widget-box-one">
                                        <div class="wigdet-one-content">
                                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-danger" title="User This Month">Statistik</p>
                                        </div>
                                    </div>
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


            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="mdi mdi-close-circle-outline"></i>
                </a>
                <h4 class="">Settings</h4>
                <div class="setting-list nicescroll">
                    <div class="row m-t-20">
                        <div class="col-xs-8">
                            <h5 class="m-0">Notifications</h5>
                            <p class="text-muted m-b-0"><small>Do you need them?</small></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-xs-8">
                            <h5 class="m-0">API Access</h5>
                            <p class="m-b-0 text-muted"><small>Enable/Disable access</small></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-xs-8">
                            <h5 class="m-0">Auto Updates</h5>
                            <p class="m-b-0 text-muted"><small>Keep up to date</small></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-xs-8">
                            <h5 class="m-0">Online Status</h5>
                            <p class="m-b-0 text-muted"><small>Show your status to all</small></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Right-bar -->

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

        <!-- Counter js  -->
        <script src="../plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="../plugins/counterup/jquery.counterup.min.js"></script>

        <!--Morris Chart-->
		<script src="../plugins/morris/morris.min.js"></script>
		<script src="../plugins/raphael/raphael-min.js"></script>

        <!-- Dashboard init -->
        <script src="assets/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

    </body>
</html>
<?php } ?>