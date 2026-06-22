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


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>News Portal | Home Page</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/modern-business.css" rel="stylesheet">
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
            tblposts.PostImage as PostImage
        FROM tblposts 
        LEFT JOIN tblcategory ON tblcategory.id=tblposts.CategoryId 
        LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId=tblposts.SubCategoryId 
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
              <p class="card-text">
                <?php echo nl2br(htmlentities($row['postdetails'])); ?>
              </p>
            </div>
            <div class="card-footer text-muted">
              Posted on <?php echo htmlentities($row['postingdate']);?>
            </div>
          </div>
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
