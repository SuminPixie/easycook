<?php
// 세션이 이미 시작되었는지 확인
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include('./inc/dbconn.php');

if (isset($_SESSION['id'])) {
  // $id = $_SESSION['id'];
  // $name = $_SESSION['name'];

  //내가 선택한 강의정보 조회
  $class_no = $_GET['class_no'];

  $sql = "select * from easycook_academy_list where class_no = '$class_no'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);
  
}else{
  echo "<script>
    alert('로그인이 필요한 서비스입니다.');
    window.location.href = './login.php';
  </script>";
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>실습실 예약</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <!-- flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    /* -----------실습실 예약 서식------------ */
    section{
      padding: 0 20px;
    }
    /* 768px 이상일때 요소배치 */
    @media (min-width: 768px) {
      section{
        width: 600px;
        margin: 50px auto 0 auto;
      }
    }
    section > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 40px 0 10px 0;
      text-align: center;
    }

    /* 달력 서식 */
    #datepicker {
      display: none;
    }
    .flatpickr-calendar {
      width: 100%;
      box-sizing: border-box;
      padding: 10px;
    }
    .flatpickr-rContainer{
      width: 100%;
    }
    .flatpickr-month{
      margin-bottom: 20px;
    }
    .flatpickr-prev-month, .flatpickr-next-month{
      top: 6px !important;
    }
    .flatpickr-days{
      width: 100%;
    }
    .dayContainer{
      width: 100%;
      min-width: unset;
      max-width: unset;
    }
    .flatpickr-day{
      width: calc(100% / 7);
      max-width: calc(100% / 7);
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover{
      background: var(--green);
    }

    /* 월과 연도 순서 변경 */
    .flatpickr-current-month {
      display: flex;
      flex-direction: row-reverse;
      align-items: center;
      justify-content: center;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-months {
      order: 1;
    }
    .numInputWrapper {
      order: 0;
    }

    /* 폼 서식 */
    #reserve p{
      padding: 0 10px;
      font-size: var(--fs-medium);
      font-weight: var(--fw-semi-bold);
      margin-bottom: 15px;
    }
    .form-group {
      margin-bottom: 10px;
    }
    .radio-group label {
      display: block;
      cursor: pointer;
      border: 2px solid var(--gray);
      border-radius: 5px;
      padding: 10px 20px;
      margin: 0px;
      transition: background-color 0.3s, color 0.3s;
    }
    .radio-group input[type="radio"] {
      display: none;
    }
    .radio-group input[type="radio"]:checked + label {
      background-color: rgba(38, 164, 80, 0.08);
      border-color: var(--green);
    }
    /* 실습실 선택 */
    .room{
      width:100%; overflow:hidden;
    }
    .room label{
      padding: 0;
      height: 150px;
      border: 5px solid rgba(0,0,0,0);
      border-radius: 5px;
      position: relative;
    }
    .room label img{
      width: 100%; height: 100%;
      object-fit:cover;
      border-radius: 5px;
    }
    .room label span{
      position: absolute;
      width: 100%;
      left:0px;
      bottom: 0px;
      color: var(--white);
      font-size: var(--fs-medium-large);
      background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 30%, rgba(0, 0, 0, 0));
      padding: 30px 0 10px 10px; 
      border-radius: 5px; 
    }

    /* 시간 선택 */
    .time .form-group label{
      display: flex;
      justify-content: space-between;
    }

    .disabled-slot {
      opacity: 0.6; /* 비활성화된 상태의 투명도 */
      pointer-events: none; /* 클릭 이벤트를 차단 */
      background-color: #e9ecef; /* 비활성화된 상태의 배경색 */
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section id="reserve">
      <h2>실습실 예약</h2>
      <!-- 예약 폼 -->
      <form action="./act/reserve_input.php?class_no=<?= $row['class_no']; ?>" method="POST" id="reserve_form">
        <!-- 날짜 선택 -->
        <div class="mb-5">
          <label for="datepicker"></label>
          <input type="text" id="datepicker" name="selected_date" class="form-control" placeholder="날짜를 선택하세요">
        </div>

        <!-- 실습실 선택 리스트 -->
        <div class="mb-4 room">
          <p>실습실 선택</p>
          <div class="swiper-container radio-group">
            <div class="swiper-wrapper">
              <div class="swiper-slide form-group">
                <input type="radio" id="room1" name="room" value="101" checked>
                <label for="room1">
                  <img src="./images/sub/room_101.jpg" alt="101호 이미지">
                  <span>101호</span>
                </label>
              </div>
              <div class="swiper-slide form-group">
                <input type="radio" id="room2" name="room" value="102">
                <label for="room2">
                  <img src="./images/sub/room_102.jpg" alt="102호 이미지">
                  <span>102호</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- 사용시간 및 예약상황 -->
        <div class="mb-4">
          <p>
            <span>사용시간</span>
            <span style="float:right;">예약상황</span>
          </p>
          <div class="radio-group time">
            <div class="form-group">
              <input type="radio" id="time1" name="time_slot" value="08:00-09:00">
              <label for="time1">
                <span>08:00 - 09:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time2" name="time_slot" value="09:00-10:00">
              <label for="time2">
                <span>09:00 - 10:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time3" name="time_slot" value="10:00-11:00">
              <label for="time3">
                <span>10:00 - 11:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time4" name="time_slot" value="11:00-12:00">
              <label for="time4">
                <span>11:00 - 12:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time5" name="time_slot" value="12:00-13:00">
              <label for="time5">
                <span>12:00 - 13:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time6" name="time_slot" value="13:00-14:00">
              <label for="time6">
                <span>13:00 - 14:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time7" name="time_slot" value="15:00-16:00">
              <label for="time7">
                <span>15:00 - 16:00</span>
                <span></span>
              </label>
            </div>
            <div class="form-group">
              <input type="radio" id="time8" name="time_slot" value="16:00-17:00">
              <label for="time8">
                <span>16:00 - 17:00</span>
                <span></span>
              </label>
            </div>
          </div>
        </div>

        <!-- 버튼 -->
        <div class="btn-box-l mb-5">
          <button type="submit" class="btn-l" style="background:var(--green);">예약하기</button>
          <a href="./mypage.php" class="btn-l">마이페이지로</a>
        </div>
      </form>

    </section>
  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>


  <!-- 스와이퍼 js -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.5/swiper-bundle.min.js"></script>


  
  <!-- flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- 한국어 locale JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>


  <script>
    //------스와이퍼---------
    var swiper = new Swiper('.swiper-container', {
      //0 ~ 375일때 (작은 모바일일때 설정)
      slidesPerView: 1.5,
      spaceBetween: 12,
      touchRatio: 1,
      simulateTouch: true,
      breakpoints : { //반응형 설정
        376 : { //376~767일때 (큰 모바일일때 설정)
          slidesPerView : 2.4,
          spaceBetween: 12,
        },
        768 : { //768~일때 (태블릿 이상일때 설정)
          slidesPerView : 2.4,
          spaceBetween: 12,
        }
      }
    });


    // ----------달력-----------
    flatpickr.localize(flatpickr.l10ns.ko);
    var today = new Date(); // 현재 날짜

    var fp = flatpickr("#datepicker", {
      local: "ko", // 한국어로 설정
      dateFormat: "Y-m-d", // 날짜 형식 설정
      minDate: "today", // 오늘 날짜 이후로만 선택 가능
      inline: true, // 달력을 항상 보이도록 설정
      defaultDate: today, // 기본 날짜를 오늘 날짜로 설정
    });



    $(document).ready(function(){
      // ---------폼 제출 시 빈값 검사---------
      $('#reserve_form').submit(function(event) {
        event.preventDefault(); // 기본 제출 동작 막기
        var isFormValid = true;

        // 시간 선택 여부 확인
        if ($('input[name="time_slot"]:checked').length === 0) {
          alert('사용시간을 선택해주세요.');
          isFormValid = false;
        }

        if (isFormValid) {
          this.submit(); // 유효성 검사 통과 시 폼 제출
        }
      });

      // 예약 데이터 가져오기
      function updateReservationStatus() {
        var classNo = '<?php json_encode($class_no); ?>';
        var selectedDate = $('#datepicker').val(); // 선택한 날짜
        var roomNo = $('input[name="room"]:checked').val(); // 선택한 실습실
        // console.log('Selected Date:', selectedDate);
        // console.log('Room No:', roomNo);

        // 현재 시간 가져오기
        var now = new Date();
        var currentDate = now.toISOString().split('T')[0]; // YYYY-MM-DD 형식
        var currentHour = now.getHours();
        var currentMinute = now.getMinutes();
        var currentTime = (currentHour < 10 ? '0' : '') + currentHour + ':' + (currentMinute < 10 ? '0' : '') + currentMinute;

        $.ajax({
          url: './act/reserve_status.php',
          method: 'GET',
          data: { class_no: classNo, selected_date: selectedDate, room_no: roomNo},
          dataType: 'json',
          success: function(reservations) {
            var allSlotsFull = true;
            $('input[name="time_slot"]').each(function() {
              var timeSlot = $(this).val();
              var count = reservations[timeSlot] || 0;
              var max = 8; // 정원 수
              var label = $(this).next('label');
              label.find('span:last').text(count + '/' + max);
              
              // 현재 시간 이후의 시간 슬롯 비활성화
              var timeSlotStart = timeSlot.split('-')[0];
              
              // 선택한 날짜와 현재 날짜를 비교
              if (selectedDate < currentDate || (selectedDate === currentDate && timeSlotStart <= currentTime)) {
                label.addClass('disabled-slot');
                $(this).prop('checked', false); // 체크 해제
                $(this).prop('disabled', true);
              } else {
                label.removeClass('disabled-slot');
                $(this).prop('disabled', false);
                
                // 예약 정원이 가득 찬 경우 label에 비활성화 클래스 추가
                if (count >= max) {
                  label.addClass('disabled-slot');
                  $(this).prop('checked', false); // 체크 해제
                  $(this).prop('disabled', true);
                } else {
                  label.removeClass('disabled-slot');
                  $(this).prop('disabled', false);
                }

              }

        });
      },
      error: function() {
        console.error('예약 현황을 가져오는 데 실패했습니다.');
      }
    });
  }

      // 페이지 로드 시 예약 현황 업데이트
      updateReservationStatus();

      // 날짜 변경 시 예약 현황 업데이트
      $('#datepicker').on('change', function() {
        updateReservationStatus();
      });
      
      // 실습실 변경 시 예약 현황 업데이트
      $('input[name="room"]').on('change', function() {
        updateReservationStatus();
      });








    })



  </script>
</body>
</html>