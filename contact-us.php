<?php
include('includes/config.php');

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>News Portal | Contact us</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
      .page-title { font-size: clamp(1.4rem, 5vw, 2rem); word-break: break-word; }
      .breadcrumb { flex-wrap: wrap; font-size: 0.875rem; background: transparent; }
      .about-content { font-size: 1rem; line-height: 1.75; word-break: break-word; overflow-wrap: break-word; }
      @media (max-width: 575.98px) { .about-content { font-size: 0.9375rem; } }
    </style>

  </head>

  <body>

    <!-- Navigation -->
    <?php include('includes/header.php');?>
    <!-- Page Content -->
    <div class="container">

<?php 
$pagetype='contactus';
$query=mysqli_query($con,"select PageTitle,Description from tblpages where PageName='$pagetype'");
while($row=mysqli_fetch_array($query))
{

?>
      <h1 class="page-title mt-4 mb-3"><?php echo htmlentities($row['PageTitle'])?></h1>

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Contact</li>
        </ol>
      </nav>

      <div class="row">
        <div class="col-12">
          <div class="about-content"><?php echo $row['Description'];?></div>
        </div>
      </div>
<?php } ?>
    
    </div>
    <!-- /.container -->

    <!-- Footer -->
 <?php include('includes/footer.php');?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>