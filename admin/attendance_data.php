<?php
include('./header.php');

// 쿼리 생성
$sql = "SELECT student_name, attendance_date FROM easycook_attendance";

// 쿼리 실행
$result = $conn->query($sql);

// 결과 처리
$attendanceData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentName = $row['student_name'];
        $attendanceDate = $row['attendance_date'];

        // 출석부 데이터 배열에 추가
        if (!isset($attendanceData[$studentName])) {
            $attendanceData[$studentName] = array();
        }
        $attendanceData[$studentName][$attendanceDate] = true; // 날짜가 있으면 true (출석), 없으면 false (결석)
    }
}

// JSON 형식으로 출력
header('Content-Type: application/json');
echo json_encode($attendanceData);

// 연결 종료
$conn->close();
?>
