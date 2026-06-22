<?php
session_start();
include('includes/config.php');

// hanya Admin (role = 1) yang bisa akses
if($_SESSION['role'] != 1){
    header("Location: dashboard.php");
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: author-list.php");
    exit;
}

$id = intval($_GET['id']);

// ambil data user
$stmt = $con->prepare("SELECT * FROM tbladmin WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user){
    $_SESSION['msg'] = "User tidak ditemukan.";
    header("Location: author-list.php");
    exit;
}

// jika form disubmit
if(isset($_POST['update'])){
    $username = trim($_POST['AdminUserName']);
    $email    = trim($_POST['AdminEmailId']);
    $role     = intval($_POST['role']);
    $status   = intval($_POST['Is_Active']);

    // update password jika diisi
    if(!empty($_POST['AdminPassword'])){
        $password = password_hash($_POST['AdminPassword'], PASSWORD_BCRYPT);
        $sql = "UPDATE tbladmin SET AdminUserName=?, AdminEmailId=?, AdminPassword=?, role=?, Is_Active=?, UpdationDate=NOW() WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssiii", $username, $email, $password, $role, $status, $id);
    } else {
        $sql = "UPDATE tbladmin SET AdminUserName=?, AdminEmailId=?, role=?, Is_Active=?, UpdationDate=NOW() WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssiii", $username, $email, $role, $status, $id);
    }

    if($stmt->execute()){
        $_SESSION['msg'] = "User berhasil diupdate.";
    } else {
        $_SESSION['msg'] = "Gagal update user.";
    }
    header("Location: author-list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
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
        <title>Cakrawala | Daftar Pengguna</title>

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

     <!-- Top Bar Start -->
<?php include('includes/topheader.php');?>

            <!-- ========== Left Sidebar Start ========== -->
<?php include('includes/leftsidebar.php');?>
<div class="content-page">
    <div class="content">
        <div class="container">
            
                         <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Edit Pengguna </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Pengguna</a>
                                        </li>
                                        <li class="active">
                                            Edit Pengguna
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->

    <form method="post">
        <div class="form-group m-b-20">
            <label>Username</label>
            <input type="text" name="AdminUserName" class="form-control" value="<?= htmlentities($user['AdminUserName']); ?>" required>
        </div>

        <div class="form-group m-b-20">
            <label>Email</label>
            <input type="email" name="AdminEmailId" class="form-control" value="<?= htmlentities($user['AdminEmailId']); ?>" required>
        </div>

        <div class="form-group m-b-20">
            <label>Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="AdminPassword" class="form-control">
        </div>

        <div class="form-group m-b-20">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="1" <?= $user['role']==1?'selected':''; ?>>Admin</option>
                <option value="2" <?= $user['role']==2?'selected':''; ?>>Staff</option>
                <option value="3" <?= $user['role']==3?'selected':''; ?>>Wartawan</option>
            </select>
        </div>

        <div class="form-group m-b-20">
            <label>Status</label>
            <div class="checkbox checkbox-primary m-b-20">
                <input id="checkbox1" type="checkbox" name="is_active" value="1" checked>
                <label for="checkbox1"> Aktifkan Akun </label>
            </div>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="author-list.php" class="btn btn-secondary">Kembali</a>
    </form>
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
