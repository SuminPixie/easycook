<?php
  include('./inc/dbconn.php');

    // 세션이 이미 시작되었는지 확인
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (isset($_SESSION['id'])) {
      $id = $_SESSION['id'];
      $name = $_SESSION['name'];
    }else{
      $id = null;
    }

    // question count
    $query = "select count(*) from easycook_question WHERE question_id='$id'";
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
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 문의</title>
    <!-- 공통 헤드정보 삽입 -->
    <?php include('./inc/head.php'); ?>
    <!-- 서식 연결 -->
    <link rel="stylesheet" href="./css/sub.css">
    <style>
      .page-link{
        background: none !important;
        color: #000 !important;
      }
    </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <!-- 나의 문의 내역 -->
  <main>
    <section class="inquire_list">
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./inquire_list.php" title="나의 문의">나의 문의</a></b>
      </p>
      <h2>내가 쓴 문의 총 <?php echo $max_Num[0]; ?>개</h2>
      <!-- 테이블 시작 -->
      <article class="review_detail">
        <h2 style="display:none;">리뷰목록</h2>
        <table class="table table-hover table-responsive inquire_table mt-3 mb-3">
          <caption>나의 문의 리스트</caption>
          <thead>
            <tr>
              <th>번호</th>
              <th>답변</th>
              <th>제목</th>
              <th>날짜</th>
            </tr>
          </thead>
          <tbody>
          <!-- 전체강의 보기 -->
          <?php 
            // question 불러오는 곳
            $sql = "select count(*) as total_rows from easycook_question where question_id='$id';";
            $result_count = mysqli_query($conn, $sql);
            $row_count = mysqli_fetch_assoc($result_count)['total_rows'];

            // 행 번호 초기화 (시작 번호를 총 행 수로 설정)
            $row_number = $row_count - $start;

            // 현재 페이지의 행 가져오기
            $sql = "select * from easycook_question where question_id='$id' order by no DESC limit $start, $list_num;";
            $result = mysqli_query($conn, $sql);
            
            while($q=mysqli_fetch_row($result)){
              // 클래스명
              $query = "select * from easycook_academy_list where class_no='$q[1]'";
              $result2 = mysqli_query($conn, $query);
              $class = mysqli_fetch_array($result2);

              if(empty($q[7])){
                print 
              "<tr class='inq_list'>
                <td>$row_number</td>
                <td>
                  <a href='inquire_view.php?no=".$q[0]."' title='' class='inq_btn'>
                    <span class='question_r1'>답변대기</span>
                  </a>
                </td>
                <td>
                  <a href='inquire_view.php?no=".$q[0]."' title=''>
                    <span style='color:#999;'>".$class[1]."</span>
                    <b>".$q[3]."</b>
                  </a>
                </td>
                <td><a href='inquire_view.php?no=".$q[0]."' title=''>".date("y-m-d",strtotime($q[5]))."</a></td>
              </tr>";

            }else{
              print 
              "<tr>
                <td>$row_number<td>
                  <a href='inquire_view.php?no=".$q[0]."' title=''><span class='question_r2'>답변완료</span></a>
                </td>
                <td>
                  <a href='inquire_view.php?no=".$q[0]."' title=''>
                    <span style='color:#999;'>".$class[1]."</span>
                    <b>".$q[3]."</b>
                  </a>
                </td>
                <td>
                  <a href='inquire_view.php?no=".$q[0]."' title=''>".date("y-m-d",strtotime($q[5]))."</a>
                </td>
              </tr>";
                }
                // 행 번호 감소
                $row_number--;
            }
          ?>
          </tbody>
        </table>

        <!-- 페이지 네이션 -->
        <nav aria-label="페이지네이션" class="padding50 mt-3 mb-3" style="position: relative;">
          <ul class="pagination justify-content-center">

          <?php //페이지네이션이 들어가는 곳
            //이전페이지
            if($page > 1){ ?> 
              <li class="page-item"><a href="inquire_list.php?page=<?php echo ($page-1); ?>" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
              <?php } 
              else { ?>
                <li class="page-item disabled" style="opacity:0.3;"><a href="#" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
              <?php } ?>

            <?php // 페이지 번호 출력
            for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) { ?>
              <li class="page-item<?php echo ($page == $print_page) ? ' active' : ''; ?>">
                <a href="inquire_list.php?page=<?php echo $print_page; ?>" class="page-link">
                  <?php echo $print_page; ?>
                </a>
              </li>
            <?php } ?> 

            <!-- 다음 버튼 나오는 곳 -->
            <?php if($page>$total_page){ ?>
              <li class="page-item"><a href="inquire_list.php?page<?php echo $total_page; ?>" title="다음페이지로" class="page-link"><i class="bi bi-chevron-right"></i></i></a></li>
            <?php } else { ?>
              <li class="page-item disabled" style="opacity:0.3;"><a href="#" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
            <?php } ?>
          </ul>
        </nav>  

        <!-- 마이페이지로 가기 -->
        <div class="btn-box-l">
          <a href="mypage.php" class="btn-l">마이페이지로</a>
        </div>
      </article>
    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>

</body>
</html>