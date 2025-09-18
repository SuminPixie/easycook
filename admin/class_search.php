<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>나의 강의실 | 이지쿡</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">

    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; <span style="font-weight:bold">나의 강의실</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-5">나의 강의실</h2>

    <?php
      // 아이디와 일치하는 강사코드 받아오기
      $sql_t = "select * from easycook_register where id='$s_id';";
      $result_t = mysqli_query($conn, $sql_t);
      $t = mysqli_fetch_array($result_t);// echo "선생님 코드".$t[7];
      $teacher_code = $t['teacher_code'];// echo "선생님 코드".$teacher_code;

      //입력한  search 값 받아오고
      $search = $_POST['search'];    // echo $search;

      //입력한 값이랑 데이터 값을 비교한다
      $sql = "select * from easycook_academy_list where 
      (name like '%".$search."%' or category1 like '%".$search."%' or category2 like '%".$search."%'or category3 like '%".$search."%')
      and teacher_code='$teacher_code';";
      $result = mysqli_query($conn, $sql);
  ?>

    <article class="class_1">
      <h3>나의 강의실</h3>
      <!-- 내용 -->
      <div class="mt-4 mb-2" id="content">
        <div id="container">
          <table class="table table-hover table-responsive">
            <caption>Q&A 목록</caption>
            <thead class="text-center">
              <tr style="border-top: 3px solid black; line-height:50px;">
                <th>No.</th>
                <th>강의명</th>
                <th>상태</th>
              </tr>
            </thead>    
            <tbody>
              <!-- 전체강의 보기 -->
              <?php 
                while($db=mysqli_fetch_array($result)){   ?>
                  <tr>
                    <td>
                      <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><?php echo $db['0'];?></a>
                    </td>
                    <td>
                      <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로">[<?php echo $db['3'];?>][<?php echo  $db['4'];?>][<?php echo  $db['5'];?>]<?php echo  $db['1'];?>                    
                      <?php 
                      $class_no_this =$db['0'];
                      $sql_student = "select count(*) from `easycook_order` where class_no  =$class_no_this";
                      $result_student = mysqli_query($conn, $sql_student);
                      $row = mysqli_fetch_array($result_student);
                      echo  "<span style=color:var(--red);'>(".$row[0]."명)</span>";
                    ?>
                      </a>
                    </td>
  
                    <?php 
                    $today = (new DateTime())->format('Y-m-d');
                    $today2 = new DateTime();                  
                    $startDate = new DateTime($db['13']);
                    $endDate = new DateTime($db['14']);
                    if($db['13'] <= $today and $db['14'] >= $today){
                      ?>
                      <td class="text-center">
                        <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title green">현재강의<span></a>
                      </td>
                    <?php }elseif ($startDate > $today){
                        $startDate = new DateTime($db['13']);
                        $interval = $today2->diff($startDate); // 날짜 간의 차이 계산
                        $daysRemaining = $interval->days; // 남은 일수
                    ?> 
                      <td class="text-center">
                        <?php if ($daysRemaining <= 7) { ?>
                          <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title red">임박강의</span>
                            <span class="status_detail">D - <?php echo $daysRemaining; ?></span></a>
                        <?php }else{ ?>
                          <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title yellow">예정강의</span>
                          <?php }?>
                      </td>
                    <?php }else{ ?> 
                      <td class="text-center">
                        <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title darkbrown">지난강의</span></a>
                    </td>
                    <?php }; ?> 
                  </tr>
                  <?php }; ?> 
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="mt-5 mb-3" style="position:relative;">
        <a href="./class_1.php" title="다시 검색하기" class="admin_btn admin_btn_yellow position_l_b">다시 검색</a>
      </div>

    </article>
  </section>
<?php
include('./footer.php');
?>
</body>
</html>
