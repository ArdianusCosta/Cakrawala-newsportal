<?php
session_start();
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

$msg = '';
$error = '';

if (isset($_POST['submit'])) {
    // Get form data
    $current_password = $_POST['password'];
    $new_password = $_POST['newpassword'];
    $confirm_password = $_POST['confirmpassword'];

    // Basic validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Use session username (stored during login)
        $username = $_SESSION['login'];

        // Fetch current password hash from database using prepared statement
        $stmt = $con->prepare("SELECT AdminPassword FROM tbladmin WHERE AdminUserName = ? OR AdminEmailId = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($dbpassword);
        $stmt->fetch();

        if ($stmt->num_rows == 0) {
            $error = "User not found.";
        } else {
            // Verify current password
            if (password_verify($current_password, $dbpassword)) {
                // Hash new password with bcrypt
                $options = ['cost' => 12];
                $new_hashed = password_hash($new_password, PASSWORD_BCRYPT, $options);

                // Update password using prepared statement
                $update = $con->prepare("UPDATE tbladmin SET AdminPassword = ?, UpdationDate = NOW() WHERE AdminUserName = ? OR AdminEmailId = ?");
                $update->bind_param("sss", $new_hashed, $username, $username);
                if ($update->execute()) {
                    $msg = "Password Changed Successfully!";
                } else {
                    $error = "Failed to update password: " . $update->error;
                }
                $update->close();
            } else {
                $error = "Current password is incorrect.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Password | Cakrawala</title>
    <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/modernizr.min.js"></script>
    <script>
        function valid() {
            var password = document.forms["chngpwd"]["password"].value;
            var newpass = document.forms["chngpwd"]["newpassword"].value;
            var confirmpass = document.forms["chngpwd"]["confirmpassword"].value;

            if (password == "") {
                alert("Current Password is empty!");
                document.forms["chngpwd"]["password"].focus();
                return false;
            }
            if (newpass == "") {
                alert("New Password is empty!");
                document.forms["chngpwd"]["newpassword"].focus();
                return false;
            }
            if (confirmpass == "") {
                alert("Confirm Password is empty!");
                document.forms["chngpwd"]["confirmpassword"].focus();
                return false;
            }
            if (newpass != confirmpass) {
                alert("New Password and Confirm Password do not match!");
                document.forms["chngpwd"]["confirmpassword"].focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="fixed-left">
    <div id="wrapper">
        <?php include('includes/topheader.php'); ?>
        <?php include('includes/leftsidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Change Password</h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li><a href="#">Admin</a></li>
                                    <li class="active">Change Password</li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title"><b>Change Password</b></h4>
                                <hr />

                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php if ($msg): ?>
                                            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
                                        <?php endif; ?>
                                        <?php if ($error): ?>
                                            <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <form class="form-horizontal" name="chngpwd" method="post" onsubmit="return valid();">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Current Password</label>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="password" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">New Password</label>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="newpassword" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Confirm Password</label>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="confirmpassword" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">&nbsp;</label>
                                                <div class="col-md-8">
                                                    <button type="submit" class="btn btn-custom waves-effect waves-light btn-md" name="submit">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>
</body>
</html>
<?php ?>