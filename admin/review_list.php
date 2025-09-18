<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 수강생 후기</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    
    <!-- 검색영역 -->
    <form name="검색하기" method="post" action="./review_search.php" onsubmit="return form_check()">
    
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 학생 관리 &#x003E; <span style="font-weight:bold">수강생 후기</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">나의 수강생 후기</h2>
    <p class="text-center mt-4 mb-4">최신 후기 50개 중 나의 후기만을 보여드립니다.</p>

        <!-- 검색버튼 -->
        <div class="mb-2 admin_search_box">
          <input type="search" placeholder="리뷰 내용+점수로 찾기" aria-label="리뷰 내용+점수로 찾기" class="admin_search" id="search" name="search">
          <button aria-label="검색" class="btn" type="submit" id="button-addon2" style="border: none;"><i class="bi bi-search"></i></button>        
        </div> 
        <script> // 검색어 유효성 검사
          function form_check(){
          //alert('검색어를 입력하지 않았습니다.')
          if(document.getElementById('search').value.length<1){
          alert('검색어를 입력하지 않았습니다. 확인하세요');
          return false;
          }
          return true;
          }
        </script>
      <article class="mt-5" >
        <h3>리뷰</h3>
        <!-- 후기 데이터 부르기 -->
        <?php
        //아이디를 찾아라
        $s_id = $_SESSION['id'];   
    
        //나의 강사코드를 찾아라
        $query = "select * from easycook_register where id='$s_id'";
        $result = mysqli_query($conn, $query);
        $class = mysqli_fetch_array($result);        // echo '강사코드'.$class[7];
        ?>
    
        <table class="table table-hover table-responsive m-0" id="review_table">
          <caption>나의 수강생 후기</caption>
          <thead>
            <tr class="bold_line line50">
              <th class="text-center">No.</th>
              <th class="text-center">내용</th>
            </tr>
          </thead>
          <tbody>
            <?php    
              // 리뷰 데이터 가져오기
              $sql = "SELECT * FROM easycook_review order by no Desc limit 50";
              $result = mysqli_query($conn, $sql);   
      
              // 데이터 출력
              $row_count = 0; // 번호를 매기기 위한 변수 초기화
              while ($r = mysqli_fetch_array($result)) {
                  //academy_list에서 데이터 받아오기
                  $sql2 = "select * from easycook_academy_list where class_no='$r[1]' and teacher_code='$class[7]';";
                  $result2 = mysqli_query($conn, $sql2);
                  $db = mysqli_fetch_array($result2);
      
                  if(isset($db[2])){
                      // 나의 강사코드와, class_no가 있는 것은 데이터로 표현해주자
            ?>
            <tr style="border-bottom: 1px solid var(--gray);">
              <td><?php echo $row_count+1; ?></td> <!-- 번호 출력 -->
              <td>
                <!-- 강의명 -->
                <b>[<?php echo $db['3'];?>][<?php echo  $db['4'];?>][<?php echo  $db['5'];?>]&#x3000; <span style="color:var(--red);"><?php echo $db['1'];?></span></b><br>

                <!-- 별점 -->
                <?php
                $stars = $r['star']; // 별점 데이터 필드
                $starIcons = ''; // 별 아이콘 준비
                for ($i = 0; $i < $stars; $i++) { $starIcons .= '<i class="bi bi-star-fill"></i>';}
                for ($i = $stars; $i < 5; $i++) {$starIcons .= '<i class="bi bi-star"></i>';}
                ?>
                <div class="review-item">
                  <span><?= $starIcons ?></span>&#x3000;|&#x3000;
                  <span><!-- 작성자 --><?php echo $r[6];?></span>&#x3000;|&#x3000;
                  <span><!-- 작성일 --><?php echo date("Y.m.d",strtotime($r[8]));?></span>
                </div>

                <!-- 리뷰내용 -->
                <?php echo nl2br($r[5]);?>
              </td>
            </tr>
            <?php  
              $row_count=$row_count+1;
              } else { // 나의 강사코드와, class_no가 없다면 아무것도 출력하지 않음
              }} 
            ?>
          </tbody>
        </table>
        <div class="mt-5 mb-5" style="position:relative;">
          <a href="./index.php" title="메인으로" class="admin_btn admin_btn_yellow position_l_b">메인으로</a>
        </div>
      </article>
    </form>
  </section>
<?php
include('./footer.php');
?>
</body>
</html>
