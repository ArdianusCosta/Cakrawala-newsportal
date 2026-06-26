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
</body>
</html>