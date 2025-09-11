<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 학생관리</title>
  <?php
    include('header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">

    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; <span style="font-weight:bold">학생관리</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">나의 학생 리스트</h2>

    <!-- 내용 -->
    <article class="mt-2 mb-2">
      <table class="table table-striped">
        <thead>
          <tr class="bold_line line50 text-center">
            <th class="display_none">No.</th>
            <th>아이디</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // echo $s_id;
            $sql2 = "select * from register where id='$s_id'";
            $result2 = mysqli_query($conn, $sql2); 
            $db2 = mysqli_fetch_array($result2);     //echo $db2[7];

            $count = 0;

            $sql ="SELECT id FROM `order` WHERE teacher_code = '$db2[7]' GROUP BY id";
            $result = mysqli_query($conn, $sql);
            while($db = mysqli_fetch_array($result)){
              $count ++;
          ?>
          <tr>
            <td class="display_none"><?php echo $count;?></td>
            <td><?php echo $db[0];?></td>
            <td style="width: 80%">
              <ul style="display: flex;justify-content: left;flex-wrap: wrap;">
                <?php
                $sql_class ="SELECT * FROM `order` WHERE teacher_code = '$db2[7]' and id='$db[0]' order by class_no;  ";
                $re_class = mysqli_query($conn, $sql_class);
                while($db_class = mysqli_fetch_array($re_class)){

                  $sql_class2 = "select * from academy_list where class_no = '$db_class[1]'";
                  $re_class2 = mysqli_query($conn, $sql_class2);
                  $db_class2 = mysqli_fetch_array($re_class2);
                ?>
                <li style="width: 240px; border: 1px solid var(--gray); border-radius: 5px; margin: 2px; padding: 0 10px;">
                    <a href="student.php?class_no=<?php echo $db_class[1];?>" title="">[<?php echo $db_class[1]."] ".$db_class2[1];?>
                  <?php 
                  if($db_class[5] == '중도포기'){
                    echo "( <b> ".$db_class[5]." </b>)";
                  }else{}  
                  ?>
                  </a>
                </li>
                <?php }?>
              </ul>
            </td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <table class="table table-striped">
        <thead>
          <tr class="bold_line line50 text-center">
            <th class="display_none">No.</th>
            <th style="width: 80%">강의명</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // echo $s_id;
            $sql2 = "select * from register where id='$s_id'";
            $result2 = mysqli_query($conn, $sql2); 
            $db2 = mysqli_fetch_array($result2);     //echo $db2[7];

            $count = 0;

            $sql ="SELECT id FROM `order` WHERE teacher_code = '$db2[7]' GROUP BY id";
            $result = mysqli_query($conn, $sql);
            while($db = mysqli_fetch_array($result)){
              $count ++;
          ?>
          <tr>
            <td class="display_none"><?php echo $count;?></td>
            <td style="width: 80%">
              <ul style="display: flex;justify-content: left;flex-wrap: wrap;">
                <?php
                $sql_class ="SELECT * FROM `order` WHERE teacher_code = '$db2[7]' and id='$db[0]' order by class_no;  ";
                $re_class = mysqli_query($conn, $sql_class);
                while($db_class = mysqli_fetch_array($re_class)){

                  $sql_class2 = "select * from academy_list where class_no = '$db_class[1]'";
                  $re_class2 = mysqli_query($conn, $sql_class2);
                  $db_class2 = mysqli_fetch_array($re_class2);
                ?>
                <li style="width: 240px; border: 1px solid var(--gray); border-radius: 5px; margin: 2px; padding: 0 10px;">
                    <a href="student.php?class_no=<?php echo $db_class[1];?>" title="">[<?php echo $db_class[1]."] ".$db_class2[1];?>
                  <?php 
                  if($db_class[5] == '중도포기'){
                    echo "( <b> ".$db_class[5]." </b>)";
                  }else{}  
                  ?>
                  </a>
                </li>
                <?php }?>
              </ul>
            </td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="mt-5 mb-3" style="position:relative;">
          <!-- 목록으로 -->
          <a href="class_1.php" title="목록으로" class="admin_btn admin_btn_yellow position_l_b">강의목록</a>
      </div>
    </article>
  </section>
<?php include('footer.php'); ?>
</body>
</html>