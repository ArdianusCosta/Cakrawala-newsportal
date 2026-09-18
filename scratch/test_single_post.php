<?php
$_SERVER['HTTP_HOST'] = 'localhost';
include('includes/config.php');

$q = mysqli_query($con, 'SELECT id, PostTitle, PostingDate, PostDetails FROM tblposts WHERE id=410');
$r = mysqli_fetch_assoc($q);
echo "Post ID: " . $r['id'] . "\n";
echo "Post Title: " . $r['PostTitle'] . "\n";
echo "Post Date: " . $r['PostingDate'] . "\n";
echo "Extracted Event Date: " . getEventDateFromText($r['PostDetails'], $r['PostingDate']) . "\n";
