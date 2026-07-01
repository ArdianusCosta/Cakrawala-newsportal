<?php
// Dynamic sitemap generator — outputs sitemap XML for all active posts
header('Content-Type: application/xml; charset=utf-8');
include('includes/config.php');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// static pages
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$static = [
    '/',
    '/index.php',
    '/all-news.php',
    '/contact-us.php',
    '/about-us.php'
];
foreach ($static as $path) {
    $loc = rtrim($base, '/') . $path;
    echo "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <changefreq>daily</changefreq>\n    <priority>0.8</priority>\n  </url>\n";
}

// posts from database
$res = mysqli_query($con, "SELECT id, PostingDate, PostUrl FROM tblposts WHERE Is_Active=1 ORDER BY PostingDate DESC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $pid = $row['id'];
        $posting = $row['PostingDate'];
        $url = rtrim($base, '/') . '/news-details.php?nid=' . $pid;
        $lastmod = date('c', strtotime($posting));
        echo "  <url>\n    <loc>" . htmlspecialchars($url) . "</loc>\n    <lastmod>" . $lastmod . "</lastmod>\n    <changefreq>weekly</changefreq>\n    <priority>0.7</priority>\n  </url>\n";
    }
}

echo '</urlset>';

?>