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
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<!-- Animated Page Preloader (Hai Motion Style with Cakrawala Logo) -->
<div id="cakrawala-preloader" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: radial-gradient(circle at center, #1b202c 0%, #090a0d 100%); z-index: 999999; display: flex; justify-content: center; align-items: center; flex-direction: column;">
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
          <img src="images/Logo-Black.png" class="mobile-logo-img" alt="Logo Cakrawala">
        </a>
      </div>

      <!-- Desktop Logo -->
      <a class="navbar-brand d-none d-md-flex align-items-center py-1 mr-md-4 flex-shrink-0" href="index.php" style="text-decoration: none;">
        <img src="images/Logo-Black.png" class="desktop-logo-img" alt="Logo Cakrawala">
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

        <!-- WhatsApp Channel Button -->
        <div class="social-header-icons d-flex align-items-center ml-2">
          <a href="https://whatsapp.com/channel/0029Vb8nPNS77qVMF3Xdxq31" target="_blank" rel="noopener" class="btn btn-light border rounded-circle p-2" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;" title="Saluran WhatsApp Cakrawala">
            <i class="bi bi-whatsapp text-success font-weight-bold" style="font-size: 1.25rem;"></i>
          </a>
        </div>

      </div>
    </div>
  </nav>

</div>

<style>
/* ====== Prevent horizontal page shift & overflow ====== */
html, body {
  overflow-x: hidden;
  max-width: 100%;
}

/* ====== Atur ukuran logo header ====== */
.desktop-logo-img {
  max-height: 55px;
  width: auto;
  object-fit: contain;
}

.mobile-logo-img {
  max-height: 44px;
  max-width: 90%;
  width: auto;
  height: auto;
  object-fit: contain;
}

/* ====== Atur jarak body & kontrol header di HP ====== */
body {
  padding-top: 85px;
}

@media (max-width: 767.98px) {
  body {
    padding-top: 145px !important;
  }

  .header-container {
    padding-left: 8px !important;
    padding-right: 8px !important;
  }

  .header-controls-wrapper {
    width: 100% !important;
    max-width: 100% !important;
  }

  .btn-tulis-berita {
    font-size: 0.78rem !important;
    padding: 0.35rem 0.55rem !important;
    white-space: nowrap;
    margin-left: 4px !important;
  }

  .search-input-field {
    font-size: 0.82rem !important;
    padding-left: 32px !important;
    height: 36px !important;
  }

  .category-toggle-btn {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    padding: 0 !important;
    margin-left: 4px !important;
  }

  .mobile-logo-wrapper {
    padding-top: 6px !important;
    padding-bottom: 4px !important;
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