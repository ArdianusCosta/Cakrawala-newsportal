<?php
// Function helper for dynamic & real-time Trending Topik calculation
if (!function_exists('getDynamicTrendingData')) {
    function getDynamicTrendingData($con) {
        $totalRes = mysqli_query($con, "SELECT COUNT(*) as total FROM tblposts WHERE Is_Active=1");
        $totalRow = mysqli_fetch_assoc($totalRes);
        $totalPosts = (int)($totalRow['total'] ?? 0);

        if ($totalPosts === 0) {
            return ['tokoh' => [], 'peristiwa' => [], 'total' => 0];
        }

        $postsQuery = mysqli_query($con, "SELECT PostTitle, SUBSTRING(PostDetails, 1, 300) as Excerpt FROM tblposts WHERE Is_Active=1");
        $posts = [];
        if ($postsQuery) {
            while ($r = mysqli_fetch_assoc($postsQuery)) {
                $posts[] = $r['PostTitle'] . " " . strip_tags($r['Excerpt'] ?? '');
            }
        }

        // Tokoh candidates matched against actual published articles
        $tokohCandidates = [
            ["name" => "Wali Kota Jaksel", "search" => "Wali Kota Jaksel", "keywords" => ["Syafrin", "Walikota Syafrin", "Wali Kota Jaksel", "Walikota Jaksel", "Wali Kota Jakarta Selatan", "Walikota Jakarta Selatan"]],
            ["name" => "Bupati Sukabumi", "search" => "Bupati Sukabumi", "keywords" => ["Bupati Sukabumi", "Marwan Hamami"]],
            ["name" => "Bupati Irwan Hamid", "search" => "Bupati Irwan Hamid", "keywords" => ["Irwan Hamid", "Bupati Pinrang"]],
            ["name" => "Kapolres Lebak", "search" => "Kapolres Lebak", "keywords" => ["Kapolres Lebak", "Polres Lebak"]],
            ["name" => "Paoji Nurjaman", "search" => "Paoji Nurjaman", "keywords" => ["Paoji Nurjaman"]],
            ["name" => "Prabowo Subianto", "search" => "Prabowo Subianto", "keywords" => ["Prabowo Subianto", "Prabowo"]],
            ["name" => "Sekda A. Calo Kerrang", "search" => "Sekda A. Calo Kerrang", "keywords" => ["Calo Kerrang", "A. Calo Kerrang", "Sekda Pinrang"]],
            ["name" => "Letkol I Nyoman Artawan", "search" => "Letkol I Nyoman Artawan", "keywords" => ["Nyoman Artawan", "Letkol Kav I Nyoman", "Dandim 0504"]],
            ["name" => "Kombes Putu Yuni", "search" => "Kombes Putu Yuni", "keywords" => ["Putu Yuni", "Kombes Pol I Putu Yuni", "Kapolres Jaksel"]],
            ["name" => "Hasto Kristiyanto", "search" => "Hasto Kristiyanto", "keywords" => ["Hasto Kristiyanto", "Sekjen PDI-P Hasto"]],
            ["name" => "Megawati Soekarnoputri", "search" => "Megawati Soekarnoputri", "keywords" => ["Megawati Soekarnoputri", "Megawati"]],
            ["name" => "Shin Tae-yong", "search" => "Shin Tae-yong", "keywords" => ["Shin Tae-yong"]],
            ["name" => "Ufairha Nur Afifah", "search" => "Ufairha Nur Afifah", "keywords" => ["Ufairha Nur Afifah"]],
            ["name" => "Ence Benno", "search" => "Ence Benno", "keywords" => ["Ence Benno", "Kades Babakanjaya"]],
            ["name" => "Deliar Marzoeki", "search" => "Deliar Marzoeki", "keywords" => ["Deliar Marzoeki", "Kadisnakertrans Sumsel"]],
            ["name" => "Mustari S.Pd", "search" => "Mustari S.Pd", "keywords" => ["Mustari S.Pd", "UPT SDN 1 Pinrang"]],
            ["name" => "Totok Supriyadi", "search" => "Totok Supriyadi", "keywords" => ["Totok Supriyadi", "Camat Tanjungsari"]],
            ["name" => "Wabup Iing", "search" => "Wabup Iing", "keywords" => ["Wabup Iing"]],
            ["name" => "Joko Widodo", "search" => "Joko Widodo", "keywords" => ["Joko Widodo", "Jokowi"]],
            ["name" => "Gibran Rakabuming", "search" => "Gibran Rakabuming", "keywords" => ["Gibran Rakabuming", "Gibran"]],
            ["name" => "Anies Baswedan", "search" => "Anies Baswedan", "keywords" => ["Anies Baswedan", "Anies"]]
        ];

        $tokohCounts = [];
        foreach ($tokohCandidates as $cand) {
            $count = 0;
            foreach ($posts as $text) {
                foreach ($cand['keywords'] as $kw) {
                    if (stripos($text, $kw) !== false) {
                        $count++;
                        break;
                    }
                }
            }
            if ($count > 0) {
                $tokohCounts[] = [
                    'name' => $cand['name'],
                    'full_name' => $cand['name'],
                    'search' => $cand['search'],
                    'count' => $count
                ];
            }
        }

        usort($tokohCounts, function($a, $b) { return $b['count'] <=> $a['count']; });
        $tokohTop = array_slice($tokohCounts, 0, 8);
        $maxTokohCount = !empty($tokohTop) ? $tokohTop[0]['count'] : 1;

        $tokohData = [];
        foreach ($tokohTop as $item) {
            $pct = round(($item['count'] / $totalPosts) * 100, 2);
            $barWidth = round(($item['count'] / $maxTokohCount) * 100);
            if ($barWidth < 12) $barWidth = 12;
            $tokohData[] = [
                'name' => $item['name'],
                'full_name' => $item['full_name'],
                'search' => $item['search'],
                'count' => $item['count'],
                'percentage' => number_format($pct, 2) . '%',
                'bar_width' => $barWidth
            ];
        }

        // Peristiwa candidates matched against actual published articles
        $peristiwaCandidates = [
            ["name" => "Reses & Paripurna DPRD", "search" => "Reses & Paripurna DPRD", "keywords" => ["Rapat Paripurna", "Paripurna DPRD", "Reses DPRD", "Reses Kedua", "DPRD"]],
            ["name" => "Jaga Jakarta On The Spot", "search" => "Jaga Jakarta On The Spot", "keywords" => ["Jaga Jakarta", "Jakarta On The Spot", "Kondusif Jakarta"]],
            ["name" => "Operasi Knalpot Brong", "search" => "Operasi Knalpot Brong", "keywords" => ["Knalpot Brong", "Amankan 207 Knalpot"]],
            ["name" => "Santunan Anak Yatim", "search" => "Santunan Anak Yatim", "keywords" => ["Anak Yatim", "Baznas Bazis", "Pemberdayaan Yatim"]],
            ["name" => "Perbaikan Jalan PUPR", "search" => "Perbaikan Jalan PUPR", "keywords" => ["PUPR", "UPTD PJJ", "Perbaikan Jalan"]],
            ["name" => "Bantuan Sembako Lansia", "search" => "Bantuan Sembako Lansia", "keywords" => ["Sembako", "Bantuan Paket Sembako", "Lansia"]],
            ["name" => "Penangkaran Badak Jawa", "search" => "Penangkaran Badak Jawa", "keywords" => ["Badak Jawa", "Penangkaran Badak"]],
            ["name" => "Pelepasan Murid SD", "search" => "Pelepasan Murid SD", "keywords" => ["Pelepasan Murid", "SDN 1 Penganjang"]],
            ["name" => "Pelatihan AI Drone", "search" => "Pelatihan AI Drone", "keywords" => ["AI Drone", "Korea Drone Nusantara"]],
            ["name" => "Surplus APBD Pinrang", "search" => "Surplus APBD Pinrang", "keywords" => ["Surplus Keuangan", "Surplus APBD"]],
            ["name" => "Coffee Morning Forkopimko", "search" => "Coffee Morning Forkopimko", "keywords" => ["Coffee Morning", "FORKOPIMKO"]],
            ["name" => "Penanganan Rutilahu", "search" => "Penanganan Rutilahu", "keywords" => ["Rutilahu", "Rumah Tidak Layak Huni"]],
            ["name" => "Program IJD Jalan", "search" => "Program IJD Jalan", "keywords" => ["Instruksi Presiden Jalan Daerah", "IJD"]],
            ["name" => "Pemeriksaan KPK", "search" => "Pemeriksaan KPK", "keywords" => ["Pemeriksaan KPK", "Gedung KPK"]],
            ["name" => "Ekspansi Hai Motion", "search" => "Ekspansi Hai Motion", "keywords" => ["Hai Motion", "Layanan Kreatif"]],
            ["name" => "Pelantikan TP PKK", "search" => "Pelantikan TP PKK", "keywords" => ["TP PKK", "Pelantikan Enam Ketua"]],
            ["name" => "Keberatan SK Kades", "search" => "Keberatan SK Kades", "keywords" => ["Babakanjaya", "Keberatan SK Bupati"]],
            ["name" => "Pemecatan Shin Tae-yong", "search" => "Pemecatan Shin Tae-yong", "keywords" => ["Shin Tae-yong", "Dipecat dari Kursi"]],
            ["name" => "Bazaar Ramadhan", "search" => "Bazaar Ramadhan", "keywords" => ["Bazaar Ramadhan"]],
            ["name" => "HUT PDIP & Pidato", "search" => "HUT PDIP & Pidato", "keywords" => ["HUT ke-52 PDIP", "Pidato Politik"]]
        ];

        $peristiwaCounts = [];
        foreach ($peristiwaCandidates as $cand) {
            $count = 0;
            foreach ($posts as $text) {
                foreach ($cand['keywords'] as $kw) {
                    if (stripos($text, $kw) !== false) {
                        $count++;
                        break;
                    }
                }
            }
            if ($count > 0) {
                $peristiwaCounts[] = [
                    'name' => $cand['name'],
                    'full_name' => $cand['name'],
                    'search' => $cand['search'],
                    'count' => $count
                ];
            }
        }

        usort($peristiwaCounts, function($a, $b) { return $b['count'] <=> $a['count']; });
        $peristiwaTop = array_slice($peristiwaCounts, 0, 8);
        $maxPeristiwaCount = !empty($peristiwaTop) ? $peristiwaTop[0]['count'] : 1;

        $peristiwaData = [];
        foreach ($peristiwaTop as $item) {
            $pct = round(($item['count'] / $totalPosts) * 100, 2);
            $barWidth = round(($item['count'] / $maxPeristiwaCount) * 100);
            if ($barWidth < 12) $barWidth = 12;
            $displayName = strlen($item['name']) > 22 ? substr($item['name'], 0, 20) . '...' : $item['name'];
            $peristiwaData[] = [
                'name' => $displayName,
                'full_name' => $item['full_name'],
                'search' => $item['search'],
                'count' => $item['count'],
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

$sidebarActiveTab = isset($_GET['tab']) ? trim($_GET['tab']) : 'tokoh';
if (!in_array($sidebarActiveTab, ['tokoh', 'peristiwa'])) {
    $sidebarActiveTab = 'tokoh';
}
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
          <a class="nav-link <?php echo ($sidebarActiveTab === 'tokoh') ? 'active' : ''; ?> py-1 px-3" id="tokoh-tab" data-toggle="tab" href="#tokoh" role="tab" aria-controls="tokoh" aria-selected="<?php echo ($sidebarActiveTab === 'tokoh') ? 'true' : 'false'; ?>" style="font-size: 0.85rem;">Tokoh</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($sidebarActiveTab === 'peristiwa') ? 'active' : ''; ?> py-1 px-3" id="peristiwa-tab" data-toggle="tab" href="#peristiwa" role="tab" aria-controls="peristiwa" aria-selected="<?php echo ($sidebarActiveTab === 'peristiwa') ? 'true' : 'false'; ?>" style="font-size: 0.85rem;">Peristiwa</a>
        </li>
      </ul>
      <div class="tab-content" id="trendingTabContent">
        <div class="tab-pane fade <?php echo ($sidebarActiveTab === 'tokoh') ? 'show active' : ''; ?>" id="tokoh" role="tabpanel" aria-labelledby="tokoh-tab">
          <ul class="trending-list mt-3">
            <?php if (!empty($tokohList)) {
              foreach ($tokohList as $item) { ?>
                <li class="d-flex align-items-center mb-2" style="display: flex !important; align-items: center !important; margin-bottom: 10px !important;">
                  <a href="search.php?s=<?php echo urlencode($item['full_name']); ?>&tab=tokoh" 
                     title="Cari berita seputar <?php echo htmlentities($item['full_name']); ?>"
                     class="d-flex align-items-center w-100 text-decoration-none"
                     style="display: flex !important; align-items: center !important; width: 100% !important; text-decoration: none !important; color: inherit !important; padding: 4px 6px; border-radius: 6px;">
                    <div class="trending-name" style="width: 130px !important; min-width: 130px !important; font-size: 0.85rem !important; color: #444 !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; margin-right: 8px !important; text-decoration: none !important;">
                      <?php echo htmlentities($item['name']); ?>
                    </div>
                    <div class="trending-bar-container d-flex align-items-center flex-grow-1" style="display: flex !important; align-items: center !important; flex-grow: 1 !important;">
                      <div class="trending-bar" style="height: 12px !important; background-color: #dc3545 !important; border-radius: 2px !important; margin-right: 10px !important; width: <?php echo $item['bar_width']; ?>% !important;"></div>
                      <span class="trending-value" style="font-size: 0.75rem !important; color: #888 !important; white-space: nowrap !important; text-decoration: none !important;">
                        <?php echo htmlentities($item['percentage']); ?>
                      </span>
                    </div>
                  </a>
                </li>
              <?php }
            } else { ?>
              <li class="text-center text-muted py-3">Belum ada data trending</li>
            <?php } ?>
          </ul>
        </div>
        <div class="tab-pane fade <?php echo ($sidebarActiveTab === 'peristiwa') ? 'show active' : ''; ?>" id="peristiwa" role="tabpanel" aria-labelledby="peristiwa-tab">
          <ul class="trending-list mt-3">
            <?php if (!empty($peristiwaList)) {
              foreach ($peristiwaList as $item) { ?>
                <li class="d-flex align-items-center mb-2" style="display: flex !important; align-items: center !important; margin-bottom: 10px !important;">
                  <a href="search.php?s=<?php echo urlencode($item['full_name']); ?>&tab=peristiwa" 
                     title="Cari berita seputar <?php echo htmlentities($item['full_name']); ?>"
                     class="d-flex align-items-center w-100 text-decoration-none"
                     style="display: flex !important; align-items: center !important; width: 100% !important; text-decoration: none !important; color: inherit !important; padding: 4px 6px; border-radius: 6px;">
                    <div class="trending-name" style="width: 130px !important; min-width: 130px !important; font-size: 0.85rem !important; color: #444 !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; margin-right: 8px !important; text-decoration: none !important;">
                      <?php echo htmlentities($item['name']); ?>
                    </div>
                    <div class="trending-bar-container d-flex align-items-center flex-grow-1" style="display: flex !important; align-items: center !important; flex-grow: 1 !important;">
                      <div class="trending-bar" style="height: 12px !important; background-color: #dc3545 !important; border-radius: 2px !important; margin-right: 10px !important; width: <?php echo $item['bar_width']; ?>% !important;"></div>
                      <span class="trending-value" style="font-size: 0.75rem !important; color: #888 !important; white-space: nowrap !important; text-decoration: none !important;">
                        <?php echo htmlentities($item['percentage']); ?>
                      </span>
                    </div>
                  </a>
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
        $sideQuery = mysqli_query($con,"
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
        if ($sideQuery) {
          while ($sideRow = mysqli_fetch_array($sideQuery)) {
        ?>
          <li class="list-group-item border-0 pt-3 pb-3" style="border-bottom: 1px solid #f0f0f0 !important;">
            <div class="d-flex">
              <img src="admin/uploads/<?php echo htmlentities($sideRow['PostImage']);?>" 
                   class="mr-3 rounded" style="width:100px; height:75px; object-fit:cover;">
              <div class="d-flex flex-column justify-content-between">
                <div>
                  <span class="badge badge-danger mb-1" style="font-size: 0.65rem;">
                    <?php echo htmlentities($sideRow['CategoryName']);?>
                  </span>
                  <a href="news-details.php?nid=<?php echo htmlentities($sideRow['pid'])?>" 
                     class="font-weight-bold d-block text-dark" style="font-size:0.85rem; line-height: 1.3;">
                    <?php echo htmlentities($sideRow['PostTitle']);?>
                  </a>
                </div>
                <small class="text-muted" style="font-size: 0.65rem;">
                  Redaksi Cakrawala | <?php echo date("d M Y", strtotime($sideRow['PostingDate']));?> | <?php echo htmlentities($sideRow['views']);?> Views
                </small>
              </div>
            </div>
          </li>
        <?php 
          }
        }
        ?>
      </ul>
    </div>
  </div>

</div>
