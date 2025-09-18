<!-- 나의 강의실 중 전체강의 -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; <span style="font-weight:bold">나의 강의실</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">나의 강의실</h2>

    <!-- 검색영역 -->
    <form name="검색하기" method="post" action="./class_search.php" onsubmit="return form_check()">
      <!-- 페이지네이션 만드는 php 수식 -->
      <?php
        // 아이디와 일치하는 강사코드 받아오기
        $sql_t = "select * from easycook_register where id='$s_id';";
        $result_t = mysqli_query($conn, $sql_t);
        $t = mysqli_fetch_array($result_t);// echo "선생님 코드".$t[7];
        $teacher_code = $t['teacher_code'];// echo "선생님 코드11".$teacher_code;

        $today = (new DateTime())->format('Y-m-d');

        $query = "select count(*) from easycook_academy_list where teacher_code='$teacher_code' and (end_date < '$today') ";
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

      <article>
        <!-- 검색버튼 -->
        <div class="mb-2 admin_search_box">
          <input type="search" placeholder="검색어를 입력하세요" aria-label="검색어를 입력하세요" class="admin_search" id="search" name="search">
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

        <h3>나의 강의실</h3>
        <!-- 탭컨텐츠 -->
        <div id="tab_con">
          <ul>
            <li><a href="./class_1.php" title="전체" class="act">전체</a></li>
            <li><a href="./class_2.php" title="지난 강의">지난 강의</a></li>
            <li><a href="./class_3.php" title="현재 강의">현재 강의</a></li>
            <li><a href="./class_4.php" title="보류 &#x007C; 예정">보류 &#x007C; 예정</a></li>
          </ul>
        </div>

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
        <div class="mt-2 mb-2" id="content">
          <div id="container" class="position_relative">
            <table class="table table-hover table-responsive">
              <caption>Q&A 목록</caption>
              <thead class="text-center">
                <tr class="bold_line line50">
                  <th>No.</th>
                  <th>강의명</th>
                  <th>상태</th>
                </tr>
              </thead>    
              <!-- 테이블 불러오기 php -->
              <tbody>
                <!-- 전체강의 보기 -->
                <?php 
                // 강사코드가 일치하는 모든 것들을 가져오기
                $sql = "select * from easycook_academy_list where teacher_code='$teacher_code' and (end_date < '$today') order by class_no DESC limit $start, $list_num ";
                $result = mysqli_query($conn, $sql);
      
                while($db=mysqli_fetch_array($result)){   ?>
                <tr>
                  <td>
                    <a href="student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><?php echo $db['0'];?></a>
                  </td>
                  <td>
                    <a href="student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로">[<?php echo $db['3'];?>][<?php echo  $db['4'];?>][<?php echo  $db['5'];?>]<?php echo  $db['1'];?>
                    <?php 
                      $class_no_this =$db['0'];
                      $sql_student = "select count(*) from `easycook_order` where class_no  =$class_no_this";
                      $result_student = mysqli_query($conn, $sql_student);
                      $row = mysqli_fetch_array($result_student);
                      echo  "<span style=color:var(--red);'>(".$row[0]."명)</span>";
                    ?></a>
                  </td>

                  <?php 
                  if($db['13'] <= $today and $db['14'] >= $today){
                    ?>
                    <td class="text-center">
                      <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title green">현재강의<span></a>
                    </td>
                  <?php }elseif($db['13'] > $today ){; ?> 
                    <td class="text-center">
                      <a href="./student.php?class_no=<?php echo $db['0'];?>" title="학생페이지로"><span class="span_title yellow">예정강의</span></a>
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

            <style>
              .pagination {
              --bs-pagination-color: var(--black) !important;
              --bs-pagination-border-width: fit-content;
            }
              .page-link:hover {
                z-index: 2;
                color: var(--bs-pagination-hover-color);
                background-color: var(--bs-pagination-hover-bg);
                border-color: var(--bs-pagination-hover-border-color);
              }
            </style>
            <!-- 페이지 네이션 -->
            <nav aria-label="페이지네이션" class="padding50">
              <ul class="pagination justify-content-center">
                <?php //페이지네이션이 들어가는 곳
                  //이전페이지
                  if($page <= 1){ ?> 
                    <li class="page-item"><a href="./class_2.php?page=1" class="page-link" title="이전페이지로"><i class="bi bi-chevron-left"></i></i></a></li>
                  <?php } else{ ?> 
                    <li class="page-item"><a href="./class_2.php?page=<?php echo ($page-1); ?>" class="page-link " title="이전페이지로"><i class="bi bi-chevron-left"></i></i></a></li>
                  <?php }; ?> 
                <?php //여기서부터 페이지 번호출력하기
                  for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
                    <li class="page-item"><a href="./class_2.php?page=<?php echo $print_page; ?>" class="page-link" title="해당페이지로">
                      <?php echo $print_page ?>
                    </a></li>
                  <?php }; ?>  

                  <!-- 다음 버튼 나오는 곳 -->
                  <?php if($page>=$total_page){ ?>
                    <li class="page-item"><a href="./class_2.php?page=<?php echo $total_page; ?>" title="다음페이지로" class="page-link"><i class="bi bi-chevron-right"></i></i></a></li>
                  <?php }else{ ?>
                    <li class="page-item"><a href="./class_2.php?page=<?php echo ($page+1); ?>" title="다음페이지로" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
                <?php };    ?>
              </ul>
            </nav>   

          </div>
        </div>
        
        <div class="mt-5 mb-3" style="position:relative;">
          <!-- 강의 개설 하기 -->
          <a href="./class_create.php" title="강의개설" class="admin_btn admin_btn_red position_r_b">강의개설</a>
          <!-- 상태 수정 하기 -->
          <a href="./class_update.php" title="상태수정" class="admin_btn admin_btn_yellow position_l_b">상태수정</a>
        </div>
      </article>
    </form>
  </section>
<?php
include('./footer.php');
?>
</body>
</html>
