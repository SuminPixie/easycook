<?php
  include('../inc/dbconn.php');
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
  }else{
    $id = null;
  }
  include('../inc/dbconn.php');
  $no = $_POST['no'];
  $edit = $_POST['edit'];

  $sql2 = "select * from easycook_question where no = '$no'";
  // $sql = "select * from academy_list where class_no = '14'";
  $result2 = mysqli_query($conn,$sql2);
  $row2 = mysqli_fetch_array($result2);

  // $class_no = $row2['class_no'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>문의 수정</title>
    <!-- 공통 헤드정보 삽입 -->
    <?php include('../inc/head.php'); ?>
    <!-- 서브서식 연결 -->
    <link rel="stylesheet" href="./css/sub.css">
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('../inc/header.php');?>

  <main>
    <section class="in_quire">
      <h2 class="hide"></h2>
      <article>
        <?php if($edit=='수정'){ ?>
          <p class="bread_c">홈&#62; 마이페이지&#62; <b>나의 문의</b></p>
          
          <!-- 수정 페이지 -->
          <h2>문의 수정</h2>
          <form action="./act/inquire_update.php" method="post">
            <input type="hidden" value=" <?php echo $no ?>" name="no">
            <input type="text" value="<?php echo $row2['question'] ?>"  placeholder="<?php echo $row2['question'] ?>" name="question">
            <textarea  name="question_memo" ><?php echo $row2['question_memo'] ?></textarea>

            <!-- 등록 버튼 -->
            <p class="btn-box-l">
              <input type="submit" id="submitBtn" value="수정완료" class="btn-l">
            </p>
          </form>
        <?php }else{ 
          // <!-- 없으면 삭제php -->
          $sql = "delete from easycook_question where no='$no'";
          $result = mysqli_query($conn, $sql);

          if ($result) {
            echo "<script>alert('삭제완료');location.replace('./inquire_list.php') </script>";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
        }?>
      </article>
    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('../inc/bottom.php');?>

</body>
</html>