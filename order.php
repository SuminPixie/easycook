<?php
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  include('./inc/dbconn.php');

  //사용자가 로그인한 경우 결제할 상품 보여줌
  if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];

    $class_no = $_GET['class_no'];

    $sql = "select * from easycook_academy_list where class_no = '$class_no'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
    
    // academy_info에서 가격 정보 가져오기
    $code = $row['code'];
    $sql_price = "SELECT price FROM easycook_academy_info WHERE code = '$code'";
    $result_price = mysqli_query($conn, $sql_price);
    $row_price = mysqli_fetch_array($result_price);


  } else {
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
  <title>수강신청</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <style>
    /* -----------수강신청 페이지 서식------------ */
    section{
      padding: 0 20px;
    }
    section > h2{
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 40px 0 20px 0;
      text-align: center;
    }
    .col{
      max-width: 600px;
      margin: 0 auto;
    }
    .card{
      padding: 20px;
    }
    .card img{
      max-height: 200px;
      object-fit: cover;
      border-radius: 5px;
    }
    .card-body{
      padding: 10px 10px 0 10px;
    }
    .card-title{
      font-size: var(--fs-medium-large);
      margin: 10px 0;
    }
    .card-text{
      line-height: 160%;
    }
    /* 가격 */
    .price{
      display: flex;
      justify-content: space-between;
      font-size: var(--fs-large);
      font-weight: bold;
      padding: 0 10px;
      margin: 20px 0 50px 0;
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section class="container mt-5">
      <h2 class="mb-3">수강신청</h2>
      <!-- 상품목록 카드 스타일 -->
      <ul class="row">
        <li class="col">
          <div class="card">
            <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" class="card-img-top" alt="강의 썸네일 사진">
            <div class="card-body">
              <p class="card-title"><strong><?php echo $row['name']; ?></strong></p>
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
          <p class="price">
            <span>결제 금액</span>
            <span><?php echo number_format($row_price['price']); ?>원</span>
          </p>
          <div class="btn-box-l mb-5">
            <a href="./act/order_input.php?class_no=<?= $row['class_no']; ?>" class="btn-l">결제하기</a>
            <button class="btn-l" onclick="history.back();">이전으로</button>
          </div>
        </li>
      </ul>
    </section>


  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>

  <!-- 공통 바텀바삽입 -->
  <?php include('./inc/bottom.php');?>


  <script>
    //찜목록에서 삭제하는 기능
    function removeCart(classNo) {
      $.ajax({
        url: './act/cart_toggle.php',
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