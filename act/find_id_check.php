<?php
  // 데이터베이스 연결
  include('../inc/dbconn.php');

  // 입력값 받기
  $name = $_POST['name'];
  $phone = $_POST['phone'];

  // SQL 쿼리 작성
  $sql = "SELECT id FROM easycook_register WHERE name = '$name' AND phone = '$phone'";
  $result = mysqli_query($conn, $sql);


  if ($result) {
    if ($row = mysqli_fetch_assoc($result)) {
      // 일치하는 ID 반환
      echo json_encode(['status' => 'success', 'id' => $row['id']]);
    } else {
      // 일치하는 정보 없음
      echo json_encode(['status' => 'error', 'message' => '일치하는 정보가 없습니다.']);
    }
  } else {
    // SQL 쿼리 오류 처리
    echo json_encode(['status' => 'error', 'message' => '쿼리 실행 오류']);
  }

  mysqli_close($conn);
?>