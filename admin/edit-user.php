<?php
session_start();
include('includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// hanya admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: dashboard.php");
    exit;
}

// cek id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = "ID user tidak valid.";
    header("Location: author-list.php");
    exit;
}

$id = intval($_GET['id']);

// ambil data user (tanpa get_result)
$stmt = $con->prepare("SELECT id, AdminUserName, AdminEmailId, role, Is_Active, CreationDate, UpdationDate FROM tbladmin WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['msg'] = "User tidak ditemukan.";
    header("Location: author-list.php");
    exit;
}

// bind hasil
$stmt->bind_result($uid, $username, $email, $role, $is_active, $creation, $update);
$stmt->fetch();

// update data jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['AdminUserName']);
    $email     = trim($_POST['AdminEmailId']);
    $role      = intval($_POST['role']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($username == "" || $email == "") {
        $error = "Username dan Email wajib diisi.";
    } else {
        $stmt2 = $con->prepare("UPDATE tbladmin SET AdminUserName=?, AdminEmailId=?, role=?, Is_Active=?, UpdationDate=NOW() WHERE id=?");
        $stmt2->bind_param("ssiii", $username, $email, $role, $is_active, $id);

        if ($stmt2->execute()) {
            $_SESSION['msg'] = "Data user berhasil diperbarui.";
            header("Location: author-list.php");
            exit;
        } else {
            $error = "Gagal menyimpan data: " . $stmt2->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Pengguna | Cakrawala</title>
    <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/core.css" rel="stylesheet">
    <link href="assets/css/components.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/pages.css" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet">
    <link href="assets/css/responsive.css" rel="stylesheet">
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
                        <h4 class="page-title">Edit Pengguna</h4>
                        <ol class="breadcrumb p-0 m-0">
                            <li><a href="author-list.php">Pengguna</a></li>
                            <li class="active">Edit</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlentities($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="form-horizontal" action="">
                <div class="form-group">
                    <label class="col-md-2 control-label">Username</label>
                    <div class="col-md-8">
                        <input type="text" name="AdminUserName" class="form-control" required value="<?= htmlentities($username); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Email</label>
                    <div class="col-md-8">
                        <input type="email" name="AdminEmailId" class="form-control" required value="<?= htmlentities($email); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Role</label>
                    <div class="col-md-8">
                        <select name="role" class="form-control" required>
                            <option value="1" <?= $role==1?'selected':''; ?>>Admin</option>
                            <option value="2" <?= $role==2?'selected':''; ?>>Staff</option>
                            <option value="3" <?= $role==3?'selected':''; ?>>Wartawan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <input type="checkbox" name="is_active" value="1" <?= $is_active ? 'checked' : ''; ?>> Aktif
                    </div>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <a href="author-list.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
