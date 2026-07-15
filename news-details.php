<?php 
include('includes/config.php');

$pid = intval($_GET['nid']);

// Update views setiap kali artikel dibuka
$updateViews = mysqli_query($con, "UPDATE tblposts SET Views = Views + 1 WHERE id = '$pid'");

// Ambil data artikel
$query = mysqli_query($con,"SELECT 
    tblposts.PostTitle as posttitle,
    tblcategory.CategoryName as category,
    tblcategory.id as cid,
    tblsubcategory.Subcategory as subcategory,
    tblposts.PostDetails as postdetails,
    tblposts.PostingDate as postingdate,
    tblposts.PostUrl as url,
    tblposts.PostImage as PostImage,
    tblposts.Views as views
FROM tblposts 
LEFT JOIN tblcategory ON tblcategory.id=tblposts.CategoryId 
LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId=tblposts.SubCategoryId 
WHERE tblposts.id='$pid'");
?>

<?php
$row = mysqli_fetch_array($query);
$imageUrl = !empty($row['PostImage']) ? 'https://cakrawalaonline.com/admin/uploads/' . htmlspecialchars($row['PostImage']) : 'https://cakrawalaonline.com/admin/uploads/default.jpg';
$metaDescription = htmlspecialchars(substr(strip_tags($row['postdetails']), 0, 160));
$pageUrl = 'https://cakrawalaonline.com/news-details.php?nid=' . $pid;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $metaDescription ?>">
  <meta name="author" content="">
  <title><?= htmlspecialchars($row['posttitle']) ?> | Cakrawala</title>

  <meta property="og:type" content="article">
  <meta property="og:title" content="<?= htmlspecialchars($row['posttitle']) ?>">
  <meta property="og:description" content="<?= $metaDescription ?>">
  <meta property="og:url" content="<?= htmlspecialchars($pageUrl) ?>">
  <meta property="og:image" content="<?= $imageUrl ?>">
  <meta property="og:site_name" content="Cakrawala">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($row['posttitle']) ?>">
  <meta name="twitter:description" content="<?= $metaDescription ?>">
  <meta name="twitter:image" content="<?= $imageUrl ?>">

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/modern-business.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <!-- Font Awesome (for share icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    /* ===== Article content images ===== */
    .card-text img {
      display: block;
      max-width: 100%;       /* never overflow the column */
      height: auto;          /* preserve aspect ratio */
      max-height: 500px;     /* cap tall images on desktop */
      object-fit: contain;
      margin: 12px 0;        /* left-aligned with breathing room */
      border-radius: 4px;
    }

    @media (max-width: 767.98px) {
      .card-text img {
        max-height: 260px;   /* tighter cap on mobile */
      }
    }

    /* ===== Share buttons ===== */
    .share-box {
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      row-gap: 8px;
    }
    .share-box .share-label {
      font-weight: 600;
      margin-right: 10px;
    }
    .share-icons {
      display: inline-flex;
      align-items: center;
      flex-wrap: wrap;
    }
    .share-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      color: #fff !important;
      font-size: 16px;
      margin-right: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: transform .15s ease, opacity .15s ease;
    }
    .share-btn:hover { transform: translateY(-2px); opacity: .9; color: #fff; }
    .share-whatsapp { background: #25D366; }
    .share-facebook { background: #1877F2; }
    .share-twitter  { background: #000000; }
    .share-telegram { background: #0088cc; }
    .share-email    { background: #6c757d; }
    .share-copy     { background: #dc3545; }
    .share-native   { background: #343a40; }
    .copy-toast {
      display: none;
      margin-left: 6px;
      padding: 4px 10px;
      background: #28a745;
      color: #fff;
      border-radius: 4px;
      font-size: 0.8rem;
      vertical-align: middle;
      white-space: nowrap;
    }
    .copy-toast.show {
      display: inline-block;
      animation: shareToastFade 2s forwards;
    }
    @keyframes shareToastFade {
      0%   { opacity: 1; }
      70%  { opacity: 1; }
      100% { opacity: 0; }
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <?php include('includes/header.php');?>

  <!-- Page Content -->
  <div class="container">
    <div class="row" style="margin-top: 4%">
      <!-- Blog Entries Column -->
      <div class="col-md-8">

        <!-- Blog Post -->
        <?php
        $pid = intval($_GET['nid']);
        $query = mysqli_query($con,"SELECT 
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblcategory.id as cid,
            tblsubcategory.Subcategory as subcategory,
            tblposts.PostDetails as postdetails,
            tblposts.PostingDate as postingdate,
            tblposts.PostUrl as url,
          tblposts.PostImage as PostImage,
          tblposts.PostImageDesc as PostImageDesc,
            tblposts.Views as views,
            tbladmin.AdminUserName as author
        FROM tblposts 
        LEFT JOIN tblcategory ON tblcategory.id=tblposts.CategoryId 
        LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId=tblposts.SubCategoryId 
        LEFT JOIN tbladmin ON tbladmin.id = tblposts.PostedBy
        WHERE tblposts.id='$pid'");

        while ($row=mysqli_fetch_array($query)) {
        ?>
          <div class="card mb-4">

            <!-- Thumbnail -->
            <?php if (isset($row['PostImage']) && trim($row['PostImage']) !== ''): ?>
              <img class="card-img-top" 
                   src="admin/uploads/<?php echo htmlentities($row['PostImage']); ?>" 
                   alt="<?php echo htmlentities($row['posttitle']); ?>" 
                   style="max-height:400px; object-fit:cover;">
            <?php else: ?>
              <img class="card-img-top" 
                   src="admin/uploads/default.jpg" 
                   alt="No Image" 
                   style="max-height:400px; object-fit:cover;">
            <?php endif; ?>

            <?php if (!empty($row['PostImageDesc'])): ?>
              <div class="px-3 pt-2 text-muted" style="font-size:0.95rem;">
                <?php echo htmlentities($row['PostImageDesc']); ?>
              </div>
            <?php endif; ?>

            <div class="card-body">
              <h2 class="card-title"><?php echo htmlentities($row['posttitle']);?></h2>
              <p>
                <b>Category : </b> 
                <a href="category.php?catid=<?php echo htmlentities($row['cid'])?>">
                  <?php echo htmlentities($row['category']);?>
                </a> | 
                <b>Sub Category : </b><?php echo htmlentities($row['subcategory']);?>
              </p>
              <hr />
              <div class="card-text">
                <?php echo $row['postdetails']; ?>
              </div>

              <!-- Share Buttons -->
              <div class="share-box mt-4 pt-3 border-top">
                <span class="share-label">Bagikan:</span>
                <div class="share-icons">
                  <a class="share-btn share-whatsapp"
                     title="Bagikan ke WhatsApp"
                     target="_blank" rel="noopener"
                     href="https://api.whatsapp.com/send?text=<?php echo urlencode($row['posttitle'] . ' - ' . $metaDescription . ' ' . $pageUrl); ?>">
                    <i class="fab fa-whatsapp"></i>
                  </a>
                  <a class="share-btn share-facebook"
                     title="Bagikan ke Facebook"
                     target="_blank" rel="noopener"
                     href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($pageUrl); ?>">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a class="share-btn share-twitter"
                     title="Bagikan ke X / Twitter"
                     target="_blank" rel="noopener"
                     href="https://twitter.com/intent/tweet?text=<?php echo urlencode($row['posttitle']); ?>&url=<?php echo urlencode($pageUrl); ?>">
                    <i class="fab fa-x-twitter"></i>
                  </a>
                  <a class="share-btn share-telegram"
                     title="Bagikan ke Telegram"
                     target="_blank" rel="noopener"
                     href="https://t.me/share/url?url=<?php echo urlencode($pageUrl); ?>&text=<?php echo urlencode($row['posttitle']); ?>">
                    <i class="fab fa-telegram-plane"></i>
                  </a>
                  <a class="share-btn share-email"
                     title="Bagikan lewat Email"
                     href="mailto:?subject=<?php echo urlencode($row['posttitle']); ?>&body=<?php echo urlencode($metaDescription . "\n\n" . $pageUrl); ?>">
                    <i class="fas fa-envelope"></i>
                  </a>
                  <button type="button" class="share-btn share-copy"
                          title="Salin tautan"
                          data-text="<?php echo htmlspecialchars($metaDescription); ?>"
                          data-url="<?php echo htmlspecialchars($pageUrl); ?>"
                          onclick="copyShareLink(this)">
                    <i class="fas fa-link"></i>
                  </button>
                  <button type="button" class="share-btn share-native d-inline-flex d-md-none"
                          title="Bagikan"
                          data-title="<?php echo htmlspecialchars($row['posttitle']); ?>"
                          data-text="<?php echo htmlspecialchars($metaDescription); ?>"
                          data-url="<?php echo htmlspecialchars($pageUrl); ?>"
                          onclick="nativeShare(this)">
                    <i class="fas fa-share-alt"></i>
                  </button>
                </div>
                <span id="copyToast" class="copy-toast">Disalin!</span>
              </div>

            </div>
            <div class="card-footer text-muted">
              Posted on <?php echo htmlentities($row['postingdate']);?> 
              | Oleh: <?php echo htmlentities($row['author']);?> 
              | Dilihat: <?php echo htmlentities($row['views']); ?> kali
            </div>

          </div>
        <?php } ?>
      </div>
      

      <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php');?>

      </div><!-- /row -->

       <!-- TERBARU -->
      <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h4 class="mb-0">Terbaru</h4>
        <a href="all-news.php" class="text-danger mr-4" style="text-decoration: none;">Lihat Semua
          <i class="fa fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <div class="terbaru-grid">
        <?php
        $terbaruQuery = mysqli_query($con, "SELECT 
              p.id as pid,
              p.PostTitle as posttitle,
              c.CategoryName as category,
              p.PostImage as PostImage,
              p.PostingDate as postingdate,
              p.PostDetails as postdetails,
              p.Views as views,
              a.AdminUserName as author
          FROM tblposts p
          LEFT JOIN tblcategory c ON c.id = p.CategoryId 
          LEFT JOIN tbladmin a ON a.id = p.PostedBy
          WHERE p.Is_Active = 1
          ORDER BY p.PostingDate DESC 
          LIMIT 4
        ");

        while ($row = mysqli_fetch_array($terbaruQuery)) {
        ?>
          <div class="terbaru-card">
            <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
               class="text-decoration-none text-dark d-block h-100">
              <div class="thumb-landscape mb-2">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>">
              </div>
              <div class="mb-1"><span class="badge-category"><?php echo htmlentities($row['category']); ?></span></div>
              <h2 class="mb-1"><?php echo htmlentities($row['posttitle']); ?></h2>
              <small class="text-muted" style="font-size: 0.8rem;">
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> |
                <?php echo htmlentities($row['author']); ?> |
                <?php echo htmlentities($row['views']); ?> views
              </small>
            </a>
          </div>
        <?php } ?>
      </div>

<!-- Artikel per kategori -->
<div class="mt-5">
  <div class="row">
    <?php
    $catQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory ORDER BY id ASC LIMIT 4");
    while ($cat = mysqli_fetch_array($catQuery)) {
      $catId = $cat['id'];
      $catName = $cat['CategoryName'];
      $postQuery = mysqli_query($con, "
        SELECT p.id, p.PostTitle, p.PostImage, p.PostingDate, p.PostDetails, 
        p.Views as views, a.AdminUserName as author
        FROM tblposts p
        LEFT JOIN tbladmin a ON a.id = p.PostedBy
        WHERE p.CategoryId = '$catId' AND p.Is_Active = 1
        ORDER BY p.PostingDate DESC 
        LIMIT 2
      ");
      ?>
      <div class="col-md-6 mb-4">
        <h5 class="mb-3"><span class="badge badge-danger"><?php echo htmlentities($catName); ?></span></h5>
        <div class="row">
          <?php while ($post = mysqli_fetch_array($postQuery)) { ?>
            <div class="col-md-6 mb-3">
              <div class="card h-100 shadow-sm border-0">
                <a href="news-details.php?nid=<?php echo htmlentities($post['id']); ?>">
                  <div style="width:100%; height:180px; overflow:hidden; border-radius:6px 6px 0 0;">
                    <img src="admin/uploads/<?php echo htmlentities($post['PostImage'] ?: 'default.jpg'); ?>" style="width:100%; height:100%; object-fit:cover;">
                  </div>
                </a>
                <div class="card-body p-2">
                  <a href="news-details.php?nid=<?php echo htmlentities($post['id']); ?>" class="text-dark text-decoration-none">
                    <h6 class="mb-1" style="font-size:1rem; font-weight:600;"><?php echo htmlentities($post['PostTitle']); ?></h6>
                  </a>
                  <small class="text-muted d-block mb-1">
                    <?php echo date("d M Y", strtotime($post['PostingDate'])); ?> | 
                    <?php echo htmlentities($post['author']); ?> | 
                    <?php echo htmlentities($post['views']); ?> views
                  </small>


                  <p class="text-muted mb-0" style="font-size:0.85rem;"><?php echo substr(strip_tags($post['PostDetails']),0,80); ?>...</p>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <a href="category.php?catid=<?php echo $catId; ?>" class="text-danger small">Lihat semua »</a>
      </div>
    <?php } ?>
  </div>
</div><!-- /kategori -->

  </div><!-- /.container -->
  <!-- Footer -->
  <?php include('includes/footer.php');?>
  

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Share buttons logic -->
  <script>
    function copyShareLink(btn) {
      var desc = btn.getAttribute('data-text') || '';
      var url  = btn.getAttribute('data-url') || '';

      // Build "Description\nURL"
      var shareText = desc ? (desc + '\n' + url) : url;

      function showToast() {
        var toast = document.getElementById('copyToast');
        toast.classList.remove('show');
        void toast.offsetWidth; // restart animation
        toast.classList.add('show');
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(shareText).then(showToast).catch(function () {
          fallbackCopy(shareText, showToast);
        });
      } else {
        fallbackCopy(shareText, showToast);
      }
    }

    function fallbackCopy(url, cb) {
      var temp = document.createElement('textarea');
      temp.value = url;
      temp.style.position = 'fixed';
      temp.style.opacity = '0';
      document.body.appendChild(temp);
      temp.focus();
      temp.select();
      try { document.execCommand('copy'); } catch (e) {}
      document.body.removeChild(temp);
      if (cb) cb();
    }

    function nativeShare(btn) {
      if (navigator.share) {
        navigator.share({
          title: btn.getAttribute('data-title'),
          text: btn.getAttribute('data-text'),
          url: btn.getAttribute('data-url')
        }).catch(function (err) {
          console.log('Share dibatalkan:', err);
        });
      } else {
        copyShareLink(btn);
      }
    }
  </script>
</body>
</html>