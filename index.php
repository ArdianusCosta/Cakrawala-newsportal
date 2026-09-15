<?php 
session_start();
include('includes/config.php');

// Page-specific SEO variables, read by includes/seo-meta.php below.
$pageTitle = 'Cakrawala Online - Berita Terkini Indramayu, Nusantara & Nasional';
$pageDescription = 'Cakrawala Online, situs berita daerah dan nasional terpercaya. Sajikan info terkini Seputar Indramayu, Hukum & Kriminal, Olahraga, dan Ragam peristiwa';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php include('includes/seo-meta.php'); ?>

<!-- CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/modern-business.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container">
  <div class="row mt-2 mt-md-3">
    <div class="col-12 px-2 px-md-3">

      <!-- ===== HIGHLIGHT NEWS TICKER BAR (Mobile & Desktop) ===== -->
      <?php
        // Fetch recent news for ticker / highlight
        $tickerQuery = mysqli_query($con, "SELECT p.id, p.PostTitle, p.PostImage, c.CategoryName FROM tblposts p LEFT JOIN tblcategory c ON c.id = p.CategoryId WHERE p.Is_Active = 1 ORDER BY p.PostingDate DESC LIMIT 10");
        $tickerItems = [];
        while($t = mysqli_fetch_array($tickerQuery)) {
          $tickerItems[] = $t;
        }
      ?>
      <div class="highlight-ticker-container mb-3">
        <div class="highlight-ticker-header border rounded-top overflow-hidden d-flex">
          <div class="ticker-label bg-danger text-white font-weight-bold px-3 py-2 d-flex align-items-center flex-grow-1 text-uppercase">
            BERITA VIRAL
          </div>
        </div>
        
        <!-- Scrolling Ticker Container (Active on BOTH Mobile & Desktop) -->
        <div class="ticker-wrapper d-flex align-items-center bg-white border border-top-0 rounded-bottom p-2 overflow-hidden shadow-sm" style="white-space: nowrap; height: 58px;">
          <div class="ticker-content">
            <?php 
            // Duplicate loop for seamless infinite marquee scroll
            for ($loop = 0; $loop < 2; $loop++):
              foreach($tickerItems as $ticker): 
            ?>
              <a href="news-details.php?nid=<?php echo $ticker['id']; ?>" class="ticker-item text-dark text-decoration-none mx-3 font-weight-bold d-inline-flex align-items-center">
                <img src="admin/uploads/<?php echo $ticker['PostImage'] ?: 'default.jpg'; ?>" 
                     alt="Thumbnail" class="ticker-img mr-2 rounded" style="width: 48px; height: 36px; object-fit: cover;">
                <span class="ticker-text text-dark" style="font-size: 0.9rem; font-weight: 600; line-height: 1.2;">
                  <?php echo htmlentities($ticker['PostTitle']); ?>
                </span>
              </a>
            <?php 
              endforeach; 
            endfor;
            ?>
          </div>
        </div>
      </div>

      <!-- ===== HEADLINE SECTION (CAROUSEL) ===== -->
      <?php 
        $headlineQuery = mysqli_query(
          $con, 
          "SELECT p.*, c.CategoryName, a.AdminUserName as author
            FROM tblposts p 
            LEFT JOIN tblcategory c ON c.id = p.CategoryId 
            LEFT JOIN tbladmin a ON a.id = p.PostedBy
            WHERE p.Is_Active = 1
            ORDER BY p.PostingDate DESC 
            LIMIT 5"
        );

        $headlines = [];
        while($h = mysqli_fetch_array($headlineQuery)) {
          $headlines[] = $h;
        }
      ?>
      <div id="headlineCarousel" class="carousel slide headline mb-4 mb-md-5 shadow-sm" data-ride="carousel">
        <!-- Carousel Indicators (Dots on Bottom Right) -->
        <ol class="carousel-indicators custom-carousel-dots">
          <?php foreach($headlines as $idx => $hl): ?>
            <li data-target="#headlineCarousel" data-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>"></li>
          <?php endforeach; ?>
        </ol>

        <div class="carousel-inner">
        <?php foreach($headlines as $idx => $headline): ?>
          <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
            <a href="news-details.php?nid=<?php echo htmlentities($headline['id']); ?>" class="headline-link d-block">
              <img src="admin/uploads/<?php echo $headline['PostImage'] ?: 'default.jpg'; ?>" class="d-block w-100 headline-img" alt="Headline">
              <div class="headline-title">
                <h3><?php echo htmlentities($headline['PostTitle']); ?></h3>
                <small>
                  <?php echo date("d M Y", strtotime($headline['PostingDate'])); ?> | 
                  <?php echo htmlentities($headline['author'] ?: 'Redaksi Cakrawala'); ?> | 
                  <?php echo htmlentities($headline['CategoryName'] ?: 'Daerah'); ?> | 
                  <?php echo htmlentities($headline['views'] ?: '0'); ?> views
                </small>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
        </div>
        <!-- Controls -->
        <a class="carousel-control-prev custom-carousel-btn" href="#headlineCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon carousel-arrow-bg" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next custom-carousel-btn" href="#headlineCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon carousel-arrow-bg" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

    </div>

    <!-- Blog Entries Column -->
    <div class="col-md-8 px-2 px-md-3">

      <!-- Tabs Nav (Close gap with carousel) -->
      <ul class="nav custom-tabs mb-2" id="mainTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="terbaru-tab" data-toggle="tab" href="#terbaru" role="tab" aria-controls="terbaru" aria-selected="true">Terbaru</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="terpopuler-tab" data-toggle="tab" href="#terpopuler" role="tab" aria-controls="terpopuler" aria-selected="false">Terpopuler</a>
        </li>
      </ul>

      <!-- Tabs Content -->
      <div class="tab-content mt-2" id="mainTabContent">
        
        <!-- ================= TERBARU TAB ================= -->
        <div class="tab-pane fade show active" id="terbaru" role="tabpanel" aria-labelledby="terbaru-tab">
          <?php
          $terbaruQuery = mysqli_query($con, "SELECT 
                p.id as pid, p.PostTitle as posttitle, c.CategoryName as category,
                p.PostImage as PostImage, p.PostingDate as postingdate,
                p.PostDetails as postdetails, p.Views as views, a.AdminUserName as author
            FROM tblposts p
            LEFT JOIN tblcategory c ON c.id = p.CategoryId 
            LEFT JOIN tbladmin a ON a.id = p.PostedBy
            WHERE p.Is_Active = 1
            ORDER BY p.PostingDate DESC 
            LIMIT 10
          ");
          while ($row = mysqli_fetch_array($terbaruQuery)) {
          ?>
            <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
               class="card mb-3 text-decoration-none text-dark article-card border-0 shadow-sm">
              <div class="article-thumb-wrapper">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>" class="article-thumb-img">
              </div>
              <div class="card-body p-3">
                <div class="mb-2"><span class="badge badge-category"><?php echo htmlentities($row['category']); ?></span></div>
                <h3 class="article-title mb-2"><?php echo htmlentities($row['posttitle']); ?></h3>
                <p class="article-excerpt mb-2">
                  <?php echo substr(strip_tags($row['postdetails']),0,110); ?>...
                </p>
                <small class="article-meta text-muted d-block">
                  <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
                  Redaksi Cakrawala | <?php echo htmlentities($row['category']); ?> | <?php echo htmlentities($row['views']); ?> Views
                </small>
              </div>
            </a>
          <?php } ?>
        </div>

        <!-- ================= TERPOPULER TAB ================= -->
        <div class="tab-pane fade" id="terpopuler" role="tabpanel" aria-labelledby="terpopuler-tab">
          <?php
          $populerQuery = mysqli_query($con, "SELECT 
                p.id as pid, p.PostTitle as posttitle, c.CategoryName as category,
                p.PostImage as PostImage, p.PostingDate as postingdate,
                p.PostDetails as postdetails, p.Views as views, a.AdminUserName as author
            FROM tblposts p
            LEFT JOIN tblcategory c ON c.id = p.CategoryId 
            LEFT JOIN tbladmin a ON a.id = p.PostedBy
            WHERE p.Is_Active = 1
            ORDER BY p.Views DESC 
            LIMIT 10
          ");
          while ($row = mysqli_fetch_array($populerQuery)) {
          ?>
            <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
               class="card mb-3 text-decoration-none text-dark article-card border-0 shadow-sm">
              <div class="article-thumb-wrapper">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>" class="article-thumb-img">
              </div>
              <div class="card-body p-3">
                <div class="mb-2"><span class="badge badge-category"><?php echo htmlentities($row['category']); ?></span></div>
                <h3 class="article-title mb-2"><?php echo htmlentities($row['posttitle']); ?></h3>
                <p class="article-excerpt mb-2">
                  <?php echo substr(strip_tags($row['postdetails']),0,110); ?>...
                </p>
                <small class="article-meta text-muted d-block">
                  <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
                  Redaksi Cakrawala | <?php echo htmlentities($row['category']); ?> | <?php echo htmlentities($row['views']); ?> Views
                </small>
              </div>
            </a>
          <?php } ?>
        </div>

      </div>

    </div>
    <!-- Sidebar -->
    <?php include('includes/sidebar.php'); ?>
  </div>
</div>

<!-- Semua Artikel Grid -->
<div class="container mt-5 mb-5">
  <h4 class="mb-4" style="font-weight:bold; font-size: 1.5rem;">Semua Artikel</h4>
  <div class="row">
    <?php
    $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
    $no_of_records_per_page = 8;
    $offset = ($pageno-1) * $no_of_records_per_page;
    $total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE Is_Active = 1";
    $result = mysqli_query($con,$total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    $gridQuery = mysqli_query($con, "SELECT 
          p.id as pid, p.PostTitle as posttitle, c.CategoryName as category,
          p.PostImage as PostImage, p.PostingDate as postingdate,
          p.PostDetails as postdetails, p.Views as views, a.AdminUserName as author
      FROM tblposts p
      LEFT JOIN tblcategory c ON c.id = p.CategoryId 
      LEFT JOIN tbladmin a ON a.id = p.PostedBy
      WHERE p.Is_Active = 1
      ORDER BY p.PostingDate DESC 
      LIMIT $offset, $no_of_records_per_page
    ");

    while ($row = mysqli_fetch_array($gridQuery)) {
    ?>
      <div class="col-md-3 mb-4">
        <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" class="card h-100 text-decoration-none border-0 shadow-sm article-card">
          <div class="article-thumb-wrapper">
            <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" class="article-thumb-img" alt="<?php echo htmlentities($row['posttitle']); ?>">
          </div>
          <div class="card-body">
            <div class="mb-1"><span class="badge badge-category"><?php echo htmlentities($row['category']); ?></span></div>
            <h5 class="article-title mb-1">
              <?php echo htmlentities($row['posttitle']); ?>
            </h5>
            <p class="article-excerpt mb-2">
              <?php echo substr(strip_tags($row['postdetails']),0,80); ?>...
            </p>
            <div class="mt-auto">
              <small class="article-meta text-muted d-block">
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | Redaksi Cakrawala | <?php echo htmlentities($row['category']); ?> | <?php echo htmlentities($row['views']); ?> Views
              </small>
            </div>
          </div>
        </a>
      </div>
    <?php } ?>
  </div>

  <!-- Pagination -->
  <ul class="pagination justify-content-center mt-4">
    <li class="page-item"><a href="?pageno=1" class="page-link text-secondary bg-transparent border-0">«</a></li>
    <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
      <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>" class="page-link text-secondary bg-transparent border-0">‹</a>
    </li>
    
    <?php 
    // Show a limited number of pages to prevent long lists
    $start_page = max(1, $pageno - 2);
    $end_page = min($total_pages, $pageno + 2);
    for($i = $start_page; $i <= $end_page; $i++): 
    ?>
      <?php if ($i == $pageno): ?>
        <li class="page-item active"><a class="page-link bg-danger border-danger text-white rounded"><?php echo $i; ?></a></li>
      <?php else: ?>
        <li class="page-item"><a href="?pageno=<?php echo $i; ?>" class="page-link text-secondary bg-transparent border-0"><?php echo $i; ?></a></li>
      <?php endif; ?>
    <?php endfor; ?>

    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
      <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>" class="page-link text-secondary bg-transparent border-0">›</a>
    </li>
    <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link text-secondary bg-transparent border-0">»</a></li>
  </ul>
</div>

<?php include('includes/footer.php'); ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Script to pause BERITA VIRAL running text animation on hover/touch on all devices (Mouse, Touch & Pointer support) -->
<script>
(function() {
  var tickerWraps = document.querySelectorAll('.ticker-wrapper, .highlight-ticker-container, .ticker-item, .ticker-content');
  var tickerContents = document.querySelectorAll('.ticker-content');
  
  function pauseTicker() {
    tickerContents.forEach(function(tc) {
      tc.classList.add('paused');
      tc.style.animationPlayState = 'paused';
      tc.style.webkitAnimationPlayState = 'paused';
    });
  }

  function resumeTicker() {
    tickerContents.forEach(function(tc) {
      tc.classList.remove('paused');
      tc.style.animationPlayState = 'running';
      tc.style.webkitAnimationPlayState = 'running';
    });
  }

  tickerWraps.forEach(function(wrap) {
    // Mouse events (Desktop)
    wrap.addEventListener('mouseenter', pauseTicker, false);
    wrap.addEventListener('mouseleave', resumeTicker, false);
    wrap.addEventListener('mouseover', pauseTicker, false);
    wrap.addEventListener('mouseout', resumeTicker, false);

    // Touch events (Mobile devices)
    wrap.addEventListener('touchstart', pauseTicker, {passive: true});
    wrap.addEventListener('touchend', function() {
      setTimeout(resumeTicker, 1200);
    }, {passive: true});
    wrap.addEventListener('touchcancel', resumeTicker, {passive: true});

    // Pointer events (Mobile DevTools emulator & modern touch screens)
    wrap.addEventListener('pointerenter', pauseTicker, false);
    wrap.addEventListener('pointerleave', resumeTicker, false);
    wrap.addEventListener('pointerdown', pauseTicker, false);
    wrap.addEventListener('pointerup', function() {
      setTimeout(resumeTicker, 1200);
    }, false);
  });
})();
</script>
</body>
</html>