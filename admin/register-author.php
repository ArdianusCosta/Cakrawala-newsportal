<?php
session_start();
include('includes/config.php');

if(isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $date     = date("Y-m-d H:i:s");

    // Cek apakah username atau email sudah ada
    $stmt = $con->prepare("SELECT id FROM tbladmin WHERE AdminUserName=? OR AdminEmailId=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0) {
        echo "<script>alert('Username atau Email sudah terdaftar.');</script>";
    } else {
        $role = 3; // default penulis
        $is_active = 1;

        $stmt = $con->prepare("INSERT INTO tbladmin 
            (AdminUserName, AdminPassword, AdminEmailId, Is_Active, CreationDate, role) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisi", $username, $password, $email, $is_active, $date, $role);
        
        if($stmt->execute()) {
            echo "<script>alert('Pendaftaran berhasil. Silakan login.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan, coba lagi.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Cakrawala | Penulis</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-transparent">

<section>
    <div class="container-alt">
        <div class="row">
            <div class="col-sm-12">
                <div class="wrapper-page">

                    <div class="m-t-40 account-pages">
                        <div class="text-center account-logo-box">
                            <h2 class="text-uppercase">
                                <a href="index.php" class="text-success">
                                    <span><img src="assets/images/logo.png" alt="" height="56"></span>
                                </a>
                            </h2>
                        </div>

                        <div class="account-content">
                            <form class="form-horizontal" method="post">

                                <div class="form-group ">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="text" required name="username" placeholder="Name">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="email" required name="email" placeholder="Email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="password" name="password" required placeholder="Password">
                                    </div>
                                </div>

                                 <div class="form-group text-center m-t-10">
                                    <p>Sudah punya akun? <a href="index.php">Login</a></p>
                                </div>

                                <div class="form-group account-btn text-center m-t-10">
                                    <div class="col-xs-12">
                                        <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit" name="register">Daftar</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div> <!-- end wrapper -->
            </div>
        </div>
    </div>
</section>

<!-- JS -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>
