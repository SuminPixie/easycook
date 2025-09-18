<?php
  // 데이터베이스 연결
  include('../inc/dbconn.php');
  // 세션 ID 확인
  if (!isset($_SESSION['id'])) {
    echo "로그인이 필요합니다.";
    exit(); // 스크립트 종료
  }
  $no = $_GET['no'];
  if (!$no) { echo '잘못된 요청입니다.'; exit; }
  $id = $_SESSION['id'];


  $sql = "DELETE FROM easycook_review WHERE no = '$no' AND id = '$id'";
  if (mysqli_query($conn, $sql)) {
    echo "<script>
    alert('리뷰가 삭제되었습니다.');
    location.replace('../review_list.php');
    </script>";
  } else {
    echo "실패: 오류가 발생했습니다: " . mysqli_error($conn);
  }

  // 데이터베이스 연결 종료
  mysqli_close($conn);
?>