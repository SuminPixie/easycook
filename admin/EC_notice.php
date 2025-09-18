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
    <h2 class="text-center mt-4 mb-2">학원소식</h2>
    <p class="text-center mt-4 mb-4">EASY & COOK 직원 공지사항 게시판 입니다.</p>

    <!-- 페이지네이션 만드는 php 수식 -->
      <?php
      $query = "select count(*) from easycook_notice order by no desc";
      $result = mysqli_query($conn, $query);
      $max_Num = mysqli_fetch_array($result);

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
    ?>

    <!-- 검색영역 -->
    <form name="검색하기" method="post" action="./EC_notice_search.php" onsubmit="return form_check()">

      <article class="class_1 ">
        <h3>학원소식</h3>
        <!-- 내용 -->
        <div class="mt-2 mb-3" id="content" >
          <div id="container">
            <!-- 검색버튼 -->
            <div class="mb-2 admin_search_box" style="top: 97px !important;">
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
            <table class="table table-hover">
              <caption>Q&A 목록</caption>
              <thead class="text-center">
                <tr class="bold_line line50">
                  <th>No.</th>
                  <th>제목</th>
                  <th class="display_none">작성일</th>
                </tr>
              </thead>    
              <tbody>
                <!-- 전체강의 보기 -->
                <?php 
                $sql = "select * from easycook_notice order by no desc limit $start, $list_num;";
                $result2 = mysqli_query($conn, $sql);              
                while($db=mysqli_fetch_array($result2)){   ?>
                <tr>
                  <td class="text-center">
                    <a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="<?php echo $db['1'];?>"><?php echo $db['0'];?></a>
                  </td>
                  <td >
                    <a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="<?php echo $db['1'];?>"><?php echo  $db['1'];?></a>
                  </td>
                  <td class="./text-center display_none"><a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="<?php echo $db['1'];?>"><?php echo date("Y.m.d",strtotime($db['3']));?></a></td>
                </tr>
                <?php     }; ?> 
              </tbody>
            </table>

            
            <!-- 페이지 네이션 -->
            <nav aria-label="페이지네이션" class="padding50 mt-3 mb-3" style="position: relative;">
              <ul class="pagination justify-content-center">
              <?php //페이지네이션이 들어가는 곳
                //이전페이지
                if($page <= 1){ ?> 
                  <li class="page-item"><a href="./EC_notice.php?page=1" title ="이전페이지" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
                  <?php } 
                  else{ ?> 
                  <li class="page-item"><a href="./EC_notice.php?page=<?php echo ($page-1); ?>" title ="이전페이지" class="page-link "><i class="bi bi-chevron-left"></i></a></li>
                  <?php };
                  ?> 
              <?php //여기서부터 페이지 번호출력하기
                for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
                  <li class="page-item"><a href="./EC_notice.php?page=<?php echo $print_page; ?>" title ="현재페이지" class="page-link">
                    <?php echo $print_page ?>
                  </a></li>
                <?php }; ?>  
                <!-- 다음 버튼 나오는 곳 -->
                <?php if($page>=$total_page){ ?>
                  <li class="page-item"><a href="./EC_notice.php?page=<?php echo $total_page; ?>" title="다음페이지" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
                <?php }else{ ?>
                  <li class="page-item"><a href="./EC_notice.php?page=<?php echo ($page+1); ?>" title="다음페이지" class="page-link " ><i class="bi bi-chevron-right"></i></a></li>
              <?php };     ?>    
              </ul>
            </nav>

          </div>

          <div class="mt-5 mb-3" style="position:relative;">
            <a href="index.php" title="메인으로" class="admin_btn admin_btn_yellow position_l_b">메인으로</a>
          </div>
          
        </div>
      </article>        
    </form>
    <div class="copyright">
      Copyright ⓒ Easy Cook, All Rights Reserved.
    </div>
  </section>
  <?php
  include('./footer.php');
  ?>
</body>
</html>
