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

<!-- CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link href="css/modern-business.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container">
  <div class="row" style="margin-top: 4%">

    <!-- ===== HEADLINE SECTION ===== -->
    <div class="container-fluid position-relative headline">
    <?php 
    $headlineQuery = mysqli_query(
        $con, 
        "SELECT p.*, c.CategoryName 
         FROM tblposts p 
         LEFT JOIN tblcategory c ON c.id = p.CategoryId 
         WHERE p.Is_Active = 1
         ORDER BY p.PostingDate DESC 
         LIMIT 1"
    );
    $headline = mysqli_fetch_array($headlineQuery);
    if($headline):
    ?>
      <a href="news-details.php?nid=<?php echo htmlentities($headline['id']); ?>" class="headline-link d-block">
        <img src="admin/uploads/<?php echo $headline['PostImage'] ?: 'default.jpg'; ?>" alt="Headline">
        <div class="headline-title">
          <div class="category-label"><?php echo htmlentities($headline['CategoryName']); ?></div>
          <h3><?php echo htmlentities($headline['PostTitle']); ?></h3>
          <small><?php echo date("d M Y", strtotime($headline['PostingDate'])); ?></small>
        </div>
      </a>
    <?php endif; ?>
    </div>

    <!-- Blog Entries Column -->
    <div class="col-md-8">

      <!-- TERPOPULER -->
      <h4 class="mt-4 mb-3">Terpopuler</h4>
      <?php
      $populerQuery = mysqli_query($con, "SELECT 
            tblposts.id as pid,
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblcategory.id as cid,
            tbladmin.AdminUserName as aid,
            tblsubcategory.Subcategory as subcategory,
            tblposts.PostDetails as postdetails,
            tblposts.PostingDate as postingdate,
            tblposts.PostUrl as url,
            tblposts.PostImage as PostImage,
            tblposts.PostedBy as postedby,
            tblposts.Views as views
        FROM tblposts 
        LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
        LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId
        LEFT JOIN tbladmin ON tbladmin.AdminUserName = tblposts.PostedBy
        WHERE tblposts.Is_Active = 1
        ORDER BY tblposts.Views DESC 
        LIMIT 5");

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
                <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | <?php echo htmlentities($row['postedby']); ?> | <?php echo htmlentities($row['category']); ?> | <?php echo htmlentities($row['views']); ?> views
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
            tblposts.id as pid,
            tblposts.PostTitle as posttitle,
            tblcategory.CategoryName as category,
            tblposts.PostImage as PostImage,
            tblposts.PostingDate as postingdate,
            tblposts.PostDetails as postdetails
          FROM tblposts 
          LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
          WHERE tblposts.Is_Active = 1
          ORDER BY tblposts.PostingDate DESC 
          LIMIT 4");

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
              <small class="text-muted" style="font-size: 0.8rem;"><?php echo date("d M Y", strtotime($row['postingdate'])); ?> | <?php echo htmlentities($row['category']); ?></small>
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
            tblposts.PostImage as PostImage
          FROM tblposts 
          LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
          LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
          WHERE tblposts.Is_Active = 1
          ORDER BY tblposts.PostingDate DESC 
          LIMIT $offset, $no_of_records_per_page");

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
              <small class="text-secondary mt-auto" style="font-size: 0.8rem;"><?php echo date("d M Y", strtotime($row['postingdate'])); ?> | <?php echo htmlentities($row['category']); ?></small>
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
      $postQuery = mysqli_query($con, "SELECT id, PostTitle, PostImage, PostingDate, PostDetails 
                                      FROM tblposts 
                                      WHERE CategoryId = '$catId' AND Is_Active = 1
                                      ORDER BY PostingDate DESC LIMIT 2");
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
                  <small class="text-muted d-block mb-1"><?php echo date("d M Y", strtotime($post['PostingDate'])); ?></small>
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
