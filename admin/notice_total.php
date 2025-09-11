<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 공지관리</title>
  <?php  include('header.php');  ?>
  <main>
    <section class="m-center m-auto mb-5 class_size">
      <!-- 부스러기 -->
      <p class="bread">홈 &#x003E; 게시판 관리 &#x003E; <span style="font-weight:bold">공지관리</span></p>

      <!-- 제목 -->
      <h2 class="text-center mt-4 mb-4" >나의 전체 공지</h2>
      <p class="text-center mt-4 mb-4">나의 강의실에 올린 모든 공지를 한눈에 확인하세요</p>

      <!-- 검색영역 -->
      <form name="검색하기" method="post" action="./notice_search.php" onsubmit="return form_check()">
        <!-- 내용 -->
        <div class="con mt-2 mb-2 article" style="position:relative;">
          <!-- 검색버튼 -->
          <div class="mb-2 admin_search_box">
            <input type="search" placeholder="제목+내용으로 찾기" aria-label="제목+내용으로 찾기" class="admin_search" id="search" name="search">
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
          <table class="table table-hover notice table-responsive">
            <caption>Q&A 목록</caption>
            <thead class="text-center">
              <tr class="bold_line line50">
                <th>No.</th>
                <th>제목</th>
                <th class="display_none">날짜</th>
              </tr>
            </thead>
            <tbody>
              <!-- 페이지네이션 시작 -->
              <?php
                // 아이디와 일치하는 강사코드 받아오기
                $sql_t = "SELECT * FROM register WHERE id='$s_id';";
                $result_t = mysqli_query($conn, $sql_t);
                $t = mysqli_fetch_array($result_t);
                $teacher_code = $t['teacher_code'];  //echo $teacher_code;
    
                // count
                $query = "select count(*) from board WHERE id='$s_id'";
                $result_c = mysqli_query($conn, $query);
                $max_Num = mysqli_fetch_array($result_c); //echo "카운트".$max_Num[0]; 
    
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
                //블럭당 시작페이지 번호$start
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
              <?php
                // 전체 공지를 가져와라
                $sql_b = "select * from board where id='$s_id' order by no desc limit $start, $list_num;";
                $result_b = mysqli_query($conn, $sql_b);
                while($db_b = mysqli_fetch_array($result_b)){
                  // 아카데미 이름 가져오기
                  $sql_n = "select * from academy_list where class_no='$db_b[1]'";
                  $result_n = mysqli_query($conn, $sql_n);
                  $db_n = mysqli_fetch_array($result_n); //                echo $db_n[1];
                  echo "<tr>";
                    echo "<td class='text-center'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>".$db_b[0]."</a></td>";
                    echo "<td style='position:relative;'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>
                            <span>".$db_b[2]."</span>
                            <span class='span_subtitle'>".$db_n[1]."</span></a></td>";
                    echo "<td  class='text-center display_none'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>".date("y-m-d",strtotime($db_b[5]))."</a></td>";
                  echo "</tr>";
                };      ?>  
            </tbody>
          </table>
          
          <!-- 페이지 네이션 -->
          <nav aria-label="페이지네이션" class="padding50 mt-3 mb-3" style="position: relative;">
            <ul class="pagination justify-content-center">
              <?php //페이지네이션이 들어가는 곳
              //이전페이지
              if($page <= 1){ ?> 
                <li class="page-item"><a href="notice_total.php?page=<?php echo ($page-1); ?>" title="이전페이지로" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
                <?php } 
                else{ ?> 
                <li class="page-item"><a href="notice_total.php?page=<?php echo ($page-1); ?>" title="이전페이지로" class="page-link "><i class="bi bi-chevron-left"></i></a></li>
                <?php }; ?> 
                <?php //여기서부터 페이지 번호출력하기
              for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
                <li class="page-item"><a href="notice_total.php?page=<?php echo $print_page; ?>" title="현재페이지로" class="page-link">
                  <?php echo $print_page ?>
                </a></li>
                <?php }; ?>  
                <!-- 다음 버튼 나오는 곳 -->
                <?php if($page>=$total_page){ ?>
                  <li class="page-item"><a href="notice_total.php?page=<?php echo $total_page; ?>" title="다음페이지로" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
                <?php }else{ ?>
                  <li class="page-item"><a href="notice_total.php?page=<?php echo ($page+1); ?>" title="다음페이지로" class="page-link ">
                    <i class="bi bi-chevron-right"></i></a>
                  </li>
                <?php };   ?>    
            </ul>
          </nav>  
        
        <div class="mt-5 mb-5" style="position:relative;">
          <a href="index.php" title="메인으로" class="admin_btn admin_btn_yellow position_l_b">메인으로</a>
          <a href="notice_select.php" title="공지등록하기" class="admin_btn admin_btn_red position_r_b">공지등록</a>
        </div>   

      </div>
    </form>
  </section>
  <?php
  include('footer.php');
  ?>
</body>
</html>
