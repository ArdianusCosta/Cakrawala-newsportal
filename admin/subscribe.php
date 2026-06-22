<?php
session_start();
include('includes/config.php');

if($_SESSION['role'] != 3){
    header("Location: penulis.php");
    exit;
}

$msg = '';
$error = '';

if(isset($_POST['submit'])){
    $user_id      = $_SESSION['id'];
    $full_name    = $_POST['full_name'];
    $bank_name    = $_POST['bank_name'];
    $bank_account = $_POST['bank_account'];

    // handle file upload
    if(isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0){
        $ext = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
        $new_name = time().'_'.$user_id.'.'.$ext;
        $target = __DIR__ . "/proof/" . $new_name;

        // buat folder kalau belum ada
        if(!is_dir(__DIR__ . "/proof")){
            mkdir(__DIR__ . "/proof", 0777, true);
        }

        if(move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target)){
            // simpan ke db
            $stmt = $con->prepare("INSERT INTO tblauthors 
                (user_id, full_name, bank_name, bank_account, payment_proof, status, created_at) 
                VALUES (?,?,?,?,?, 'pending', NOW())");
            $stmt->bind_param("issss", $user_id, $full_name, $bank_name, $bank_account, $new_name);
            $stmt->execute();
            $stmt->close();

            $_SESSION['msg'] = "✅ Bukti pembayaran berhasil diupload, menunggu verifikasi admin.";
            header("Location: subscribe.php");
            exit;
        } else {
            $error = "❌ Gagal upload bukti pembayaran!";
        }
    } else {
        $error = "❌ Harap pilih file bukti pembayaran!";
    }
}
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
        <title>Cakrawala | Subscribe</title>

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

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>

    </head>
<body>

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

                    <div class="container mt-5">
                        <h3>Langganan Penulis</h3>
                        <p>Untuk bisa upload artikel, silakan transfer Rp100.000 ke rekening admin dan upload bukti transaksi di bawah ini.</p>

                       <?php if(isset($_SESSION['msg'])): ?>
                            <div class="mb-3">
                                <span class="badge badge-success p-2">
                                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error)): ?>
                            <div class="mb-3">
                                <span class="badge badge-danger p-2">
                                    <?php echo $error; ?>
                                </span>
                            </div>
                        <?php endif; ?>


                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" name="bank_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Nomor Rekening</label>
                                <input type="text" name="bank_account" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Upload Bukti Pembayaran (JPG/PNG)</label>
                                <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                            </div>

                            <button type="submit" name="submit" class="btn btn-success mt-3">Kirim Bukti</button>
                        </form>
                    </div>

                </div> <!-- content -->

            </div>
            <!-- End content-page -->
            <?php include('includes/footer.php');?>

        </div>
</body>
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
</html>
