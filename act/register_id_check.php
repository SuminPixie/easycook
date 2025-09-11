<?php
  // 데이터베이스 연결
  include('./include/dbconn.php');

  // POST 데이터 가져오기
  $id = $_POST['id'];

  // 아이디 중복 확인 쿼리
  $sql = "SELECT * FROM register WHERE id = '$id'";
  $result = $conn->query($sql);

  // 중복된 아이디가 존재하는 경우
  if ($result->num_rows > 0) {
    echo '사용중';
  } else {
    echo '사용가능';
  }

  // 데이터베이스 연결 종료
  $conn->close();
?>