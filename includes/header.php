<?php 
session_start();
error_reporting(0);
include('includes/config.php');

// ambil catid dari URL untuk cek menu aktif
$currentCat = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
?>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<!-- Navbar Atas (Logo + Search) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow border-bottom">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img src="images/Logo.png" height="50" alt="Logo">
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
  body {
    padding-top: 120px; /* Sesuaikan dengan tinggi navbar total */
  }
  .navbar {
    transition: box-shadow 0.3s ease;
  }
  .navbar.shadow {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
  }

  /* Style menu aktif */
  .nav-link.active {
    color: red !important;
    border-bottom: 2px solid red;
  }

    /* Supaya input dan tombol rapi */
  .input-group {
    position: relative;
  }
  .input-group .form-control {
    padding-right: 15px; /* beri ruang untuk ikon */
  }
  .input-group button {
    top: 50%;
    transform: translateY(-50%);
  }

  .btn-danger {
    font-weight: 500;
  }
  .bi {
    font-size: 18px;
  }

</style>
