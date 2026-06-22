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
            text-decoration: none;
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
            background: #fb0000ff;
            transform: translateY(-2px);
        }
        
        .footer-bottom {
            background: rgba(0,0,0,0.2);
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
    <footer class="footer pt-5">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <h3 class="footer-title">
                        <img src="images/Logo.png" class="img-fluid">
                    </h3>
                    <p class="mb-4">Jl. Jenderal R.S. Sukamto No.60 Pondok Kopi, Duren Sawit Jakarta Timur</p>
                    <div class="social-links mb-4">
                        <a href="www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a href="mailto:suratkabarcakrawala@gmail.com"><i class="fa-regular fa-envelope"></i></a>
                        <a href="www.instagram.com"><i class="fab fa-instagram"></i></a>
                        <a href="wa.me/628111516310"><i class="fa-solid fa-phone"></i></a>
                        <a href="www.youtube.com"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h3 class="footer-title">Kategori</h3>
                    <ul class="footer-links">
                         <!-- Menu Dinamis dari Category -->
                        <?php 
                        $query=mysqli_query($con,"SELECT id,CategoryName FROM tblcategory WHERE Is_Active=1");
                        while($row=mysqli_fetch_array($query)) {
                        $isActive = ($currentCat == $row['id']) ? 'active' : '';
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $isActive; ?>" 
                            href="category.php?catid=<?php echo htmlentities($row['id']); ?>">
                            <?php echo htmlentities($row['CategoryName']); ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-lg-2 col-md-6">
                    <h3 class="footer-title">Tentang Kami  </h3>
                    <ul class="footer-links">
                        <li><a href="about-us.php">Tentang Kami</a></li>
                        <li><a href="contact-us.php">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-4 col-md-6">
                    <h3 class="footer-title">Surat Kabar</h3>
                    <p class= "mb-4">Berlangganan Surat Kabar Mingguan Cakrawala melalui email kami</p>
                    <form class="mb-4">
                        <div class="input-group">
                            <input type="email" class="form-control newsletter-input" placeholder="Enter your email">
                            <button class="btn btn-subscribe text-white" type="submit">Berlangganan</button>
                        </div>
                    </form>
                    <p class="small">Dengan berlangganan kamu menyetujui peraturan dari Cakrawala</p>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom mt-5">
            <div class="container">
                <div class="row py-3">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">2026 &copy; PT Cakrawala Pers Media</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">  </a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>