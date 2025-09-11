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

    // 세션 정보를 업데이트
    $_SESSION['name'] = $name;
    $_SESSION['profile'] = $profile;

    // --------------내 강의 정보 불러오는 부분----------------
    // cart 테이블에서 세션ID와 일치하는 class_no 값을 가져오기
    $sql_my = "SELECT class_no FROM easycook_cart WHERE id='$id'";
    $result_my = mysqli_query($conn, $sql_my);

    // class_no 값을 배열로 저장
    $class_no = array();
    while ($row_my = mysqli_fetch_array($result_my)) {
      $class_no[] = $row_my['class_no'];
    }

    // 현재 강의 쿼리
    $class_list = [];
    if (!empty($class_no)) {
        // 배열을 문자열로 변환하여 쿼리 작성
        $class_no_str = implode(',', array_map('intval', $class_no));

        // 강의 목록 쿼리 작성
        $class_sql = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_str)";
        
        // 쿼리 실행
        $class_result = mysqli_query($conn, $class_sql);
        
        // 결과를 배열로 저장
        while ($row = mysqli_fetch_assoc($class_result)) {
            $class_list[] = $row;
        }
    }
  } else {
    $class_list = [];
  }


?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>찜 목록</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <style>
    /* -----------찜목록 서식------------ */
    section{
      padding: 0 20px;
      max-width: 1025px;
      margin: 0 auto;
    }
    section > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 40px 0 20px 0;
      text-align: center;
    }
    main{
      height: 100%;
      min-height: 100vh;
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header.php');?>

  <main>
    <section>
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./cart.php" title="찜 목록">찜 목록</a></b>
      </p>
      <h2>찜 목록</h2>
      <!-- 상품목록 카드 스타일 -->
      <ul class="card-list">
        <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
        <?php if (isset($_SESSION['id'])): ?>
          <?php if (empty($class_list)): ?>
            <li>찜 한 강의가 없습니다.</li>
          <?php else: ?>
            <?php foreach ($class_list as $row): ?>
              <li id="class-<?= $row['class_no']; ?>">
                <div onclick="location.href='./detail.php?class_no=<?= $row['class_no']; ?>'" style="cursor:pointer;">
                  <!-- 강의 썸네일 이미지 -->
                  <a href="./detail.php?class_no=<?= $row['class_no']; ?>" title="상세페이지로 이동">
                    <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
                  </a>
                  <!-- 강의 이름 -->
                  <div>
                    <h2>
                      <a href="./detail.php?class_no=<?= $row['class_no']; ?>" title="상세페이지로 이동">
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
                    <button class="btn-s order_btn" data-class-no="<?= $row['class_no']; ?>">수강신청</button>
                    <button class="btn-s" onclick="removeCart('<?= $row['class_no']; ?>')">삭제</button>
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
    </section>


  </main>
  
  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>

  <!-- 공통 바텀바삽입 -->
  <?php include('./inc/bottom.php');?>


  <script>
    $(document).ready(function() {
      // 수강신청 버튼 기능
      $('.order_btn').on('click', function() {
        var classNo = $(this).data('class-no');

        window.location.href = 'order.php?class_no=' + encodeURIComponent(classNo);
      });
    });



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
  </script>
</body>
</html>