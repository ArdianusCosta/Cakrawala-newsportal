<?php 
session_start();
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cakrawala | Home Page</title>

<link rel="icon" href="images/Logo.ico" type="image/x-icon">
<!-- CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link href="css/modern-business.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container">

    <!-- ===== HEADLINE SECTION ===== -->
    <div class="row" style="margin-top: 4%">
    <div class="col-12 position-relative headline px-0">
    <?php 
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $headlineQuery = mysqli_query(
            $con,
            "SELECT p.*, c.CategoryName, a.AdminUserName as author
             FROM tblposts p
             LEFT JOIN tblcategory c ON c.id = p.CategoryId
             LEFT JOIN tbladmin a ON a.id = p.PostedBy
             WHERE p.Is_Active = 1 AND p.PostingDate >= '$sevenDaysAgo'
             ORDER BY p.PostingDate DESC
             LIMIT 6"
        );

        $headlineItems = [];
        while ($headlineRow = mysqli_fetch_array($headlineQuery)) {
            $headlineItems[] = $headlineRow;
        }

        if (count($headlineItems) === 0) {
            $fallbackQuery = mysqli_query(
                $con,
                "SELECT p.*, c.CategoryName, a.AdminUserName as author
                 FROM tblposts p
                 LEFT JOIN tblcategory c ON c.id = p.CategoryId
                 LEFT JOIN tbladmin a ON a.id = p.PostedBy
                 WHERE p.Is_Active = 1
                 ORDER BY p.PostingDate DESC
                 LIMIT 1"
            );
            if ($fallbackRow = mysqli_fetch_array($fallbackQuery)) {
                $headlineItems[] = $fallbackRow;
            }
        }
    ?>

    <?php if (count($headlineItems)): ?>
      <div id="hl-slider" class="hl-slider">
        <div class="hl-track" id="hl-track">
          <?php foreach ($headlineItems as $index => $item): ?>
            <div class="hl-slide">
              <a href="news-details.php?nid=<?php echo htmlentities($item['id']); ?>" class="headline-link">
                <img src="admin/uploads/<?php echo htmlentities($item['PostImage'] ?: 'default.jpg'); ?>" alt="Headline">
                <div class="headline-title">
                  <div class="category-label"><?php echo htmlentities($item['CategoryName']); ?></div>
                  <h3><?php echo htmlentities($item['PostTitle']); ?></h3>
                  <small>
                    <?php echo date("d M Y", strtotime($item['PostingDate'])); ?> |
                    <?php echo htmlentities($item['author']); ?> |
                    <?php echo htmlentities($item['views']); ?> views
                  </small>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if (count($headlineItems) > 1): ?>
          <button class="hl-btn hl-btn-prev" id="hl-prev" aria-label="Previous">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button class="hl-btn hl-btn-next" id="hl-next" aria-label="Next">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
          <div class="hl-dots" id="hl-dots">
            <?php foreach ($headlineItems as $index => $item): ?>
              <span class="hl-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <script>
      (function() {
        var track   = document.getElementById('hl-track');
        var slides  = track.querySelectorAll('.hl-slide');
        var dots    = document.querySelectorAll('.hl-dot');
        var total   = slides.length;
        var current = 0;
        var timer;

        function goTo(n) {
          current = (n + total) % total;
          track.style.transform = 'translateX(-' + (current * 100) + '%)';
          dots.forEach(function(d, i) {
            d.classList.toggle('active', i === current);
          });
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function startTimer() { timer = setInterval(next, 4000); }
        function resetTimer()  { clearInterval(timer); startTimer(); }

        document.getElementById('hl-prev').addEventListener('click', function(){ prev(); resetTimer(); });
        document.getElementById('hl-next').addEventListener('click', function(){ next(); resetTimer(); });

        dots.forEach(function(d) {
          d.addEventListener('click', function() {
            goTo(parseInt(this.dataset.index));
            resetTimer();
          });
        });

        /* pause on hover */
        var slider = document.getElementById('hl-slider');
        slider.addEventListener('mouseenter', function() { clearInterval(timer); });
        slider.addEventListener('mouseleave', startTimer);

        startTimer();
      })();
      </script>
    <?php endif; ?>
    </div><!-- /col-12 headline -->
    </div><!-- /headline row -->

    <!-- Blog Entries Column -->
    <div class="row">
    <div class="col-md-8">

      <!-- TERPOPULER -->
      <h4 class="mt-4 mb-3">Terpopuler</h4>
      <?php
      $populerQuery = mysqli_query($con, " SELECT 
          p.id as pid,
          p.PostTitle as posttitle,
          c.CategoryName as category,
          s.Subcategory as subcategory,
          p.PostDetails as postdetails,
          p.PostingDate as postingdate,
          p.PostUrl as url,
          p.PostImage as PostImage,
          p.Views as views,
          a.AdminUserName as author
        FROM tblposts p
        LEFT JOIN tblcategory c ON c.id = p.CategoryId 
        LEFT JOIN tblsubcategory s ON s.SubCategoryId = p.SubCategoryId
        LEFT JOIN tbladmin a ON a.id = p.PostedBy
        WHERE p.Is_Active = 1
        ORDER BY p.Views DESC 
        LIMIT 5
      ");


      while ($row = mysqli_fetch_array($populerQuery)) {
      ?>
        <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
           class="card mb-3 text-decoration-none text-dark shadow-sm border-0 article-card">
          <div class="row no-gutters">
            <div class="col-md-4">
              <div class="thumb-landscape">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>">
              </div>
            </div>
            <div class="col-md-8 d-flex flex-column p-2">
              <div class="mb-1"><span class="badge-category"><?php echo htmlentities($row['category']); ?></span></div>
              <h3 class="mb-1"><?php echo htmlentities($row['posttitle']); ?></h3>
              <p class="text-muted mb-1" style="font-size: 0.85rem;">
                <?php echo substr(strip_tags($row['postdetails']),0,100); ?>...
              </p>
              <small class="text-secondary mt-auto" style="font-size: 0.8rem;">
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
                <?php echo htmlentities($row['author']); ?> | 
                <?php echo htmlentities($row['category']); ?> | 
                <?php echo htmlentities($row['views']); ?> views
              </small>

            </div>
          </div>
        </a>
      <?php } ?>
      </div>
      <!-- Sidebar -->
    <?php include('includes/sidebar.php'); ?>
  </div>

      <!-- TERBARU -->
      <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h4 class="mb-0">Terbaru</h4>
        <a href="all-news.php" class="text-danger mr-4" style="text-decoration: none;">Lihat Semua
          <i class="fa fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <div class="row">
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
          <div class="col-md-4 col-lg-3 mb-4">
            <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
               class="text-decoration-none text-dark d-block h-100">
              <div class="thumb-landscape mb-2" style="height:200px; overflow:hidden; border-radius:8px;">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>" 
                     style="width:100%; height:100%; object-fit:cover;">
              </div>
              <div class="mb-1"><span class="badge-category" style="font-size: 0.75rem;"><?php echo htmlentities($row['category']); ?></span></div>
              <h2 class="mb-1" style="font-size: 1.5rem; font-weight:600px; line-height:1.4em;"><?php echo htmlentities($row['posttitle']); ?></h2>
              <small class="text-muted" style="font-size: 0.8rem;">
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
                <?php echo htmlentities($row['author']); ?> | 
                <?php echo htmlentities($row['category']); ?> | 
                <?php echo htmlentities($row['views']); ?> views
              </small>


              </a>
          </div>
        <?php } ?>
      </div>

      <div class="col-md-8 mt-5">
      <h4 class="mb-3">Semua Artikel</h4>
      <!-- Pagination -->
      <?php 
      $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
      $no_of_records_per_page = 8;
      $offset = ($pageno-1) * $no_of_records_per_page;
      $total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE Is_Active = 1";
      $result = mysqli_query($con,$total_pages_sql);
      $total_rows = mysqli_fetch_array($result)[0];
      $total_pages = ceil($total_rows / $no_of_records_per_page);

      $query = mysqli_query($con, "SELECT 
            tblposts.id as pid,
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblcategory.id as cid,
            tblsubcategory.Subcategory as subcategory,
            tblposts.PostDetails as postdetails,
            tblposts.PostingDate as postingdate,
            tblposts.PostUrl as url,
        tblposts.PostImage as PostImage,
            tblposts.Views as views,
            a.AdminUserName as author
          FROM tblposts 
          LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
          LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
          LEFT JOIN tbladmin a ON a.id = tblposts.PostedBy
          WHERE tblposts.Is_Active = 1
          ORDER BY tblposts.PostingDate DESC 
          LIMIT $offset, $no_of_records_per_page
          ");

      while ($row=mysqli_fetch_array($query)) {
      ?>
        <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
          class="card mb-3 text-decoration-none text-dark shadow-sm border-0 article-card">
          <div class="row no-gutters">
            <div class="col-md-4">
              <div class="thumb-landscape">
                <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlentities($row['posttitle']); ?>">
              </div>
            </div>
            <div class="col-md-8 d-flex flex-column p-2">
              <div class="mb-1"><span class="badge-category"><?php echo htmlentities($row['category']); ?></span></div>
              <h3 class="mb-1"><?php echo htmlentities($row['posttitle']); ?></h3>
              <p class="text-muted mb-1" style="font-size: 0.85rem;"><?php echo substr(strip_tags($row['postdetails']),0,100); ?>...</p>
              <small class="text-secondary mt-auto" style="font-size: 0.8rem;">
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
                <?php echo htmlentities($row['category']); ?> | 
                <?php echo htmlentities($row['author']); ?> | 
                <?php echo htmlentities($row['views']); ?> views
              </small>

              </div>
          </div>
        </a>
      <?php } ?>

      <ul class="pagination justify-content-center mt-4">
        <li class="page-item"><a href="?pageno=1" class="page-link">«</a></li>
        <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
          <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>" class="page-link">‹</a>
        </li>
        <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
          <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>" class="page-link">›</a>
        </li>
        <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">»</a></li>
      </ul>
      </div>
    </div>

    
</div>

<!-- Artikel per kategori -->
<div class="container mt-5">
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
</div>

<?php include('includes/footer.php'); ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>