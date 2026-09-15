<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer {
            background: linear-gradient(145deg, #1a1c23, #242730);
            color: #ffffff;
        }
        
        .footer-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 30px;
            height: 2px;
            background: #fb0000ff;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 1rem;
        }
        
        .footer-links a {
            color: #b4b6bb;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #ffffff;
            padding-left: 5px;
        }

        .footer-category-link {
            color: #b4b6bb !important;
            text-decoration: none;
            font-size: 0.88rem;
            display: inline-block;
            transition: all 0.25s ease;
        }

        .footer-category-link:hover,
        .footer-category-link.active {
            color: #ffffff !important;
            transform: translateX(3px);
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: #fb0000ff;
            transform: translateY(-3px);
        }
        
        .newsletter-input {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            color: #ffffff;
        }
        
        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .newsletter-input:focus {
            background: rgba(255,255,255,0.15);
            border-color: #fb0000ff;
            color: #ffffff;
            box-shadow: none;
        }
        
        .btn-subscribe {
            background: #fb0000ff;
            border: none;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-subscribe:hover {
            background: #e00000;
            transform: translateY(-2px);
        }
        
        .footer-bottom {
            background: rgba(0,0,0,0.3);
        }
        
        .footer-bottom a {
            color: #fb0000ff;
            text-decoration: none;
        }
        
        .footer-bottom a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Desktop Footer (Only visible on MD and larger screens) -->
    <footer class="footer pt-5 d-none d-md-block">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <h3 class="footer-title">
                        <img src="images/logo_footer.png" class="img-fluid" alt="Cakrawala Logo" style="max-width: 230px;">
                    </h3>
                    <p class="mb-4 mt-3" style="font-size: 0.9rem; color: #b4b6bb; line-height: 1.5;">
                        Jl. Jenderal R.S. Sukamto No.60 Pondok Kopi,<br>Duren Sawit Jakarta Timur
                    </p>
                    <div class="social-links mb-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="far fa-envelope"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fas fa-phone-alt"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Kategori (Sub-grid 2 Kolom Rapi untuk Seluruh 17 Kategori) -->
                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                    <h3 class="footer-title">Kategori</h3>
                    <div class="row g-2">
                        <?php 
                        $allFooterCatQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1 ORDER BY id ASC");
                        while($cat = mysqli_fetch_array($allFooterCatQuery)) {
                            $isActive = ($currentCat == $cat['id']) ? 'active' : '';
                        ?>
                            <div class="col-6 mb-1">
                                <a class="footer-category-link <?php echo $isActive; ?>" 
                                   href="category.php?catid=<?php echo htmlentities($cat['id']); ?>">
                                    <?php echo htmlentities($cat['CategoryName']); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Tentang Kami -->
                <div class="col-lg-2 col-md-6 col-12 mb-4 mb-lg-0">
                    <h3 class="footer-title">Tentang Kami</h3>
                    <ul class="footer-links">
                        <li><a href="about-us.php">Tentang Kami</a></li>
                        <li><a href="contact-us.php">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-3 col-md-6 col-12">
                    <h3 class="footer-title">Surat Kabar</h3>
                    <p class="mb-4" style="font-size: 0.9rem; color: #b4b6bb;">Berlangganan Surat Kabar Mingguan Cakrawala melalui email kami</p>
                    <form class="mb-4">
                        <div class="input-group">
                            <input type="email" class="form-control newsletter-input" placeholder="Enter your email" required>
                            <button class="btn btn-subscribe text-white" type="submit">Berlangganan</button>
                        </div>
                    </form>
                    <p class="small text-muted" style="font-size: 0.78rem;">Dengan berlangganan kamu menyetujui peraturan dari Cakrawala</p>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Desktop -->
        <div class="footer-bottom mt-5">
            <div class="container">
                <div class="row py-4">
                    <div class="col-12 text-center">
                        <p class="mb-0 text-white" style="font-size: 0.85rem; letter-spacing: 0.5px;">&copy; 2025 Hai Motion - Created for PT Cakrawala Pers Media. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Specific Footer (Rata Kiri, Logo Besar, Rapi Sesuai Gambar 3) -->
    <footer class="mobile-footer-wrapper d-block d-md-none pt-4 pb-0 px-0" style="background-color: #16181f; color: #ffffff;">
      <div class="container px-3">
        
        <!-- Top Brand & Address (Rata Kiri, Logo Lebih Besar) -->
        <div class="mobile-footer-brand mb-4 text-start">
          <img src="images/logo_footer.png" alt="Cakrawala Logo" class="mobile-footer-logo mb-3" style="max-width: 270px; height: auto; display: block;">
          <p class="mobile-footer-address mb-3 text-start" style="font-size: 0.88rem; color: rgba(255, 255, 255, 0.85); line-height: 1.5; text-align: left;">
            Jl. Jenderal R.S. Sukamto No.60 Pondok Kopi,<br>
            Duren Sawit Jakarta Timur
          </p>
          <div class="mobile-social-links d-flex justify-content-start align-items-center mb-4">
            <a href="mailto:info@cakrawala.com" class="mobile-social-icon"><i class="bi bi-envelope-fill"></i></a>
            <a href="https://facebook.com" target="_blank" class="mobile-social-icon"><i class="bi bi-facebook"></i></a>
            <a href="https://instagram.com" target="_blank" class="mobile-social-icon"><i class="bi bi-instagram"></i></a>
            <a href="tel:+628123456789" class="mobile-social-icon"><i class="bi bi-telephone-fill"></i></a>
            <a href="https://youtube.com" target="_blank" class="mobile-social-icon"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Kategori Section (2 Kolom Rapi Rata Kiri dengan 17 Kategori Lengkap) -->
        <div class="mobile-categories-section mb-4 text-start">
          <h5 class="mobile-footer-heading mb-3 text-danger font-weight-bold" style="font-size: 1.05rem; letter-spacing: 0.5px; text-align: left;">Kategori</h5>
          <div class="row g-2">
            <?php 
            $mobCatQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1 ORDER BY id ASC");
            while($mCat = mysqli_fetch_array($mobCatQuery)) {
            ?>
              <div class="col-6 mb-2 text-start">
                <a href="category.php?catid=<?php echo htmlentities($mCat['id']); ?>" 
                   class="mobile-footer-link text-decoration-none text-light d-block text-start" 
                   style="font-size: 0.85rem; opacity: 0.88; transition: opacity 0.2s ease; text-align: left;">
                  • <?php echo htmlentities($mCat['CategoryName']); ?>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Tentang Kami Section (Rata Kiri) -->
        <div class="mobile-about-section mb-4 text-start">
          <h5 class="mobile-footer-heading mb-2 text-danger font-weight-bold" style="font-size: 1.05rem; text-align: left;">Tentang Kami</h5>
          <div class="d-flex gap-4 mt-2">
            <a href="about-us.php" class="text-light text-decoration-none me-4" style="font-size: 0.88rem; opacity: 0.88;">Tentang Kami</a>
            <a href="contact-us.php" class="text-light text-decoration-none" style="font-size: 0.88rem; opacity: 0.88;">Hubungi Kami</a>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Surat Kabar Section (Rata Kiri) -->
        <div class="mobile-newsletter-section mb-4 text-start">
          <h5 class="mobile-footer-heading mb-2 text-danger font-weight-bold" style="font-size: 1.05rem; text-align: left;">Surat Kabar</h5>
          <p class="mobile-newsletter-desc mb-3" style="font-size: 0.85rem; text-align: left; color: #ffffff !important; opacity: 0.92;">
            Berlangganan Surat Kabar Mingguan Cakrawala melalui email kami
          </p>
          <form class="mobile-newsletter-form mb-3">
            <div class="input-group mobile-input-group">
              <input type="email" class="form-control mobile-newsletter-input" placeholder="Enter your email" required style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); color: #ffffff;">
              <button class="btn btn-danger mobile-btn-subscribe" type="submit" style="background-color: #dc3545; border: none; padding: 6px 16px; color: #ffffff;">Berlangganan</button>
            </div>
          </form>
          <p class="mobile-newsletter-disclaimer m-0" style="font-size: 0.82rem; text-align: left; color: #ffffff !important; opacity: 0.92;">
            Dengan berlangganan kamu menyetujui peraturan dari Cakrawala
          </p>
        </div> <!-- End container -->

        <!-- Bottom Copyright Bar (Full Width Seamless Edge-to-Edge Centered Text) -->
        <div class="mobile-footer-bottom py-3 px-3 text-center w-100" style="background-color: #16181f; border-top: 1px solid rgba(255,255,255,0.15);">
          <p class="m-0 mobile-copyright-text text-center" style="font-size: 0.82rem; text-align: center !important; color: #ffffff !important; opacity: 0.95;">
            &copy; 2025 Hai Motion - Created for PT Cakrawala Pers Media. All rights reserved.
          </p>
        </div>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>