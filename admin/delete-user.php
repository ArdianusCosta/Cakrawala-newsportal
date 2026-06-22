<?php
session_start();
include('includes/config.php');

// hanya Admin (role = 1) yang bisa akses
if($_SESSION['role'] != 1){
    header("Location: dashboard.php");
    exit;
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    // Jangan sampai admin menghapus dirinya sendiri
    if($id == $_SESSION['id']){
        $_SESSION['msg'] = "Tidak bisa menghapus akun Anda sendiri.";
        header("Location: manage-users.php");
        exit;
    }

    $stmt = $con->prepare("DELETE FROM tbladmin WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        $_SESSION['msg'] = "User berhasil dihapus.";
    } else {
        $_SESSION['msg'] = "Gagal menghapus user.";
    }
}
header("Location: author-list.php");
exit;
?>
