<?php
  include('./php/include/dbconn.php');

  // URL 쿼리 문자열에서 데이터 가져오기
  $date = $_GET['date'];
  $room = $_GET['room'];
  $start = $_GET['start'];
  $end = $_GET['end'];

  // 날짜 형식 변환
  $dateObj = DateTime::createFromFormat('Y-m-d', $date);
  $formattedDate = $dateObj ? $dateObj->format('Y.m.d') : $date;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>예약완료</title>
    <!-- 공통 헤드정보 삽입 -->
    <?php include('./php/include/head.php'); ?>
    <!-- 메인서식 연결 -->
    <link rel="stylesheet" href="./css/main.css">

    <style>
      .hide{display:none;}
      .re_com{
        width:90%;
        margin:0 auto;
      }
      .re_com article{
        width:100%;
        text-align:center;
        padding-top:115px;
      }
      .re_com a{
        color:#fff;
        font-weight:var(--fw-normal);
      }
      .re_com h2{
        font-size:var(--fs-xlarge);
        font-weight:var(--fw-bold);
        margin-top:20px;
        margin-bottom:28px;
      }
      .re_com p{
        font-size:var(--fs-medium-large);
        margin-bottom:62px;
      }
      @media (min-width: 768px) {
        .re_com {
          width: 768px;
        }
        .re_com article{
          width:60%;
          margin:0 auto;
          text-align:center;
          padding-top:180px;
        }
      }

      /* 체크 아이콘 추가서식 */
      .checkmark-container{
        margin: 0 auto;
      }
      .checkmark-container svg .circle, .checkmark-container svg .checkmark{
        stroke: var(--green);
      }

      /* 테이블 서식 */
      .info-table{
        width: 100%;
        margin: 0 auto;
        margin-bottom: 20px;
      }
      .info-table td{
        text-align: left;
      }
    </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header_sub.php');?>

  <main>
    <section class="re_com">
      <article>
        <!-- 체크되는 애니메이션 아이콘 -->
        <div class="checkmark-container">
          <svg width="100" height="100" viewBox="0 0 100 100">
            <circle class="circle" cx="50" cy="50" r="45" />
            <path class="checkmark" d="M20,50 L45,75 L75,35" />
          </svg>
        </div>
        <h2>예약 완료</h2>
        <table class="table info-table">
          <tbody>
            <tr>
              <th>날짜</th>
              <td><?php echo htmlspecialchars($formattedDate); ?></td>
            </tr>
            <tr>
              <th>시간</th>
              <td><?php echo htmlspecialchars($start); ?> - <?php echo htmlspecialchars($end); ?></td>
            </tr>
            <tr>
              <th>장소</th>
              <td><?php echo htmlspecialchars($room); ?>호</td>
            </tr>
          </tbody>
        </table>
        <div class="btn-box-l">
          <a href="./reserve_list.php" class="btn-l" style="background: var(--green);">나의 예약으로</a>
        </div>
      </article>
    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>

</body>
</html>