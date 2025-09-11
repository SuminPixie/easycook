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
    // order 테이블에서 세션ID와 일치하는 class_no 값을 가져오기
    $sql_my = "SELECT class_no FROM `easycook_order` WHERE id='$id'";
    $result_my = mysqli_query($conn, $sql_my);

    // class_no 값을 배열로 저장
    $class_no = array();
    while ($row_my = mysqli_fetch_array($result_my)) {
      $class_no[] = $row_my['class_no'];
    }

    // 강의목록
    $class_list = [];
    if (!empty($class_no)) {

      $class_no_str = implode(',', array_map('intval', $class_no));
      $class_sql = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_str)";
      $class_result = mysqli_query($conn, $class_sql);
      
      // 결과를 배열로 저장
      while ($row = mysqli_fetch_assoc($class_result)) {
        $class_no = intval($row['class_no']);
        //중도포기 강좌 조회
        //$student_status = $row['student_status'];
        
        // 각 강의의 결제 정보와 중도포기상태 가져오기
        $order_info_sql = "SELECT name, datetime, student_status FROM `easycook_order` WHERE class_no = $class_no AND id = '$id'";
        $order_info_result = mysqli_query($conn, $order_info_sql);
        $order_info = mysqli_fetch_assoc($order_info_result);

        $row['order_name'] = $order_info['name'];
        $row['order_datetime'] = $order_info['datetime'];
        $row['student_status'] = $order_info['student_status'];

        // 강의목록 저장
        $class_list[] = $row;
      }

      // start_date를 기준으로 정렬 (내림차순)
      usort($class_list, function($a, $b) {
        return strtotime($b['start_date']) - strtotime($a['start_date']);
      });
    }
  } else {
    $class_list = [];
  }

  // 현재 날짜를 가져오기
  $today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 신청목록</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <style>
    /* -----------신청목록 서식------------ */
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

    /* ---------모달 스타일--------- */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.4);
      padding-top: 60px;
    }
    .modal-content {
      background-color: #fefefe;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #888;
      top: 50%;
      transform: translateY(-50%);
      width: 300px;
      min-height: 300px;
    }
    .modal-content h2{
      margin: 10px 0;
      text-align: center;
      font-size: var(--fs-medium-large);
    }
    .modal-content p{
      line-height: 160%;
    }
    .close {
      color: #aaa;
      font-size: 30px;
      font-weight: bold;
      width: 40px;height: 40px;
      text-align: center;
      position: absolute;
      top: 10px; right:10px;
    }
    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    /* 중도포기 후 비활성화된 강의 항목 스타일 */
    .disabled {
      opacity: 0.5;
      pointer-events: none;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section>
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./order_list.php" title="신청목록">신청목록</a></b>
      </p>
      <h2>신청 목록</h2>
      <!-- 상품목록 카드 스타일 -->
      <ul class="card-list">
        <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
        <?php if (isset($_SESSION['id'])): ?>
          <?php if (empty($class_list)): ?>
            <li>신청한 강의가 없습니다.</li>
          <?php else: ?>
            <?php foreach ($class_list as $row): ?>
              <li id="class-<?= $row['class_no']; ?>" 
                data-order-name="<?= $row['order_name'] ?>" 
                data-order-datetime="<?= $row['order_datetime'] ?>" class="<?= $row['student_status'] === '중도포기' ? 'disabled' : '' ?>">
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
                    <?php
                      if ($row['student_status'] === '중도포기') {
                        // 중도포기 상태인 경우
                        echo '<button class="btn-s" style="background: #aaa; color: #fff; font-weight: bold;" disabled>중도포기 완료</button>';
                      } elseif ($row['end_date'] < $today) {
                        // 강의 종료일이 오늘보다 이전일 때: 수강 완료 버튼
                        echo '<button class="btn-s" style="background: #888; color: #fff; font-weight: bold;" disabled>수강완료</button>';
                      } elseif ($row['start_date'] <= $today) {
                        // 강의 시작일이 오늘 또는 그 이전일 때: 중도 포기 버튼
                        echo '<button class="btn-s stop" onclick="abandonOrder(\'' . $row['class_no'] . '\')" style="background: var(--yellow); color: #333; font-weight: bold;">중도포기</button>';
                      } else {
                        // 강의 시작일이 오늘보다 이후일 때: 신청 취소 버튼
                        echo '<button class="btn-s" onclick="removeOrder(\'' . $row['class_no'] . '\')">신청취소</button>';
                      }
                    ?>
                    <button class="btn-s order_info" data-class-no="<?= $row['class_no']; ?>">결제정보</button>
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
  
  
  <!-- 결제정보 모달 -->
  <div id="paymentModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>결제 정보</h2>
      <p id="orderName">이름: <span></span></p>
      <p id="orderDateTime">결제시간: <span></span></p>
    </div>
  </div>



  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>

  <!-- 공통 바텀바삽입 -->
  <?php include('./inc/bottom.php');?>


  <script>
    $(document).ready(function() {
      // 결제정보 버튼 기능
      $('.order_info').on('click', function() {
        var classNo = $(this).data('class-no');
        var classElement = $('#class-' + classNo);

        // 선택한 강의 항목에서 결제 정보를 가져옴
        var orderName = classElement.data('order-name');
        var orderDateTime = classElement.data('order-datetime');

        // 모달에 결제 정보 표시
        $('#orderName span').text(orderName);
        $('#orderDateTime span').text(orderDateTime);

        // 모달 표시
        $('#paymentModal').show();
      });

      // 모달 닫기 버튼
      $('.close').on('click', function() {
        $('#paymentModal').hide();
      });


      // 페이지 로드 시 .disabled 클래스를 가진 강의의 버튼 상태 업데이트
      $('.disabled').each(function() {
        var classElement = $(this);
        var button = classElement.find('.btn-s.stop');
        
        // 버튼의 텍스트와 스타일을 업데이트
        button.text('중도포기 완료')
              .css('background', '#999')
              .css('cursor', 'not-allowed')
      });
      
      // 수강 완료 버튼이 있는 항목들을 아래로 이동
      var completedItems = $('.card-list li').filter(function() {
        return $(this).find('.btn-s').text() === '수강완료';
      }).detach();
      $('.card-list').append(completedItems);
      
      // 페이지 로드 시 .disabled 클래스를 가진 강의 항목을 리스트의 맨 아래로 이동
      var disabledItems = $('.disabled').detach();
      $('.card-list').append(disabledItems);
      
    });




    //신청취소 누르면 삭제하는 기능
    function removeOrder(classNo) {
      if (confirm('신청을 취소하시겠습니까?')){
        $.ajax({
          url: './act/order_list_delete.php',
          type: 'POST',
          data: {
            class_no: classNo,
            action: 'remove'
          },
          success: function(response) {
            console.log(response);
            alert('신청이 취소되었습니다.');
            // 삭제 성공 후 리스트에서 항목 제거
            $('#class-' + classNo).remove();
          },
          error: function(xhr, status, error) {
            console.error('요청 실패:', error);
          }
        });
      }
    }

    // 중도포기 누르면 상태를 변경하는 기능
    function abandonOrder(classNo) {
      if (confirm('중도 포기를 하시겠습니까?')){
        $.ajax({
          url: './act/order_list_delete.php',
          type: 'POST',
          data: {
            class_no: classNo,
            action: 'abandon'
          },
          success: function(response) {
            console.log(response);
            // 중도 포기 후 리스트에서 항목 비활성화
            var classElement = $('#class-' + classNo);
            classElement.addClass('disabled');
            // 중도 포기 버튼 텍스트 변경
            classElement.find('.btn-s.stop').text('중도포기 완료').css('background', '#999');
          },
          error: function(xhr, status, error) {
            console.error('요청 실패:', error);
          }
        });
      }
    }

  </script>
</body>
</html>