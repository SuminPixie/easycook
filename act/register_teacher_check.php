<?php
  session_start();  // 세션 시작
  
  // 데이터베이스 연결
  include('./include/dbconn.php');

  $teacher_code = trim($_POST['teacher_code']);

  // 데이터베이스에서 검색
  $sql = "SELECT * FROM teacher_list WHERE teacher_code = '$teacher_code'";
  $result = mysqli_query($conn, $sql);

  if ($result->num_rows == 0) {
    // teacher_code가 데이터베이스에 없는 경우
    echo "사번이 일치하지 않습니다.";
  } else {
    // teacher_code가 데이터베이스에 있는 경우
    $_SESSION['teacher_code'] = $teacher_code;  // 세션 변수에 저장
    echo "사번이 일치합니다.";
  }

  $conn->close();
?>
