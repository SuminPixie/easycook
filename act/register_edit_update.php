<?php
  session_start(); // 세션 시작

  // 데이터베이스 연결
  include('../inc/dbconn.php');

  // 사용자가 제출한 데이터
  $file = uniqid() . '_' . $_FILES['profile']['name']; //파일이름 중복 방지
  $name = $_POST['name']; // 이름
  $id = $_POST['id']; // 아이디
  $password = $_POST['password']; // 비밀번호
  $phone = $_POST['phone']; // 전화번호
  $email = $_POST['email']; // 이메일(선택적)

  date_default_timezone_set('Asia/Seoul');
  $datetime = date('Y-m-d H:i:s', time());

  // 비밀번호 해시화 처리
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 기존 이미지 파일 경로 가져오기
  $sql_profile = "SELECT profile FROM easycook_register WHERE id = '$id'";
  $result_sql_profile = mysqli_query($conn, $sql_profile);
  $row_sql_profile = mysqli_fetch_assoc($result_sql_profile);
  $profile = $row_sql_profile['profile'];

  $upload_directory = '../uploads/profile/';
  $new_profile_path = $upload_directory . $file;

    // 이미지 파일이 없을 경우 파일 업로드를 제외하고 업데이트
    if (empty($_FILES['profile']['name'])) {
      $sql = "UPDATE easycook_register
              SET name = '$name',
                  password = '$hashed_password',
                  phone = '$phone',
                  email = '$email'
              WHERE id = '$id'";
    } else {
    // 새로운 파일이 업로드되었을 경우
    // 기존 파일이 profile.png가 아닌 경우에만 삭제
    if ($sql_profile !== 'profile.png' && file_exists($upload_directory . $profile)) {
      unlink($upload_directory . $profile);
    }
    // 파일 업로드 처리
    if (move_uploaded_file($_FILES['profile']['tmp_name'], $new_profile_path)) {
      $sql = "UPDATE easycook_register
              SET name = '$name',
                  password = '$hashed_password',
                  phone = '$phone',
                  email = '$email',
                  profile = '$file'
              WHERE id = '$id'";
      } else {
        // 파일 업로드 실패 시 오류 처리
        die("파일 업로드 중 오류가 발생했습니다.");
      }
    }

    // 쿼리 실행
    if (mysqli_query($conn, $sql)) {
      echo "<script>
              alert('회원정보가 수정되었습니다.');
              window.location.href = '../mypage.php';
            </script>";
    } else {
      echo "오류가 발생했습니다: " . mysqli_error($conn);
    }


    // 데이터베이스 연결 종료
    mysqli_close($conn);









?>