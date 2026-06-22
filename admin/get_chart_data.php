<?php
include 'includes/config.php';

$periode = $_GET['periode'] ?? 'month';

$where = "WHERE Is_Active=1";

switch($periode){
    case 'week':
        $where .= " AND YEARWEEK(PostingDate, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'month':
        $where .= " AND YEAR(PostingDate) = YEAR(CURDATE()) 
                    AND MONTH(PostingDate) = MONTH(CURDATE())";
        break;
    case 'year':
        $where .= " AND YEAR(PostingDate) = YEAR(CURDATE())";
        break;
}

$qTop = mysqli_query($con, "
    SELECT id, PostTitle, views 
    FROM tblposts 
    $where
    ORDER BY views DESC 
    LIMIT 5
");

$labelsShort = [];
$labelsFull  = [];
$viewsArr    = [];

while($row = mysqli_fetch_assoc($qTop)){
    $words = preg_split('/\s+/', trim($row['PostTitle']));
    $short = implode(' ', array_slice($words, 0, 3));
    if(count($words) > 3) $short .= '...';
    $labelsShort[] = $short;
    $labelsFull[]  = $row['PostTitle'];
    $viewsArr[]    = (int)$row['views'];
}

echo json_encode([
    'labelsShort' => $labelsShort,
    'labelsFull'  => $labelsFull,
    'viewsData'   => $viewsArr
]);
