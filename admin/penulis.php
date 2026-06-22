<?php
session_start();
include('includes/config.php');

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek login
if (empty($_SESSION['login'])) { 
    header('location:index.php');
    exit;
}

$userId = (int) $_SESSION['id']; // pastikan integer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cakrawala | Penulis Dashboard</title>
     
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

    <style>
    .post-card { border:1px solid #ddd; border-radius:8px; padding:15px; background:#fff; }
    .post-thumb { width:100%; height:120px; object-fit:cover; border-radius:6px; }
    .post-title { font-size:16px; font-weight:bold; margin-bottom:5px; }
    .badge-category { background:#dc3545; color:#fff; font-size:12px; padding:3px 6px; border-radius:4px; }
    .post-meta { font-size:13px; color:#666; }
    </style>
</head>
<body class="fixed-left" style="padding-top:10px;">
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">
        <div class="topbar-left">
            <a href="index.php" class="logo"><span>Admin</span></a>
        </div>
        <?php include('includes/topheader.php'); ?>
    </div>
    <!-- Top Bar End -->

    <!-- Sidebar -->
    <?php include('includes/leftsidebar.php'); ?>

    <!-- Content -->
    <div class="content-page">
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
                <div class="row mb-3">
                    <div class="col-md-8">

                        <div class="card-box widget-box-one">
                            <p class="mt-2 text-uppercase font-600 text-danger">Artikel dalam antrian</p>
                            <?php
                                $sql = "
                                    SELECT 
                                        p.id, p.PostTitle, p.PostImage, p.PostingDate, p.views, p.PostUrl,
                                        c.CategoryName, s.Subcategory, a.AdminUserName as author
                                    FROM tblposts p
                                    LEFT JOIN tblcategory c ON c.id = p.CategoryId
                                    LEFT JOIN tblsubcategory s ON s.SubCategoryId = p.SubCategoryId
                                    LEFT JOIN tbladmin a ON a.id = p.PostedBy
                                    WHERE p.Is_Active = 0 AND p.PostedBy = ?
                                    ORDER BY p.PostingDate DESC
                                ";

                           if ($stmt = mysqli_prepare($con, $sql)) {
                                mysqli_stmt_bind_param($stmt, "i", $userId);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_store_result($stmt); // simpan hasil di buffer

                                mysqli_stmt_bind_result(
                                    $stmt,
                                    $id,
                                    $PostTitle,
                                    $PostImage,
                                    $PostingDate,
                                    $views,
                                    $PostUrl,
                                    $CategoryName,
                                    $Subcategory,
                                    $author
                                );

                                if (mysqli_stmt_num_rows($stmt) > 0) {
                                    while (mysqli_stmt_fetch($stmt)) { ?>
                                        <div class="post-card mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?php if (!empty($PostImage)): ?>
                                                        <img src="uploads/<?php echo htmlspecialchars($PostImage); ?>" class="post-thumb">
                                                    <?php else: ?>
                                                        <img src="assets/images/no-image.png" class="post-thumb">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <span class="badge-category"><?php echo htmlspecialchars($CategoryName); ?></span>
                                                    <div class="post-title"><?php echo htmlspecialchars($PostTitle); ?></div>
                                                    <div class="post-meta">
                                                        <i class="mdi mdi-account"></i> <?php echo htmlspecialchars($author); ?> | 
                                                        <i class="mdi mdi-calendar"></i> <?php echo date("d M Y", strtotime($PostingDate)); ?> | 
                                                        <i class="mdi mdi-eye"></i> <?php echo (int)$views; ?> views | 
                                                        <i class="mdi mdi-tag"></i> <?php echo htmlspecialchars($Subcategory); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } else {
                                    echo "<div class='alert alert-warning'>Belum ada artikel pending.</div>";
                                }
                                mysqli_stmt_close($stmt);
                            }

                                                
                            ?>
                        </div>
                    </div>

                    <!-- Sidebar info -->
                    <div class="col-md-4">
                        <?php
                        $stats = [
                            'Artikel Approve' => "Is_Active=1",
                            'Artikel Pending' => "Is_Active=0",
                            'Artikel Dibuang' => "Is_Active=3"
                        ];
                        foreach ($stats as $label => $cond) {
                            $sqlCount = "SELECT COUNT(*) as cnt FROM tblposts WHERE $cond AND PostedBy=?";
                            if ($stmt = mysqli_prepare($con, $sqlCount)) {
                                mysqli_stmt_bind_param($stmt, "i", $userId);
                                mysqli_stmt_execute($stmt);
                                $res = mysqli_stmt_get_result($stmt);
                                $count = ($res && $row = mysqli_fetch_assoc($res)) ? $row['cnt'] : 0;
                                mysqli_stmt_close($stmt);
                            } else {
                                $count = 0;
                            }
                            ?>
                            <div class="card mb-3">
                                <div class="card-box widget-box-one">
                                    <p class="m-0 text-uppercase font-600 text-danger"><?php echo $label; ?></p>
                                    <h2><?php echo $count; ?></h2>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include('includes/footer.php'); ?>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
