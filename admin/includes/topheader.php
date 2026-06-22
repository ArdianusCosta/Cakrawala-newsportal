<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($con)) {
    include('includes/config.php');
}

// Ambil data user login
$adminId = $_SESSION['id'] ?? 0;
$adminUser = null;
$avatarPath = "avatar/default.png";

if ($adminId) {
    $res = mysqli_query($con, "SELECT avatar, AdminUserName FROM tbladmin WHERE id='$adminId'");
    if ($res && mysqli_num_rows($res) > 0) {
        $adminUser = mysqli_fetch_assoc($res);

        if (!empty($adminUser['avatar']) && file_exists("avatar/" . $adminUser['avatar'])) {
            $avatarPath = "avatar/" . $adminUser['avatar'];
        }
    }
}
?>


<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left" style="background-color: #ffffff">
        <a class="logo">
            <span>
                <img src="assets/images/Logo.png" height="30">
            </span>
            <i>
                <img src="assets/images/Logo-sm.png" height="28">
            </i>
        </a>
    </div>

    <!-- Navbar -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <button class="button-menu-mobile open-left waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user-box">
                    <a href="#" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                        <img src="<?php echo $avatarPath; ?>" alt="user-img" class="img-circle user-img" width="40" height="40">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                        <li>
                            <h5>
                                Hi, <?php echo htmlentities($adminUser['AdminUserName'] ?? 'User'); ?>
                            </h5>
                        </li>
                        <li><a href="profile.php"><i class="ti-user m-r-5"></i> My Profile</a></li>
                        <li><a href="change-password.php"><i class="ti-settings m-r-5"></i> Change Password</a></li>
                        <li><a href="logout.php"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
