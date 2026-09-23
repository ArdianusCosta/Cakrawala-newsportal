<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            width: 35px;
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
            transform: translateY(-2px);
        }
        
        @media (min-width: 992px) {
            .footer-col-about {
                padding-left: 20px !important;
            }
            .footer-col-newsletter {
                padding-left: 15px !important;
            }
        }
        
        /* Custom Footer Action Buttons (WA & Email) */
        .footer-icon-btn,
        .mobile-footer-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 50% !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            border: none !important;
            outline: none !important;
        }

        .wa-footer-btn {
            background: #25D366 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35) !important;
        }

        .wa-footer-btn:hover {
            background: #1da851 !important;
            color: #ffffff !important;
            transform: translateY(-3px) scale(1.05) !important;
        }

        .email-footer-btn {
            background: #dc3545 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.35) !important;
        }

        .email-footer-btn:hover {
            background: #bb2d3b !important;
            color: #ffffff !important;
            transform: translateY(-3px) scale(1.05) !important;
        }

        .contact-option-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
            border-radius: 12px !important;
        }

        .contact-option-card:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08) !important;
            border-color: #cbd5e1 !important;
        }
        
        .newsletter-input {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            color: #ffffff;
            font-size: 0.82rem !important;
            padding: 0.4rem 0.75rem !important;
        }
        
        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.82rem !important;
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
            padding: 0.4rem 0.85rem !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
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
    <!-- Desktop Footer -->
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
                    <div class="social-links mb-4 d-flex align-items-center">
                        <button type="button" class="btn p-0 border-0 me-3 mr-3 footer-icon-btn wa-footer-btn" onclick="openFooterModal('footerWaModal')" data-toggle="modal" data-target="#footerWaModal" data-bs-toggle="modal" data-bs-target="#footerWaModal" title="Hubungi via WhatsApp">
                            <i class="bi bi-whatsapp" style="font-size: 1.35rem;"></i>
                        </button>
                        <button type="button" class="btn p-0 border-0 footer-icon-btn email-footer-btn" onclick="openFooterModal('footerEmailModal')" data-toggle="modal" data-target="#footerEmailModal" data-bs-toggle="modal" data-bs-target="#footerEmailModal" title="Kirim Email Redaksi">
                            <i class="bi bi-envelope-fill" style="font-size: 1.35rem;"></i>
                        </button>
                    </div>
                </div>

                <!-- Kategori -->
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
                <div class="col-lg-2 col-md-6 col-12 mb-4 mb-lg-0 footer-col-about">
                    <h3 class="footer-title">Tentang Kami</h3>
                    <ul class="footer-links">
                        <li><a href="about-us.php">Tentang Kami</a></li>
                        <li><a href="contact-us.php">Hubungi Kami</a></li>
                        <li><a href="pedoman-media-siber.php">Pedoman Media Siber</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-3 col-md-6 col-12 footer-col-newsletter">
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
                        <p class="mb-0 text-white" style="font-size: 0.85rem; letter-spacing: 0.5px;">&copy; 2026 Cakrawala Online. Seluruh Hak Cipta Dilindungi Undang-Undang</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Specific Footer (Rata Tengah) -->
    <footer class="mobile-footer-wrapper d-block d-md-none pt-4 pb-0 px-0" style="background-color: #16181f; color: #ffffff;">
      <div class="container px-3 text-center">
        
        <!-- Top Brand & Address (Centered) -->
        <div class="mobile-footer-brand mb-4 text-center d-flex flex-column align-items-center">
          <img src="images/logo_footer.png" alt="Cakrawala Logo" class="mobile-footer-logo mb-3" style="max-width: 270px; height: auto; display: block; margin: 0 auto;">
          <p class="mobile-footer-address mb-3 text-center" style="font-size: 0.88rem; color: rgba(255, 255, 255, 0.85); line-height: 1.5; text-align: center !important;">
            Jl. Jenderal R.S. Sukamto No.60 Pondok Kopi,<br>
            Duren Sawit Jakarta Timur
          </p>
          <div class="mobile-social-links d-flex justify-content-center align-items-center mb-4">
            <button type="button" class="btn p-0 border-0 me-3 mr-3 mobile-footer-btn wa-footer-btn" onclick="openFooterModal('footerWaModal')" data-toggle="modal" data-target="#footerWaModal" data-bs-toggle="modal" data-bs-target="#footerWaModal" title="Hubungi via WhatsApp">
              <i class="bi bi-whatsapp" style="font-size: 1.35rem;"></i>
            </button>
            <button type="button" class="btn p-0 border-0 mobile-footer-btn email-footer-btn" onclick="openFooterModal('footerEmailModal')" data-toggle="modal" data-target="#footerEmailModal" data-bs-toggle="modal" data-bs-target="#footerEmailModal" title="Kirim Email Redaksi">
              <i class="bi bi-envelope-fill" style="font-size: 1.35rem;"></i>
            </button>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Kategori Section (Centered) -->
        <div class="mobile-categories-section mb-4 text-center">
          <h5 class="mobile-footer-heading mb-3 text-danger font-weight-bold" style="font-size: 1.05rem; letter-spacing: 0.5px; text-align: center !important;">Kategori</h5>
          <div class="row g-2 text-center justify-content-center">
            <?php 
            $mobCatQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1 ORDER BY id ASC");
            while($mCat = mysqli_fetch_array($mobCatQuery)) {
            ?>
              <div class="col-6 mb-2 text-center">
                <a href="category.php?catid=<?php echo htmlentities($mCat['id']); ?>" 
                   class="mobile-footer-link text-decoration-none text-light d-block text-center" 
                   style="font-size: 0.85rem; opacity: 0.88; transition: opacity 0.2s ease; text-align: center !important;">
                  • <?php echo htmlentities($mCat['CategoryName']); ?>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Tentang Kami Section (Centered) -->
        <div class="mobile-about-section mb-4 text-center d-flex flex-column align-items-center">
          <h5 class="mobile-footer-heading mb-2 text-danger font-weight-bold" style="font-size: 1.05rem; text-align: center !important;">Tentang Kami</h5>
          <div class="d-flex flex-wrap justify-content-center gap-3 mt-2">
            <a href="about-us.php" class="text-light text-decoration-none me-3 mr-3 mb-1" style="font-size: 0.88rem; opacity: 0.88;">Tentang Kami</a>
            <a href="contact-us.php" class="text-light text-decoration-none me-3 mr-3 mb-1" style="font-size: 0.88rem; opacity: 0.88;">Hubungi Kami</a>
            <a href="pedoman-media-siber.php" class="text-light text-decoration-none mb-1" style="font-size: 0.88rem; opacity: 0.88;">Pedoman Media Siber</a>
          </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15); margin: 20px 0;">

        <!-- Surat Kabar Section (Centered) -->
        <div class="mobile-newsletter-section mb-4 text-center d-flex flex-column align-items-center">
          <h5 class="mobile-footer-heading mb-2 text-danger font-weight-bold" style="font-size: 1.05rem; text-align: center !important;">Surat Kabar</h5>
          <p class="mobile-newsletter-desc mb-3 text-center" style="font-size: 0.85rem; text-align: center !important; color: #ffffff !important; opacity: 0.92;">
            Berlangganan Surat Kabar Mingguan Cakrawala melalui email kami
          </p>
          <form class="mobile-newsletter-form mb-3 w-100 d-flex justify-content-center">
            <div class="input-group mobile-input-group" style="max-width: 320px;">
              <input type="email" class="form-control mobile-newsletter-input" placeholder="Enter your email" required style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); color: #ffffff;">
              <button class="btn btn-danger mobile-btn-subscribe" type="submit" style="background-color: #dc3545; border: none; padding: 6px 16px; color: #ffffff;">Berlangganan</button>
            </div>
          </form>
          <p class="mobile-newsletter-disclaimer m-0 text-center" style="font-size: 0.82rem; text-align: center !important; color: #ffffff !important; opacity: 0.92;">
            Dengan berlangganan kamu menyetujui peraturan dari Cakrawala
          </p>
        </div>
      </div>

      <!-- Bottom Copyright Bar -->
      <div class="mobile-footer-bottom py-3 px-3 text-center w-100" style="background-color: #16181f; border-top: 1px solid rgba(255,255,255,0.15);">
        <p class="m-0 mobile-copyright-text text-center" style="font-size: 0.82rem; text-align: center !important; color: #ffffff !important; opacity: 0.95;">
          &copy; 2025 Hai Motion - Created for PT Cakrawala Pers Media. All rights reserved.
        </p>
      </div>
    </footer>

    <!-- ===== MODAL PILIHAN WHATSAPP (2 NOMOR) ===== -->
    <div class="modal fade" id="footerWaModal" tabindex="-1" aria-labelledby="footerWaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; color: #1e293b;">
          <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #25D366, #128C7E);">
            <h5 class="modal-title font-weight-bold d-flex align-items-center mb-0" id="footerWaModalLabel" style="font-size: 1.1rem;">
              <i class="bi bi-whatsapp me-2 mr-2" style="font-size: 1.4rem;"></i>
              Hubungi Redaksi via WhatsApp
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1); opacity: 0.8;"></button>
          </div>
          <div class="modal-body p-4 bg-light">
            <p class="text-muted mb-3 text-center" style="font-size: 0.9rem;">
              Silakan pilih nomor WhatsApp Redaksi / Admin yang ingin Anda hubungi:
            </p>

            <!-- Option 1 -->
            <a href="https://api.whatsapp.com/send?phone=628111516310&text=Halo%20Redaksi%20Cakrawala" 
               target="_blank" rel="noopener" 
               class="contact-option-card d-flex align-items-center p-3 mb-3 bg-white border rounded-lg text-decoration-none shadow-sm">
              <div class="option-icon-wrapper rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 mr-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
              </div>
              <div class="flex-grow-1 text-start">
                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">Redaksi 1 - Cakrawala</h6>
                <span class="text-success font-weight-bold" style="font-size: 0.9rem;">0811 151 6310</span>
              </div>
              <i class="bi bi-chevron-right text-muted" style="font-size: 1.2rem;"></i>
            </a>

            <!-- Option 2 -->
            <a href="https://api.whatsapp.com/send?phone=628119830464&text=Halo%20Redaksi%20Cakrawala" 
               target="_blank" rel="noopener" 
               class="contact-option-card d-flex align-items-center p-3 bg-white border rounded-lg text-decoration-none shadow-sm">
              <div class="option-icon-wrapper rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 mr-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
              </div>
              <div class="flex-grow-1 text-start">
                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">Redaksi 2 - Cakrawala</h6>
                <span class="text-success font-weight-bold" style="font-size: 0.9rem;">0811 983 0464</span>
              </div>
              <i class="bi bi-chevron-right text-muted" style="font-size: 1.2rem;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL PILIHAN EMAIL (2 EMAIL) ===== -->
    <div class="modal fade" id="footerEmailModal" tabindex="-1" aria-labelledby="footerEmailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; color: #1e293b;">
          <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #dc3545, #b02a37);">
            <h5 class="modal-title font-weight-bold d-flex align-items-center mb-0" id="footerEmailModalLabel" style="font-size: 1.1rem;">
              <i class="bi bi-envelope-at-fill me-2 mr-2" style="font-size: 1.4rem;"></i>
              Kirim Pesan via Email
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1); opacity: 0.8;"></button>
          </div>
          <div class="modal-body p-4 bg-light">
            <p class="text-muted mb-3 text-center" style="font-size: 0.9rem;">
              Silakan pilih alamat email Redaksi yang ingin Anda tuju:
            </p>

            <!-- Option 1 -->
            <a href="mailto:cakra211@yahoo.co.id" 
               class="contact-option-card d-flex align-items-center p-3 mb-3 bg-white border rounded-lg text-decoration-none shadow-sm">
              <div class="option-icon-wrapper rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-3 mr-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="bi bi-envelope-at-fill" style="font-size: 1.4rem;"></i>
              </div>
              <div class="flex-grow-1 text-start">
                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">Email Redaksi (Yahoo)</h6>
                <span class="text-danger font-weight-bold" style="font-size: 0.88rem; word-break: break-all;">cakra211@yahoo.co.id</span>
              </div>
              <i class="bi bi-chevron-right text-muted" style="font-size: 1.2rem;"></i>
            </a>

            <!-- Option 2 -->
            <a href="mailto:suratkabarcakrawala@gmail.com" 
               class="contact-option-card d-flex align-items-center p-3 bg-white border rounded-lg text-decoration-none shadow-sm">
              <div class="option-icon-wrapper rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-3 mr-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="bi bi-envelope-fill" style="font-size: 1.4rem;"></i>
              </div>
              <div class="flex-grow-1 text-start">
                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">Email Redaksi (Gmail)</h6>
                <span class="text-danger font-weight-bold" style="font-size: 0.88rem; word-break: break-all;">suratkabarcakrawala@gmail.com</span>
              </div>
              <i class="bi bi-chevron-right text-muted" style="font-size: 1.2rem;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Script to Trigger Modals across BS4 / BS5 environments -->
    <script>
    function openFooterModal(modalId) {
      var modalEl = document.getElementById(modalId);
      if (!modalEl) return;
      
      if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        bsModal.show();
      } else if (window.jQuery && window.jQuery.fn && window.jQuery.fn.modal) {
        window.jQuery(modalEl).modal('show');
      } else {
        modalEl.style.display = 'block';
        modalEl.classList.add('show');
        document.body.classList.add('modal-open');
      }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>