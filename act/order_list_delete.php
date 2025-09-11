<?php
  session_start(); // 세션 시작

  // 데이터베이스 연결
  include('../inc/dbconn.php');
  // 세션 ID 확인
  if (!isset($_SESSION['id'])) {
    echo "로그인";
    exit(); // 스크립트 종료
  }


  $class_no = $_POST['class_no'];
  $action = $_POST['action']; // 'remove'
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  date_default_timezone_set('Asia/Seoul');
  $datetime = date('Y-m-d H:i:s', time());

  if ($action == 'remove') {
    // order 테이블에서 삭제
    $sql = "DELETE FROM `easycook_order` WHERE class_no = '$class_no' AND id = '$id'";
    if (mysqli_query($conn, $sql)) {
      echo "성공: 신청이 취소되었습니다.";
    } else {
      echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
    }
  } elseif ($action == 'abandon') {
    // 중도 포기
    $sql = "UPDATE `easycook_order` SET student_status = '중도포기' WHERE class_no = '$class_no' AND id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "성공: 중도 포기 처리되었습니다.";
    } else {
        echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
    }
} else {
    echo "실패: 잘못된 요청입니다.";
}
  // 데이터베이스 연결 종료
  mysqli_close($conn);
?>