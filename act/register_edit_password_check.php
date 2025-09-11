<?php
  session_start();  // 세션 시작
  
  // 데이터베이스 연결
  include('../inc/dbconn.php');

  // 사용자 ID가 세션에 저장되어 있는지 확인
  if (!isset($_SESSION['id'])) {
    die('로그인이 필요합니다.');
  }



  $id = $_SESSION['id'];
  $password = trim($_POST['password']);
  
  // 데이터베이스에서 아이디를 조회하여 일치하는지 확인하고 해당 비밀번호 검색
  $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  $db_password = $row['password'];

  if (password_verify($password, $db_password)) {
    echo "비밀번호가 일치합니다.";
  } else {
    echo "비밀번호가 일치하지 않습니다.";
  }


  $conn->close();
?>
