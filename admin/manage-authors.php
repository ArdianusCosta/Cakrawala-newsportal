<?php
session_start();
include('includes/config.php');

// hanya admin superadmin
if($_SESSION['role'] != 1) {
    header("Location: dashboard.php");
    exit;
}

// =======================================================
//   *** LOGIKA OTOMATIS UBAH STATUS JIKA KEDALUWARSA ***
// =======================================================

// Ambil waktu saat ini dengan jam untuk perbandingan yang akurat
// Waktu sekarang: 2025-10-30 00:14:37 (WIB)
$today_full_time = date('Y-m-d H:i:s'); 

// Query untuk mencari semua langganan yang masih 'approved' TAPI end_date-nya sudah lewat
$checkQuery = mysqli_query($con, 
    "SELECT id FROM tblauthors WHERE status='approved' AND end_date < '$today_full_time'"
);

$rejectedCount = 0;
if ($checkQuery) {
    // Update setiap langganan yang kedaluwarsa
    while ($row = mysqli_fetch_assoc($checkQuery)) {
        $subId = $row['id'];
        
        // Update status di database menjadi 'rejected'
        $updateQuery = mysqli_query($con, 
            "UPDATE tblauthors SET status='rejected' WHERE id='$subId'"
        );

        if ($updateQuery) {
            $rejectedCount++;
        }
    }
}

if ($rejectedCount > 0) {
    $_SESSION['msg'] = "$rejectedCount langganan kedaluwarsa berhasil diperbarui menjadi 'rejected'.";
}
// =======================================================
// *** AKHIR LOGIKA OTOMATIS UBAH STATUS JIKA KEDALUWARSA ***
// =======================================================


// Approve Author
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    // Waktu mulai sekarang
    $today_start = date("Y-m-d H:i:s"); 
    // Berakhir tepat di akhir hari ke-30
    $end_date_full = date("Y-m-d 23:59:59", strtotime("+30 days")); 

    $stmt = $con->prepare("UPDATE tblauthors SET status='approved', start_date=?, end_date=? WHERE id=?");
    $stmt->bind_param("ssi", $today_start, $end_date_full, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Author approved for 30 days!";
    // MEMPERTAHANKAN nama file manage-authors.php
    header("Location: manage-authors.php"); 
    exit;
}

// Reject Author
if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    $stmt = $con->prepare("UPDATE tblauthors SET status='rejected', start_date=NULL, end_date=NULL WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Author rejected!";
    // MEMPERTAHANKAN nama file manage-authors.php
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
    // MEMPERTAHANKAN nama file manage-authors.php
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

    <title>Cakrawala | Kelola Berlangganan</title>
     
        <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/core.css" rel="stylesheet">
    <link href="assets/css/components.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/pages.css" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet">
    <link href="assets/css/responsive.css" rel="stylesheet">

    <style>
        .badge { font-size: 12px; }
        .table th, .table td { vertical-align: middle !important; }
    </style>
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
                            <h4 class="page-title">Daftar Langganan</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="#">Pengguna</a></li>
                                <li class="active">Daftar Langganan</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <?php if(isset($_SESSION['msg'])): ?>
                    <div class="alert alert-info">
                        <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    </div>
                <?php endif; ?>

                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Penulis</th>
                            <th>Email</th>
                            <th>Status Langganan</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Bukti Pembayaran</th>
                            <th>Status Aktivitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $cnt=1; 
                        $modals="";
                        while($row = $query->fetch_assoc()): ?>
                        <tr>
                            <td><?= $cnt++; ?></td>
                            <td><?= htmlentities($row['full_name'] ?: ($row['AdminUserName'] ?? 'Unknown')); ?></td>
                            <td><?= htmlentities($row['AdminEmailId'] ?? '-'); ?></td>
                            <td>
                                <?php if($row['status']=='approved'): ?>
                                    <span class="badge badge-success">Terima</span>
                                <?php elseif($row['status']=='pending'): ?>
                                    <span class="badge badge-warning">Menunggu</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['start_date'] ?: '-'; ?></td>
                            <td><?= $row['end_date'] ?: '-'; ?></td>
                            <td>
                                <?php if($row['payment_proof']): ?>
                                    <button type="button" class="btn btn-info btn-sm" 
                                        data-toggle="modal" data-target="#proofModal<?= $row['id']; ?>">
                                        View
                                    </button>
                                <?php else: ?>
                                    No Proof
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                // Pengecekan status aktivitas berdasarkan end_date
                                $now = date("Y-m-d H:i:s");
                                if($row['status']=='approved' && $row['end_date'] > $now) {
                                    echo "<span class='badge bg-success text-white'>Aktif</span>";
                                } elseif($row['status']=='approved' && $row['end_date'] < $now) {
                                    // Status ini akan segera di-update ke 'rejected' saat halaman di-load
                                    echo "<span class='badge bg-danger text-white'>Kedaluwarsa (Harus Rejected)</span>"; 
                                } elseif($row['status']=='rejected') {
                                    echo "<span class='badge bg-danger text-white'>Tidak Aktif</span>";
                                } else {
                                    echo "<span class='badge bg-secondary text-white'>Menunggu</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($row['status']=='pending'): ?>
                                    <a href="manage-authors.php?approve=<?= $row['id'];?>" 
                                    class="btn btn-sm btn-success">Approve</a>
                                    <a href="manage-authors.php?reject=<?= $row['id'];?>" 
                                    class="btn btn-sm btn-warning">Reject</a>
                                <?php endif; ?>
                                <a href="manage-authors.php?delete=<?= $row['id'];?>" 
                                onclick="return confirm('Yakin hapus author ini?')" 
                                class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>

                        <?php 
                        // Modal bukti (tidak ada perubahan)
                        $modals .= '
                        <div class="modal fade" id="proofModal'.$row['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h4 class="modal-title">Bukti Pembayaran - '.htmlentities($row['full_name'] ?? $row['AdminUserName']).'</h4>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <tr><th>Nama Lengkap</th><td>'.htmlentities($row['full_name'] ?? '-').'</td></tr>
                                            <tr><th>Bank</th><td>'.htmlentities($row['bank_name'] ?? '-').'</td></tr>
                                            <tr><th>No. Rekening</th><td>'.htmlentities($row['bank_account'] ?? '-').'</td></tr>
                                            <tr><th>Bukti</th><td>';
                        if($row['payment_proof']){
                            $modals .= '<img src="proof/'.$row['payment_proof'].'" class="img-fluid img-thumbnail" style="max-height:600px;">';
                        } else {
                            $modals .= 'No proof uploaded.';
                        }
                        $modals .= '</td></tr></table>
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

                <?= $modals; ?>

            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/jquery.app.js"></script>

</body>
</html>