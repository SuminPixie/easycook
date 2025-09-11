<?php
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  include('./php/include/dbconn.php');

  //사용자가 로그인한 경우 나의 강의정보 보여줌
  if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];

    $class_no = $_GET['class_no'];

    $sql = "select * from academy_list where class_no = '$class_no'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
    $class_no2 = $row['class_no'];

    // 출석 현황 조회
    $sql_attendance = "
        SELECT
        a.class_no,
        a.id,
        a.datetime AS attendance_time,
        al.start_time,
        al.end_time,
        CASE
          WHEN TIME(a.datetime) <= TIME(al.start_time) THEN '출석' -- 수업 시작 전 출석
          WHEN TIME(a.datetime) <= TIME(al.start_time) + INTERVAL 10 MINUTE THEN '출석' -- 수업 시작 후 10분 이내 출석
          WHEN TIME(a.datetime) <= TIME(al.end_time) THEN '지각' -- 수업 종료 전 지각
          ELSE '결석' -- 수업 종료 후 결석
        END AS status
    FROM
        attendance a
    JOIN
        academy_list al ON a.class_no = al.class_no
    WHERE
        a.id = '$id'
    AND
        a.class_no = '$class_no';
    ";
    $result_attendance = mysqli_query($conn, $sql_attendance);

    // 출석 상태 카운트
    $attendance_count = ['출석' => 0, '지각' => 0, '결석' => 0];
    while ($attendance_row = mysqli_fetch_array($result_attendance)) {
        $attendance_count[$attendance_row['status']]++;
    }

    // 강의 전체 기간 계산
    $start_date = new DateTime($row['start_date']);
    $end_date = new DateTime($row['end_date']);
    $current_date = new DateTime(); // 현재 날짜
    $interval = $start_date->diff($end_date);
    $total_days = $interval->days + 1; // 전체 기간 (포함)

    // 진행된 기간 계산
    $progress_interval = $start_date->diff($current_date);
    $progress_days = $progress_interval->days + 1; // 현재 날짜까지의 기간 (포함)

    // 날짜가 현재 날짜보다 이후인 경우 진행된 기간을 전체 기간으로 설정
    if ($current_date > $end_date) {
        $progress_days = $total_days;
    }

    // 출석과 지각을 제외한 결석 수 계산
    $attendance_and_late_days = $attendance_count['출석'] + $attendance_count['지각'];
    $attendance_count['결석'] = $progress_days - $attendance_and_late_days;


    // 출석률 계산
    $attend_rate = ($attendance_count['출석'] / $total_days) * 100;


  } else {
    echo "<script>
            alert('로그인이 필요한 서비스입니다.');
            window.location.href = './login.php';
          </script>";
  }

    // 페이지네이션
    $query = "select count(*) from board where class_no='$class_no'";
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
  <title>나의 강의정보</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./php/include/head.php'); ?>

  <!-- Chart.js CDN 원형그래프 -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* -----------강의정보 페이지 서식------------ */
    section{
      padding: 0 20px;
    }
    section > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 40px 0 20px 0;
      text-align: center;
    }
    .back_gray{
      background:#ededed;
      padding-top:40px;
    }
    .back_gray .card{
      margin-bottom:40px;
    }
    .card{
      border:none;
      /* padding: 0px 20px 0px 0px; */
      /* background:none; */
      /* margin-bottom:40px; */
    }
    .card img{
      max-height: 200px;
      object-fit: cover;
      border-radius: 5px;
      margin-bottom:40px;
      padding:0px 10px;
    }
    .card-body{
      padding: 0px 20px 20px 20px !important;
    }
    .card-title{
      font-size: var(--fs-medium-large);
      /* margin: 10px 0; */

    }
    /*공통 제목 초록배경 */
    .com_title{
      height:50px;
      background:var(--green);
      border-radius:5px 5px 0px 0px;
      line-height:50px;
      color:#fff;
      font-weight:var(--fw-semi-bold);
    }
    .card-text{
      line-height: 160%;
    }

    /* 이전 버튼 */
    .gray_btn{
      background: #D9D9D9 !important;
      color: #000000 !important;
    }

    /* 원형 그래프 */
    .circles{
      display: flex;
      width: 65%;
      margin: 190px auto;
    }
    .circle{
      margin: 0 auto;
      width: 160px;
      height: 160px;
      position: relative;
      align-items: center;
    }
    .outer{
      height: 160px; width: 160px;
      border-radius: 50%;
      padding: 20px;
      border: none;
      background: rgba(38, 164, 80, 0.15);
    }
    .inner{
      height: 120px; width: 120px; 
      border-radius: 50%;
      display: flex; align-items: center; 
      justify-content: center;
      background: #fff;
    }
    .inner > p{
      font-size: 24px;
      color: var(--green);
      font-weight: 550;
    }

    .circle_cap{
      display: inline-block;
      fill: none;
      stroke: url(#gauge);
      stroke-width: 20px;
      stroke-dasharray: 472;
      stroke-dashoffset: 472;
      transform: rotate(-90deg);
      transform-origin: 50% 50%;
    }
    svg{
      position: absolute;
      top: 0; left: 0;
      margin-left: auto;
    }

    /* 테이블 및 flexbox 스타일 */
    .attend_box {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      align-items: center;
    }
    .table-wrapper {
      flex: 1;
      max-width: 600px;
      width: 100%;
      white-space: nowrap;
    }

    /*공지사항 부분 스타일 */
    .s_board{
      min-height: 300px;
    }
    .s_board li{
      width:90%;
      margin:0 auto;
      border-bottom:1px solid var(--gray);
      padding:10px 0px;
    }
    .s_board li:last-child{
      border-bottom: none;
    }
    .s_board li>p:first-child{
      font-weight:var(--fw-bold);
      margin-bottom:5px;
    }

    .container{padding:0px;}

    /* 페이지네이션 */
    .page-link{
      color: var(--darkbrown);
      border: none;
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header_sub.php');?>

  <main>
    <section class="container mt-5">
      <h2 class="mb-3">나의 강의정보</h2>
      <!-- 상품목록 카드 스타일 -->
        <div class="card">
          <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" class="card-img-top" alt="강의 썸네일 사진">
          <div class="card-body">
            <p class="card-title m_top"><strong><?php echo $row['name']; ?></strong></p>
            <hr>
            <p class="card-text">
              일정 : <?php echo $row['start_date']; ?> ~ <?php echo $row['end_date']; ?><br>
              차수 : <?php echo $row['nth']; ?>차<br>
              난이도 : <?php echo $row['grade']; ?><br>
              장소 : <?php echo $row['place']; ?><br>
              강사명 : <?php echo $row['teacher']; ?><br>
              연락처 : 02-1234-1234
            </p>
          </div>
        </div>
    </section>
    <section class="back_gray">
      <article class="card container">
        <p class="card-title text-center com_title">공지사항</p>
        <!--php문 반복문 쓰기 for(row2=mysqli 이거 ) 공지사항 불러오기-->
        <ul class="s_board">
          <?php  
            $s_board = "select * from board where class_no = '$class_no2' order by no DESC limit $start, $list_num";
            $s_result = mysqli_query($conn, $s_board);
            if(mysqli_num_rows($s_result) > 0){
            while($s_row = mysqli_fetch_array($s_result)){
          ?>          
          <li>
            <p><?php echo $s_row['title']; ?></p>
            <p><?php echo $s_row['datetime']; ?></p>
          </li>
          <?php }}else{
                echo '<li class="text-center">게시글이 없습니다</li>';
          } ?>
        </ul>        
        <!-- 페이지 네이션 -->
        <nav aria-label="페이지네이션" class="padding50">
          <ul class="pagination justify-content-center">
          <?php //페이지네이션이 들어가는 곳
            //이전페이지
            if($page <= 1){ ?> 
              <?php } 
              else{ ?> 
              <li class="page-item"><a href="myclass_info.php?class_no=<?php echo $class_no;?>&page=<?php echo ($page-1); ?>" title="이전페이지로" class="page-link "><i class="bi bi-chevron-left"></i></a></li>
              <?php };?> 
            <?php //여기서부터 페이지 번호출력하기
            for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
              <li class="page-item"><a href="myclass_info.php?class_no=<?php echo $class_no;?>&page=<?php echo $print_page; ?>" title="선택한페이지로" class="page-link">
                <?php echo $print_page ?>
              </a></li>
            <?php }; ?>  
            <!-- 다음 버튼 나오는 곳 -->
            <?php if($page>=$total_page){ ?>
            <?php }else{ ?>
              <li class="page-item"><a href="myclass_info.php?class_no=<?php echo $class_no;?>&page=<?php echo ($page+1); ?>" title="다음페이지로" class="page-link " ><i class="bi bi-chevron-right"></i></a></li>
          <?php };    ?>    
          </ul>
        </nav>
      </article>
      </nav>
      <article class="card container" >
        <p class="card-title text-center com_title">출석현황</p>
        <div class="card-body">
          <div class="attend_box mt-5">
            <!-- 원형 그래프 -->
            <div class="circle">
              <div class="outer">
                <div class="inner">
                  <p><!-- 퍼센트 들어가는 부분 --></p>
                </div>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160">
                <defs>
                  <linearGradient id="gauge">
                    <stop offset="0%" stop-color="#26A450"></stop>
                    <stop offset="100%" stop-color="#26A450"></stop>
                  </linearGradient>
                </defs>
                <circle class="circle_cap" cx="80" cy="80" r="70" stroke-linecap="round" style="stroke-dashoffset: 115.75px;"></circle>
              </svg>
            </div>
            <!-- 출석일자 -->
            <div class="table-wrapper">
              <table class="table table-bordered text-center">
                <thead class="thead-light">
                  <tr>
                    <th>출석</th>
                    <th>지각</th>
                    <th>결석</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo $attendance_count['출석']; ?>일</td>
                    <td><?php echo $attendance_count['지각']; ?>일</td>
                    <td><?php echo $attendance_count['결석']; ?>일</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="pt-4" style="color: #888;">
                      <span style="margin-right:15px;">강의 진행률</span>
                      <span><?php echo $progress_days; ?> / <?php echo $total_days; ?>일</span>
                    </td>
                  </tr>
                </tbody>
                </table>
            </div>
          </div>
        </div>
      </article>

        <div class="btn-box-l mb-5 mt-4">
          <button class="btn-l gray_btn" onclick="history.back();">이전으로</button>
        </div>
    </section>


  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./php/include/footer.php');?>

  <!-- 공통 바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>


  <script>
    //찜목록에서 삭제하는 기능
    function removeCart(classNo) {
      $.ajax({
        url: './php/cart_toggle.php',
        type: 'POST',
        data: {
          class_no: classNo,
          action: 'remove'
        },
        success: function(response) {
          console.log(response);
          // 삭제 성공 후 리스트에서 항목 제거
          $('#class-' + classNo).remove();
        },
        error: function(xhr, status, error) {
          console.error('요청 실패:', error);
        }
      });
    }

    //-----------------원형 그래프--------------- 
    $(document).ready(function() {
        // 원형 그래프 애니메이션 함수
        function circle() {
          $('.circle').each(function() {
            const $this = $(this);
            const $inner = $this.find('.inner p');
            const $circle = $this.find('circle');
            const circleLength = $circle[0].getTotalLength(); 
            // PHP에서 계산된 출석률을 JavaScript 변수에 전달
            const attendLate = <?php echo $attend_rate; ?>;
            $circle.css({
              'stroke-dasharray': circleLength,
              'stroke-dashoffset': circleLength
            });

            $({ rate: 0 }).animate({ rate: attendLate}, {
              duration: 1500,
              step: function() {
                const currentOffset = circleLength - (circleLength * this.rate / 100);
                $circle.css('stroke-dashoffset', currentOffset);
              }
            });
            // 출석률 %를 원형 안에 설정
            $inner.text(Math.round(attendLate) + '%');
          });
        }

        // 페이지 로딩 시 애니메이션 실행
        circle();
      
    });



  </script>
</body>
</html>