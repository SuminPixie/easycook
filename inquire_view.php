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
  $no = $_GET['no'];

  $sql2 = "select * from question where no = '$no'";
  // $sql = "select * from academy_list where class_no = '14'";
  $result2 = mysqli_query($conn,$sql2);
  $row2 = mysqli_fetch_array($result2);

  $class_no = $row2['class_no'];


  $sql = "select * from academy_list where class_no = '$class_no'";
  // $sql = "select * from academy_list where class_no = '14'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡</title>
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
        <b><a href="./inquire_list.php" title="나의 문의">나의 문의</a></b>
      </p>
      
      <h2>문의 보기</h2>
      
      <?php if($row2{'answer'} == '' ){ ?>
        <!--답변이 없다면-->
        <article class="inq_view ina">
          <ul>
            <li>
              <!-- <p>NO. <?php echo $row2['no'] ?></p> -->
              <p><?php echo $row['name'] ?></p>
            </li>
            <li><?php echo $row2['question'] ?></li>
            <li>
              <p><?php echo $row2['question_id'] ?></p>
              <p><?php echo $row2['question_time'] ?></p>
            </li>
          </ul>
          <p><?php echo nl2br($row2['question_memo']) ?></p>
        </article>
        <!--수정 삭제 버튼-->
        <form action="./inquire_edit.php" method="post" class="inq_edit">
          <p class="btn-box-l">
            <div class="btn-box-s mt-4">
              <input type="hidden" value="<?php echo $row2['no'] ?>" name="no" >
              <input type="submit" value="수정" name="edit" class="btn-s ina1">
              <input type="submit" value="삭제" class="btn-s ">
            </div>
          </p>
  
          <p class="btn-box-l edit ">
            <a href="./inquire_list.php" title="나의 문의로 이동" id="submitBtn" class="btn-l">목록으로</a>
          </p>
        </form>


      <?php }else { ?>
        <!--답변이 있다면-->
        <article class="inq_view inb">
          <ul>
            <li>
              <!-- <p>NO. <?php echo $row2['no'] ?></p> -->
              <p><?php echo $row['name'] ?></p>
            </li>
            <li><?php echo $row2['question'] ?></li>
            <li>
              <p><?php echo $row2['question_id'] ?></p>
              <p><?php echo $row2['question_time'] ?></p>
            </li>
          </ul>
          <p><?php echo nl2br($row2['question_memo']) ?></p>
        </article>
  
        <!--답변 달리는곳-->
        <article class="answer_b">
          <span style="background: var(--yellow); width:50px; height: 24px; display: block; text-align: center;line-height: 24px; border-radius: 5px; margin-bottom: 10px;">답변</span>
          <p><?php echo nl2br($row2['answer']) ?></p>
        </article>
        <!-- 목록으로 버튼 -->
        <p class="btn-box-l ina2">
        <a href="./inquire_list.php" title="나의 문의로 이동" id="submitBtn" class="btn-l">목록으로</a>
        </p>
      <?php } ?>

    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>

</body>
</html>