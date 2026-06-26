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

  <title>News Portal | About us</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/modern-business.css" rel="stylesheet">

  <style>
    /* ── Mobile-first fixes ── */

    /* Prevent horizontal scroll from any overflowing element */
    html, body {
      overflow-x: hidden;
    }

    /* Page heading: scale down on small screens */
    .page-title {
      font-size: clamp(1.4rem, 5vw, 2rem);
      word-break: break-word;
      margin-top: 1.25rem;
      margin-bottom: 0.75rem;
    }

    /* Breadcrumb: wrap gracefully and reduce font on xs */
    .breadcrumb {
      flex-wrap: wrap;
      font-size: 0.875rem;
      padding: 0.5rem 0;
      background: transparent;
      margin-bottom: 1.25rem;
    }

    /* Content area: comfortable reading on mobile */
    .about-content {
      font-size: 1rem;
      line-height: 1.75;
      word-break: break-word;
      overflow-wrap: break-word;
    }

    /* ── Susunan Redaksi table ── */
    .about-content table {
      width: 100% !important;
      border-collapse: collapse !important;
      table-layout: fixed !important;
    }

    .about-content table td {
      padding: 0.5rem 0.75rem !important;
      vertical-align: top !important;
      word-break: break-word !important;
      overflow-wrap: break-word !important;
      white-space: normal !important;
    }

    /* Position label: fixed 45% — wraps long titles to next line */
    .about-content table td:first-child {
      width: 45% !important;
      font-weight: 600 !important;
    }

    /* Name column: fixed 55% — enough room for names to show fully */
    .about-content table td:last-child {
      width: 55% !important;
    }

    /* Add breathing room around the main container on small screens */
    @media (max-width: 575.98px) {
      .container {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .page-title {
        font-size: 1.4rem;
      }

      .about-content {
        font-size: 0.9375rem; /* 15px – comfortable mobile reading size */
      }
    }

    /* Tablet tweaks */
    @media (min-width: 576px) and (max-width: 767.98px) {
      .page-title {
        font-size: 1.65rem;
      }
    }
  </style>
</head>

<body>

  <!-- Navigation -->
  <?php include('includes/header.php'); ?>

  <!-- Page Content -->
  <div class="container">

    <?php
    $pagetype = 'aboutus';
    $query = mysqli_query($con, "SELECT PageTitle, Description FROM tblpages WHERE PageName='$pagetype'");
    while ($row = mysqli_fetch_array($query)) :
    ?>

      <h1 class="page-title"><?php echo htmlentities($row['PageTitle']); ?></h1>

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">About</li>
        </ol>
      </nav>

      <!-- Intro Content -->
      <div class="row">
        <div class="col-12">
          <div class="about-content">
            <?php echo $row['Description']; ?>
          </div>
        </div>
      </div>
      <!-- /.row -->

    <?php endwhile; ?>

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <?php include('includes/footer.php'); ?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>