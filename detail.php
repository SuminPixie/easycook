<?php
  session_start();
  include('./inc/dbconn.php');
  $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

  $class_no = $_GET['class_no'];

  $sql = "select * from easycook_academy_list where class_no = '$class_no'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);

  // academy_info에서 가격 정보 가져오기
  $code = $row['code'];
  $sql_price = "SELECT price FROM easycook_academy_info WHERE code = '$code'";
  $result_price = mysqli_query($conn, $sql_price);
  $row_price = mysqli_fetch_array($result_price);

  // 사용자가 카트에 담은 상품 목록 가져오기
  $cart_class_no = [];
  if ($id) {
    $cart_sql = "SELECT class_no FROM easycook_cart WHERE id = '$id'";
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
  <title>이지쿡</title>

  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <link rel="stylesheet" href="./css/sub.css"> 


</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>
  <style>
    header{
      background: linear-gradient(to bottom, rgba(255, 255, 255, 1) 0%,  rgba(255, 255, 255, 0.7) 50%, rgba(255, 255, 255, 0) 100%);
      border-bottom: none;
    }
    header h1{
      display: none;
    }


    #topButton{
      bottom: 110px !important;
    }
    /* PC버전 탑버튼 위치 */
    @media (min-width: 1025px) {
      #topButton{
      bottom: 65px !important;
      }
    }
  </style>

  <main style="margin:0;">
    <article class="detail_top">
      <h2 class="hide">강의 상세 페이지</h2>

      <img src="./uploads/class_main/<?php echo $row['thumnail_img'] ?>" alt="강의 썸네일 사진" class="img_size">
      <div class="table_box">
        <table class="table">
          <thead>
            <tr>
              <th colspan="2"><?php echo $row['name']; ?></th>
            </tr>
            <tr>
              <td colspan="2">
                <?php
                $start = strtotime($row['start_date']);
                $end = strtotime($row['end_date']);
                $days = ($end - $start) / (60 * 60 * 24);
                $weeks = floor($days / 7);
                echo "{$row['start_date']} ~ {$row['end_date']} ({$weeks}주)";
                ?>
              </td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>난이도</td>
              <td><?php echo $row['grade']; ?></td>
            </tr>
            <tr>
              <td>장소</td>
              <td><?php echo $row['place']; ?></td>
            </tr>
            <tr>
              <td>강사명</td>
              <td><?php echo $row['teacher']; ?></td>
            </tr>
            <tr>
              <td>수강료</td>
              <td><span class="yellow"><?php echo number_format($row_price['price']); ?></span> 원</td>
            </tr>
          </tbody>
        </table>
      </div>


      <div class="btn-box-s">
        <button class="btn-s line" id="cart">찜 하기</button>
        <a href="./order.php?class_no=<?= $row['class_no']; ?>" class="btn-s fill">수강신청</a>
      </div>
    </article>
    <!--여기서부터는 탭컨텐츠 상세, 후기 ,문의 -->
    <article class="t_con cook">
      <h2 class="hide">카테고리 소개페이지</h2>
      <!-- 탭 메뉴 버튼 -->
      <div class="tabs">
        <button class="t_mnu on2" data-tab="tab1">상세정보</button>
        <button class="t_mnu" data-tab="tab2">수강생 리뷰</button>
        <button class="t_mnu" data-tab="tab3">상담</button>
      </div>

      <!-- 탭 콘텐츠 -->
      <div class="tab-content">
        <!-- 상세정보 -->
        <div id="tab1" class="tab-pane active">
          <img src="./uploads/class_detail/<?php echo $row["img"] ?>" alt="상세정보" class="img_size">
        </div>
        
        <!-- 수강생 리뷰 -->
        <div id="tab2" class="tab-pane">
          <div class="community_con detail">
            <div class="community">
              <ul>
                <?php 
                  $sql2 = "SELECT * FROM easycook_review WHERE code = '$code'";
                  $result2 = mysqli_query($conn, $sql2);
                  
                  // 리뷰 데이터가 존재하는지 확인
                  if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_array($result2)) {
                      $id = $row2['id'];

                      $sql3 = "SELECT * FROM easycook_register WHERE id = '$id'";
                      $result3 = mysqli_query($conn, $sql3);
                      $row3 = mysqli_fetch_array($result3);
                ?>
                  <li>
                    <div style="display: flex;align-items:center;gap:10px;">
                      <p class="profile_img_size">
                        <img src="./uploads/profile/<?php echo $row3['profile'] ?>">
                      </p>

                      <p style="margin:0; color:#000; font-weight:normal;">
                        <!--이름-->
                        <?php echo $row2["name"] ?>
                      </p>

                    </div>
                    
                    <!-- 별점 -->
                    <p class="day_gray">
                    <?php
                      $review_star = "";
                      $star = $row2["star"];
                      for($i = 0; $i < 5; $i++){
                        if($i < $star){
                          $review_star .= '<i class="bi bi-star-fill active"></i>' ;
                        }else{
                          $review_star .= '<i class="bi bi-star-fill"></i>' ;
                        }
                      }
                    ?>
                    <span class="star"><?php echo $review_star; ?></span>

                    <!-- 몇개월전 -->
                    <?php
                      $datetime = $row2["datetime"];
                      $date = new DateTime($datetime);

                      $today = new DateTime();
                      $interval = $today->diff($date);

                      $years = $interval->y;
                      $months = $interval->m;
                      $days = $interval->d;
                      $hours = $interval->h;
                      $minutes = $interval->i;
                      $seconds = $interval->s;

                      if ($years > 0) {
                          echo "{$years}년 전";
                      } elseif ($months > 0) {
                          echo "{$months}개월 전";
                      } elseif ($days > 0) {
                          echo "{$days}일 전";
                      } elseif ($hours > 0) {
                          echo "{$hours}시간 전";
                      } elseif ($minutes > 0) {
                          echo "{$minutes}분 전";
                      } else {
                          echo "{$seconds}초 전";
                      }
                    ?>
                    </p>

                    <!-- 내용 -->
                    <p class="comu_review"><?php echo $row2['review']; ?></p>

                    <!-- 사진 -->
                    <p class="review_img">
                      <?php foreach (explode(",",$row2['img']) as $file) { ?>
                        <?php if($file != ''){ ?>
                          <img src="./uploads/review/<?php echo $file; ?>" alt="">
                        <?php } ?>
                      <?php } ?>
                    </p>
                  </li>
                <?php
                  } 
                } else {
                  // 리뷰가 없는 경우
                ?>
                  <li>
                    <p class="no-reviews">등록된 리뷰가 없습니다.</p>
                  </li>
                <?php
                }
                ?>
              </ul>
            </div>
          </div>
        </div>

        <!-- 상담 -->
        <div id="tab3" class="tab-pane">
          <div class="inquire">
            <h2>해당과정 바로 상담 (신청)</h2>
            <form method="post" action="./act/inquire_send.php">
              <!--강의 내용 hidden으로 담아서 보내기-->
              <input type="hidden" value="<?php echo $row['name']?>" name="academy_name">

              <label for="inquire_name" >이름</label>
              <input type="text" value="" name="inquire_name" id="inquire_name" >
              <br>
              <label for="inquire_phone" >전화번호</label>
              <input type="text" value="" name="inquire_phone" id="inquire_phone" >
    
              <p>
                <input type="submit" value="문의하기">
              </p>
            </form>
          </div>
        </div>
      </div>
    </article>
  </main>
    <!-- 공통푸터삽입 -->
    <?php include('./inc/footer.php');?>
    <!-- 공통바텀바삽입 -->
    <?php include('./inc/bottom.php');?>

    <script>  
      $(document).ready(function() {
        $('.t_mnu').on('click', function() {
          // 모든 탭 버튼에서 'on2' 클래스 제거
          $('.t_mnu').removeClass('on2');
          
          // 클릭된 버튼에 'on2' 클래스 추가
          $(this).addClass('on2');
          
          // 모든 탭 콘텐츠 숨기기
          $('.tab-pane').removeClass('active');
          
          // 클릭된 버튼에 해당하는 콘텐츠만 보이기
          var tabId = $(this).data('tab');
          $('#' + tabId).addClass('active');
        });
      });
    </script>
</body>
</html>

