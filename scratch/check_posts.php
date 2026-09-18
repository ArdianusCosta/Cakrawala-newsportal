<?php
$_SERVER['HTTP_HOST'] = 'localhost';
include('includes/config.php');

$res = mysqli_query($con, 'SELECT id, PostTitle, PostingDate, PostDetails FROM tblposts ORDER BY id DESC LIMIT 15');
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . " | PostDate: " . $row['PostingDate'] . "\n";
    echo "Title: " . $row['PostTitle'] . "\n";
    $text = strip_tags($row['PostDetails']);
    echo "Text: " . mb_substr($text, 0, 400) . "\n";
    echo "---------------------------------------------------------\n";
}
