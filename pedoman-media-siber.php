<?php
include('includes/config.php');

$pageTitle = 'Pedoman Media Siber - CakrawalaOnline.com';
$pageDescription = 'Pedoman Media Siber Cakrawalaonline.com sebagai acuan kegiatan jurnalistik, hak jawab, akurasi, dan tanggung jawab terhadap publik.';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include('includes/seo-meta.php'); ?>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom styles -->
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">

    <style>
        .pedoman-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            margin-bottom: 3rem;
            border: 1px solid #edf2f7;
        }

        .pedoman-header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .pedoman-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .pedoman-subtitle {
            font-size: 1.05rem;
            color: #64748b;
        }

        .pedoman-section {
            margin-bottom: 2.25rem;
        }

        .pedoman-section h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #dc3545;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .pedoman-section h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 18px;
            background-color: #dc3545;
            border-radius: 2px;
            margin-right: 10px;
        }

        .pedoman-body p {
            color: #334155;
            line-height: 1.75;
            font-size: 0.98rem;
            margin-bottom: 1rem;
        }

        .pedoman-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1rem;
        }

        .pedoman-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 0.75rem;
            color: #334155;
            line-height: 1.7;
            font-size: 0.97rem;
        }

        .pedoman-list li .item-letter {
            position: absolute;
            left: 0;
            top: 0;
            font-weight: 700;
            color: #dc3545;
            width: 1.5rem;
        }

        .pedoman-footer-note {
            background-color: #f8fafc;
            border-left: 4px solid #dc3545;
            padding: 1.25rem;
            border-radius: 0 12px 12px 0;
            margin-top: 2.5rem;
        }

        @media (max-width: 768px) {
            .pedoman-container {
                padding: 1.5rem;
                border-radius: 12px;
            }
            .pedoman-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <?php include('includes/header.php'); ?>

    <!-- Page Content -->
    <div class="container mt-4">

        <!-- Breadcrumb -->
        <ol class="breadcrumb bg-light rounded-pill px-3 py-2 mb-4" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-danger font-weight-bold"><i class="bi bi-house-door-fill me-1"></i> Home</a></li>
            <li class="breadcrumb-item active text-muted">Pedoman Media Siber</li>
        </ol>

        <div class="row">
            <div class="col-lg-12">
                <div class="pedoman-container">
                    
                    <div class="pedoman-header text-center text-md-start">
                        <span class="badge badge-danger text-uppercase px-3 py-2 mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">Ketentuan & Regulasi Redaksi</span>
                        <h1 class="pedoman-title mt-1 mb-2">PEDOMAN MEDIA SIBER CAKRAWALAONLINE.COM</h1>
                        <p class="pedoman-subtitle mb-0">Standar Etika dan Panduan Jurnalistik Redaksi Cakrawala Online</p>
                    </div>

                    <div class="pedoman-body">
                        
                        <!-- Pendahuluan -->
                        <div class="pedoman-section">
                            <h2>Pendahuluan</h2>
                            <p>Cakrawalaonline.com merupakan media siber yang menjalankan kegiatan jurnalistik dengan menjunjung tinggi kemerdekaan pers, kepentingan publik, akurasi informasi, keberimbangan, independensi, serta tanggung jawab terhadap masyarakat.</p>
                            <p>Dalam menjalankan kegiatan jurnalistik, Cakrawalaonline.com berpedoman pada Undang-Undang Nomor 40 Tahun 1999 tentang Pers, Kode Etik Jurnalistik, Pedoman Pemberitaan Media Siber, serta berbagai ketentuan dan pedoman Dewan Pers yang berlaku.</p>
                            <p>Pedoman ini menjadi acuan bagi redaksi, wartawan, kontributor, serta pihak yang terlibat dalam proses produksi dan publikasi konten jurnalistik Cakrawalaonline.com. Pedoman ini disusun untuk menjaga kualitas pemberitaan sekaligus memberikan kepastian mengenai hak dan kewajiban media, narasumber, dan masyarakat.</p>
                        </div>

                        <!-- 1. Prinsip Dasar Pemberitaan -->
                        <div class="pedoman-section">
                            <h2>1. Prinsip Dasar Pemberitaan</h2>
                            <p>Cakrawalaonline.com berkomitmen menyajikan informasi yang akurat, faktual, relevan, dan memiliki nilai kepentingan publik.</p>
                            <p>Dalam menjalankan tugas jurnalistik, redaksi dan wartawan Cakrawalaonline.com wajib:</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Mengutamakan kepentingan publik dalam pemberitaan.</li>
                                <li><span class="item-letter">b.</span> Menjaga independensi dan tidak membiarkan kepentingan pribadi, politik, bisnis, atau tekanan dari pihak tertentu memengaruhi isi pemberitaan.</li>
                                <li><span class="item-letter">c.</span> Menghormati hukum, norma kesusilaan, hak asasi manusia, serta prinsip keberagaman masyarakat Indonesia.</li>
                                <li><span class="item-letter">d.</span> Menghindari pemberitaan yang mengandung prasangka, diskriminasi, kebencian, atau penghakiman terhadap seseorang atau kelompok.</li>
                                <li><span class="item-letter">e.</span> Menghormati hak privasi seseorang sepanjang tidak berkaitan dengan kepentingan publik.</li>
                                <li><span class="item-letter">f.</span> Mengutamakan asas praduga tak bersalah terhadap seseorang yang sedang menjalani proses hukum.</li>
                            </ul>
                        </div>

                        <!-- 2. Akurasi dan Verifikasi Informasi -->
                        <div class="pedoman-section">
                            <h2>2. Akurasi dan Verifikasi Informasi</h2>
                            <p>Cakrawalaonline.com berupaya memastikan setiap informasi yang diterbitkan telah melalui proses pemeriksaan dan verifikasi yang memadai.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Informasi yang menjadi dasar pemberitaan harus berasal dari sumber yang jelas dan dapat dipertanggungjawabkan.</li>
                                <li><span class="item-letter">b.</span> Redaksi melakukan pengecekan terhadap nama, jabatan, tempat, waktu, angka, kutipan, dan fakta penting lainnya sebelum berita dipublikasikan.</li>
                                <li><span class="item-letter">c.</span> Berita yang berpotensi merugikan nama baik atau kepentingan pihak tertentu harus diupayakan memperoleh konfirmasi dari pihak yang diberitakan.</li>
                                <li><span class="item-letter">d.</span> Apabila konfirmasi belum dapat diperoleh, redaksi dapat tetap menerbitkan berita apabila terdapat kepentingan publik yang mendesak dan informasi yang tersedia memiliki dasar yang memadai. Dalam keadaan tersebut, Cakrawalaonline.com akan menjelaskan bahwa konfirmasi masih diupayakan.</li>
                                <li><span class="item-letter">e.</span> Setelah memperoleh informasi atau konfirmasi tambahan, redaksi dapat melakukan pemutakhiran terhadap berita yang telah diterbitkan.</li>
                            </ul>
                        </div>

                        <!-- 3. Keberimbangan dan Hak Narasumber -->
                        <div class="pedoman-section">
                            <h2>3. Keberimbangan dan Hak Narasumber</h2>
                            <p>Cakrawalaonline.com memberikan kesempatan yang wajar kepada pihak-pihak yang terkait dengan suatu pemberitaan untuk memberikan penjelasan atau tanggapan.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Berita yang memuat tuduhan, sengketa, dugaan pelanggaran, atau informasi yang berpotensi merugikan seseorang atau lembaga akan diupayakan memuat keterangan dari pihak terkait.</li>
                                <li><span class="item-letter">b.</span> Ketidakhadiran atau tidak tersedianya narasumber untuk memberikan konfirmasi tidak boleh secara otomatis dianggap sebagai pengakuan atas informasi yang diberitakan.</li>
                                <li><span class="item-letter">c.</span> Setiap kutipan narasumber harus disampaikan secara utuh sesuai konteks dan tidak dipelintir sehingga mengubah makna pernyataan.</li>
                                <li><span class="item-letter">d.</span> Cakrawalaonline.com menghormati hak narasumber untuk menyampaikan klarifikasi, koreksi, maupun hak jawab sesuai ketentuan yang berlaku.</li>
                            </ul>
                        </div>

                        <!-- 4. Identitas dan Perlindungan Privasi -->
                        <div class="pedoman-section">
                            <h2>4. Identitas dan Perlindungan Privasi</h2>
                            <p>Dalam pemberitaan, Cakrawalaonline.com mempertimbangkan kepentingan publik sekaligus melindungi hak privasi individu.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Informasi pribadi yang tidak memiliki relevansi dengan kepentingan publik tidak akan dipublikasikan secara berlebihan.</li>
                                <li><span class="item-letter">b.</span> Data pribadi seperti nomor telepon, alamat tempat tinggal, dokumen identitas, dan informasi pribadi lainnya hanya dicantumkan apabila memiliki alasan jurnalistik yang jelas dan sesuai ketentuan yang berlaku.</li>
                                <li><span class="item-letter">c.</span> Dalam pemberitaan mengenai korban kejahatan, anak, kekerasan seksual, atau kelompok rentan, redaksi menerapkan perlindungan identitas dan prinsip kehati-hatian sesuai pedoman jurnalistik yang berlaku.</li>
                                <li><span class="item-letter">d.</span> Foto, video, atau materi visual yang dapat membuka identitas pihak yang seharusnya dilindungi akan dipertimbangkan secara khusus sebelum dipublikasikan.</li>
                            </ul>
                        </div>

                        <!-- 5. Pemberitaan Perkara Hukum -->
                        <div class="pedoman-section">
                            <h2>5. Pemberitaan Perkara Hukum</h2>
                            <p>Cakrawalaonline.com menghormati proses hukum dan menerapkan asas praduga tak bersalah.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Seseorang yang masih berstatus terduga, tersangka, terdakwa, atau pihak yang sedang menjalani proses hukum tidak boleh diberitakan seolah-olah telah terbukti bersalah.</li>
                                <li><span class="item-letter">b.</span> Status hukum seseorang harus disebutkan secara tepat sesuai informasi resmi yang tersedia.</li>
                                <li><span class="item-letter">c.</span> Redaksi tidak menjadikan pemberitaan sebagai sarana untuk menghakimi pihak yang sedang berhadapan dengan proses hukum.</li>
                                <li><span class="item-letter">d.</span> Perkembangan perkara akan diperbarui apabila terdapat informasi resmi atau perkembangan yang relevan.</li>
                            </ul>
                        </div>

                        <!-- 6. Judul, Foto, dan Materi Pendukung -->
                        <div class="pedoman-section">
                            <h2>6. Judul, Foto, dan Materi Pendukung</h2>
                            <p>Judul dan materi visual merupakan bagian dari informasi yang disampaikan kepada pembaca dan harus mengikuti prinsip akurasi.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Judul berita harus menggambarkan isi berita dan tidak dibuat secara menyesatkan.</li>
                                <li><span class="item-letter">b.</span> Cakrawalaonline.com menghindari judul yang sengaja dibuat untuk menimbulkan kesan berbeda dari isi berita.</li>
                                <li><span class="item-letter">c.</span> Foto, video, grafik, maupun ilustrasi harus memiliki hubungan dengan berita yang disajikan.</li>
                                <li><span class="item-letter">d.</span> Foto atau video lama yang digunakan untuk menjelaskan peristiwa tertentu harus diberikan keterangan yang sesuai agar tidak menimbulkan kesalahpahaman.</li>
                                <li><span class="item-letter">e.</span> Penggunaan materi milik pihak lain dilakukan dengan memperhatikan hak cipta dan ketentuan peraturan perundang-undangan.</li>
                            </ul>
                        </div>

                        <!-- 7. Koreksi, Ralat, dan Hak Jawab -->
                        <div class="pedoman-section">
                            <h2>7. Koreksi, Ralat, dan Hak Jawab</h2>
                            <p>Cakrawalaonline.com terbuka terhadap koreksi atas kesalahan informasi yang ditemukan setelah berita diterbitkan.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Kesalahan faktual yang diketahui setelah publikasi akan diperbaiki secara proporsional.</li>
                                <li><span class="item-letter">b.</span> Perubahan yang bersifat substantif akan dilakukan secara transparan dan, apabila diperlukan, disertai keterangan mengenai adanya koreksi atau pemutakhiran.</li>
                                <li><span class="item-letter">c.</span> Koreksi tidak dilakukan untuk mengubah fakta secara sepihak demi menghilangkan informasi yang tidak disukai oleh pihak tertentu.</li>
                                <li><span class="item-letter">d.</span> Pihak yang merasa dirugikan oleh pemberitaan dapat mengajukan hak jawab sesuai mekanisme yang berlaku.</li>
                                <li><span class="item-letter">e.</span> Hak jawab atau koreksi yang diterima dan memenuhi ketentuan akan diproses oleh redaksi secara proporsional serta ditautkan dengan berita terkait apabila diperlukan.</li>
                            </ul>
                        </div>

                        <!-- 8. Pencabutan atau Penghapusan Berita -->
                        <div class="pedoman-section">
                            <h2>8. Pencabutan atau Penghapusan Berita</h2>
                            <p>Cakrawalaonline.com pada prinsipnya tidak menghapus berita hanya karena adanya permintaan dari pihak yang tidak menyukai isi pemberitaan.</p>
                            <p>Namun, pencabutan atau perubahan terhadap berita dapat dipertimbangkan dalam keadaan tertentu, antara lain apabila terdapat kesalahan serius, persoalan perlindungan anak, privasi, kesusilaan, pengalaman traumatis korban, atau pertimbangan jurnalistik dan hukum lainnya.</p>
                            <p>Setiap pencabutan berita yang dilakukan karena alasan khusus akan dipertimbangkan oleh redaksi dan, apabila diperlukan, disertai penjelasan kepada pembaca.</p>
                        </div>

                        <!-- 9. Konten Buatan Pengguna -->
                        <div class="pedoman-section">
                            <h2>9. Konten Buatan Pengguna</h2>
                            <p>Apabila Cakrawalaonline.com menyediakan ruang bagi pembaca untuk mengirimkan atau menampilkan konten buatan pengguna, setiap konten yang masuk dapat melalui proses moderasi.</p>
                            <p>Konten buatan pengguna tidak diperbolehkan memuat:</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Informasi palsu yang sengaja dibuat untuk menyesatkan.</li>
                                <li><span class="item-letter">b.</span> Fitnah, penghinaan, atau serangan terhadap pribadi maupun kelompok.</li>
                                <li><span class="item-letter">c.</span> Konten yang mengandung kebencian berbasis suku, agama, ras, antargolongan, atau bentuk diskriminasi lainnya.</li>
                                <li><span class="item-letter">d.</span> Ajakan atau ancaman kekerasan.</li>
                                <li><span class="item-letter">e.</span> Materi pornografi, sadisme, atau konten lain yang melanggar ketentuan hukum.</li>
                                <li><span class="item-letter">f.</span> Informasi pribadi seseorang yang disebarkan tanpa dasar yang sah.</li>
                            </ul>
                            <p>Cakrawalaonline.com berhak menolak, menyunting, menyembunyikan, atau menghapus konten pengguna yang melanggar ketentuan tersebut.</p>
                        </div>

                        <!-- 10. Iklan dan Konten Berbayar -->
                        <div class="pedoman-section">
                            <h2>10. Iklan dan Konten Berbayar</h2>
                            <p>Cakrawalaonline.com membedakan secara jelas antara produk jurnalistik dengan materi iklan atau konten berbayar.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Konten yang dibuat berdasarkan kerja sama komersial akan diberikan penanda yang sesuai.</li>
                                <li><span class="item-letter">b.</span> Penanda dapat berupa “Advertorial”, “Iklan”, “Sponsored”, “Konten Berbayar”, atau keterangan lain yang menjelaskan sifat komersial dari konten tersebut.</li>
                                <li><span class="item-letter">c.</span> Materi iklan tidak boleh disajikan sedemikian rupa sehingga pembaca kesulitan membedakannya dari berita jurnalistik.</li>
                                <li><span class="item-letter">d.</span> Kerja sama komersial tidak menghilangkan tanggung jawab redaksi untuk menjaga akurasi informasi.</li>
                            </ul>
                        </div>

                        <!-- 11. Penggunaan Kecerdasan Buatan -->
                        <div class="pedoman-section">
                            <h2>11. Penggunaan Kecerdasan Buatan</h2>
                            <p>Cakrawalaonline.com dapat memanfaatkan teknologi kecerdasan buatan sebagai alat bantu dalam proses kerja jurnalistik, sepanjang penggunaannya tidak mengurangi tanggung jawab manusia terhadap karya jurnalistik.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> AI tidak digunakan sebagai pengganti proses verifikasi dan penilaian jurnalistik.</li>
                                <li><span class="item-letter">b.</span> Informasi yang dihasilkan oleh AI harus diperiksa kembali sebelum digunakan dalam berita.</li>
                                <li><span class="item-letter">c.</span> Redaksi bertanggung jawab terhadap akurasi, konteks, dan isi akhir karya jurnalistik yang dipublikasikan.</li>
                                <li><span class="item-letter">d.</span> Cakrawalaonline.com tidak menggunakan AI untuk membuat fakta, narasumber, kutipan, peristiwa, atau data yang tidak pernah terjadi.</li>
                                <li><span class="item-letter">e.</span> Penggunaan foto, video, audio, atau materi visual yang dibuat atau dimodifikasi menggunakan AI harus mempertimbangkan transparansi kepada pembaca dan tidak boleh digunakan untuk menyesatkan publik.</li>
                            </ul>
                        </div>

                        <!-- 12. Hak Cipta dan Atribusi Sumber -->
                        <div class="pedoman-section">
                            <h2>12. Hak Cipta dan Atribusi Sumber</h2>
                            <p>Cakrawalaonline.com menghormati hak cipta dan kepemilikan karya jurnalistik maupun karya kreatif lainnya.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Penggunaan tulisan, foto, video, grafik, atau materi pihak lain dilakukan dengan memperhatikan hak cipta.</li>
                                <li><span class="item-letter">b.</span> Sumber informasi, foto, data, atau materi yang diperoleh dari pihak lain dicantumkan secara proporsional dan jelas apabila diperlukan.</li>
                                <li><span class="item-letter">c.</span> Pengutipan dari media lain tidak dilakukan secara berlebihan dan tetap memperhatikan prinsip atribusi serta ketentuan hak cipta.</li>
                                <li><span class="item-letter">d.</span> Cakrawalaonline.com tidak mengklaim karya pihak lain sebagai karya redaksi sendiri.</li>
                            </ul>
                        </div>

                        <!-- 13. Sumber Anonim -->
                        <div class="pedoman-section">
                            <h2>13. Sumber Anonim</h2>
                            <p>Cakrawalaonline.com dapat menggunakan sumber anonim dalam keadaan tertentu apabila informasi tersebut memiliki kepentingan publik dan sumber memiliki alasan yang dapat dipertanggungjawabkan untuk tidak mengungkapkan identitasnya.</p>
                            <p>Penggunaan sumber anonim menjadi pertimbangan redaksi dan tidak dilakukan semata-mata untuk memperkuat berita tanpa dasar yang memadai.</p>
                            <p>Redaksi tetap berkewajiban melakukan verifikasi terhadap informasi yang diberikan oleh sumber tersebut.</p>
                        </div>

                        <!-- 14. Independensi Redaksi -->
                        <div class="pedoman-section">
                            <h2>14. Independensi Redaksi</h2>
                            <p>Redaksi Cakrawalaonline.com memiliki tanggung jawab untuk menjaga independensi dalam menentukan isi dan arah pemberitaan.</p>
                            <ul class="pedoman-list">
                                <li><span class="item-letter">a.</span> Pemberitaan tidak boleh dipengaruhi oleh tekanan, kepentingan pribadi, atau kepentingan komersial yang bertentangan dengan prinsip jurnalistik.</li>
                                <li><span class="item-letter">b.</span> Hubungan kerja sama dengan pihak tertentu tidak secara otomatis memberikan hak kepada pihak tersebut untuk menentukan isi berita.</li>
                                <li><span class="item-letter">c.</span> Wartawan dan redaksi wajib menghindari konflik kepentingan yang dapat memengaruhi independensi pemberitaan.</li>
                            </ul>
                        </div>

                        <!-- 15. Pengaduan dan Penyelesaian Sengketa -->
                        <div class="pedoman-section">
                            <h2>15. Pengaduan dan Penyelesaian Sengketa</h2>
                            <p>Cakrawalaonline.com menyediakan ruang komunikasi bagi masyarakat atau pihak yang merasa dirugikan oleh pemberitaan untuk menyampaikan keberatan, koreksi, maupun hak jawab.</p>
                            <p>Setiap pengaduan akan diterima dan dipertimbangkan berdasarkan fakta, ketentuan jurnalistik, Kode Etik Jurnalistik, dan peraturan yang berlaku.</p>
                            <p>Penyelesaian sengketa pers diupayakan terlebih dahulu melalui mekanisme hak jawab, hak koreksi, dan mekanisme penyelesaian sengketa pers sesuai ketentuan Dewan Pers dan peraturan perundang-undangan yang berlaku.</p>
                        </div>

                        <!-- 16. Tanggung Jawab Redaksi -->
                        <div class="pedoman-section">
                            <h2>16. Tanggung Jawab Redaksi</h2>
                            <p>Seluruh materi jurnalistik yang diterbitkan melalui Cakrawalaonline.com merupakan tanggung jawab redaksi sesuai dengan kewenangan dan proses editorial yang berlaku.</p>
                            <p>Cakrawalaonline.com berkomitmen untuk terus meningkatkan kualitas pemberitaan, memperbaiki kesalahan secara bertanggung jawab, menghormati hak masyarakat, serta menjalankan fungsi pers secara profesional dan berorientasi pada kepentingan publik.</p>
                        </div>

                        <!-- 17. Pencantuman Pedoman -->
                        <div class="pedoman-section">
                            <h2>17. Pencantuman Pedoman</h2>
                            <p>Pedoman Media Siber Cakrawalaonline.com ini tersedia untuk diketahui oleh masyarakat dan menjadi bagian dari komitmen redaksi dalam menjalankan kegiatan jurnalistik secara profesional, transparan, dan bertanggung jawab.</p>
                            <p>Pedoman ini dapat diperbarui apabila terdapat perubahan peraturan perundang-undangan, pedoman Dewan Pers, perkembangan teknologi, maupun kebutuhan pengelolaan media siber.</p>
                        </div>

                        <!-- Footer Statement -->
                        <div class="pedoman-footer-note">
                            <h6 class="font-weight-bold text-dark mb-1">Cakrawalaonline.com</h6>
                            <p class="mb-0 text-muted" style="font-size: 0.92rem;">
                                Pedoman ini mulai berlaku sejak ditetapkan dan menjadi acuan bagi pengelolaan pemberitaan Cakrawalaonline.com.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
