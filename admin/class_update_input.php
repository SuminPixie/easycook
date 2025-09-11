<?php

include('../include/dbconn.php');

// GET 파라미터 받기
$no = $_GET['no'];
$class_status = $_GET['class_status'];

// 디버깅용 출력
echo $no . "<br>";
echo $class_status . "<br>";

// 날짜 데이터 가져오기
$sql_date = "SELECT * FROM academy_list WHERE class_no = '$no'";
$query_date = mysqli_query($conn, $sql_date);

// 결과 배열을 연관 배열로 가져오기
$result_date = mysqli_fetch_assoc($query_date);

// 컬럼 이름으로 값 참조하기 (예: start_date 컬럼)
echo "<br>날짜는 " . $result_date['start_date']; 

// 날짜 조정
if ($class_status == '1주 미루기') {
    // 1주 미루기
    $start_date = date('Y-m-d', strtotime('+1 week', strtotime($result_date['start_date'])));
    $end_date = date('Y-m-d', strtotime('+1 week', strtotime($result_date['end_date'])));
} else {
    // 1주 당기기
    $start_date = date('Y-m-d', strtotime('-1 week', strtotime($result_date['start_date'])));
    $end_date = date('Y-m-d', strtotime('-1 week', strtotime($result_date['end_date'])));
}

// 상태에 따른 업데이트 쿼리 실행
$sql = "
UPDATE academy_list SET 
    start_date = '$start_date',
    end_date = '$end_date'
WHERE class_no = '$no'
";

// 쿼리 실행
$result = mysqli_query($conn, $sql);

// 결과 확인
if ($result) {
    if ($class_status == '1주 미루기') {
        echo "<script>alert('1주 미루기가 완료되었습니다.');</script>";
    } else {
        echo "<script>alert('1주 당기기가 완료되었습니다.');</script>";
    }
    echo "<script>location.replace('class_1.php');</script>";
} else {
    echo "글 입력 실패 : " . mysqli_error($conn);
}

?>
