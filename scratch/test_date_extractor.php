<?php
$_SERVER['HTTP_HOST'] = 'localhost';
include('includes/config.php');

if (!function_exists('getEventDateFromText')) {
    function getEventDateFromText($postDetails, $postingDate) {
        $text = strip_tags($postDetails);
        
        $monthsMap = [
            'januari' => 'Januari', 'februari' => 'Februari', 'maret' => 'Maret',
            'april' => 'April', 'mei' => 'Mei', 'juni' => 'Juni',
            'juli' => 'Juli', 'agustus' => 'Agustus', 'september' => 'September',
            'oktober' => 'Oktober', 'november' => 'November', 'desember' => 'Desember',
            'jan' => 'Januari', 'feb' => 'Februari', 'mar' => 'Maret',
            'apr' => 'April', 'jun' => 'Juni', 'jul' => 'Juli',
            'agu' => 'Agustus', 'sep' => 'September', 'okt' => 'Oktober',
            'nov' => 'November', 'des' => 'Desember',
            'january' => 'Januari', 'february' => 'Februari', 'march' => 'Maret',
            'june' => 'Juni', 'july' => 'Juli', 'august' => 'Agustus',
            'october' => 'Oktober', 'december' => 'Desember'
        ];

        $monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $dayRegex = 'Senin|Selasa|Rabu|Kamis|Jumat|Jum\'at|Sabtu|Minggu';
        $monthRegex = 'Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember|Jan|Feb|Mar|Apr|Jun|Jul|Agu|Sep|Okt|Nov|Des|January|February|March|June|July|August|October|December';

        $postYear = date('Y', strtotime($postingDate));

        // 1. Pattern: Hari (dd/mm/yyyy) or Hari, (dd/mm/yy)
        if (preg_match('/(?:(' . $dayRegex . '),?\s*)?\(?\s*(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})\s*\)?/i', $text, $m)) {
            $dayNum = (int)$m[2];
            $monthNum = (int)$m[3];
            $yearNum = strlen($m[4]) == 2 ? '20' . $m[4] : $m[4];
            
            if ($monthNum >= 1 && $monthNum <= 12 && $dayNum >= 1 && $dayNum <= 31) {
                $dayName = !empty($m[1]) ? ucfirst(strtolower($m[1])) . ', ' : '';
                return $dayName . $dayNum . ' ' . $monthNames[$monthNum] . ' ' . $yearNum;
            }
        }

        // 2. Pattern: Hari, dd NamaBulan yyyy
        if (preg_match('/(?:(' . $dayRegex . '),?\s*)?(\d{1,2})\s+(' . $monthRegex . ')\s+(\d{4})/i', $text, $m)) {
            $dayName = !empty($m[1]) ? ucfirst(strtolower($m[1])) . ', ' : '';
            $mKey = strtolower($m[3]);
            $mName = isset($monthsMap[$mKey]) ? $monthsMap[$mKey] : ucfirst($mKey);
            return $dayName . (int)$m[2] . ' ' . $mName . ' ' . $m[4];
        }

        // 3. Pattern: Hari (dd/mm) without year
        if (preg_match('/(?:(' . $dayRegex . '),?\s*)?\(?\s*(\d{1,2})[\/\-](\d{1,2})\s*\)?/i', $text, $m)) {
            $dayNum = (int)$m[2];
            $monthNum = (int)$m[3];
            if ($monthNum >= 1 && $monthNum <= 12 && $dayNum >= 1 && $dayNum <= 31) {
                $dayName = !empty($m[1]) ? ucfirst(strtolower($m[1])) . ', ' : '';
                return $dayName . $dayNum . ' ' . $monthNames[$monthNum] . ' ' . $postYear;
            }
        }

        // 4. Pattern: Kota, NamaBulan yyyy
        if (preg_match('/([A-Z][a-z]+),\s+(' . $monthRegex . ')\s+(\d{4})/i', $text, $m)) {
            $mKey = strtolower($m[2]);
            $mName = isset($monthsMap[$mKey]) ? $monthsMap[$mKey] : ucfirst($mKey);
            return $mName . ' ' . $m[3];
        }

        // Fallback: Format PostingDate
        $pTime = strtotime($postingDate);
        $pDay = date('j', $pTime);
        $pMonth = (int)date('n', $pTime);
        $pYear = date('Y', $pTime);
        return $pDay . ' ' . $monthNames[$pMonth] . ' ' . $pYear;
    }
}

$res = mysqli_query($con, 'SELECT id, PostTitle, PostingDate, PostDetails FROM tblposts ORDER BY id DESC LIMIT 10');
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . "\n";
    echo "PostDate : " . date('d M Y', strtotime($row['PostingDate'])) . "\n";
    echo "EventDate: " . getEventDateFromText($row['PostDetails'], $row['PostingDate']) . "\n";
    echo "----------------------------------------\n";
}
