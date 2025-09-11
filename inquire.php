<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
  }else{
    $id = null;
  }
  include('./php/include/dbconn.php');
  $class_no = $_GET['class_no'];

  $sql = "select * from academy_list where class_no = '$class_no'";
  // $sql = "select * from academy_list where class_no = '14'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 문의하기</title>
    <!-- 공통 헤드정보 삽입 -->
    <?php include('./php/include/head.php'); ?>
    <!-- 서브서식 연결 -->
    <link rel="stylesheet" href="./css/sub.css">
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header_sub.php');?>

  <main>
    <section class="in_quire">
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b>문의하기</b>
      </p>

      <h2 class="hide">문의 하기</h2>
      <article>
        <h2>문의 하기</h2>
        <form action="./php/inquire_input.php" method="post">
          <input type="hidden" value=" <?php echo $class_no ?>" name="class_no">
          <input type="hidden" value=" <?php echo $id ?>" name="id">
          <input type="text"  placeholder="제목을 입력해주세요" name="question">
          <textarea name="question_memo"  placeholder="질문 내용을 입력해주세요"></textarea>

          <!-- 등록 버튼 -->
          <p class="btn-box-l">
            <input type="submit" id="submitBtn" value="작성완료" class="btn-l">
          </p>
        </form>
      </article>
    </section>
  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./php/include/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>

</body>
</html>