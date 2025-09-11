<?php
  session_start(); // 세션 시작

  // 데이터베이스 연결
  include('../inc/dbconn.php');
  // 세션 ID 확인
  if (!isset($_SESSION['id'])) {
    echo "로그인";
    exit(); // 스크립트 종료
  }


  $no = $_POST['no'];
  $action = $_POST['action']; // 'remove'
  $id = $_SESSION['id'];

  if ($action == 'remove') {
    $sql = "DELETE FROM room WHERE no = '$no' AND id = '$id'";
    if (mysqli_query($conn, $sql)) {
      echo "성공: 신청이 취소되었습니다.";
    } else {
      echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
    }
  } else {
    echo "실패: 잘못된 요청입니다.";
  }
  // 데이터베이스 연결 종료
  mysqli_close($conn);
?>