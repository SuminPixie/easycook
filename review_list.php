<?php
  include('./php/include/dbconn.php');

  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // 세션 변수 설정
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
  } else {
    $id = null;
  }

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./php/include/head.php'); ?>
  <!-- 서브서식 연결 -->
  <link rel="stylesheet" href="./css/sub.css">
  <style>
    main {
      min-height: 80vh;
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header.php');?>

  <main>
    <?php
      // 페이지네이션
      $query1 = "SELECT COUNT(*) FROM review WHERE id = '$id'";
      $result1 = mysqli_query($conn, $query1);
      $num = mysqli_fetch_array($result1)[0];
      
      // 페이지 설정
      $list_num = 3;
      $page_num = 3;
      $page = isset($_GET["page"]) ? $_GET["page"] : 1;
      $total_page = ceil($num / $list_num);
      $total_block = ceil($total_page / $page_num);
      $now_block = ceil($page / $page_num);
      $s_pageNum = max(1, ($now_block - 1) * $page_num + 1);
      $e_pageNum = min($total_page, $now_block * $page_num);
      $start = ($page - 1) * $list_num;

      // 리뷰 불러오기
      $sql3 = "SELECT * FROM review WHERE id='$id' ORDER BY datetime DESC LIMIT $start, $list_num";
      $result3 = mysqli_query($conn, $sql3);

      // 리뷰 개수를 구하는 쿼리
      $query = "SELECT COUNT(*) AS total_review FROM review WHERE id='$id'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);

      $total_review = $row['total_review'];
    ?>

    <section class="review_list">
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./review_list.php" title="나의 후기">나의 후기</a></b>
      </p>

      <h2>내가 쓴 후기 총 <?php echo $total_review ?>개</h2>
      <p class="hov_info">후기 수정 안내 <i class="bi bi-info-circle"></i></p>
      <div class="hover_info">수강기간이 1주일 이상 지난 후에는 리뷰를 수정할 수 없습니다.</div>
      <article class="review_detail">
        <ul>
          <?php 
            while($row3 = mysqli_fetch_array($result3)){ 
              $class_no = $row3['class_no'];
              $arry_img = $row3['img'];
              
              // 강의명과 종료일 가져오기
              $sql2 = "SELECT name, end_date FROM academy_list WHERE class_no = '$class_no'";
              $result2 = mysqli_query($conn, $sql2);
              $row2 = mysqli_fetch_array($result2);
          ?>
            <li>
              <!--강의명-->
              <h2>
                <a href="./detail.php?class_no=<?php echo $class_no?>" title="해당 강의상세로 이동">
                  <?php echo $row2['name'] ?> <i class="bi bi-chevron-right"></i>
                </a>
              </h2>
              
              <!--별점-->
              <p class="day_gray">
              <?php
                $review_star = "";
                $star = $row3["star"];
                for($i = 0; $i < 5; $i++){
                  $review_star .= $i < $star ? '<i class="bi bi-star-fill active"></i>' : '<i class="bi bi-star-fill"></i>';
                }
              ?>
              <span class="star"><?php echo $review_star; ?></span>
              <!--몇개월전-->
              <?php
                $datetime = $row3["datetime"];
                $date = new DateTime($datetime);
                $today = new DateTime();
                $interval = $today->diff($date);
                
                if ($interval->y > 0) {
                    echo "{$interval->y}년 전";
                } elseif ($interval->m > 0) {
                    echo "{$interval->m}개월 전";
                } elseif ($interval->d > 0) {
                    echo "{$interval->d}일 전";
                } elseif ($interval->h > 0) {
                    echo "{$interval->h}시간 전";
                } elseif ($interval->i > 0) {
                    echo "{$interval->i}분 전";
                } else {
                    echo "{$interval->s}초 전";
                }
              ?>
              </p>

              <!--내용-->
              <p><?php echo $row3['review']; ?></p>

              <!--사진-->
              <p class="review_img">
                <?php foreach (explode(",", $row3['img']) as $file) { ?>
                  <?php if($file != '') { ?>
                    <img src="./uploads/review/<?php echo $file; ?>" alt="">
                  <?php } ?>
                <?php } ?>
              </p>

              <?php
                // 강의 종료 날짜
                $endDateObj = new DateTime($row2['end_date']);
                $endDatePlus10 = clone $endDateObj;
                $endDatePlus10->modify('+10 days');
                $showEditButton = $today <= $endDatePlus10;
              ?>
              <p class="ud_btn">
                <?php if($showEditButton){ ?>
                <a href="./review_edit.php?no=<?php echo $row3['no'] ?>" title="수정">수정</a>
                <a href="./php/review_del.php?no=<?php echo $row3['no'] ?>" title="삭제">삭제</a>
                <?php } ?>
              </p>

            </li>
          <?php } ?>
        </ul>
      </article>

      <!-- 페이지 네이션 -->
      <nav aria-label="페이지네이션" id="pagena">
        <ul class="pagination justify-content-center mt-4 mb-5">
          <?php //이전페이지
            if($page > 1){ 
          ?> 
            <li class="page-item"><a href="review_list.php?page=<?php echo ($page - 1); ?>" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
          <?php } else { ?>
            <li class="page-item disabled"><a href="#" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
          <?php } ?>
          
          <?php // 페이지 번호 출력
            for($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++){?>
              <li class="page-item <?php echo $print_page == $page ? 'active' : ''; ?>">
                <a href="review_list.php?page=<?php echo $print_page; ?>" class="page-link"><?php echo $print_page ?></a>
              </li>
          <?php } ?>

          <?php //다음 버튼
            if($page < $total_page){ ?>
              <li class="page-item"><a href="review_list.php?page=<?php echo ($page + 1); ?>" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
          <?php } else { ?>
              <li class="page-item disabled"><a href="#" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
          <?php } ?>
        </ul>
      </nav>  
    </section>
  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./php/include/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>

  <script>
    $(document).ready(function(){
      $('.hov_info').mouseenter(function(){
        $('.hover_info').css('display', 'block');
      }).mouseleave(function(){
        $('.hover_info').css('display', 'none');
      }).click(function() {
        $('.hover_info').toggle(); // 클릭 시 보이기/숨기기
      });
    });
  </script>

</body>
</html>
