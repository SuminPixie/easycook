<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('./header.php');      
  ?>
  <script>
      // 모든 버튼과 select 요소를 처리하는 함수
      function updateLinks() {
          // 모든 select 요소를 가져옵니다
          var selects = document.querySelectorAll('.class_status');          
          selects.forEach(function(select) {
              var selectedValue = select.value;              
              // 해당 select와 관련된 버튼을 찾습니다
              var button = select.closest('td').nextElementSibling.querySelector('.update_link');              
              // 링크의 href 속성을 업데이트합니다
              if (button) {
                  button.href = 'class_update_input.php?no=' + encodeURIComponent(select.getAttribute('data-no')) + '&class_status=' + encodeURIComponent(selectedValue);
              }
          });
      }
      // 페이지가 로드된 후 이벤트 리스너를 추가합니다
      window.onload = function() {
          // 모든 select 요소에 change 이벤트 리스너를 추가합니다
          var selects = document.querySelectorAll('.class_status');
          selects.forEach(function(select) {
              select.addEventListener('change', updateLinks);
          });
          // 페이지 로드 시에도 링크를 업데이트합니다
          updateLinks();
      };
  </script>
<main>
  <section class="m-center m-auto mb-5 class_size">

  <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; <span style="font-weight:bold">나의 강의실</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">강의상태 수정</h2>

      <!-- 페이지네이션 만드는 php 수식 -->
      <?php
        // 아이디와 일치하는 강사코드 받아오기
        $sql_t = "select * from easycook_register where id='$s_id';";
        $result_t = mysqli_query($conn, $sql_t);
        $t = mysqli_fetch_array($result_t);            // echo "선생님 코드".$t[7];
        $teacher_code = $t['teacher_code'];            // echo "선생님 코드11".$teacher_code;

        $today = (new DateTime())->format('Y-m-d');

        $query = "select count(*) from easycook_academy_list where teacher_code='$teacher_code' ";
        $result = mysqli_query($conn, $query);
        $max_Num = mysqli_fetch_array($result);            // echo $max_Num[0];

        // 전체 목록의 갯수
        $num = $max_Num[0];
        //한 페이지에 보여질 게시물 개수
        $list_num = 5;            
        //이전, 다음 버튼 클릭시 나오는 페이지 수
        $page_num =3;            
        //현재 페이지
        $page = isset($_GET["page"])? $_GET["page"] : 1;            
        // 전체페이지수 계산
        $total_page = ceil($num / $list_num);        
        //전체블럭 계산
        $total_block = ceil($total_page / $page_num);            
        //현재블럭번호 계산
        $now_block = ceil($page / $page_num);            
        //블럭당 시작페이지 번호
        $s_pageNum = ($now_block - 1) * $page_num + 1;            
        //데이터가 0인 경우
        if($s_pageNum <= 0){ $s_pageNum = 1; };            
        //블럭당 마지막페이지 번호
        $e_pageNum = $now_block * $page_num;
        //마지막 번호가 전체 페이지번호보다 크다면 동일한 값을 준다.
        if($e_pageNum > $total_page){ $e_pageNum = $total_page; };

        $start = ($page - 1) * $list_num;
        $cnt = $start + 1;
      ?>

    <article class="class_1">
      <h3>강의 상태 수정</h3>

      <!-- 비동기방식으로 하기 script -->
      <script>
        // 새개의 문서를 하나의 주소로 연결하기
        $(document).ready(function(){
          // 메뉴를 클릭시 함수내용 실행
          $('#tab_con a').click(function(e){
            // 새로고침 방지
            e.preventDefault();

            let url = this.href; //속성값 변수에 담기
            console.log(url); //출력하기

            // 클래스 제거 및 추가하기
            $('#tab_con a.act').removeClass('act');
            $(this).addClass('act');
            
            $('#container').remove();
            $('#content').load(`${url} #container`).hide().fadeIn('slow');            
          });
        });
      </script>
  
    <!-- 내용 -->
    <div class="mt-2 mb-2 update_table" id="content">
      <div id="container">
        <table class="table table-hover">
          <caption>Q&A 목록</caption>
          <thead class="text-center">
            <tr class="bold_line line50">
              <th class='display_none'>No.</th>
              <th>강의명</th>
              <th class='display_none'>상태</th>
              <th colspan="2" style="width: 30%;">수정</th>
            </tr>
          </thead>    
          <tbody>
            <?php 
            // 강사코드가 일치하는 모든 것들을 가져오기
            $sql = "select * from easycook_academy_list where teacher_code='$teacher_code' order by class_no DESC limit $start, $list_num";
            $result = mysqli_query($conn, $sql);
            
            while($db=mysqli_fetch_array($result)){   ?>
            <tr>
              <td class='display_none'>
                <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><?php echo $db['0'];?></a>
              </td>
              <td>
                <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로">[<?php echo $db['3'];?>][<?php echo  $db['4'];?>][<?php echo  $db['5'];?>]<?php echo  $db['1'];?></a>
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
              <td style="width: 30%;">
                <!-- 강의상태 수정하기  -->
                <select name="class_status" style="max-width: 100px;" class="form-select class_status" data-no="<?php echo $db['0'];?>">
                  <option value=""></option>
                  <option value="1주 당기기">1주 당기기</option>
                  <option value="1주 미루기">1주 미루기</option>
                </select>
              </td>
              <td>
                <a href="./class_update_input.php?no=<?php echo $db['0'];?>" class="update_link admin_btn_red responsive_40 span_title" style="width: 100%; max-width: 80px;" title="상태수정">수정</a>
              </td>
            </tr>              
            <?php  }; ?> 
          </tbody>
        </table>

        <!-- 페이지 네이션 -->
        <nav aria-label="페이지네이션" class="padding50">
          <ul class="pagination justify-content-center">
          <?php //페이지네이션이 들어가는 곳
            //이전페이지
            if($page <= 1){ ?> 
              <li class="page-item"><a href="./class_update.php?page=1" class="page-link" title="이전페이지"><i class="bi bi-chevron-left"></i></a></li>
              <?php } 
              else{ ?> 
              <li class="page-item"><a href="./class_update.php?page=<?php echo ($page-1); ?>" class="page-link" title="이전페이지"><i class="bi bi-chevron-left"></i></a></li>
              <?php };
              ?> 
            <?php //여기서부터 페이지 번호출력하기
            for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
              <li class="page-item"><a href="./class_update.php?page=<?php echo $print_page; ?>" title="현재페이지" class="page-link">
                <?php echo $print_page ?>
              </a></li>
            <?php }; ?>  
            <!-- 다음 버튼 나오는 곳 -->
            <?php if($page>=$total_page){ ?>
              <li class="page-item"><a href="./class_update.php?page=<?php echo $total_page; ?>" title="다음페이지로" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
            <?php }else{ ?>
              <li class="page-item"><a href="./class_update.php?page=<?php echo ($page+1); ?>" title="다음페이지로" class="page-link " ><i class="bi bi-chevron-right"></i></a></li>
            <?php };    ?>
        </nav>   
      </div>
    </div>
    
  </div>
      
    <div class="mt-5 mb-3" style="position:relative;">
      <!-- 상태 수정 하기 -->
      <a href="./class_1.php" title="이전으로" class="admin_btn admin_btn_yellow position_l_b">이전으로</a>
    </div>
  </article>

  <?php
  include('./footer.php');
  ?>
</body>
</html>
