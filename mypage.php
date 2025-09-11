<?php
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  include('./inc/dbconn.php');

  //사용자가 로그인한 경우, 데이터베이스에서 정보 가져옴
  if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    
    $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $name = htmlspecialchars($row['name']);
    $profile = htmlspecialchars($row['profile']);
    $teacher_code = isset($_SESSION['teacher_code']) ? $_SESSION['teacher_code'] : null;

    // 세션 정보를 업데이트
    $_SESSION['name'] = $name;
    $_SESSION['profile'] = $profile;

    // --------------내 강의 정보 불러오는 부분----------------
    // order 테이블에서 세션ID와 일치하는 '수강중'인 class_no 값을 가져오기
    $sql_my = "SELECT class_no FROM `easycook_order` WHERE id='$id' AND student_status='수강중'";
    $result_my = mysqli_query($conn, $sql_my);

    // class_no 값을 배열로 저장
    $class_no = array();
    while ($row_my = mysqli_fetch_array($result_my)) {
      $class_no[] = $row_my['class_no'];
    }

    // 현재 날짜
    $today = date('Y-m-d');

    // 현재 강의와 지난 강의를 구분하여 배열에 저장
    $class_no_list = implode(",", $class_no);

    // 현재 강의 쿼리
    $now_class_list = [];
    if (!empty($class_no_list)) {
      $now_class = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_list) AND start_date <= '$today' AND end_date >= '$today'";
      $now_class_result = mysqli_query($conn, $now_class);
      while ($row = mysqli_fetch_array($now_class_result)) {
        $now_class_list[] = $row;
      }

      // 지난 강의 쿼리
      $past_class_list = [];
      $past_class = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_list) AND end_date < '$today'";
      $past_class_result = mysqli_query($conn, $past_class);
      while ($row = mysqli_fetch_array($past_class_result)) {
        $past_class_list[] = $row;
      }
    }

    // 출석 여부 확인
    $attend_check = [];
    if (!empty($class_no_list)) {
      $check_attendance = "SELECT class_no FROM easycook_attendance WHERE id='$id' AND DATE(datetime)='$today'";
      $check_result = mysqli_query($conn, $check_attendance);
      while ($row = mysqli_fetch_array($check_result)) {
        $attend_check[] = $row['class_no'];
      }
    }

  } else {
    $now_class_list = [];
    $past_class_list = [];
    $attend_check = [];
  }

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 마이페이지</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <style>
    /* -----------마이페이지 서식------------ */
    section{
      padding: 0 20px;
      max-width: 1025px;
      margin: 0 auto;
    }
    section .title_box{
      padding: 40px 0 20px 0;
      display: flex;
      justify-content: space-between;
    }
    section .title_box a{
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 5px;
    }
    section > div > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      display: inline-block;
    }
    /* 타이틀 서식 */
    .mytitle{
      display: flex;
      justify-content: space-between;
      align-items:center;
      cursor: pointer;
    }
    .mytitle > i{
      color: #aaa;
      font-size: var(--fs-large);
    }
    .mytitle > div{
      display: flex;
      align-items:center;
      gap: 10px;
      margin-left: 10px;
    }
    .mytitle > div > div{
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .mytitle > div > div p:nth-of-type(1){
      font-size: var(--fs-large);
      font-weight: var(--fw-semi-bold);
    }
    .mytitle > div > div p:nth-of-type(2){
      font-size: var(--fs-medium);
      font-weight: var(--fw-normal);
      color: #888;
    }
    /* 프로필이미지 서식 */
    #profile_img{
      width: 50px; height: 50px;
      border-radius:50%;
      border: 2px solid #ccc;
    }


    /* -----------아이콘 메뉴 서식--------- */
    .icon_menu{
      width: 100%;
      background: #fff;
      margin-top: 20px;
    }
    .icon_menu ul{
      display: flex;
      justify-content: space-evenly;
      width: 100%;
      padding: 0;
    }
    .icon_menu ul li{
      text-align: center;
      width: 60px; height: 60px;
      list-style: none;
    }
    .icon_menu a{
      display: flex;
      flex-direction: column;
      text-decoration: none;
      width: 100%;
      height: 100%;
      color: #666;
      justify-content: center;
      align-items: center;
      gap: 5px;
    }
    /* 아이콘 크기 조정 */
    .icon_menu ul li i{
      font-size: var(--fs-xlarge);
      color: var(--black);
    }
    .bi-heart::before{
      transform: scale(0.9);
    }
    .bi-person::before{
      transform: scale(1.1);
    }
    /* 글씨 스타일 조정 */
    .icon_menu ul li span{
      text-align: center;
      font-size: var(--fs-small);
      font-weight: var(--fw-light);
      color: var(--black);
      text-wrap: nowrap;
      margin-top: 5px;
    }

    /* -----------강의 리스트 서식--------- */
    .my_class{
      margin-top: 30px;
      padding: 0;
    }
    .my_class .tab > ul{
      gap: 0;
    }
    .my_class .tab > ul li button{
      border: none;
      border-bottom: 3px solid #ccc;
      background: #fff;
      color: #888;
    }
    .my_class .tab > ul li button.active{
      border-bottom: 3px solid var(--red);
      color: var(--red);
    }
    .tab_con{
      padding: 20px;
    }
    .tab_con .btn-s.line{
      border: 1px solid #888;
      color: #888;
    }

    /* 로그아웃 버튼 */
    .btn-outline-secondary:hover,
    .btn-outline-secondary:focus,
    .btn-outline-secondary:active {
      box-shadow: none;
      background-color: transparent;
      border-color: #000; /* 회색 테두리 색상 */
      color: #000;
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header.php');?>

  <main id="myPage">
    <section>
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <b><a href="./mypage.php" title="마이페이지">마이페이지</a></b>
      </p>
      <div class="title_box">
        <h2>마이페이지</h2>
        <div>
        <?php 
        if (isset($_SESSION['id'])){
          echo "<a style='color:#666; padding:5px 10px; display:inline-block; border:1px solid #ccc; border-radius:5px;' href='./act/logout.php' title='로그아웃'>로그아웃</a>";
        ?>
        <?php
          if ($teacher_code) {
            echo "<a style='color:#666; padding:5px 10px; display:inline-block; border:1px solid #ccc; border-radius:5px;' href='./admin/index.php' title='강사페이지'>강사페이지</a>";
          }
        }else{
          echo "";
        }
        ?>
        </div>
      </div>
      <?php
        if (isset($_SESSION['id'])) {
          echo "
          <div class='mytitle'>
            <div>
              <img id='profile_img' src='./uploads/profile/$profile' alt='프로필이미지'>
              <div>
                <p>$name 님 환영합니다!</p>
                <p>오늘 하루도 화이팅:)</p>
              </div>
            </div>
            <i class='bi bi-chevron-right'></i>
          </div>";
        } else {
          echo "
          <div class='mytitle'>
            <div>
              <img id='profile_img' src='./uploads/profile/profile.png' alt='프로필이미지'>
              <div>
                <p class='fs-5'>로그인이 필요합니다.</p>
              </div>
            </div>
            <i class='bi bi-chevron-right'></i>
          </div>";
        }
      ?>
      <nav class='icon_menu'>
        <ul>
          <li>
            <a href='./order_list.php' title='신청목록'>
              <i class='bi bi-bag-check'></i>
              <span>신청목록</span>
            </a>
          </li>
          <li>
            <a href='./reserve_list.php' title='실습실'>
              <i class='bi bi-door-closed'></i>
              <span>실습실</span>
            </a>
          </li>
          <li>
            <a href='./inquire_list.php' title='나의 문의'>
              <i class='bi bi-chat-square-dots'></i>
              <span>나의 문의</span>
            </a>
          </li>
          <li>
            <a href='./review_list.php' title='나의 후기'>
              <i class='bi bi-pencil-square'></i>
              <span>나의 후기</span>
            </a>
          </li>
        </ul>
      </nav>
    </section>

    <section class="my_class">
      <!-- 탭 컨텐츠 형식 -->
      <div class="tab">
        <ul>
          <li class="tab-item">
            <button class="tab-link active" data-tab-target="#tab1" type="button">현재 강의</button>
          </li>
          <li class="tab-item">
            <button class="tab-link" data-tab-target="#tab2" type="button">지난 강의</button>
          </li>
        </ul>
        <div class="tab_con">
          <div class="active" id="tab1">
            <!-- 상품목록 카드 스타일 -->
            <ul class="card-list">
              <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
              <?php if (isset($_SESSION['id'])): ?>
                <?php if (empty($now_class_list)): ?>
                  <li>현재 강의가 없습니다.</li>
                <?php else: ?>
                  <?php foreach ($now_class_list as $row): ?>
                    <li>
                      <div onclick="location.href='./myclass_info.php?class_no=<?= $row['class_no']; ?>'" style="cursor:pointer;">
                        <!-- 강의 썸네일 이미지 -->
                        <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="나의 강의정보">
                          <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
                        </a>
                        <!-- 강의 이름 -->
                        <div>
                          <h2>
                            <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="강의이름">
                              <?php echo $row['name']; ?>
                            </a>
                          </h2>

                          <!-- 강의 # 태그 -->
                          <p>
                            <span>#<?php echo $row['category2']; ?></span>
                            <span>#<?php echo $row['category1']; ?></span>
                            <span>#<?php echo $row['category3']; ?></span>
                          </p>

                          <!-- 기간 -->
                          <div>
                            <span><?php echo $row['start_date']; ?> ~ <?php echo $row['end_date']; ?></span>
                          </div>
                        </div>
                      </div>

                      <!-- 버튼이 들어가는 경우에만 삽입 -->
                      <div>
                        <div class="btn-box-s mt-4">
                          <a href="./reserve.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">실습실 예약</a>
                          <a href="./inquire.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">문의하기</a>
                        </div>
                        <div class="btn-box-l mt-2 mb-2">
                          <button class="btn-l attend" data-class-no="<?= $row['class_no']; ?>" 
                          <?php if (in_array($row['class_no'], $attend_check)): ?> 
                            disabled style="background:#aaa;">출석완료
                          <?php else: ?>
                            >출석체크
                          <?php endif; ?>
                          </button>
                        </div>
                        
                      </div>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
                <?php else: ?>
                  <!-- 세션 ID가 없을 때 출력할 메시지 -->
                  <li>로그인이 필요한 서비스입니다.</li>
              <?php endif; ?>
            </ul>
          </div>
          <div id="tab2">
            <!-- 상품목록 카드 스타일 -->
            <ul class="card-list">
              <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
              <?php if (isset($_SESSION['id'])): ?>
                <?php if (empty($past_class_list)): ?>
                  <li>지난 강의가 없습니다.</li>
                <?php else: ?>
                  <?php foreach ($past_class_list as $row): ?>
                    <?php
                      // 전에 쓴 리뷰가 존재하는지 확인
                      $classNo = $row['class_no'];
                      $query = "SELECT 1 FROM easycook_review WHERE class_no = '$classNo' AND id = '$id'";
                      $result = mysqli_query($conn, $query);
                      $isReviewed = mysqli_num_rows($result) > 0;
                    ?>
                    <li>
                      <div onclick="location.href='./myclass_info.php?class_no=<?= $row['class_no']; ?>'" style="cursor:pointer;">
                        <!-- 강의 썸네일 이미지 -->
                        <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="나의 강의정보">
                          <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
                        </a>
                        <!-- 강의 이름 -->
                        <div>
                          <h2>
                            <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="강의 이름">
                              <?php echo $row['name']; ?>
                            </a>
                          </h2>

                          <!-- 강의 # 태그 -->
                          <p>
                            <span>#<?php echo $row['category2']; ?></span>
                            <span>#<?php echo $row['category1']; ?></span>
                            <span>#<?php echo $row['category3']; ?></span>
                          </p>

                          <!-- 기간 -->
                          <div>
                            <span><?php echo $row['start_date']; ?> ~ <?php echo $row['end_date']; ?></span>
                          </div>
                        </div>
                      </div>

                      <!-- 버튼이 들어가는 경우에만 삽입 -->
                      <div>
                        <div class="btn-box-s mt-4">
                          <a href="./reserve.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">실습실 예약</a>
                          <a href="./inquire.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">문의하기</a>
                        </div>
                        <div class="btn-box-l mt-2 mb-2">
                          <!--후기작성으로 이동-->
                          <a href="./review_write.php?class_no=<?= $row['class_no']; ?>" 
                          class="btn-l" 
                          <?= $isReviewed ? 'style="background:#aaa; pointer-events:none;"' : ''; ?>>
                          <?= $isReviewed ? '후기작성 완료' : '후기작성'; ?>
                          </a>
                        </div>
                      </div>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
                <?php else: ?>
                  <!-- 세션 ID가 없을 때 출력할 메시지 -->
                  <li>로그인이 필요한 서비스입니다.</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </section>




  </main>

  <!-- 공통 바텀바삽입 -->
  <?php include('./inc/bottom.php');?>
  
  <script>
    $(document).ready(function() {

      //탭 콘텐츠 버튼
      $('#myPage .tab-link').on('click', function() {
        $('#myPage .tab-link').removeClass('active');
        $(this).addClass('active');
        
        // 모든 탭 콘텐츠 숨기기
        $('#myPage .tab_con > div').removeClass('active');
        // 데이터 속성으로 타겟팅된 탭 콘텐츠 보이기
        var target = $(this).data('tab-target');
        $(target).addClass('active');
      });

      //1025px 초과되는 순간 자동으로 index.php로 이동
      $(window).on('load resize', function(){
        if (window.innerWidth > 1025) location.replace('./index.php');
      });
    });



  </script>




</body>
</html>