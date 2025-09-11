<?php
  session_start(); // 세션 시작

  // 데이터베이스 연결
  include('../inc/dbconn.php');
  // 세션 ID 확인
  if (!isset($_SESSION['id'])) {
    echo "<script>
          alert('로그인이 필요합니다.');
          </script>";
    exit(); // 스크립트 종료
  }


  $class_no = $_GET['class_no'];
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  date_default_timezone_set('Asia/Seoul');
  $datetime = date('Y-m-d H:i:s', time());


  // 1. 신청한 강의의 teacher_code 조회
  $teacher_code_sql = "SELECT teacher_code FROM easycook_academy_list WHERE class_no = '$class_no'";
  $teacher_code_result = mysqli_query($conn, $teacher_code_sql);
  $teacher_code_row = mysqli_fetch_assoc($teacher_code_result);
  $teacher_code = $teacher_code_row['teacher_code'];


  // 2. 동일한 class_no가 있는지 확인
  $check_sql = "SELECT * FROM `easycook_order` WHERE class_no = '$class_no' AND id = '$id'";
  $result = mysqli_query($conn, $check_sql);

  if (mysqli_num_rows($result) > 0) {
    // 이미 신청한 강의인 경우
    echo "<script>
          alert('이미 신청한 강의입니다.');
          window.location.href = '../order_list.php';
          </script>";
  } else {
    // order 테이블에 데이터 추가
    $sql = "INSERT INTO `easycook_order` (class_no, id, name, teacher_code, datetime, student_status) VALUES ('$class_no', '$id', '$name', '$teacher_code', '$datetime', '수강중')";
    if (mysqli_query($conn, $sql)) {
      echo "<script>
            window.location.href = '../order_complete.php';
            </script>";
    } else {
      echo "<script>
            alert('결제실패 : " . mysqli_error($conn) . "');
            history.back();
            </script>";
    }
  }
  // 데이터베이스 연결 종료
  mysqli_close($conn);
?>