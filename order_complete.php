<?php
  include('./inc/dbconn.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡</title>
    <!-- head.php -->
    <?php include('./inc/head.php');?>

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
      @media (min-width: 1400px) {
        .re_com {
          width: 1400px;
        }
        .re_com article{
          width:50%;
          margin:0 auto;
          text-align:center;
          padding-top:180px;
        }
      }

      /* 체크 아이콘 추가서식 */
      .checkmark-container{
        margin: 0 auto;
      }
    </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

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
        <h2>결제 완료</h2>
        <p>강의 신청이 완료되었습니다.</p>
        <div class="btn-box-l">
          <a href="./order_list.php" class="btn-l">신청 목록보기</a>
        </div>
      </article>
    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>

</body>
</html>