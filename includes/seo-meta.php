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
$meta_title = isset($pageTitle) ? $pageTitle : 'Cakrawala';
?>
  <title><?php echo htmlspecialchars($meta_title); ?></title>
  <meta name="description" content="<?php echo $meta_description; ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($meta_url); ?>">

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta property="og:description" content="<?php echo $meta_description; ?>">
  <meta property="og:url" content="<?php echo htmlspecialchars($meta_url); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($meta_image); ?>">
  <meta property="og:site_name" content="Cakrawala">

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
  <link rel="icon" href="/images/Logo.ico" type="image/x-icon">
