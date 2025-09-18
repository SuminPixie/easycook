<?php
include('../inc/dbconn.php');

$select_time = $_POST['select_time'] ?? '';
if ($select_time === '') {
  echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>날짜가 없습니다</div>";
  exit;
}

// 각 실습실의 “시간대별 예약 인원 수” 결과
$sql = "SELECT start, COUNT(*) AS cnt 
        FROM easycook_room 
        WHERE room_date = ? AND room = ? 
        GROUP BY start 
        ORDER BY start";

$stmtCnt = $conn->prepare($sql);

// 렌더 함수 ---------------------------------------------------------
function renderRoomSection($conn, $room, $select_time, $stmtCnt, $baseHour = 9, $capacity = 8) {
  // 집계 결과 가져오기
  $stmtCnt->bind_param("ss", $select_time, $room);
  $stmtCnt->execute();
  $resCnt = $stmtCnt->get_result();

  echo '<div class="mb-3 admin_reserve_con">';
  echo "<p>실습실 {$room}호</p>";
  echo '<div><p>사용시간</p><p>예약현황</p></div>';

  if ($resCnt && $resCnt->num_rows > 0) {
    // 상세 목록 쿼리(해당 슬롯의 예약자)
    $stmtDetail = $conn->prepare(
      "SELECT * FROM easycook_room 
       WHERE `start` = ? AND room_date = ? AND room = ?
       ORDER BY `start`"
    );

    while ($row = $resCnt->fetch_array(MYSQLI_NUM)) {
      // $row[0] = start 슬롯 인덱스(0부터), $row[1] = 인원 수
      $idx   = (int)($row[0] ?? 0);
      $cnt   = (int)($row[1] ?? 0);
      $hour  = ($baseHour + $idx) % 24;
      $start = sprintf('%02d:00', $hour);
      $end   = sprintf('%02d:00', ($hour + 1) % 24);
      $inputId = "room{$room}_time{$hour}";

      echo '<div class="reserve_time">';
      echo "  <input type=\"checkbox\" id=\"{$inputId}\">";
      echo "  <label for=\"{$inputId}\">";
      echo "    <ul>";
      echo "      <li>{$start} ~ {$end}</li>";
      echo "      <li><span style=\"color:var(--red); font-weight:bold;\">{$cnt}</span> / {$capacity}</li>";
      echo "    </ul>";
      echo "  </label>";

      // 상세(이름/식별자 등) 표시 — 기존 사용 인덱스 [7], [6] 유지
      $stmtDetail->bind_param("iss", $idx, $select_time, $room);
      $stmtDetail->execute();
      $resDetail = $stmtDetail->get_result();

      echo '  <div class="reserve_p"><ul>';
      while ($db3 = $resDetail->fetch_array(MYSQLI_NUM)) {
        // 필요에 맞게 컬럼 인덱스 조정 (예: [7]=이름, [6]=식별자)
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
