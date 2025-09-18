<?php
  session_start();
  // 데이터베이스 연결
  include('../inc/dbconn.php');

  $id = trim($_POST['id']);
  $password = trim($_POST['password']);
  
  // if(!$id || !$password) {
  //     echo "<script>alert('아이디나 비밀번호가 공백이면 안됩니다.');</script>";
  //     echo "<script>location.replace('../login.php');</script>";
  //     exit;
  // }

  // 데이터베이스에서 아이디를 조회하여 일치하는지 확인하고 로그인 성공하게끔 한다.
  $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
  $result = mysqli_query($conn, $sql); // 조회한 결과를 변수에 저장
  $row = mysqli_fetch_assoc($result);

  if (!$row) {
    echo "<script>alert('가입된 회원아이디가 아닙니다.')</script>";
    echo "<script>location.replace('../login.php');</script>";
    exit;
  }

  // 입력한 비밀번호와 데이터베이스에 저장된 비밀번호를 확인한다.
  $hashed_password = $row['password'];

  if (!password_verify($password, $hashed_password)) {
    echo "<script>alert('비밀번호가 틀립니다.')</script>";
    echo "<script>location.replace('../login.php');</script>";
    exit;
  }

  // 로그인에 성공할경우
  // session_start();

  $_SESSION['id'] = $id;
  $_SESSION['name'] = $row['name'];
  $_SESSION['profile'] = $row['profile'];
  $_SESSION['teacher_code'] = $row['teacher_code'];

  mysqli_close($conn);


  if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
    // 강사코드가 있다면 강사페이지로 이동한다.
    if (!empty($_SESSION['teacher_code'])) {
        echo "<script>alert('강사페이지로 이동합니다.');</script>";
        echo "<script>location.replace('../admin/index.php');</script>";
    } else { // 강사코드가 없다면 메인페이지로 이동한다.
        echo "<script>alert('로그인 성공');</script>";
        echo "<script>location.replace('../index.php');</script>";
    }
  } else {
    // 로그인 오류
    echo "<script>alert('로그인 오류');</script>";
    echo "<script>location.replace('../login.php');</script>";
  }

?>