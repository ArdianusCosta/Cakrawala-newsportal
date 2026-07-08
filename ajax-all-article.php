<?php
session_start();
include('includes/config.php');

$pageno = isset($_GET['pageno']) ? intval($_GET['pageno']) : 1;
if ($pageno < 1) { $pageno = 1; }

include('includes/all-list.php');
