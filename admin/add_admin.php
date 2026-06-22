<?php 
session_start();
include('includes/config.php');

if(strlen($_SESSION['login'])==0){ 
    header('location:index.php');
    exit;
}
else {
    if(isset($_POST['submit'])){
        $username  = mysqli_real_escape_string($con, $_POST['username']);
        $email     = mysqli_real_escape_string($con, $_POST['email']);
        $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $role      = intval($_POST['role']); // ambil role dari form

        $sql = "INSERT INTO tbladmin (AdminUserName, AdminPassword, AdminEmailId, Is_Active, role, CreationDate) 
                VALUES ('$username', '$password', '$email', '$is_active', '$role', NOW())";
        $query = mysqli_query($con, $sql);

        if($query){
            $msg="Admin berhasil ditambahkan.";
        } else {
            $error="Query error: " . mysqli_error($con);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Cakrawala | Add Admin</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/modernizr.min.js"></script>
</head>

<body class="fixed-left">
<div id="wrapper">
    <?php include('includes/topheader.php');?>
    <?php include('includes/leftsidebar.php');?>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Tambah Pengguna</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="#">Pengguna</a></li>
                                <li class="active">Tambah Pengguna</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <!-- message -->
                <div class="row">
                    <div class="col-sm-12">
                        <?php if(!empty($msg)){ ?>
                        <div class="alert alert-success"><strong>Sukses!</strong> <?php echo htmlentities($msg);?></div>
                        <?php } ?>
                        <?php if(!empty($error)){ ?>
                        <div class="alert alert-danger"><strong>Error!</strong> <?php echo htmlentities($error);?></div>
                        <?php } ?>
                    </div>
                </div>

                <!-- form -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="p-6">
                            <form method="post">
                                <div class="form-group m-b-20">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="form-group m-b-20">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="form-group m-b-20">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="form-group m-b-20">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="1">Admin</option>
                                        <option value="2">Staff</option>
                                        <option value="3">Wartawan</option>
                                    </select>
                                </div>

                                <div class="checkbox checkbox-primary m-b-20">
                                    <input id="checkbox1" type="checkbox" name="is_active" value="1" checked>
                                    <label for="checkbox1"> Aktifkan Admin </label>
                                </div>

                                <button type="submit" name="submit" class="btn btn-success">Save</button>
                                <button type="reset" class="btn btn-danger">Discard</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div> <!-- container -->
        </div> <!-- content -->

        <?php include('includes/footer.php');?>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

</body>
</html>
<?php } ?>
