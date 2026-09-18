<?php
if (!isset($con)) {
    include('includes/config.php');
}
$currentCat = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
?>

<!-- ===== REMODELED HORIZONTAL CATEGORY SCROLL BAR (Gambar 3 Style) ===== -->
<div class="category-bar-container mb-3 bg-white border rounded shadow-sm px-2 px-md-3 py-2 d-flex align-items-center">

  <!-- Scrollable Category Items (Starts Directly with Categories) -->
  <div class="category-bar-scroll-wrapper flex-grow-1 overflow-hidden position-relative">
    <div class="category-scroll-content d-flex align-items-center" id="categoryScrollContainer">
      <a href="index.php" class="category-bar-item text-dark font-weight-bold px-3 py-1 mx-1 text-decoration-none rounded <?php echo ($currentCat == 0 && !isset($_GET['searchtitle'])) ? 'active' : ''; ?>">
        Berita Utama
      </a>
      <?php 
      $catBarQuery = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1 ORDER BY id ASC");
      while($cRow = mysqli_fetch_array($catBarQuery)):
        $isActiveCat = ($currentCat == $cRow['id']) ? 'active' : '';
      ?>
        <a href="category.php?catid=<?php echo htmlentities($cRow['id']); ?>" 
           class="category-bar-item text-dark font-weight-bold px-3 py-1 mx-1 text-decoration-none rounded <?php echo $isActiveCat; ?>">
          <?php echo htmlentities($cRow['CategoryName']); ?>
        </a>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Right Side: Left (<) and Right (>) Navigation Control Buttons -->
  <div class="category-bar-arrows d-flex align-items-center pl-2 border-left ml-2 flex-shrink-0">
    <button type="button" class="btn btn-sm btn-light border rounded-circle p-0 mr-1 category-arrow-btn" id="catScrollLeft" title="Scroll Left" aria-label="Scroll Left">
      <i class="bi bi-chevron-left text-primary font-weight-bold"></i>
    </button>
    <button type="button" class="btn btn-sm btn-light border rounded-circle p-0 category-arrow-btn" id="catScrollRight" title="Scroll Right" aria-label="Scroll Right">
      <i class="bi bi-chevron-right text-primary font-weight-bold"></i>
    </button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var scrollContainer = document.getElementById('categoryScrollContainer');
  var btnLeft = document.getElementById('catScrollLeft');
  var btnRight = document.getElementById('catScrollRight');

  if (!scrollContainer) return;

  // Arrow Click Navigation
  if (btnLeft && btnRight) {
    btnLeft.addEventListener('click', function() {
      scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
    });
    btnRight.addEventListener('click', function() {
      scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
    });
  }

  // Mouse & Touch Drag-to-Scroll Functionality
  var isDown = false;
  var startX, scrollLeft;

  scrollContainer.addEventListener('mousedown', function(e) {
    isDown = true;
    scrollContainer.classList.add('dragging');
    startX = e.pageX - scrollContainer.offsetLeft;
    scrollLeft = scrollContainer.scrollLeft;
  });

  scrollContainer.addEventListener('mouseleave', function() {
    isDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mouseup', function() {
    isDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mousemove', function(e) {
    if (!isDown) return;
    e.preventDefault();
    var x = e.pageX - scrollContainer.offsetLeft;
    var walk = (x - startX) * 1.5;
    scrollContainer.scrollLeft = scrollLeft - walk;
  });
});
</script>
