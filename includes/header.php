<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
</head>

<!-- Animated Page Preloader (Hai Motion Style with Cakrawala Logo) -->
<div id="cakrawala-preloader">
  <div class="preloader-bg-glow"></div>
  <div class="preloader-content">
    <div class="preloader-logo-wrapper">
      <img src="images/logo_footer.png" alt="Cakrawala Logo" class="preloader-logo">
      <div class="preloader-spinner-ring"></div>
    </div>
    <div class="preloader-bar">
      <div class="preloader-progress"></div>
    </div>
    <span class="preloader-tagline">RUANG INFORMASI TERPERCAYA</span>
  </div>
</div>

<script>
  // Hide preloader smoothly after page loads
  window.addEventListener('load', function() {
    var preloader = document.getElementById('cakrawala-preloader');
    if (preloader) {
      setTimeout(function() {
        preloader.classList.add('fade-out');
      }, 350);
    }
  });

  // Safety fallback (max 3 seconds)
  setTimeout(function() {
    var preloader = document.getElementById('cakrawala-preloader');
    if (preloader && !preloader.classList.contains('fade-out')) {
      preloader.classList.add('fade-out');
    }
  }, 3000);
</script>

<!-- Fixed Top Main Header Wrapper -->
<div class="fixed-top bg-white border-bottom shadow-sm header-main-wrapper" style="z-index: 1030;">

  <!-- Main Navbar (Logo + Search + Hamburger Toggle) -->
  <nav class="navbar navbar-light bg-white header-nav-main py-1">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between header-container">
      
      <!-- Mobile Top Logo (Centered) -->
      <div class="mobile-logo-wrapper d-block d-md-none text-center w-100 py-2">
        <a class="navbar-brand m-0 p-0 d-inline-block" href="index.php">
          <img src="images/Logo.png" class="mobile-logo-img" alt="Logo Cakrawala">
        </a>
      </div>

      <!-- Desktop Logo -->
      <a class="navbar-brand d-none d-md-flex align-items-center py-1 mr-md-4 flex-shrink-0" href="index.php" style="text-decoration: none;">
        <img src="images/Logo.png" class="desktop-logo-img" alt="Logo Cakrawala">
      </a>

      <!-- Mobile Row (Search + Tulis Berita) / Desktop Flex Items -->
      <div class="header-controls-wrapper d-flex align-items-center flex-grow-1 justify-content-between">
        
        <!-- Search Form -->
        <div class="header-search-form flex-grow-1">
          <form name="search" action="search.php" method="post" class="m-0">
            <div class="position-relative search-input-group">
              <span class="search-icon-inside">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#888" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                </svg>
              </span>
              <input type="text" name="searchtitle" class="form-control search-input-field" 
                     placeholder="Cari berita..." required>
            </div>
          </form>
        </div>

        <!-- Button Tulis Berita -->
        <a href="admin/" target="_blank" class="btn btn-danger btn-tulis-berita ml-2">
          Tulis Berita
        </a>

        <!-- Button Hamburger Header (Kategori Toggle) -->
        <button class="btn btn-light border rounded-circle p-2 ml-2 d-flex align-items-center justify-content-center category-toggle-btn" 
                type="button" data-toggle="collapse" data-target="#categoryCollapseMenu" 
                aria-expanded="false" aria-controls="categoryCollapseMenu" 
                style="width:40px; height:40px;" title="Kategori Berita">
          <i class="bi bi-list font-weight-bold" style="font-size: 1.3rem;"></i>
        </button>

        <!-- Sosial Media (Desktop Only) -->
        <div class="social-header-icons d-none d-md-flex align-items-center ml-2">
          <a href="https://facebook.com" target="_blank" class="btn btn-light border rounded-circle p-2 mr-2" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
            <i class="bi bi-facebook text-primary"></i>
          </a>
          <a href="https://instagram.com" target="_blank" class="btn btn-light border rounded-circle p-2" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
            <i class="bi bi-instagram text-danger"></i>
          </a>
        </div>

      </div>
    </div>
  </nav>

  <!-- Collapsible Horizontal Category Navbar (Memanjang / Landscape Full-Width di Bawah Navbar & di Atas Berita Viral) -->
  <div class="collapse w-100 bg-white border-top shadow-sm py-2 px-2" id="categoryCollapseMenu">
    <div class="container-fluid px-md-4">
      <ul class="nav category-horizontal-nav flex-row flex-wrap justify-content-center m-0 p-0">
        <?php 
        $allCatQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1 ORDER BY id ASC");
        while($catRow = mysqli_fetch_array($allCatQuery)) {
          $isCurrent = ($currentCat == $catRow['id']) ? 'active' : '';
        ?>
          <li class="nav-item">
            <a href="category.php?catid=<?php echo htmlentities($catRow['id']); ?>" 
               class="nav-link category-horizontal-link px-3 py-2 <?php echo $isCurrent; ?>">
              <?php echo htmlentities($catRow['CategoryName']); ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>

</div>

<style>
/* ====== Atur ukuran logo header ====== */
.desktop-logo-img {
  max-height: 55px;
  width: auto;
  object-fit: contain;
}

.mobile-logo-img {
  max-height: 48px;
  width: auto;
  object-fit: contain;
}

/* ====== Atur jarak body supaya tidak ketutup header fixed ====== */
body {
  padding-top: 85px;
}

@media (max-width: 767.98px) {
  body {
    padding-top: 155px !important;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var toggleBtns = document.querySelectorAll('.category-toggle-btn');
  var categoryMenu = document.getElementById('categoryCollapseMenu');
  var closeTimer = null;

  if (!categoryMenu) return;

  function showCategoryMenu() {
    clearTimeout(closeTimer);
    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.collapse) {
      window.jQuery(categoryMenu).collapse('show');
    } else {
      categoryMenu.classList.add('show');
    }
  }

  function hideCategoryMenu() {
    closeTimer = setTimeout(function() {
      if (window.jQuery && window.jQuery.fn && window.jQuery.fn.collapse) {
        window.jQuery(categoryMenu).collapse('hide');
      } else {
        categoryMenu.classList.remove('show');
      }
    }, 200);
  }

  // Hover events on hamburger buttons
  toggleBtns.forEach(function(btn) {
    btn.addEventListener('mouseenter', showCategoryMenu);
    btn.addEventListener('mouseleave', hideCategoryMenu);
  });

  // Keep menu open while hovering over the category menu itself
  categoryMenu.addEventListener('mouseenter', function() {
    clearTimeout(closeTimer);
  });

  categoryMenu.addEventListener('mouseleave', hideCategoryMenu);
});
</script>