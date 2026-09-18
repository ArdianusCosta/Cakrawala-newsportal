<?php
$_SERVER['HTTP_HOST'] = 'localhost';
include('includes/config.php');

$res = mysqli_query($con, "DESCRIBE tblposts");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}
