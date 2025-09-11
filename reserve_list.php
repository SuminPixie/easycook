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
      echo "<script>
              alert('로그인이 필요한 서비스입니다.');
              window.location.href = './login.php';
            </script>";
    }

    // question count
    $query = "select count(*) from easycook_room WHERE id='$id'";
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
  <title>이지쿡 | 실습실 예약</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <style>
    section{
      padding: 0 20px;
      max-width: 1025px;
      margin: 0 auto;
    }
    .reserve_list > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 40px 0 10px 0;
      text-align: center;
    }
    .table>:not(caption)>*>*{
      padding: 12px;
      vertical-align: middle;
    }
    .reserve_list .table{
      border-top: 2px solid #000;
    }
    .reserve_list .table thead{
      text-align: center;
    }
    .reserve_list .table tbody{
      border-top: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      text-align: center;
    }

    /* 상태버튼 */
    .status_btn{
      background:var(--red);
      color:#fff;
      padding:6px;
      text-wrap:nowrap;
      border-radius:5px;
      border:none;
    }
    .status_btn_disabled {
      background-color: #ccc; 
      color: #666;
      cursor: not-allowed;
      pointer-events: none;
    }
    .status_btn_disabled:hover {
      background-color: #ccc;
      text-decoration: none;
    }

    /* 페이지네이션 서식 */
    .pagination {
      border: none; /* 테두리 없애기 */
    }
    .pagination .page-link {
      color: #000;
      font-weight: normal;
      border: none;
    }
    .pagination .page-item.active .page-link {
      font-weight: bold; 
      background: none;
      border: none;
    }
    .pagination .page-item.disabled .page-link {
      color: #ccc;
      cursor: not-allowed;
    }
    .pagination .page-link:hover {
      text-decoration: none;
    }
    .disabled>.page-link, .page-link.disabled{
      background: none;
    }

    /* 안내문구 */
    .reserve_list  .hover_info{
      display: none;
      border: 1px solid var(--gray);
      font-size: var(--fs-small); 
      font-weight: var(--fw-light); 
      padding: 10px;
      z-index: 900;
      top: 205px;
      left:140px;
      position: absolute;
      border-radius: 0px 5px 5px 5px;
      background: #fff;
      transition: 0.3s;
    }


    .reserve_list  .hover_info:after, .reserve_list  .hover_info:before {
      top: 4px;
      left: 0px;
      border: solid transparent;
      content: "";
      height: 0;
      width: 0;
      position: absolute;
      pointer-events: none;
    }

    .reserve_list  .hover_info:after {
      border: 10px solid transparent;
      border-bottom-color: #ffffff;
      border-top: 0;
      border-left: 0;
      border-width: 15px;
      margin-left:0px;
      margin-top: -17.5px;
    }
    .reserve_list  .hover_info:before {

      border: 19px solid transparent;
      border-bottom-color: #d9d9d9;
      border-top: 0;
      border-left: 0;
      border-width: 16px;
      margin-left: -1px;
      margin-top: -20px;
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <!-- 나의 문의 내역 -->
  <main>
    <section class="reserve_list">
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./reserve_list.php" title="나의 실습실 예약">실습실</a></b>
      </p>
      <h2>나의 실습실 예약</h2>
      <!-- 테이블 시작 -->
      <article class="reserve_detail">
        <h3 style="display:none;">실습실 예약 리스트</h3>
        <table class="table table-hover table-responsive reserve_table mt-3 mb-3">
          <caption style="display:none;">나의 예약 리스트</caption>
          <p class="hov_info">실습실 예약 안내 <i class="bi bi-info-circle"></i></p>
          <div class="hover_info">실습실 예약은 수강한 이력이 있을 때 가능합니다.</div>
          <thead>
            <tr>
              <th>번호</th>
              <th>날짜</th>
              <th>시간</th>
              <th>장소</th>
              <th>상태</th>
            </tr>
          </thead>
          <tbody>
          <!-- 전체강의 보기 -->
          <?php 
            // 아시아/서울 타임존 설정
            $timezone = new DateTimeZone('Asia/Seoul');

            // 현재 시간
            $currentDateTime = new DateTime('now', $timezone);
            

            // room 불러오는곳
            $sql = "SELECT * FROM easycook_room WHERE id='$id' ORDER BY no DESC LIMIT $start, $list_num;";
            $result = mysqli_query($conn, $sql);

            while($q = mysqli_fetch_row($result)) {
              // 예약 날짜와 시간을 형식화
              $date = date("Y-m-d", strtotime($q[3]));
              $startTime = date("H:i", strtotime($q[4]));
              $endTime = date("H:i", strtotime($q[5]));
              $roomNumber = htmlspecialchars($q[2]);

              // 시간 비교
              $reserveDateTime = new DateTime($q[3] . ' ' . $q[4],  $timezone); // 예약 날짜와 시작 시간 결합

              $isExpired = $reserveDateTime < $currentDateTime;

              // 버튼 텍스트와 스타일 결정
              $buttonText = $isExpired ? '종료' : '취소';
              $buttonClass = $isExpired ? 'status_btn status_btn_disabled' : 'status_btn';

              print 
              "<tr id='no-$q[0]'>
                <td>$max_Num[0]</td>
                <td>$date</td>
                <td>$startTime - $endTime</td>
                <td style='text-wrap:nowrap;'>$roomNumber<span>호</span></td>
                <td>
                  <button onclick='Cancle($q[0])' class='$buttonClass'>$buttonText</button>
                </td>
              </tr>";

              $max_Num[0]--;
            }
          ?>

        </table>

      <!-- 페이지 네이션 -->
      <nav aria-label="페이지네이션" class="padding50 mt-3 mb-3" style="position: relative;">
        <ul class="pagination justify-content-center">

          <!-- 이전 페이지 버튼 -->
          <?php if($page > 1) { ?>
            <li class="page-item"><a href="reserve_list.php?page=<?php echo ($page - 1); ?>" class="page-link"><i class="bi bi-chevron-left"></i></a></li>
          <?php } else { ?>
            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
          <?php } ?>

          <!-- 페이지 번호 출력 -->
          <?php for($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) { ?>
            <li class="page-item <?php if($page == $print_page) echo 'active'; ?>">
              <a href="reserve_list.php?page=<?php echo $print_page; ?>" class="page-link"><?php echo $print_page; ?></a>
            </li>
          <?php } ?>

          <!-- 다음 페이지 버튼 -->
          <?php if($page < $total_page) { ?>
            <li class="page-item"><a href="reserve_list.php?page=<?php echo ($page + 1); ?>" class="page-link"><i class="bi bi-chevron-right"></i></a></li>
          <?php } else { ?>
            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
          <?php } ?>
          
        </ul>

        <!-- 바로가기 가기 -->
        <div class="btn-box-l mb-5 mt-5">
          <a href="mypage.php" class="btn-l gray">마이페이지로</a>
        </div>
      </article>
    </section>
  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>

  <script>
    //신청취소 누르면 삭제하는 기능
    function Cancle(No) {
      if (confirm('예약을 취소하시겠습니까?')){
        $.ajax({
          url: './act/reserve_delete.php',
          type: 'POST',
          data: {
            no: No,
            action: 'remove'
          },
          success: function(response) {
            console.log(response);
            alert('예약이 취소되었습니다.');
            // 삭제 성공 후 리스트에서 항목 제거
            $('#no-' + No).remove();
          },
          error: function(xhr, status, error) {
            console.error('요청 실패:', error);
          }
        });
      }
    }

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