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
  $action = $_POST['action']; // 'add' 또는 'remove'
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  date_default_timezone_set('Asia/Seoul');
  $datetime = date('Y-m-d H:i:s', time());

  if ($action == 'add') {
    // 장바구니에 추가
    $sql = "INSERT INTO easycook_cart (class_no, id, datetime) VALUES ('$class_no', '$id', '$datetime')";
    if (mysqli_query($conn, $sql)) {
      echo "성공: 장바구니에 추가되었습니다.";
    } else {
      echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
    }
  } else if ($action == 'remove') {
    // 장바구니에서 삭제
    $sql = "DELETE FROM easycook_cart WHERE class_no = '$class_no' AND id = '$id'";
    if (mysqli_query($conn, $sql)) {
      echo "성공: 장바구니에서 삭제되었습니다.";
    } else {
      echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
    }
  } else {
    echo "실패: 잘못된 요청입니다.";
  }
  // 데이터베이스 연결 종료
  mysqli_close($conn);
?>