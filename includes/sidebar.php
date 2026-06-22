<div class="col-md-4">

  <!-- Search Widget -->


  <!-- Categories Widget -->
  <div class="card mb-4">
    <h5 class="card-header">Kategori</h5>
    <div class="list-group list-group-flush">
      <?php 
      $query = mysqli_query($con,"
        SELECT id, CategoryName 
        FROM tblcategory 
        WHERE Is_Active=1 
        ORDER BY CategoryName ASC
      ");
      while($row = mysqli_fetch_array($query)) { ?>
        <a href="category.php?catid=<?php echo htmlentities($row['id'])?>" 
           class="list-group-item list-group-item-action">
          <?php echo htmlentities($row['CategoryName']);?>
        </a>
      <?php } ?>
    </div>
  </div>

<!-- Latest News Widget -->
<div class="card mb-4">
  <h5 class="card-header">Berita Terbaru</h5>
  <div class="card-body p-0">
    <ul class="list-group list-group-flush">
      <?php
      $query = mysqli_query($con,"
        SELECT p.id AS pid, p.PostTitle, p.PostImage, p.PostingDate, 
               p.views, p.PostUrl, 
               c.CategoryName,
               a.AdminUserName
        FROM tblposts p
        LEFT JOIN tblcategory c ON c.id = p.CategoryId
        LEFT JOIN tbladmin a ON a.id = p.PostedBy
        WHERE p.Is_Active=1 
        ORDER BY p.PostingDate DESC 
        LIMIT 8
      ");
      while ($row = mysqli_fetch_array($query)) {
      ?>
        <li class="list-group-item">
          <div class="d-flex">
            <img src="admin/uploads/<?php echo htmlentities($row['PostImage']);?>" 
                 class="me-2 rounded" style="width:80px; height:60px; object-fit:cover;">
            <div>
              <!-- Category Badge -->
              <span class="badge bg-danger mb-1">
                <?php echo htmlentities($row['CategoryName']);?>
              </span>

              <!-- Judul -->
              <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" 
                 class="fw-bold d-block text-dark">
                <?php echo htmlentities($row['PostTitle']);?>
              </a>

              <!-- Info tambahan -->
              <small class="text-muted">
                Penulis <?php echo htmlentities($row['AdminUserName']);?> | 
                <?php echo date("d M Y", strtotime($row['PostingDate']));?> | 
                Views: <?php echo htmlentities($row['views']);?>
              </small>
            </div>
          </div>
        </li>
      <?php } ?>
    </ul>
  </div>
</div>


</div>
