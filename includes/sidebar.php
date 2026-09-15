<?php
// Function helper for dynamic Trending Topik calculation
if (!function_exists('getDynamicTrendingData')) {
    function getDynamicTrendingData($con) {
        $totalRes = mysqli_query($con, "SELECT COUNT(*) as total FROM tblposts WHERE Is_Active=1");
        $totalRow = mysqli_fetch_assoc($totalRes);
        $totalPosts = (int)($totalRow['total'] ?? 0);

        if ($totalPosts === 0) {
            return ['tokoh' => [], 'peristiwa' => [], 'total' => 0];
        }

        $postsQuery = mysqli_query($con, "SELECT PostTitle, PostDetails FROM tblposts WHERE Is_Active=1");
        $posts = [];
        while ($r = mysqli_fetch_assoc($postsQuery)) {
            $posts[] = $r['PostTitle'] . " " . strip_tags($r['PostDetails']);
        }

        // Tokoh dictionary & canonical names
        $tokohDict = [
            "Wali Kota Jaksel" => ["Wali Kota", "Walikota Syafrin", "Jaksel"],
            "Bupati Sukabumi" => ["Bupati Sukabumi", "Marwan Hamami"],
            "Paoji Nurjaman" => ["Paoji Nurjaman"],
            "Prabowo Subianto" => ["Prabowo Subianto", "Prabowo"],
            "Dedi Mulyadi" => ["Dedi Mulyadi"],
            "Hasto Kristiyanto" => ["Hasto Kristiyanto", "Hasto"],
            "Megawati Soekarnoputri" => ["Megawati Soekarnoputri", "Megawati"],
            "Joko Widodo" => ["Joko Widodo", "Jokowi"],
            "Gibran Rakabuming" => ["Gibran Rakabuming", "Gibran"],
            "Anies Baswedan" => ["Anies Baswedan", "Anies"],
            "Teddy Setiadi" => ["Teddy Setiadi", "Tedyy Setiadi"],
            "Basuki Tjahaja Purnama" => ["Basuki Tjahaja Purnama", "Ahok"],
            "Nadiem Makarim" => ["Nadiem Makarim"],
            "Ganjar Pranowo" => ["Ganjar Pranowo"],
            "Mahfud MD" => ["Mahfud MD"],
            "Sherly Tjoanda" => ["Sherly Tjoanda"],
            "Ufairha Nur Afifah" => ["Ufairha Nur Afifah"],
            "Nicolas" => ["Nicolas"]
        ];

        $tokohCounts = [];
        foreach ($posts as $text) {
            foreach ($tokohDict as $canonical => $keywords) {
                foreach ($keywords as $kw) {
                    if (stripos($text, $kw) !== false) {
                        $tokohCounts[$canonical] = ($tokohCounts[$canonical] ?? 0) + 1;
                        break;
                    }
                }
            }
        }

        arsort($tokohCounts);
        $tokohTop = array_slice($tokohCounts, 0, 8, true);
        $maxTokohCount = !empty($tokohTop) ? max($tokohTop) : 1;

        $tokohData = [];
        foreach ($tokohTop as $name => $count) {
            $pct = round(($count / $totalPosts) * 100, 2);
            $barWidth = round(($count / $maxTokohCount) * 100);
            if ($barWidth < 12) $barWidth = 12;
            $tokohData[] = [
                'name' => $name,
                'count' => $count,
                'percentage' => number_format($pct, 2) . '%',
                'bar_width' => $barWidth
            ];
        }

        // Peristiwa dictionary & canonical names
        $peristiwaDict = [
            "Bidang Pertanian" => ["Pertanian", "Transplanter", "Pisang Cavendish"],
            "Bencana & Longsor" => ["Pergerakan Tanah", "Bencana", "Longsor"],
            "Rapat Paripurna DPRD" => ["Rapat Paripurna", "Paripurna"],
            "Pemeriksaan KPK" => ["KPK", "Pemeriksaan"],
            "Pemberdayaan Yatim" => ["Anak Yatim", "Baznas"],
            "Infrastruktur Jalan" => ["Hotmix", "Infrastruktur Jalan", "IJD"],
            "Reses DPRD" => ["Reses"],
            "Kegiatan Pramuka" => ["Pramuka", "Kwarcab"],
            "Makan Bergizi Gratis" => ["Makan Bergizi Gratis", "MBG"],
            "Hari Lahir Pancasila" => ["Hari Lahir Pancasila", "Pancasila"],
            "Hari Bhayangkara" => ["Hari Bhayangkara", "Bhayangkara"],
            "Idul Adha & Qurban" => ["Idul Adha", "Qurban"],
            "Pesta Wirausaha" => ["Pesta Wirausaha", "TDA"],
            "Laporan LKPJ Bupati" => ["LKPJ"],
            "Silatnas Anak Rantau" => ["Silatnas"],
            "Ekspansi Digital" => ["Hai Motion", "Creative Agency"]
        ];

        $peristiwaCounts = [];
        foreach ($posts as $text) {
            foreach ($peristiwaDict as $canonical => $keywords) {
                foreach ($keywords as $kw) {
                    if (stripos($text, $kw) !== false) {
                        $peristiwaCounts[$canonical] = ($peristiwaCounts[$canonical] ?? 0) + 1;
                        break;
                    }
                }
            }
        }

        arsort($peristiwaCounts);
        $peristiwaTop = array_slice($peristiwaCounts, 0, 8, true);
        $maxPeristiwaCount = !empty($peristiwaTop) ? max($peristiwaTop) : 1;

        $peristiwaData = [];
        foreach ($peristiwaTop as $name => $count) {
            $pct = round(($count / $totalPosts) * 100, 2);
            $barWidth = round(($count / $maxPeristiwaCount) * 100);
            if ($barWidth < 12) $barWidth = 12;
            $displayName = strlen($name) > 20 ? substr($name, 0, 18) . '...' : $name;
            $peristiwaData[] = [
                'name' => $displayName,
                'full_name' => $name,
                'count' => $count,
                'percentage' => number_format($pct, 2) . '%',
                'bar_width' => $barWidth
            ];
        }

        return [
            'tokoh' => $tokohData,
            'peristiwa' => $peristiwaData,
            'total' => $totalPosts
        ];
    }
}

$trendingData = getDynamicTrendingData($con);
$tokohList = $trendingData['tokoh'];
$peristiwaList = $trendingData['peristiwa'];
?>

<div class="col-md-4">

  <!-- Trending Topik Widget -->
  <div class="card mb-4 border-1 sidebar-trending-card">
    <div class="card-header bg-white border-0 pt-4 pb-0">
      <h5 class="sidebar-title p-0"><span class="sidebar-title-highlight">Trending</span> Topik</h5>
    </div>
    <div class="card-body">
      <ul class="nav custom-tabs mb-3" id="trendingTab" role="tablist" style="margin-bottom: 10px;">
        <li class="nav-item">
          <a class="nav-link active py-1 px-3" id="tokoh-tab" data-toggle="tab" href="#tokoh" role="tab" aria-controls="tokoh" aria-selected="true" style="font-size: 0.85rem;">Tokoh</a>
        </li>
        <li class="nav-item">
          <a class="nav-link py-1 px-3" id="peristiwa-tab" data-toggle="tab" href="#peristiwa" role="tab" aria-controls="peristiwa" aria-selected="false" style="font-size: 0.85rem;">Peristiwa</a>
        </li>
      </ul>
      <div class="tab-content" id="trendingTabContent">
        <div class="tab-pane fade show active" id="tokoh" role="tabpanel" aria-labelledby="tokoh-tab">
          <ul class="trending-list mt-3">
            <?php if (!empty($tokohList)) {
              foreach ($tokohList as $item) { ?>
                <li>
                  <div class="trending-name"><?php echo htmlentities($item['name']); ?></div>
                  <div class="trending-bar-container">
                    <div class="trending-bar" style="width: <?php echo $item['bar_width']; ?>%;"></div>
                    <span class="trending-value"><?php echo htmlentities($item['percentage']); ?></span>
                  </div>
                </li>
              <?php }
            } else { ?>
              <li class="text-center text-muted py-3">Belum ada data trending</li>
            <?php } ?>
          </ul>
        </div>
        <div class="tab-pane fade" id="peristiwa" role="tabpanel" aria-labelledby="peristiwa-tab">
          <ul class="trending-list mt-3">
            <?php if (!empty($peristiwaList)) {
              foreach ($peristiwaList as $item) { ?>
                <li>
                  <div class="trending-name" title="<?php echo htmlentities($item['full_name']); ?>"><?php echo htmlentities($item['name']); ?></div>
                  <div class="trending-bar-container">
                    <div class="trending-bar" style="width: <?php echo $item['bar_width']; ?>%;"></div>
                    <span class="trending-value"><?php echo htmlentities($item['percentage']); ?></span>
                  </div>
                </li>
              <?php }
            } else { ?>
              <li class="text-center text-muted py-3">Belum ada data trending</li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Berita Terbaru Widget -->
  <div class="card mb-4 border-1">
    <div class="card-header bg-white border-0 pt-4 pb-2">
      <h5 class="sidebar-title p-0"><span class="sidebar-title-highlight">Berita</span> Terbaru</h5>
    </div>
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
          <li class="list-group-item border-0 pt-3 pb-3" style="border-bottom: 1px solid #f0f0f0 !important;">
            <div class="d-flex">
              <img src="admin/uploads/<?php echo htmlentities($row['PostImage']);?>" 
                   class="mr-3 rounded" style="width:100px; height:75px; object-fit:cover;">
              <div class="d-flex flex-column justify-content-between">
                <div>
                  <span class="badge badge-danger mb-1" style="font-size: 0.65rem;">
                    <?php echo htmlentities($row['CategoryName']);?>
                  </span>
                  <a href="news-details.php?nid=<?php echo htmlentities($row['pid'])?>" 
                     class="font-weight-bold d-block text-dark" style="font-size:0.85rem; line-height: 1.3;">
                    <?php echo htmlentities($row['PostTitle']);?>
                  </a>
                </div>
                <small class="text-muted" style="font-size: 0.65rem;">
                  Redaksi Cakrawala | <?php echo date("d M Y", strtotime($row['PostingDate']));?> | <?php echo htmlentities($row['views']);?> Views
                </small>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>

</div>
