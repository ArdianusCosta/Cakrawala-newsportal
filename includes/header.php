<?php 
session_start();
error_reporting(0);
include('includes/config.php');

// ambil catid dari URL untuk cek menu aktif
$currentCat = isset($_GET['catid']) ? intval($_GET['catid']) : 0;

// Prepare meta variables with sensible fallbacks. Individual pages can set
// `$metaDescription`, `$pageUrl`, `$imageUrl`, and `$pageTitle` before
// including this file to provide page-specific metadata.
$siteDefaultDescription = 'Cakrawala Online, situs berita daerah dan nasional terpercaya. Sajikan info terkini Seputar Indramayu, Hukum & Kriminal, Olahraga, dan Ragam peristiwa';
$meta_description = isset($metaDescription) ? $metaDescription : (isset($pageDescription) ? $pageDescription : $siteDefaultDescription);
$meta_description = htmlspecialchars($meta_description);
$meta_url = isset($pageUrl) ? $pageUrl : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? "https://" : "http://") . 
  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$meta_image = isset($imageUrl) ? $imageUrl : 'https://cakrawalaonline.com/admin/uploads/default.jpg';
$meta_title = isset($pageTitle) ? $pageTitle : 'Cakrawala';
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo htmlspecialchars($meta_title); ?></title>
  <meta name="description" content="<?php echo $meta_description; ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($meta_url); ?>">

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta property="og:description" content="<?php echo $meta_description; ?>">
  <meta property="og:url" content="<?php echo htmlspecialchars($meta_url); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($meta_image); ?>">
  <meta property="og:site_name" content="Cakrawala">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta name="twitter:description" content="<?php echo $meta_description; ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($meta_image); ?>">

  <script type="application/ld+json">
  <?php
    echo json_encode([
      "@context" => "https://schema.org",
      "@type" => "WebSite",
      "name" => "Cakrawala",
      "url" => (isset($meta_url) ? $meta_url : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'])),
      "description" => (isset($metaDescription) ? $metaDescription : (isset($pageDescription) ? $pageDescription : $siteDefaultDescription))
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  ?>
  </script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
</head>

<!-- Navbar Atas (Logo + Search) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow border-bottom">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img src="images/Logo-Black.png" height="50" alt="Logo">
    </a>

  <!-- Search Widget -->
<div class="card-body d-flex justify-content-center">
  <form name="search" action="search.php" method="post" class="w-50">
    <div class="input-group">
      <input type="text" name="searchtitle" class="form-control rounded-3 pr-5" 
             placeholder="Cari berita..." required style="max-height: 40px; font-size: 16px;">
      
      <!-- Tombol ikon kaca pembesar -->
      <button class="btn position-absolute" type="submit" 
              style="right: 10px; z-index: 5; background: transparent; border: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="gray" class="bi bi-search" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 
          1.415-1.414l-3.85-3.85zm-5.242.656a5 
          5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
        </svg>
      </button>
    </div>
  </form>
</div>

 <!-- Button Tulis Berita -->
  <a href="admin/" target="blank" class="btn btn-danger rounded-3 px-3 mr-2" style="font-size: 14px; max-height: 40px; max-width: 200px; min-width: 150px; display: flex; align-items: center; text-align: center; justify-content: center;">
    Tulis Berita
  </a>

  <!-- Sosial Media -->
  <a href="https://facebook.com" target="_blank" class="btn btn-light border rounded-circle p-2 mr-2" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
    <i class="bi bi-facebook text-primary"></i>
  </a>
  <a href="https://instagram.com" target="_blank" class="btn btn-light border rounded-circle p-2" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
    <i class="bi bi-instagram text-danger"></i>
  </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" 
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- Navbar Bawah (Menu Dinamis dari Category) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm border-bottom" style="top:75px; z-index:1020;">
  <div class="container">
    <div class="collapse navbar-collapse justify-content-center" id="navbarMenu">
      <ul class="navbar-nav">

        <!-- Menu Dinamis dari Category -->
        <?php 
        $query=mysqli_query($con,"SELECT id,CategoryName FROM tblcategory WHERE Is_Active=1");
        while($row=mysqli_fetch_array($query)) {
          $isActive = ($currentCat == $row['id']) ? 'active' : '';
        ?>
          <li class="nav-item">
            <a class="nav-link px-3 <?php echo $isActive; ?>" 
               href="category.php?catid=<?php echo htmlentities($row['id']); ?>">
              <?php echo htmlentities($row['CategoryName']); ?>
            </a>
          </li>
        <?php } ?>

      </ul>
    </div>
  </div>
</nav>

<!-- Tambahkan padding di body agar konten tidak ketutup navbar -->
<style>
/* ====== Atur jarak body supaya tidak ketutup ====== */
body {
  padding-top: 120px; /* tinggi gabungan navbar */
}

/* ====== Navbar atas selalu lebih tinggi ====== */
nav.navbar.fixed-top:first-of-type {
  z-index: 1030;
}

/* ====== Navbar bawah nempel di bawah navbar atas ====== */
nav.navbar.fixed-top:nth-of-type(2) {
  top: 75px;
  z-index: 1025;
}

/* ====== Responsive fix untuk mobile ====== */
@media (max-width: 991.98px) {
  /* Collapse menu kategori muncul fixed di bawah kedua navbar */
  .navbar.navbar-light:nth-of-type(2) .collapse {
    position: fixed;
    top: 120px;   /* total tinggi navbar atas + bawah */
    left: 0;
    right: 0;
    background: #fff;
    z-index: 1040;
    border-top: 1px solid #ddd;
    padding: 10px 0;
    max-height: 60vh;       /* batasi tinggi */
    overflow-y: auto;       /* bisa scroll kalau panjang */
  }

  /* Menu kategori ditumpuk vertikal */
  .navbar-nav {
    flex-direction: column;
    text-align: center;
  }

  /* Form search biar full width */
  .card-body form.w-50 {
    width: 100% !important;
  }

  /* Tombol Tulis Berita lebih kecil */
  .btn-danger {
    min-width: auto;
    padding: 6px 12px;
    font-size: 12px;
  }

  /* Sosial media icon lebih kecil */
  .btn-light {
    width: 35px !important;
    height: 35px !important;
    padding: 6px !important;
  }
}

/* ====== Tambahan UX untuk kategori panjang di desktop ====== */
@media (min-width: 992px) {
  .navbar-nav {
    flex-wrap: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .navbar-nav .nav-item {
    flex: 0 0 auto;
  }
}

/* ====== Style menu aktif ====== */
.nav-link.active {
  color: red !important;
  border-bottom: 2px solid red;
}


</style>