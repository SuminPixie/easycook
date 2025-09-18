<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 공지관리</title>
  <?php
    include('./header.php');      
  ?>
  <main>
    <section class="m-center m-auto mb-5 class_size">

      <?php
        // 번호 받아오기
        $class_no = $_GET['class_no'];    
      
        //academy_list에서 데이터 받아오기
        $sql = "select * from easycook_academy_list where class_no='$class_no'";
        $result = mysqli_query($conn, $sql);
        $db = mysqli_fetch_array($result);
      ?>
      <!-- 부스러기 -->
      <p class="bread">홈 &#x003E; 강의 관리 &#x003E; 나의 강의실 &#x003E; <span style="font-weight:bold">공지 관리</span></p>

      <!-- 제목 -->
      <h2 class="text-center mt-5 mb-4"> [<?php echo $db['3'];?>][<?php echo  $db['4'];?>][<?php echo  $db['5'];?>]<?php echo  $db['1'];?></h2>

      <!-- 탭컨텐츠 -->
      <article id="tab_con">
        <h3>공지관리</h3>
        <ul>
          <li><a href="./student.php?class_no=<?php echo $class_no;?>" title="학생관리">학생관리</a></li>
          <li><a href="./class_info.php?class_no=<?php echo $class_no;?>" title="강의소개">강의소개</a></li>
          <li><a href="./notice_list.php?class_no=<?php echo $class_no;?>" title="공지사항" class="act">공지사항</a></li>
        </ul>

      <!-- 페이지네이션 만드는 php 수식 -->
      <?php
        $query = "select count(*) from easycook_board where class_no='$class_no' order by no desc";
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

      <!-- 내용 -->
      <div class="con mt-2 mb-2">
        <table class="table table-hover">
          <caption>Q&A 목록</caption>
          <thead class="text-center">
            <tr style="border-top: 3px solid black; line-height:50px;">
              <th >No.</th>
              <th >제목</th>
              <th >날짜</th>
            </tr>
          </thead>
          <tbody>
          <?php
            //board에서 데이터 받아오기
            $sql2 = "select * from easycook_board where class_no='$class_no' order by no desc limit $start, $list_num;;";
            $result2 = mysqli_query($conn, $sql2);
            while($board=mysqli_fetch_array($result2)){   
              print "
              <tr>
                <td class='text-center'>
                  <a href='notice_view.php?no=".$board[0]."' title='공지읽기'>".$board[0]."</a>
                </td>
                <td>
                <a href='notice_view.php?no=".$board[0]."' title='공지읽기'>".$board[2]."</a>
                </td>
                <td class='text-center'>
                  <a href='notice_view.php?no=".$board[0]."' title='공지읽기'>".date("Y.m.d",strtotime($board[5]))."</a>
                </td>
              </tr>"; }?>
          </tbody>
        </table>

        <!-- 페이지 네이션 -->
        <nav aria-label="페이지네이션" class="padding50">
          <ul class="pagination justify-content-center">

          <?php //페이지네이션이 들어가는 곳
            //이전페이지
            if($page <= 1){ ?> 
              <li class="page-item"><a href="./notice_list.php?class_no=<?php echo $class_no;?>&page=<?php echo ($page-1); ?>" class="page-link"><i class="fa-solid fa-angle-left"></i></a></li>
              <?php } 
              else{ ?> 
              <li class="page-item"><a href="./notice_list.php?class_no=<?php echo $class_no;?>&page=<?php echo ($page-1); ?>" class="page-link "><i class="fa-solid fa-angle-left"></i></a></li>
              <?php };
              ?> 
          <?php //여기서부터 페이지 번호출력하기
            for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
              <li class="page-item"><a href="./notice_list.php?class_no=<?php echo $class_no;?>&page=<?php echo $print_page; ?>" class="page-link">
                <?php echo $print_page ?>
              </a></li>
            <?php }; ?>  

            <!-- 다음 버튼 나오는 곳 -->
            <?php if($page>=$total_page){ ?>
              <li class="page-item"><a href="./notice_list.php?class_no=<?php echo $class_no;?>&page=<?php echo $total_page; ?>" title="다음페이지로" class="page-link"><i class="fa-solid fa-angle-right"></i></a></li>
            <?php }else{ ?>
              <li class="page-item"><a href="./notice_list.php?class_no=<?php echo $class_no;?>&page=<?php echo ($page+1); ?>" class="page-link " ><i class="fa-solid fa-angle-right"></i></a></li>
          <?php };    
          ?>    
          </ul>
        </nav>  

        <div class="mt-5 mb-3" style="position:relative;">
          <!-- 목록으로 -->
          <a href="./class_1.php" title="강의목록" class="admin_btn admin_btn_yellow position_l_b">강의목록</a>
          <!-- 강의 개설 하기 -->
          <a href="./notice.php?class_no=<?php echo $class_no; ?>" title="공지작성" class="admin_btn admin_btn_red position_r_b">공지작성</a>
        </div>
      </div>

    </article>
  </section>  
  <?php
  include('./footer.php');
  ?>
</body>
</html>
