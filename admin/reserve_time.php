<?php
include('../inc/dbconn.php');
header('Content-Type: text/html; charset=utf-8');

$select_time = $_POST['select_time'] ?? '';
if ($select_time === '') {
  echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>날짜가 없습니다</div>";
  exit;
}

/**
 * start 값에서 '시'만 추출하는 헬퍼
 * - "13:00" / "13:00:00" -> 13
 * - "13시" -> 13
 * - 숫자 9~23 -> 그 숫자 그대로(시각)
 * - 숫자 0~8  -> 슬롯 인덱스로 해석하여 baseHour(9) + idx
 * - 기타 문자열 -> strtotime 시도
 */
function ec_extract_hour($raw, $baseHour = 9) {
  $s = trim((string)($raw ?? ''));
  if ($s === '') return 0;

  // "13:00" or "13:00:00"
  if (strpos($s, ':') !== false) {
    $parts = explode(':', $s, 2);
    return max(0, min(23, (int)trim($parts[0])));
  }

  // "13시"
  if (preg_match('/^\s*(\d{1,2})\s*시/u', $s, $m)) {
    return max(0, min(23, (int)$m[1]));
  }

  // pure number
  if (is_numeric($s)) {
    $n = (int)$s;
    if ($n >= 0 && $n <= 8) {               // 슬롯 인덱스(0..8)로 간주
      return ($baseHour + $n) % 24;         // 0->09, 1->10, ...
    }
    return max(0, min(23, $n));             // 9~23은 그 자체로 시각
  }

  // fallback: strtotime
  $ts = strtotime($s);
  if ($ts !== false) return (int)date('H', $ts);

  return 0;
}

// 각 실습실의 “시간대별 예약 인원 수”
$sql = "SELECT `start` AS s, COUNT(*) AS cnt
        FROM easycook_room
        WHERE room_date = ? AND room = ?
        GROUP BY `start`
        ORDER BY `start`";
$stmtCnt = $conn->prepare($sql);

// 렌더 함수 ---------------------------------------------------------
function renderRoomSection($conn, $room, $select_time, $stmtCnt, $baseHour = 9, $capacity = 8) {
  // 집계 결과 가져오기
  $stmtCnt->bind_param("ss", $select_time, $room);
  $stmtCnt->execute();
  $resCnt = $stmtCnt->get_result();

  echo '<div class="mb-3 admin_reserve_con">';
  echo "<p>실습실 ".htmlspecialchars($room)."호</p>";
  echo '<div><p>사용시간</p><p>예약현황</p></div>';

  if ($resCnt && $resCnt->num_rows > 0) {
    // 상세 목록 쿼리(해당 슬롯의 예약자)
    $stmtDetail = $conn->prepare(
      "SELECT * FROM easycook_room 
       WHERE `start` = ? AND room_date = ? AND room = ?
       ORDER BY `start`"
    );

    while ($row = $resCnt->fetch_assoc()) {
      $startVal = $row['s'];                   // 원본 start 값(문자열/숫자 혼용 가능)
      $cnt      = (int)($row['cnt'] ?? 0);

      $h = ec_extract_hour($startVal, $baseHour);
      $start = sprintf('%02d:00', $h);
      $end   = sprintf('%02d:00', ($h + 1) % 24);

      // 체크박스 id: 방번호 + 원본 start + 계산된 hour 조합으로 유일하게
      $timeId = 'room'.$room.'_'.preg_replace('/[^0-9a-zA-Z]/', '', (string)$startVal).'_'.$h;

      echo '<div class="reserve_time">';
      echo '  <input type="checkbox" id="'.htmlspecialchars($timeId).'">';
      echo '  <label for="'.htmlspecialchars($timeId).'">';
      echo '    <ul>';
      echo '      <li>'.$start.' ~ '.$end.'</li>';
      echo '      <li><span style="color:var(--red); font-weight:bold;">'.$cnt.'</span> / '.$capacity.'</li>';
      echo '    </ul>';
      echo '  </label>';

      // 상세(이름/식별자 등) 표시 — 기존 사용 인덱스 [7], [6] 유지
      $stmtDetail->bind_param("sss", $startVal, $select_time, $room);
      $stmtDetail->execute();
      $resDetail = $stmtDetail->get_result();

      echo '  <div class="reserve_p"><ul>';
      while ($db3 = $resDetail->fetch_array(MYSQLI_NUM)) {
        $name = htmlspecialchars($db3[7] ?? '');
        $idno = htmlspecialchars($db3[6] ?? '');
        echo "<li>{$name} ({$idno})</li>";
      }
      echo '  </ul></div>';
      echo '</div>';
    }
    $stmtDetail->close();
  } else {
    echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>예약내역이 없습니다</div>";
  }

  echo '</div>'; // .admin_reserve_con
}
// ---------------------------------------------------------------

echo '<div class="admin_reserve">';
renderRoomSection($conn, '101', $select_time, $stmtCnt);  // 실습실 101호
renderRoomSection($conn, '102', $select_time, $stmtCnt);  // 실습실 102호
echo '</div>';

$stmtCnt->close();
