<?php
/**
 * Renders the "Semua Artikel" grid + pagination.
 * Expects $con (mysqli connection) and $pageno (int, already validated) to be set
 * by whichever file includes this partial.
 */

$no_of_records_per_page = 8;

$total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE Is_Active = 1";
$total_result = mysqli_query($con, $total_pages_sql);
$total_rows = mysqli_fetch_array($total_result)[0];
$total_pages = max(1, ceil($total_rows / $no_of_records_per_page));

// Clamp pageno so a stale/typed-in URL can't produce a negative offset or an empty page.
if ($pageno < 1) { $pageno = 1; }
if ($pageno > $total_pages) { $pageno = $total_pages; }

$offset = ($pageno - 1) * $no_of_records_per_page;

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
?>

<div class="semua-grid">
<?php while ($row = mysqli_fetch_array($query)) { ?>
  <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" class="semua-card">
    <div class="thumb-landscape">
      <img src="admin/uploads/<?php echo htmlentities($row['PostImage'] ?: 'default.jpg'); ?>" 
           alt="<?php echo htmlentities($row['posttitle']); ?>">
    </div>
    <div class="semua-card-body">
      <span class="badge-category"><?php echo htmlentities($row['category']); ?></span>
      <h3><?php echo htmlentities($row['posttitle']); ?></h3>
      <p><?php echo substr(strip_tags($row['postdetails']), 0, 80); ?>...</p>
      <small class="text-secondary" style="font-size: 0.75rem;">
        <?php echo date("d M Y", strtotime($row['postingdate'])); ?> | 
        <?php echo htmlentities($row['author']); ?> | 
        <?php echo htmlentities($row['views']); ?> views
      </small>
    </div>
  </a>
<?php } ?>
</div><!-- /semua-grid -->

<div class="semua-pagination-wrap">
  <div class="semua-page-info">Halaman <?php echo $pageno; ?> dari <?php echo $total_pages; ?></div>

  <ul class="pagination justify-content-center mt-2 mb-0">
    <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
      <a href="?pageno=1" data-page="1" class="page-link ajax-page-link">«</a>
    </li>
    <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
      <a href="?pageno=<?php echo max(1, $pageno - 1); ?>" data-page="<?php echo max(1, $pageno - 1); ?>" class="page-link ajax-page-link">‹</a>
    </li>

    <?php
    // Windowed page numbers, e.g. 1 ... 4 5 [6] 7 8 ... 12
    $window = 2;
    $start = max(1, $pageno - $window);
    $end   = min($total_pages, $pageno + $window);

    if ($start > 1) {
        echo '<li class="page-item"><a href="?pageno=1" data-page="1" class="page-link ajax-page-link">1</a></li>';
        if ($start > 2) {
            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        $activeClass = ($i == $pageno) ? 'active' : '';
        echo '<li class="page-item ' . $activeClass . '"><a href="?pageno=' . $i . '" data-page="' . $i . '" class="page-link ajax-page-link">' . $i . '</a></li>';
    }

    if ($end < $total_pages) {
        if ($end < $total_pages - 1) {
            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        echo '<li class="page-item"><a href="?pageno=' . $total_pages . '" data-page="' . $total_pages . '" class="page-link ajax-page-link">' . $total_pages . '</a></li>';
    }
    ?>

    <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
      <a href="?pageno=<?php echo min($total_pages, $pageno + 1); ?>" data-page="<?php echo min($total_pages, $pageno + 1); ?>" class="page-link ajax-page-link">›</a>
    </li>
    <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
      <a href="?pageno=<?php echo $total_pages; ?>" data-page="<?php echo $total_pages; ?>" class="page-link ajax-page-link">»</a>
    </li>
  </ul>
</div><!-- /semua-pagination-wrap -->
