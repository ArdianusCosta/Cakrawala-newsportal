<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['login'])==0){
    header('location:index.php');
    exit;
}

$role = $_SESSION['role'];
$postid = intval($_GET['id']); // id sesuai link dari manage-posts.php

// Hanya role 1 & 2 yang bisa approve
if($role == 1 || $role == 2){
    $query = mysqli_query($con,"UPDATE tblposts SET Is_Active=1 WHERE id='$postid'");
    if($query){
        $_SESSION['msg']="Post berhasil diapprove";
    } else {
        $_SESSION['msg']="Gagal approve post";
    }
    header('location: manage-posts.php');
    exit;
} else {
    echo "Anda tidak punya akses untuk approve.";
    exit;
}
?>
