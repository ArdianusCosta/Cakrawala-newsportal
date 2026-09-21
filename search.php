<?php 
session_start();
include('includes/config.php');

if (!empty($_GET['s'])) {
    $st = trim($_GET['s']);
    $_SESSION['searchtitle'] = $st;
} elseif (!empty($_GET['searchtitle'])) {
    $st = trim($_GET['searchtitle']);
    $_SESSION['searchtitle'] = $st;
} elseif (!empty($_POST['searchtitle'])) {
    $st = trim($_POST['searchtitle']);
    $_SESSION['searchtitle'] = $st;
} else {
    $st = isset($_SESSION['searchtitle']) ? trim($_SESSION['searchtitle']) : '';
}

$pageTitle = !empty($st) ? 'Hasil Pencarian: "' . htmlspecialchars($st) . '" - Cakrawala Online' : 'Pencarian Berita - Cakrawala Online';
?>

<!DOCTYPE html>
<html lang="id">

  <head>

    <meta charset="utf-8">
    <?php 
    $pageDescription = "Hasil pencarian berita " . $st . " di Cakrawala Online";
    include('includes/seo-meta.php'); 
    ?>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="style.css?v=<?php echo time(); ?>" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
   <?php include('includes/header.php');?>

    <!-- Page Content -->
    <div class="container" style="margin-top: 20px;">
      <div class="row mt-2">
        <div class="col-12 px-2 px-md-3">
          <?php include('includes/category-bar.php'); ?>
        </div>
      </div>
      <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

          <div class="search-header mb-4 p-3 bg-light rounded border-left border-danger" style="border-left-width: 5px !important;">
            <h4 class="mb-1 font-weight-bold" style="font-size: 1.25rem;">
              <i class="bi bi-search text-danger mr-2"></i>Hasil Pencarian
            </h4>
            <?php if (!empty($st)) { ?>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Menampilkan berita untuk kata kunci: <strong class="text-dark">"<?php echo htmlentities($st); ?>"</strong>
              </p>
            <?php } else { ?>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">Silakan masukkan kata kunci pencarian pada kolom pencarian di atas.</p>
            <?php } ?>
          </div>

<?php 
        if (isset($_GET['pageno'])) {
            $pageno = (int)$_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 8;
        $offset = ($pageno - 1) * $no_of_records_per_page;

        if (!empty($st)) {
            $st_escaped = mysqli_real_escape_string($con, $st);
            $whereConditions = [];
            $whereConditions[] = "tblposts.PostTitle LIKE '%$st_escaped%'";
            $whereConditions[] = "tblposts.PostDetails LIKE '%$st_escaped%'";

            // Extract clean individual words ignoring common stop words
            $words = preg_split('/[\s&,\-]+/', $st);
            $stopWords = ['dan', 'di', 'ke', 'yang', 'seputar', 'pada', 'atau', 'untuk', 'dengan', 'dari'];
            $cleanWords = [];
            foreach ($words as $word) {
                $w = trim($word);
                if (mb_strlen($w) >= 3 && !in_array(mb_strtolower($w), $stopWords)) {
                    $cleanWords[] = mysqli_real_escape_string($con, $w);
                }
            }

            if (!empty($cleanWords)) {
                $wordTitleConds = [];
                $wordDetailsConds = [];
                foreach ($cleanWords as $cw) {
                    if (mb_strtolower($cw) === 'wali' || mb_strtolower($cw) === 'kota' || mb_strtolower($cw) === 'walikota') {
                        $wordTitleConds[] = "(tblposts.PostTitle LIKE '%Walikota%' OR tblposts.PostTitle LIKE '%Wali Kota%' OR tblposts.PostTitle LIKE '%Wali-Kota%')";
                        $wordDetailsConds[] = "(tblposts.PostDetails LIKE '%Walikota%' OR tblposts.PostDetails LIKE '%Wali Kota%' OR tblposts.PostDetails LIKE '%Wali-Kota%')";
                    } else {
                        $wordTitleConds[] = "tblposts.PostTitle LIKE '%$cw%'";
                        $wordDetailsConds[] = "tblposts.PostDetails LIKE '%$cw%'";
                    }
                }
                if (!empty($wordTitleConds)) {
                    $whereConditions[] = "(" . implode(" OR ", $wordTitleConds) . ")";
                    $whereConditions[] = "(" . implode(" OR ", $wordDetailsConds) . ")";
                }
            }
            $sqlWhere = "(" . implode(" OR ", $whereConditions) . ") AND tblposts.Is_Active=1";
        } else {
            $sqlWhere = "tblposts.Is_Active=1";
        }

        $total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE $sqlWhere";
        $result = mysqli_query($con, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = max(1, ceil($total_rows / $no_of_records_per_page));

        $query = mysqli_query($con, "
          SELECT tblposts.id as pid,
                 tblposts.PostTitle as posttitle,
                 tblcategory.CategoryName as category,
                 tblposts.PostDetails as postdetails,
                 tblposts.PostingDate as postingdate,
                 tblposts.PostImage as postimage,
                 tblposts.views as views
          FROM tblposts 
          LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
          WHERE $sqlWhere 
          ORDER BY tblposts.PostingDate DESC 
          LIMIT $offset, $no_of_records_per_page
        ");

        $rowcount = mysqli_num_rows($query);
        if ($rowcount == 0) {
?>
          <div class="alert alert-warning py-4 text-center my-4" role="alert">
            <h5 class="alert-heading font-weight-bold mb-2">Berita Tidak Ditemukan</h5>
            <p class="mb-0 text-muted">Maaf, tidak ada berita yang sesuai dengan kata kunci "<strong><?php echo htmlentities($st); ?></strong>". Coba kata kunci lain atau cari topik trending di sidebar.</p>
          </div>
<?php 
        } else {
            while ($row = mysqli_fetch_array($query)) {
                $img = !empty($row['postimage']) ? $row['postimage'] : 'default.jpg';
                $snippet = strip_tags($row['postdetails']);
                if (mb_strlen($snippet) > 160) {
                    $snippet = mb_substr($snippet, 0, 160) . '...';
                }
?>
          <div class="card mb-4 border-0 shadow-sm rounded-lg overflow-hidden grid-card">
            <div class="card-body p-3">
              <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                  <img src="admin/uploads/<?php echo htmlentities((string)$img); ?>" 
                       class="img-fluid rounded w-100" 
                       alt="<?php echo htmlentities((string)($row['posttitle'] ?? '')); ?>"
                       style="height: 140px; object-fit: cover;">
                </div>
                <div class="col-md-8 d-flex flex-column justify-content-between">
                  <div>
                    <div class="mb-1">
                      <span class="badge badge-danger font-weight-normal px-2 py-1" style="font-size: 0.7rem;">
                        <?php echo htmlentities((string)($row['category'] ?? 'Berita')); ?>
                      </span>
                      <small class="text-muted ml-2" style="font-size: 0.75rem;">
                        <i class="bi bi-calendar3 mr-1"></i><?php echo !empty($row['postingdate']) ? date("d M Y", strtotime($row['postingdate'])) : '-'; ?>
                      </small>
                    </div>
                    <h5 class="card-title font-weight-bold mb-2" style="font-size: 1.05rem; line-height: 1.35;">
                      <a href="news-details.php?nid=<?php echo htmlentities((string)$row['pid']); ?>" class="text-dark text-decoration-none hover-danger">
                        <?php echo htmlentities((string)($row['posttitle'] ?? '')); ?>
                      </a>
                    </h5>
                    <p class="card-text text-muted mb-2" style="font-size: 0.85rem; line-height: 1.4;">
                      <?php echo htmlentities((string)$snippet); ?>
                    </p>
                  </div>
                  <div>
                    <a href="news-details.php?nid=<?php echo htmlentities((string)$row['pid']); ?>" class="btn btn-sm btn-outline-danger font-weight-bold" style="font-size: 0.78rem;">
                      Baca Selengkapnya &rarr;
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
<?php } ?>

          <!-- Pagination -->
          <ul class="pagination justify-content-center mb-4">
            <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
              <a href="?s=<?php echo urlencode($st); ?>&pageno=1" class="page-link">Awal</a>
            </li>
            <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
              <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?s=" . urlencode($st) . "&pageno=" . ($pageno - 1); } ?>" class="page-link">&laquo; Prev</a>
            </li>
            <li class="page-item active">
              <span class="page-link bg-danger border-danger"><?php echo $pageno; ?> / <?php echo $total_pages; ?></span>
            </li>
            <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
              <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?s=" . urlencode($st) . "&pageno=" . ($pageno + 1); } ?>" class="page-link">Next &raquo;</a>
            </li>
            <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
              <a href="?s=<?php echo urlencode($st); ?>&pageno=<?php echo $total_pages; ?>" class="page-link">Akhir</a>
            </li>
          </ul>
<?php } ?>

        </div>

        <!-- Sidebar Widgets Column -->
        <?php include('includes/sidebar.php');?>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php include('includes/footer.php');?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>