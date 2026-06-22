<?php 
session_start();
// Pastikan file config.php berisi koneksi database ($con)
include('includes/config.php');

// Pengecekan sesi login
if(strlen($_SESSION['login'])==0){ 
  header('location:index.php');
  exit();
}

$userId = $_SESSION['id']; // ID user dari session
$msg = $error = ''; // Inisialisasi pesan

// Ambil data user dari tbladmin
$userQuery = mysqli_query($con, "SELECT * FROM tbladmin WHERE id='$userId'");
$user = mysqli_fetch_assoc($userQuery);
$role = $user['role']; // Ambil role user

// Ambil data langganan terbaru dari tblauthors
$subQuery = mysqli_query($con, "SELECT * FROM tblauthors WHERE user_id='$userId' ORDER BY id DESC LIMIT 1");
$sub = mysqli_fetch_assoc($subQuery);

// ... (Kode Ambil data user dan data langganan $user, $sub, $role) ...

// =======================================================
//   *** LOGIKA OTOMATIS UBAH STATUS JIKA KEDALUWARSA (Revisi) ***
// =======================================================
if ($sub && $sub['status'] === 'approved') {
    
    // Ambil waktu saat ini dengan jam dan detik
    $now = date('Y-m-d H:i:s');
    
    // Cek apakah end_date sudah lewat dari waktu saat ini
    if ($sub['end_date'] < $now) {
        
        // 1. UPDATE STATUS DI DATABASE MENJADI 'rejected'
        $subId = $sub['id'];
        $updateStatusQuery = mysqli_query($con, 
            "UPDATE tblauthors SET status='rejected' WHERE id='$subId'"
        );

        if ($updateStatusQuery) {
            // 2. PERBARUI VARIABEL $sub AGAR TAMPILAN LANGSUNG BERUBAH
            $sub['status'] = 'rejected';
            // Tambahkan pesan peringatan untuk pengguna
            $error = "Masa langganan Anda telah berakhir. Silakan berlangganan kembali.";
        } else {
            // Handle error jika update database gagal
            $error = "Terjadi kesalahan saat memperbarui status langganan di database: " . mysqli_error($con);
        }
    }
}
// =======================================================
// *** AKHIR LOGIKA OTOMATIS UBAH STATUS JIKA KEDALUWARSA ***
// =======================================================

// Jika form update dikirim
if(isset($_POST['updateProfile'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    // Cek apakah password diisi
    $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['AdminPassword'];

    // Upload avatar jika ada file baru
    $avatarFile = $user['avatar']; // default avatar lama
    if(!empty($_FILES['avatar']['name'])) {
        $avatarName = time().'_'.basename($_FILES['avatar']['name']);
        $targetPath = "avatar/".$avatarName;
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $avatarFile = $avatarName;
        }
    }

    if($user['role'] == 3){
        // Wartawan hanya boleh ubah username & password
        $update = mysqli_query($con,"UPDATE tbladmin 
                                     SET AdminUserName='$username', 
                                         AdminPassword='$password',
                                         avatar='$avatarFile',
                                         UpdationDate=NOW() 
                                     WHERE id='$userId'");
    } else {
        // Admin / Staff boleh update email juga
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $update = mysqli_query($con,"UPDATE tbladmin 
                                     SET AdminUserName='$username', 
                                         AdminEmailId='$email', 
                                         AdminPassword='$password',
                                         avatar='$avatarFile',
                                         UpdationDate=NOW() 
                                     WHERE id='$userId'");
    }

    if($update){
        $msg = "Profile updated successfully";
        // Perbarui array $user agar tampilan langsung berubah
        $user['AdminUserName'] = $username;
        $user['AdminPassword'] = $password;
        $user['avatar'] = $avatarFile;
        if(isset($email)) $user['AdminEmailId'] = $email;
    } else {
        $error = "Failed to update profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="utf-8">
        <title>Cakrawala | Profil Penulis</title>
         
        <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
        <link rel="stylesheet" href="../plugins/morris/morris.css">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/css/core.css" rel="stylesheet" />
        <link href="assets/css/components.css" rel="stylesheet" />
        <link href="assets/css/icons.css" rel="stylesheet" />
        <link href="assets/css/pages.css" rel="stylesheet" />
        <link href="assets/css/menu.css" rel="stylesheet" />
        <link href="assets/css/responsive.css" rel="stylesheet" />
        <link rel="stylesheet" href="../plugins/switchery/switchery.min.css">
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
                                    <h4 class="page-title">Profil Pengguna </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Cakrawala</a>
                                        </li>
                                        <li class="active">
                                            Profil 
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <?php if(!empty($msg)){ ?>
                            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
                        <?php } elseif(!empty($error)){ ?>
                            <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
                        <?php } ?>

                        <div class="row">
                        <div class="col-md-8">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group mb-3 mt-3">
                                    <label><b>Avatar</b></label><br>
                                    <div class="text-center align-items-center">
                                        <?php 
                                        $avatarPath = !empty($user['avatar']) ? "avatar/" . htmlentities($user['avatar']) : "avatar/default.png";
                                        ?>
                                        <img src="<?php echo $avatarPath; ?>" alt="Avatar" 
                                            class="rounded-circle mb-2" width="200" height="200">
                                        
                                        <input type="file" name="avatar" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label><b>Username</b></label>
                                    <input type="text" name="username" class="form-control"
                                        value="<?php echo htmlentities($user['AdminUserName']); ?>" required>
                                </div>
                                
                                <?php if($user['role'] != 3) { ?>
                                <div class="form-group mb-3">
                                    <label><b>Email</b></label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo htmlentities($user['AdminEmailId']); ?>">
                                </div>
                                <?php } else { ?>
                                <div class="form-group mb-3">
                                    <label><b>Email</b></label>
                                    <input type="email" class="form-control"
                                        value="<?php echo htmlentities($user['AdminEmailId']); ?>" readonly>
                                </div>
                                <?php } ?>

                                <div class="form-group mb-3">
                                    <label><b>Password</b> <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                                </div>

                                <div class="text-end">
                                    <button type="submit" name="updateProfile" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                                    
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-box widget-box-one">
                                    <p class="m-0 text-uppercase font-600 text-danger"><?php echo $label; ?>Berlangganan</p>
                                    <h2><?php echo $count ?? ''; ?></h2> 
                                    <div class="card-body">
                                        <?php if($sub){ ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0">
                                                <tr>
                                                    <th>Nama Lengkap</th>
                                                    <td><?php echo htmlentities($sub['full_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status Berlangganan</th>
                                                    <td>
                                                        <?php if($sub['status'] == 'approved'){ ?>
                                                            <span class="badge bg-success">Aktif</span>
                                                        <?php } elseif ($sub['status'] == 'rejected'){ ?>
                                                            <span class="badge bg-danger">Ditolak/Kedaluwarsa</span>
                                                        <?php } else{ // Pending, dll. ?> 
                                                            <span class="badge bg-warning">Menunggu</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Masa Langganan</th>
                                                    <td><?php echo htmlentities($sub['end_date']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Akun Dibuat</th>
                                                    <td><?php echo htmlentities($sub['created_at']); ?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <?php if($role == 3 && ($sub['status'] == 'rejected' || $sub['status'] == 'pending')): ?>
                                            <div>
                                                <a href="subscribe.php" class="btn btn-danger btn-block mt-3">
                                                    <i class="mdi mdi-cart-plus"></i> Langganan
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php } else { // Tidak ada data langganan sama sekali ($sub FALSE) ?> 
                                            <?php if($role == 3): ?>
                                                <div class="alert alert-warning mb-0">
                                                    <i class="mdi mdi-alert-circle-outline"></i> Tidak ada data langganan ditemukan.
                                                </div>
                                                <div>
                                                    <a href="subscribe.php" class="btn btn-danger btn-block mt-3">
                                                        <i class="mdi mdi-cart-plus"></i>Langganan </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php } ?>

                                        <?php if($role == 1 || $role == 2): ?>
                                            <div class="alert alert-info mb-0">
                                                <i class="mdi mdi-information-outline"></i> Admin/Staff tidak memiliki langganan.
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

</div>
</div>
</div>
</div>
            <?php include('includes/footer.php'); ?>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>