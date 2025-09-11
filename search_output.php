<?php
  session_start();
  include('./php/include/dbconn.php');

  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
  }else{
    $id = null;
  }

  // 사용자가 카트에 담은 상품 목록 가져오기
  $cart_class_no = [];
  if ($id !== null) {
    $cart_sql = "SELECT class_no FROM cart WHERE id = '$id'";
    $cart_result = mysqli_query($conn, $cart_sql);
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
      $cart_class_no[] = $cart_row['class_no'];
    }
  }

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>강의 검색 | 이지쿡</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./php/include/head.php'); ?>
  <style>
    .search_output{
      padding: 0 20px;
      max-width: 1200px;
      margin: 0 auto;
      min-height: 100vh;
    }
    .search_output > h2{
      text-indent: -99999px;
      font-size: 1px;
    }
    .search_output > article{
      width : 100%;
      height: 100px;
      padding: 20px 0 20px 0;
    }
    .search_output > article > h3{
      padding: 0px 0px 13px 0;
    }
    .search_b{
      padding: 5px 10px;
      border-radius:10px;
      border: 1px solid var(--red);
      color: var(--red);
      margin: 0 10px 20px 0;
    }

  </style>

</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header_sub.php');?>

  <!-- 메인서식 -->
  <main>    
    
    <!-- 강의 목록 -->
    <section class="search_output">
      <h2>강의 검색</h2>
      <article>
        <h3>
          <?php 
          $search_key = isset($_POST['search_key']) ? $_POST['search_key'] : null;
          $search_key2 = isset($_GET['search_key2']) ? $_GET['search_key2'] : null;
          ?>

          '<strong><?php echo $search_key.$search_key2; ?></strong>'(으)로 검색한 결과입니다.
        </h3>
        <p>
          <a href="search.php" title ="다시 검색하기" class="search_b">다시 검색</a>
          <a href="academy_all.php" title ="전체 강의" class="search_b">전체 강의</a>
        </p>
      </article>

      <div class="mb-5">
        <div class="card-list-box">
          <!-- 여기에 리스트 들어감 -->
          <?php 

            if(isset($search_key)){
              // echo "search_key".$search_key;
              $sql = "select * from academy_list where
              name like '%$search_key%' or
              category1 like '%$search_key%' or
              category2 like '%$search_key%' or
              category3 like '%$search_key%' or
              teacher like '%$search_key%' or
              detail like '%$search_key%'
              order by start_date desc";
              $query = mysqli_query($conn, $sql);
              $result = mysqli_fetch_array($query);
            }else{
              // echo "search_key2".$search_key2;
              $sql = "select * from academy_list where
              name like '%$search_key2%' or
              category1 like '%$search_key2%' or
              category2 like '%$search_key2%' or
              category3 like '%$search_key2%' or
              teacher like '%$search_key2%' or
              detail like '%$search_key2%'
              order by start_date desc";
              $query = mysqli_query($conn, $sql);
            }
            while($result = mysqli_fetch_array($query)){
          ?>
          <div>
            <div class="cart" data-no="<?php echo $result[0];?>">
              <img src="./images/common/heart_w.png" alt="찜버튼">
            </div>
            <a href="./detail.php?class_no=<?php echo $result[0];?>" title="<?php echo $result[1];?> 보기">
              <img src="./uploads/class_main/<?php echo $result[18];?>" alt="<?php echo $result[1];?> 사진">
              <div class="con-text">
                <p class="con-title"><?php echo $result[1];?></p>
                <p class="con-sub"><?php echo $result[20];?></p>
              </div>
            </a>
          </div>
          <?php  } ?>

        </div>
      </div>
    </section>

  </main>



  <!-- 공통푸터삽입 -->
  <?php include('./php/include/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>
  <!-- 스와이퍼 js -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.5/swiper-bundle.min.js"></script>  
  <!-- 공통스크립트 연결 -->
  <script src="./script/common.js"></script>
</body>
</html>