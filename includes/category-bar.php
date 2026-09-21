<?php
if (!isset($con)) {
    include('includes/config.php');
}
$currentCat = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
?>

<!-- ===== REMODELED HORIZONTAL CATEGORY SCROLL BAR ===== -->
<div class="category-bar-container mb-3 bg-white border rounded shadow-sm px-2 px-md-3 py-2 d-flex align-items-center flex-nowrap">

  <!-- Scrollable Category Items -->
  <div class="category-bar-scroll-wrapper flex-grow-1 overflow-hidden position-relative" style="min-width: 0;">
    <div class="category-scroll-content d-flex align-items-center flex-nowrap" id="categoryScrollContainer">
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
      <i class="bi bi-chevron-left text-danger font-weight-bold"></i>
    </button>
    <button type="button" class="btn btn-sm btn-light border rounded-circle p-0 category-arrow-btn" id="catScrollRight" title="Scroll Right" aria-label="Scroll Right">
      <i class="bi bi-chevron-right text-danger font-weight-bold"></i>
    </button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var scrollContainer = document.getElementById('categoryScrollContainer');
  var btnLeft = document.getElementById('catScrollLeft');
  var btnRight = document.getElementById('catScrollRight');

  if (!scrollContainer) return;

  // Helper Scroll Function
  function doScrollLeft(e) {
    if (e && e.cancelable) e.preventDefault();
    try {
      scrollContainer.scrollBy({ left: -220, behavior: 'smooth' });
    } catch(err) {
      scrollContainer.scrollLeft -= 220;
    }
  }

  function doScrollRight(e) {
    if (e && e.cancelable) e.preventDefault();
    try {
      scrollContainer.scrollBy({ left: 220, behavior: 'smooth' });
    } catch(err) {
      scrollContainer.scrollLeft += 220;
    }
  }

  // Arrow Click & Touch Event Handlers
  if (btnLeft) {
    btnLeft.addEventListener('click', doScrollLeft);
    btnLeft.addEventListener('touchend', function(e) {
      if (e.cancelable) e.preventDefault();
      doScrollLeft(e);
    });
  }

  if (btnRight) {
    btnRight.addEventListener('click', doScrollRight);
    btnRight.addEventListener('touchend', function(e) {
      if (e.cancelable) e.preventDefault();
      doScrollRight(e);
    });
  }

  // Touch Swipe Dragging for Mobile, Tablet & Touchscreens
  var isTouch = false;
  var touchStartX = 0;
  var touchScrollLeft = 0;

  scrollContainer.addEventListener('touchstart', function(e) {
    if (e.touches.length === 1) {
      isTouch = true;
      touchStartX = e.touches[0].pageX - scrollContainer.offsetLeft;
      touchScrollLeft = scrollContainer.scrollLeft;
    }
  }, { passive: true });

  scrollContainer.addEventListener('touchmove', function(e) {
    if (!isTouch || e.touches.length !== 1) return;
    var x = e.touches[0].pageX - scrollContainer.offsetLeft;
    var walk = (x - touchStartX) * 1.3;
    scrollContainer.scrollLeft = touchScrollLeft - walk;
  }, { passive: true });

  scrollContainer.addEventListener('touchend', function() {
    isTouch = false;
  }, { passive: true });

  scrollContainer.addEventListener('touchcancel', function() {
    isTouch = false;
  }, { passive: true });

  // Mouse Wheel / Trackpad Horizontal Scroll
  scrollContainer.addEventListener('wheel', function(e) {
    if (e.deltaY !== 0 || e.deltaX !== 0) {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        e.preventDefault();
        scrollContainer.scrollLeft += e.deltaY;
      }
    }
  }, { passive: false });

  // Mouse Drag-to-Scroll Functionality for Desktop
  var isMouseDown = false;
  var mouseStartX = 0;
  var mouseScrollLeft = 0;

  scrollContainer.addEventListener('mousedown', function(e) {
    isMouseDown = true;
    scrollContainer.classList.add('dragging');
    mouseStartX = e.pageX - scrollContainer.offsetLeft;
    mouseScrollLeft = scrollContainer.scrollLeft;
  });

  scrollContainer.addEventListener('mouseleave', function() {
    isMouseDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mouseup', function() {
    isMouseDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mousemove', function(e) {
    if (!isMouseDown) return;
    e.preventDefault();
    var x = e.pageX - scrollContainer.offsetLeft;
    var walk = (x - mouseStartX) * 1.5;
    scrollContainer.scrollLeft = mouseScrollLeft - walk;
  });
});
</script>
