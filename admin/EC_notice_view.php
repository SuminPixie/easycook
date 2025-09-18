<!-- 나의 강의실 중 전체강의 -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 학원소식</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 학원 &#x003E; <span style="font-weight:bold">학원소식</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">학원소식</h2>

    <!-- 학원소식 출력 -->
    <article class="class_1">
      <h3>학원소식</h3>
      <table class="table">
        <caption>학원소식</caption>
        <?php 
        $no = $_GET['no'];
        $sql = "select * from easycook_notice where no='$no';";
        $result = mysqli_query($conn, $sql);              
        $db=mysqli_fetch_array($result);
        ?>
        <tr style="border-top: 3px solid black; line-height:50px;">
          <th>
            <p><?php echo $db[1];?></p>
            <p><?php echo date("Y.m.d", strtotime($db[3])); ?></p>
          </th>
        </tr>
        <tr style="border-top: 1px solid black;">
          <td>
            <?php echo $db[2];?>
          </td>
        </tr>
      </table>
      <!-- 목록으로 -->
      <a href="./EC_notice.php" title="목록으로" class="admin_btn admin_btn_red">목록으로</a>
    </article>
    <div class="copyright">
      Copyright ⓒ Easy Cook, All Rights Reserved.
    </div>
  </section>
<?php
include('./footer.php');
?>
</body>
</html>
