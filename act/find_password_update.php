<?php
  session_start();
  include('../inc/dbconn.php');

  // 사용자 ID 가져오기
  $id = $_POST['id'];

  // 입력값 받기
  $password = $_POST['password'];

  // 비밀번호 해싱
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 비밀번호 업데이트 쿼리 작성
  $sql = "UPDATE easycook_register SET password = '$hashed_password' WHERE id = '$id'";
  if (mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'success', 'message' => '비밀번호가 성공적으로 변경되었습니다.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => '비밀번호 변경 실패. 다시 시도해 주세요.']);
  }

  mysqli_close($conn); // 연결 종료
?>
