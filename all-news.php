<?php 
session_start();
include('includes/config.php');

$pageTitle = 'Semua Artikel & Berita Terbaru - Cakrawala Online';
$pageDescription = 'Kumpulan seluruh artikel dan berita terbaru Indramayu, Jawa Barat dan Nasional di Cakrawala Online';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<?php include('includes/seo-meta.php'); ?>
<!-- CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/modern-business.css" rel="stylesheet">
<link href="style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php'); ?>
 <!-- Page Content -->
  <div class="container">
    <div class="row mt-2">
      <div class="col-12 px-2 px-md-3">
        <?php include('includes/category-bar.php'); ?>
      </div>
    </div>
    <div class="row">
      <!-- Blog Entries Column -->
      <div class="col-md-8">
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
      </div><!-- /col-md-8 -->
    </div><!-- /row -->
  </div><!-- /.container -->

<?php include('includes/footer.php'); ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>