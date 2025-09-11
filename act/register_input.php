<?php
  session_start(); // 세션 시작

  // 데이터베이스 연결
  include('../inc/dbconn.php');


  // POST 메서드로 데이터가 전송되었는지 확인
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 프로필 이미지를 추가했을때 업로드 처리
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
      $upload_folder = '../uploads/profile/';
      $file_name = uniqid() . '_' . $_FILES['profile']['name']; // 파일 이름 중복 방지
      $file_tmp = $_FILES['profile']['tmp_name'];

      if (move_uploaded_file($file_tmp, $upload_folder . $file_name)) {
        $profile_img = $file_name;
      } else {
        echo "파일 업로드 실패";
        exit; // 업로드 실패 시 종료
      }
    } else {
      // 프로필 이미지를 업로드하지 않은 경우
      $profile_img = 'profile.png'; // 기본 프로필 이미지
    }

    // 사용자가 제출한 데이터
    $name = $_POST['name']; // 이름
    $id = $_POST['id']; // 아이디
    $password = $_POST['password']; // 비밀번호
    $phone = $_POST['phone']; // 전화번호
    $email = $_POST['email']; // 이메일(선택적)

    date_default_timezone_set('Asia/Seoul');
    $datetime = date('Y-m-d H:i:s', time());

    // 비밀번호 해시화 처리
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 강사 코드가 세션에 있는지 확인
    if (isset($_SESSION['teacher_code'])) {
      $teacher_code = $_SESSION['teacher_code'];

      // 강사 코드가 세션에 있으면 강사코드 삽입
      $sql = "INSERT INTO easycook_register (name, id, password, phone, email, profile, datetime, teacher_code)
              VALUES ('$name', '$id', '$hashed_password', '$phone', '$email', '$profile_img', '$datetime', '$teacher_code')";
    } else {
      // 강사 코드가 세션에 없으면 강사코드 삽입하지 않음
      $sql = "INSERT INTO easycook_register (name, id, password, phone, email, profile, datetime)
              VALUES ('$name', '$id', '$hashed_password', '$phone', '$email', '$profile_img', '$datetime')";
    }


    // 쿼리 실행 여부 확인
    if (mysqli_query($conn, $sql)) {
      // 강사코드 세션 변수 삭제
      unset($_SESSION['teacher_code']);
      echo "<script>
              alert('회원가입이 완료되었습니다.');
              window.location.href = '../login.php';
            </script>";
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // 데이터베이스 연결 종료
    mysqli_close($conn);


  }








?>