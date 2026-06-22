<head>
    <style>
    /* * CSS untuk menu aktif
     */

    /* Gaya untuk menu utama yang aktif */
    #sidebar-menu > ul > li > a.active {
        color: #D32F2F !important; /* Warna merah untuk teks & ikon */
        border-left: 4px solid #D32F2F; /* Garis merah tebal di kiri */
        background-color: #FFEBEE; /* Latar belakang merah muda lembut */
    }

    /* Memastikan ikon di dalam menu aktif juga berwarna merah */
    #sidebar-menu > ul > li > a.active i {
        color: #D32F2F !important;
    }

    /* Gaya untuk sub-menu yang aktif (jika ada) */
    #sidebar-menu ul ul a.active {
        color: #D32F2F !important; /* Warna merah untuk teks sub-menu */
    }

    /* Menghapus background pada menu yang tidak aktif saat di-hover,
       agar tidak menimpa gaya menu aktif */
    #sidebar-menu > ul > li > a:hover {
        background: #f3f3f3;
        color: #D32F2F;
    }
    #sidebar-menu > ul > li > a.active:hover {
        background-color: #FFEBEE; /* Pertahankan background saat di-hover */
    }
    </style>
</head>

<?php
// --- Bagian Atas Tetap Sama ---
$role = $_SESSION['role']; 
$user_id = $_SESSION['id']; 

$can_post = false;
$current_page = basename($_SERVER['PHP_SELF']);

if($role == 3) {
    $check = $con->prepare("SELECT status, end_date FROM tblauthors WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $check->bind_param("i", $user_id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();
    if($res) {
        $today = date("Y-m-d");
        if($res['status'] == 'approved' && $res['end_date'] >= $today) {
            $can_post = true;
        }
    }
}


$dashboard_link = 'dashboard.php';
if($role == 2) $dashboard_link = 'staff-dashboard.php';
if($role == 3) $dashboard_link = 'penulis.php';

$dashboard_pages = ['dashboard.php', 'staff-dashboard.php', 'penulis.php'];
$category_pages = ['add-category.php', 'manage-categories.php'];
$subcategory_pages = ['add-subcategory.php', 'manage-subcategories.php'];
$post_pages = ['add-post.php', 'manage-posts.php', 'trash-posts.php'];
$static_pages = ['aboutus.php', 'contactus.php'];
$author_pages = ['manage-authors.php', 'author-list.php', 'add_admin.php', 'edit-user.php', 'delete-user.php'];
$penulis_account_pages = ['subscribe.php', 'profile.php'];

?>

<div class="left side-menu" style="background-color: #ffffff;">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Navigasi</li>

                <li>
                    <a href="<?= htmlspecialchars($dashboard_link) ?>" class="waves-effect <?= in_array($current_page, $dashboard_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-home"></i> <span> Beranda </span>
                    </a>
                </li>

                <?php if($role == 1 || $role == 2): ?>
                <li>
                    <a href="manage-categories.php" class="waves-effect <?= in_array($current_page, $category_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-newspaper"></i> <span> Kategori </span>
                    </a>
                </li>

                <li>
                    <a href="manage-subcategories.php" class="waves-effect <?= in_array($current_page, $subcategory_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-layers"></i> <span> Sub Kategori </span>
                    </a>
                </li>

                <?php endif; ?>

                <?php if ($role == 3): ?>
                    <li>
                         <?php if($can_post): ?>
                        <a href="add-post.php" class="waves-effect <?= ($current_page == 'add-post.php') ? 'active' : '' ?>">
                            <i class="mdi mdi-pencil"></i> <span> Tambah Artikel </span>
                            <?php else: ?>
                                <li><a style="color:gray; cursor:not-allowed;">Tambah Artikel (Terkunci)</a></li>
                            <?php endif; ?>
                        </a>

                        <a href="manage-posts.php" class="<?= ($current_page == 'manage-posts.php') ? 'active' : '' ?>">
                            <i class="mdi mdi-format-list-bulleted"></i> <span> Kelola Artikel </span></a>
                        <a href="trash-posts.php" class="<?= ($current_page == 'trash-posts.php') ? 'active' : '' ?>">
                             <i class="mdi mdi-delete"></i><span>Artikel Dihapus</span></a>

                    </li>
                <?php endif; ?>

                <?php if($role == 1 || $role == 2 ): ?>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect <?= in_array($current_page, $post_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-lead-pencil"></i> <span> Artikel </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <?php if($role == 1 || $role == 2): ?>
                            <li><a href="add-post.php" class="<?= ($current_page == 'add-post.php') ? 'active' : '' ?>">Tambah Artikel</a></li>
                        <?php elseif($role == 3): ?>
                            <?php if($can_post): ?>
                                <li><a href="add-post.php" class="<?= ($current_page == 'add-post.php') ? 'active' : '' ?>">Tambah Artikel</a></li>
                            <?php else: ?>
                                <li><a style="color:gray; cursor:not-allowed;">Tambah Artikel (Terkunci)</a></li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <li><a href="manage-posts.php" class="<?= ($current_page == 'manage-posts.php') ? 'active' : '' ?>">Kelola Artikel</a></li>
                        <?php if($role == 1 || $role == 2): ?>
                            <li><a href="trash-posts.php" class="<?= ($current_page == 'trash-posts.php') ? 'active' : '' ?>">Artikel Dihapus</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if($role == 1): ?>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect <?= in_array($current_page, $static_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-file-outline"></i> <span > Halaman </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="aboutus.php" class="<?= ($current_page == 'aboutus.php') ? 'active' : '' ?>">Tentang Kami</a></li>
                        <li><a href="contactus.php" class="<?= ($current_page == 'contactus.php') ? 'active' : '' ?>">Hubungi Kami</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect <?= in_array($current_page, $author_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-account-multiple-outline"></i> <span> Pengguna </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="manage-authors.php" class="<?= ($current_page == 'manage-authors.php') ? 'active' : '' ?>">Langganan penulis</a></li>
                        <li><a href="author-list.php" class="<?= ($current_page == 'author-list.php') ? 'active' : '' ?>">Daftar Penulis</a></li>
                        <li><a href="add_admin.php" class="<?= ($current_page == 'add_admin.php') ? 'active' : '' ?>">Tambah Pengguna</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if($role == 3): ?>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect <?= in_array($current_page, $penulis_account_pages) ? 'active' : '' ?>">
                        <i class="mdi mdi-account"></i> <span> Akun Saya </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="subscribe.php" class="<?= ($current_page == 'subscribe.php') ? 'active' : '' ?>">Langganan</a></li>
                        <li><a href="profile.php" class="<?= ($current_page == 'profile.php') ? 'active' : '' ?>">Lihat Profil</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

