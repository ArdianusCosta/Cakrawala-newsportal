<?php
session_start();
include('includes/config.php');

// hanya admin superadmin
if($_SESSION['role'] != 1) {
    header("Location: dashboard.php");
    exit;
}

// Approve Author
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    $today = date("Y-m-d");
    $end_date = date("Y-m-d", strtotime("+30 days"));

    $stmt = $con->prepare("UPDATE tblauthors SET status='approved', start_date=?, end_date=? WHERE id=?");
    $stmt->bind_param("ssi", $today, $end_date, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Author approved for 30 days!";
    header("Location: manage-authors.php");
    exit;
}

// Reject Author
if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    $stmt = $con->prepare("UPDATE tblauthors SET status='rejected' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Author rejected!";
    header("Location: manage-authors.php");
    exit;
}

// Hapus author
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM tblauthors WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Author deleted successfully!";
    header("Location: manage-authors.php");
    exit;
}

// Ambil data authors
$query = $con->query("SELECT a.*, u.AdminUserName, u.AdminEmailId 
                      FROM tblauthors a 
                      JOIN tbladmin u ON a.user_id = u.id 
                      ORDER BY a.id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- App title -->
        <title>Cakrawala | Manage Authors</title>

        <!-- Summernote css -->
        <link href="../plugins/summernote/summernote.css" rel="stylesheet" />

        <!-- Select2 -->
        <link href="../plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <!-- Jquery filer css -->
        <link href="../plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
        <link href="../plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet" />

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

<body class="fixed-left">

<div class="wrapper">
<?php include('includes/topheader.php');?>
<?php include('includes/leftsidebar.php');?>

<div class="content-page">
    <div class="content">
        <div class="container">
            
                         <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Daftar Langganan </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Pengguna</a>
                                        </li>
                                        <li class="active">
                                            Daftar Langganan
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->

            <?php if(isset($_SESSION['msg'])): ?>
                <div class="alert alert-info"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penulis</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                    $cnt=1;
                    $modals = ""; // tampung semua modal
                    while($row = $query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo htmlentities($row['AdminUserName']); ?></td>
                            <td><?php echo htmlentities($row['AdminEmailId']); ?></td>
                            <td>
                                <?php if($row['status']=='approved'): ?>
                                    <span class="badge badge-success">Approved</span>
                                <?php elseif($row['status']=='pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['start_date'] ?: '-'; ?></td>
                            <td><?php echo $row['end_date'] ?: '-'; ?></td>
                            <td>
                                <?php if($row['payment_proof']): ?>
                                    <button type="button" class="btn btn-info btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#proofModal<?php echo $row['id']; ?>">
                                        View
                                    </button>
                                <?php else: ?>
                                    No Proof
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $today = date("Y-m-d");
                                if($row['status']=='approved' && $row['end_date'] >= $today) {
                                    echo "<span class='badge badge-success'>Active</span>";
                                } elseif($row['status']=='approved' && $row['end_date'] < $today) {
                                    echo "<span class='badge badge-danger'>Expired</span>";
                                } else {
                                    echo "<span class='badge badge-secondary'>N/A</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($row['status']=='pending'): ?>
                                    <a href="manage-authors.php?approve=<?php echo $row['id'];?>" 
                                    class="btn btn-sm btn-success">Approve</a>
                                    <a href="manage-authors.php?reject=<?php echo $row['id'];?>" 
                                    class="btn btn-sm btn-warning">Reject</a>
                                <?php endif; ?>
                                <a href="manage-authors.php?delete=<?php echo $row['id'];?>" 
                                onclick="return confirm('Delete this author?')" 
                                class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        
                        <?php 
                        // kumpulkan modal di variabel $modals
                        $modals .= '
                        <div class="modal fade" id="proofModal'.$row['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-white text-center">
                                        <h4 class="modal-title">Bukti Pembayaran - '.htmlentities($row['full_name']).'</h4>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <td>'.htmlentities($row['full_name']).'</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pemilik Rekening</th>
                                                <td>'.htmlentities($row['bank_name']).'</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Rekening</th>
                                                <td>'.htmlentities($row['bank_account']).'</td>
                                            </tr>
                                            <tr>
                                                <th>Bukti Pembayaran</th>
                                                <td>';
                                                if($row['payment_proof']){
                                                    $modals .= '<img src="proof/'.$row['payment_proof'].'" 
                                                        alt="Payment Proof" class="img-fluid img-thumbnail" style="max-height:600px;">';
                                                } else {
                                                    $modals .= 'No proof uploaded.';
                                                }
                                                $modals .= '</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        ?>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Render semua modal di luar tabel -->
            <?php echo $modals; ?>

        </div>
        </div>
    </div>
<?php include('includes/footer.php');?>
</div>

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

</body>
</html>
