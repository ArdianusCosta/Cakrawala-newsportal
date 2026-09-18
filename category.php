<?php 
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>News Portal | Category Page</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/modern-business.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
<?php include('includes/header.php');?>

<div class="container">
  <div class="row mt-2">
    <div class="col-12 px-2 px-md-3">
      <?php include('includes/category-bar.php'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">

<?php 
if($_GET['catid']!=''){
  $_SESSION['catid']=intval($_GET['catid']);
}

$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$no_of_records_per_page = 10;
$offset = ($pageno-1) * $no_of_records_per_page;

// hitung total artikel aktif di kategori
$total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE CategoryId='".$_SESSION['catid']."' AND Is_Active=1";
$result = mysqli_query($con,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

// ambil nama kategori
$categoryNameQuery = mysqli_query($con,"SELECT CategoryName FROM tblcategory WHERE id='".$_SESSION['catid']."'");
$categoryName = mysqli_fetch_array($categoryNameQuery)['CategoryName'];
echo "<h1>".htmlentities($categoryName)." News</h1>";
?>

<!-- ====== 5 artikel pertama ====== -->
<?php
$firstPart = mysqli_query($con, "SELECT 
            tblposts.id as pid,
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblposts.PostDetails as postdetails,
            tblposts.PostingDate as postingdate,
            tblposts.PostImage as PostImage,
            tbladmin.AdminUserName as author
        FROM tblposts 
        LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
        LEFT JOIN tbladmin ON tbladmin.id = tblposts.PostedBy
        WHERE tblposts.CategoryId='".$_SESSION['catid']."' AND tblposts.Is_Active=1
        ORDER BY tblposts.PostingDate DESC 
        LIMIT $offset, 5");


while ($row = mysqli_fetch_array($firstPart)) {
?>
  <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
    class="card mb-3 text-decoration-none text-dark shadow-sm border-0 article-card">
    <div class="row no-gutters">
      <div class="col-md-4">
        <div class="thumb-landscape">
          <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" alt="<?php echo htmlentities($row['posttitle']); ?>">
        </div>
      </div>
      <div class="col-md-8 d-flex flex-column p-2">
        <div class="mb-1"><span class="badge-category"><?php echo htmlentities($row['category']); ?></span></div>
        <h3 class="mb-1"><?php echo htmlentities($row['posttitle']); ?></h3>
        <p class="text-muted mb-1" style="font-size: 0.85rem;"><?php echo substr(strip_tags($row['postdetails']),0,100); ?>...</p>
        <small class="text-secondary mt-auto" style="font-size: 0.8rem;">
          <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
          <?php echo htmlentities($row['category']); ?> | 
          Oleh: <?php echo htmlentities($row['author']); ?>
        </small>

      </div>
    </div>
  </a>
<?php } ?>

<!-- ====== Lanjutan Artikel (sisa) ====== -->
<?php
$secondPart = mysqli_query($con, "SELECT 
            tblposts.id as pid,
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblposts.PostDetails as postdetails,
            tblposts.PostingDate as postingdate,
            tblposts.PostImage as PostImage
        FROM tblposts 
        LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
        WHERE tblposts.CategoryId='".$_SESSION['catid']."' AND tblposts.Is_Active=1
        ORDER BY tblposts.PostingDate DESC 
        LIMIT ".($offset+5).", ".($no_of_records_per_page-5));

while ($row = mysqli_fetch_array($secondPart)) {
?>
  <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" 
    class="card mb-3 text-decoration-none text-dark shadow-sm border-0 article-card">
    <div class="row no-gutters">
      <div class="col-md-4">
        <div class="thumb-landscape">
          <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" alt="<?php echo htmlentities($row['posttitle']); ?>">
        </div>
      </div>
      <div class="col-md-8 d-flex flex-column p-2">
        <div class="mb-1"><span class="badge-category"><?php echo htmlentities($row['category']); ?></span></div>
        <h3 class="mb-1"><?php echo htmlentities($row['posttitle']); ?></h3>
        <p class="text-muted mb-1" style="font-size: 0.85rem;"><?php echo substr(strip_tags($row['postdetails']),0,100); ?>...</p>
        <small class="text-secondary mt-auto" style="font-size: 0.8rem;">
          <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | <?php echo htmlentities($row['category']); ?>
        </small>
      </div>
    </div>
  </a>
<?php } ?>

<!-- Pagination -->
<ul class="pagination justify-content-center mt-4">
  <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?catid=".$_SESSION['catid']."&pageno=1"; } ?>">«</a>
  </li>
  <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?catid=".$_SESSION['catid']."&pageno=".($pageno - 1); } ?>">‹</a>
  </li>
  <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?catid=".$_SESSION['catid']."&pageno=".($pageno + 1); } ?>">›</a>
  </li>
  <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?catid=".$_SESSION['catid']."&pageno=".$total_pages; } ?>">»</a>
  </li>
</ul>

</div>

<!-- Sidebar -->
<?php include('includes/sidebar.php');?>

</div>
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
</div>
</div>

<?php include('includes/footer.php');?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>