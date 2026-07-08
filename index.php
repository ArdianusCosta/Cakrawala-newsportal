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

<link rel="icon" href="images/Logo.ico" type="image/x-icon">
<!-- CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/modern-business.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">

<!--
  NOTE: the rules below are new for this update (tab buttons + Semua Artikel grid).
  Feel free to move this block into style.css once you're happy with it.
-->
<style>
/* ===== Terpopuler / Terbaru toggle buttons ===== */
.content-tabs {
    display: flex;
    gap: 10px;
    margin: 1.5rem 0 1rem;
}
.tab-btn {
    background: #fff;
    border: 1px solid #dee2e6;
    color: #555;
    font-weight: 600;
    font-size: 1.05rem;
    padding: 8px 22px;
    border-radius: 6px;
    cursor: pointer;
    transition: all .2s ease;
}
.tab-btn:hover {
    border-color: #dc3545;
    color: #dc3545;
}
.tab-btn.active {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
}
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ===== Semua Artikel grid layout ===== */
.semua-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 992px) {
    .semua-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .semua-grid { grid-template-columns: 1fr; }
}
.semua-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    text-decoration: none;
    color: inherit;
    transition: transform .15s ease, box-shadow .15s ease;
}
.semua-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    color: inherit;
}
.semua-card .thumb-landscape {
    width: 100%;
    height: 160px;
    overflow: hidden;
}
.semua-card .thumb-landscape img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.semua-card-body {
    padding: 12px 0 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.semua-card-body h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 4px 0 6px;
    line-height: 1.3;
}
.semua-card-body p {
    font-size: 0.82rem;
    color: #6c757d;
    margin-bottom: 8px;
    flex: 1;
}

/* ===== Semua Artikel pagination ===== */
#semua-artikel-wrap {
    transition: opacity .15s ease;
}
.semua-pagination-wrap {
    margin-top: 1.5rem;
}
.semua-page-info {
    text-align: center;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 6px;
}
</style>
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

      <!-- TOGGLE BUTTONS: Terpopuler / Terbaru -->
      <div class="content-tabs">
        <button type="button" class="tab-btn active" data-tab="terpopuler">Terpopuler</button>
        <button type="button" class="tab-btn" data-tab="terbaru">Terbaru</button>
      </div>

      <!-- TERPOPULER PANEL -->
      <div class="tab-panel active" id="tab-terpopuler">
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
      </div><!-- /tab-terpopuler -->

      <!-- TERBARU PANEL -->
      <div class="tab-panel" id="tab-terbaru">
        <div class="d-flex justify-content-end mb-3">
          <a href="all-news.php" class="text-danger" style="text-decoration: none;">Lihat Semua
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

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
      </div><!-- /tab-terbaru -->

    </div><!-- /col-md-8 -->

    <!-- Sidebar (sidebar.php already provides its own col-md-4 wrapper) -->
    <?php include('includes/sidebar.php'); ?>

    </div><!-- /row -->

    <script>
    (function() {
      var buttons = document.querySelectorAll('.tab-btn');
      var panels  = document.querySelectorAll('.tab-panel');

      buttons.forEach(function(btn) {
        btn.addEventListener('click', function() {
          var target = this.dataset.tab;

          buttons.forEach(function(b) { b.classList.toggle('active', b === btn); });
          panels.forEach(function(p) { p.classList.toggle('active', p.id === 'tab-' + target); });
        });
      });
    })();
    </script>

    <!-- ===== SEMUA ARTIKEL ===== -->
    <div class="row mt-5">
    <div class="col-12">
      <h4 class="mb-3">Semua Artikel</h4>

      <div id="semua-artikel-wrap">
      <?php
      $pageno = isset($_GET['pageno']) ? intval($_GET['pageno']) : 1;
      if ($pageno < 1) { $pageno = 1; }
      include('includes/all-list.php');
      ?>
      </div><!-- /semua-artikel-wrap -->

      </div><!-- /col-12 -->
    </div><!-- /row semua artikel -->

    <script>
    (function() {
      var wrap = document.getElementById('semua-artikel-wrap');

      function loadPage(pageno, scroll) {
        if (!pageno) return;
        wrap.style.opacity = '0.5';

        fetch('ajax-all-article.php?pageno=' + encodeURIComponent(pageno))
          .then(function(res) { return res.text(); })
          .then(function(html) {
            wrap.innerHTML = html;
            wrap.style.opacity = '1';

            var url = new URL(window.location);
            url.searchParams.set('pageno', pageno);
            history.pushState({ pageno: pageno }, '', url);

            if (scroll) {
              wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          })
          .catch(function() {
            wrap.style.opacity = '1';
          });
      }

      // Event delegation: works for the initial links AND every link injected after an AJAX swap.
      wrap.addEventListener('click', function(e) {
        var link = e.target.closest('.ajax-page-link');
        if (!link) return;
        if (link.closest('.page-item.disabled')) { e.preventDefault(); return; }

        e.preventDefault();
        loadPage(link.dataset.page, true);
      });

      window.addEventListener('popstate', function(e) {
        var pageno = (e.state && e.state.pageno) ? e.state.pageno : 1;
        loadPage(pageno, false);
      });
    })();
    </script>

</div><!-- /.container -->

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