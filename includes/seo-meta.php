<?php
// SEO meta tags — include this INSIDE <head>...</head>, before </head>.
// Individual pages can set $pageTitle and $pageDescription (and
// optionally $pageUrl, $imageUrl) before including this file to
// provide page-specific metadata. Falls back to site defaults otherwise.

$siteDefaultDescription = 'Cakrawala Online, situs berita daerah dan nasional terpercaya. Sajikan info terkini Seputar Indramayu, Hukum & Kriminal, Olahraga, dan Ragam peristiwa';
$meta_description = isset($pageDescription) ? $pageDescription : $siteDefaultDescription;
$meta_description = htmlspecialchars($meta_description);

// Canonical host is hardcoded to non-www so pages always report the
// same URL to Google, even if visited via www.cakrawalaonline.com.
$canonicalHost = 'https://cakrawalaonline.com';
$meta_url = isset($pageUrl) ? $pageUrl : ($canonicalHost . $_SERVER['REQUEST_URI']);
$meta_image = isset($imageUrl) ? $imageUrl : 'https://cakrawalaonline.com/admin/uploads/default.jpg';
$meta_title = isset($pageTitle) ? $pageTitle : 'Cakrawala Online';
?>
  <title><?php echo htmlspecialchars($meta_title); ?></title>
  <meta name="description" content="<?php echo $meta_description; ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($meta_url); ?>">

  <!-- Mobile Viewport & iOS Safari Safe Area Optimization -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, shrink-to-fit=no, viewport-fit=cover">
  <meta name="theme-color" content="#ffffff">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Cakrawala">
  <meta name="format-detection" content="telephone=no">

  <!-- Apple Touch Icons & Favicons for iOS Safari Tab Preview, Android & Web -->
  <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
  <link rel="apple-touch-icon-precomposed" sizes="180x180" href="images/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
  <link rel="shortcut icon" href="images/Logo.ico" type="image/x-icon">
  <link rel="manifest" href="site.webmanifest">

  <!-- Open Graph / Facebook / iMessage -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta property="og:description" content="<?php echo $meta_description; ?>">
  <meta property="og:url" content="<?php echo htmlspecialchars($meta_url); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($meta_image); ?>">
  <meta property="og:site_name" content="Cakrawala">

  <!-- Twitter / Social previews -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta name="twitter:description" content="<?php echo $meta_description; ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($meta_image); ?>">

  <script type="application/ld+json">
  <?php
    echo json_encode([
      "@context" => "https://schema.org",
      "@type" => "WebSite",
      "name" => "Cakrawala",
      "url" => $meta_url,
      "description" => $meta_description
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  ?>
  </script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

