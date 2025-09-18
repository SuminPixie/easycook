<?php
include('../inc/dbconn.php');

// class_no를 URL 파라미터로 받기
$class_no = $_GET['class_no'];
$selected_date = $_GET['selected_date']; // 선택한 날짜
$room = $_GET['room_no']; // 선택한 실습실

// 시작 시간과 종료 시간의 배열 설정 (예시)
$time_slots = [
  '08:00-09:00' => ['start' => '08:00:00', 'end' => '09:00:00'],
  '09:00-10:00' => ['start' => '09:00:00', 'end' => '10:00:00'],
  '10:00-11:00' => ['start' => '10:00:00', 'end' => '11:00:00'],
  '11:00-12:00' => ['start' => '11:00:00', 'end' => '12:00:00'],
  '12:00-13:00' => ['start' => '12:00:00', 'end' => '13:00:00'],
  '13:00-14:00' => ['start' => '13:00:00', 'end' => '14:00:00'],
  '14:00-15:00' => ['start' => '14:00:00', 'end' => '15:00:00'],
  '15:00-16:00' => ['start' => '15:00:00', 'end' => '16:00:00'],
  '16:00-17:00' => ['start' => '16:00:00', 'end' => '17:00:00'],
  // 추가 시간대 설정
];

// 예약 정보를 저장할 배열
$reservations = [];

// 각 시간대에 대해 예약 현황 조회
foreach ($time_slots as $label => $times) {
  $start_time = $times['start'];
  $end_time = $times['end'];
  
  $sql = "
      SELECT COUNT(*) AS reserved_count 
      FROM easycook_room 
      WHERE room = '$room' 
      AND room_date = '$selected_date' 
      AND start >= '$start_time' 
      AND end <= '$end_time'
  ";
  $result = mysqli_query($conn, $sql);
  
  $row = mysqli_fetch_assoc($result);
  $reservations[$label] = $row['reserved_count'];
}

// JSON으로 결과 반환
echo json_encode($reservations);

mysqli_close($conn);
?>
