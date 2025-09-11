<?php
  session_start();
  include('./inc/dbconn.php');

  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
  }else{
    $id = null;
  }

  // 사용자가 카트에 담은 상품 목록 가져오기
  $cart_class_no = [];
  if ($id !== null) {
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
  <!-- head.php -->
  <?php include('./inc/head.php');?>
  <!-- main.css -->
  <link rel="stylesheet" href="./css/main.css">
  <!-- main.js -->
  <script defer src="./script/main.js"></script>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header.php');?>
  <main>
    <!-- 1. 메인배너 슬라이드 -->
    <section id="sec01">
      <h3 style="display:none;">메인배너 슬라이드</h3>
      <!-- Swiper -->
      <div class="swiper mySwiper1">
        <div class="swiper-wrapper">
          <a href="./academy.php?category1=바리스타&type=전체" class="swiper-slide">
            <img src="./images/main/visual_1.jpg" alt="이미지" loading="lazy">
            <div>
              <span>바리스타</span>
              <p>당신의 첫 번째<br>
              커피 커리어</p>
              <p>바리스타 자격증에서 카페창업까지<br>
              120가지 커리큘럼을 경험하세요.</p>
            </div>
          </a>
          <a href="./academy.php?category1=베이커리&type=전체" class="swiper-slide">
            <img src="./images/main/visual_2.jpg" alt="이미지" loading="lazy">
            <div>
              <span>베이커리</span>
              <p>세상의 모든<br>
              디저트</p>
              <p>베이커리 원데이부터 전문가 과정까지<br>
              150가지 커리큘럼을 경험하세요.</p>
            </div>
          </a>
          <a href="./academy.php?category1=기능사&type=창업" class="swiper-slide">
            <img src="./images/main/visual_3.jpg" alt="이미지">
            <div>
              <span>창업</span>
              <p>외식 창업을 위한<br>
              메뉴개발 프로그램</p>
              <p>성공과 실패는 이유가 있습니다. <br>
              창업 전 전문화된 컨설팅을 받아보세요.</p>
            </div>
          </a>
          <a href="./academy.php?category1=기능사&difficulty=하" class="swiper-slide">
            <img src="./images/main/visual_4.jpg" alt="이미지">
            <div>
              <span>요리</span>
              <p>쉽게 배우는<br>
              생활 요리</p>
              <p>누구나 부담없이 요리를 기초부터 재밌게 <br>
              배울 수 있는 과정입니다.</p>
            </div>
          </a>
        </div>
        <div class="slide-btn">
          <div class="swiper-pagination swiper-pagination-fraction">
            <span class="swiper-pagination-current"></span>
            /
            <span class="swiper-pagination-total"></span>
          </div>
          <i class="bi bi-pause swiper-button-play-pause"></i>
        </div>
      </div>
    </section>
    <!-- 2. 카테고리 -->
    <section id="sec02">
      <h3 style="display:none;">카테고리</h3>
      <div>
        <div>
          <a href="./academy.php?category1=기능사&type=전체" title="요리">
            <img src="./images/main/cate_icon_1.png" alt="요리">
            <p>요리</p>
          </a>
        </div>
        <div>
          <a href="./academy.php?category1=바리스타&type=전체" title="바리스타">
            <img src="./images/main/cate_icon_2.png" alt="바리스타">
            <p>바리스타</p>
          </a>
        </div>
        <div>
          <a href="./academy.php?category1=베이커리&type=전체" title="베이커리">
            <img src="./images/main/cate_icon_3.png" alt="베이커리">
            <p>베이커리</p>
          </a>
        </div>
      </div>

    </section>
    <!-- 3. 실시간 인기 강의 -->
    <section id="sec03">
      <div class="title">
        <h2>실시간 인기 강의</h2>
        <a href="./academy_all.php?sort=인기순" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper2">
          <div class="swiper-wrapper">
            <?php
              /* 주문 수를 기준으로 정렬된 개강전 강의 10개 */
              $currentDate = date('Y-m-d');
              $sql = "
                SELECT a.*, COUNT(o.class_no) AS order_count
                FROM easycook_academy_list a
                LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
                WHERE a.start_date >= '$currentDate' 
                GROUP BY a.class_no
                ORDER BY order_count DESC
                LIMIT 10
              ";
              $result = mysqli_query($conn, $sql);
              // 카운터 변수 초기화
              $counter = 1;
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
              <div class="swiper-slide">
                <div class="cart" data-no="<?php echo $row['class_no']; ?>">
                  <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
                </div>
                <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
                  <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지" loading="lazy">
                  <div class="con-text">
                    <p class="con-title"><?php echo $row['name']; ?></p>
                    <p class="con-sub"><?php echo $row['teacher']; ?></p>
                  </div>
                  <div class="flag">
                    <svg width="26" height="33" fill="none">
                      <path d="m13 24.25-13 10V0h26v34.25l-13-10Z"></path>
                    </svg>
                    <span><?php echo $counter; ?></span>
                  </div>
                </a>
              </div>
            <?php
              // 카운터 증가
              $counter++;
              }
            ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 4. 마감임박 강의 -->
    <section id="sec04">
      <div class="title">
        <h2>마감임박 강의</h2>
        <a href="./academy_all.php?sort=마감임박순" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper3">
          <div class="swiper-wrapper">
            <?php
              /* 현재 날짜부터 start_date까지 얼마 안남은 순으로 개강 전 상품 10개 */
              $currentDate = date('Y-m-d');
              $sql = "SELECT *, DATEDIFF(start_date, NOW()) AS days_left 
                      FROM easycook_academy_list 
                      WHERE start_date >= '$currentDate' 
                      ORDER BY days_left ASC 
                      LIMIT 10";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="swiper-slide">
              <div class="cart" data-no="<?php echo $row['class_no']; ?>">
                <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
              </div>
              <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
                <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지" loading="lazy">
                <div class="con-text">
                  <p class="con-title"><?php echo $row['name']; ?></p>
                  <p class="con-sub"><?php echo $row['detail']; ?></p>
                </div>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 5. 광고배너 -->
    <section id="sec05">
      <h3 style="display:none;">광고배너</h3>
      <!-- Swiper -->
      <div class="swiper mySwiper4">
        <div class="swiper-wrapper">
          <a href="javascript:void(0);" class="swiper-slide">
            <img src="./images/main/ad_1.jpg" alt="이미지" loading="lazy">
          </a>
          <a href="javascript:void(0);" class="swiper-slide">
            <img src="./images/main/ad_2.jpg" alt="이미지" loading="lazy">
          </a>
          <a href="javascript:void(0);" class="swiper-slide">
            <img src="./images/main/ad_3.jpg" alt="이미지" loading="lazy">
          </a>
          <a href="javascript:void(0);" class="swiper-slide">
            <img src="./images/main/ad_4.jpg" alt="이미지" loading="lazy">
          </a>
        </div>
        <div class="slide-btn">
          <div class="swiper-pagination swiper-pagination-fraction">
            <span class="swiper-pagination-current"></span>
            /
            <span class="swiper-pagination-total"></span>
          </div>
        </div>
      </div>
    </section>
    <!-- 6. 수강생 후기 -->
    <section id="sec06">
      <h3 style="display:none;">수강생 후기</h3>
      <div class="title">
        <h2>수강생 후기</h2>
        <a href="./community.php?comu=커뮤니티&tab=후기" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper5">
          <div class="swiper-wrapper">
            <?php 
              //리뷰 불러오는곳
              $sql = "SELECT * FROM easycook_review LIMIT 10"; //WHERE star='5'"
              $result = mysqli_query($conn,$sql);
              $total_review= mysqli_num_rows($result);

              while($row = mysqli_fetch_array($result)){ 
                $class_no = $row['class_no'];
                $review_id = $row['id']; // 리뷰 테이블의 ID를 가져옵니다.
                
                // easycook_academy_list에서 해당 class_no에 대한 정보 가져오기
                $sql2 = "select * from easycook_academy_list where class_no = '$class_no'";
                $result2 = mysqli_query($conn,$sql2);
                $row2 = mysqli_fetch_array($result2);

                // easycook_register 테이블에서 해당 리뷰 ID에 대한 프로필 이미지 경로 가져오기
                $sql3 = "SELECT profile FROM easycook_register WHERE id = '$review_id'";
                $result3 = mysqli_query($conn, $sql3);
                $row3 = mysqli_fetch_array($result3);
                $profile_review = $row3['profile']; // 프로필 이미지 경로
            ?>
            <div class="swiper-slide">
              <div class="profile">
                <img src="./uploads/profile/<?php echo $profile_review; ?>" alt="프로필 이미지" loading="lazy">
                <div>
                  <!--별점-->
                  <div>
                  <?php
                    $review_star = "";
                    $star = $row["star"];
                    for($i = 0; $i < 5; $i++){
                      if($i < $star){
                        $review_star .= '<i class="bi bi-star-fill active"></i>' ;
                      }else{
                        $review_star .= '<i class="bi bi-star-fill"></i>' ;
                      }
                    }
                  ?>
                  <span class="star"><?php echo $review_star; ?></span>
                  </div>
                  <p><?php echo $row['name'] ?></p>
                </div>
              </div>
              <div class="con-text">
                <p><?php echo $row2['name'] ?></p>
                <p class="con-title"><?php echo $row['review'] ?></p>
                <p class="con-sub"><?php echo $row['review'] ?></p>
              </div>
            </div>
            <?php } ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 7. 인기 요리 강의 -->
    <section id="sec07">
      <div class="title">
        <h2>인기 요리 강의</h2>
        <a href="./academy.php?category1=기능사&type=전체&sort=인기순" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper3">
          <div class="swiper-wrapper">
            <?php
              /* 주문 수를 기준으로 정렬된 개강전 강의 10개 */
              $currentDate = date('Y-m-d');
              $sql = "
                SELECT a.*, COUNT(o.class_no) AS order_count
                FROM easycook_academy_list a
                LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
                WHERE a.category1 LIKE '%기능사%' AND a.start_date >= '$currentDate'
                GROUP BY a.class_no
                ORDER BY order_count DESC
                LIMIT 10
              ";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="swiper-slide">
              <div class="cart" data-no="<?php echo $row['class_no']; ?>">
                <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
              </div>
              <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
                <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지" loading="lazy">
                <div class="con-text">
                  <p class="con-title"><?php echo $row['name']; ?></p>
                  <p class="con-sub"><?php echo $row['detail']; ?></p>
                </div>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 8. 인기 바리스타 강의 -->
    <section id="sec08">
      <div class="title">
        <h2>인기 바리스타 강의</h2>
        <a href="./academy.php?category1=바리스타&type=전체&sort=인기순" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper3">
          <div class="swiper-wrapper">
            <?php
              /* 주문 수를 기준으로 정렬된 개강전 강의 10개 */
              $currentDate = date('Y-m-d');
              $sql = "
                SELECT a.*, COUNT(o.class_no) AS order_count
                FROM easycook_academy_list a
                LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
                WHERE a.category1 = '바리스타' AND a.start_date >= '$currentDate'
                GROUP BY a.class_no
                ORDER BY order_count DESC
                LIMIT 10
              ";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="swiper-slide">
              <div class="cart" data-no="<?php echo $row['class_no']; ?>">
                <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
              </div>
              <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
                <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지" loading="lazy">
                <div class="con-text">
                  <p class="con-title"><?php echo $row['name']; ?></p>
                  <p class="con-sub"><?php echo $row['detail']; ?></p>
                </div>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 9. 인기 베이커리 강의 -->
    <section id="sec09">
      <div class="title">
        <h2>인기 베이커리 강의</h2>
        <a href="./academy.php?category1=베이커리&type=전체&sort=인기순" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3">
        <div class="swiper mySwiper3">
          <div class="swiper-wrapper">
            <?php
              /* 주문 수를 기준으로 정렬된 개강전 강의 10개 */
              $currentDate = date('Y-m-d');
              $sql = "
                SELECT a.*, COUNT(o.class_no) AS order_count
                FROM easycook_academy_list a
                LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
                WHERE a.category1 = '베이커리' AND a.start_date >= '$currentDate'
                GROUP BY a.class_no
                ORDER BY order_count DESC
                LIMIT 10
              ";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="swiper-slide">
              <div class="cart" data-no="<?php echo $row['class_no']; ?>">
                <img src="./images/common/<?php echo in_array($row['class_no'], $cart_class_no) ? 'heart_r' : 'heart_w'; ?>.png" alt="찜버튼">
              </div>
              <a href="./detail.php?class_no=<?php echo $row['class_no']; ?>" title="상품">
                <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="이미지" loading="lazy">
                <div class="con-text">
                  <p class="con-title"><?php echo $row['name']; ?></p>
                  <p class="con-sub"><?php echo $row['detail']; ?></p>
                </div>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>
    <!-- 10. 모바일앱 띠배너 -->
    <section id="sec10">
      <h3 style="display:none;">띠배너</h3>
      <a href="javascript:void(0);" title="띠배너">
        <img src="./images/main/app_ad_1.jpg" alt="이미지" loading="lazy">
      </a>
    </section>
    <!-- 11. 국비과정 베스트 -->
    <section id="sec11">
      <div class="title">
        <h2>국비과정 베스트</h2>
        <a href="./academy_all.php?type=국비" title="더 보기">더 보기 +</a>
      </div>
      <div class="mt-3 row">
        <?php
          /* 주문 수를 기준으로 정렬된 개강전 강의 3개 */
          $currentDate = date('Y-m-d');
          $sql = "
            SELECT a.*, COUNT(o.class_no) AS order_count
            FROM easycook_academy_list a
            LEFT JOIN `easycook_order` o ON a.class_no = o.class_no
            WHERE a.category2 = '국비' AND a.start_date >= '$currentDate'
            GROUP BY a.class_no
            ORDER BY order_count DESC
            LIMIT 3
          ";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <div class="col" onclick="location.href='./detail.php?class_no=<?php echo $row['class_no']; ?>';" style="cursor: pointer; background-image: url('<?php echo './uploads/class_main/' . $row['thumnail_img']; ?>');">
            <div>
              <p><?php echo $row['name']; ?></p>
              <p><?php echo $row['detail']; ?>
              </p>
              <span>
                자세히 보기
                <i class="bi bi-chevron-right"></i>
              </span>
            </div>
          </div>
        <?php
          }
        ?>
      </div>
    </section>
    <!-- 12. 커뮤니티 -->
    <section id="sec12">
      <div class="title">
        <h2>커뮤니티</h2>
        <a href="./community.php?comu=커뮤니티&tab=후기" title="더 보기">더 보기 +</a>
      </div>
      <div class="tab mt-3">
        <ul>
          <li class="tab-item">
            <button class="tab-link active" data-tab-target="#tab1" type="button">공지사항</button>
          </li>
          <li class="tab-item">
            <button class="tab-link" data-tab-target="#tab2" type="button">수강생 후기</button>
          </li>
          <li class="tab-item">
            <button class="tab-link" data-tab-target="#tab3" type="button">자주묻는 질문</button>
          </li>
        </ul>
        <div class="tab_con">
          <div class="active" id="tab1">
            <ul class="list">
              <?php 
                //공지사항 최신순으로 불러오는곳
                $sql = "SELECT * FROM easycook_board ORDER BY datetime DESC LIMIT 4";
                $result = mysqli_query($conn,$sql);
                $total_review= mysqli_num_rows($result);

                while($row = mysqli_fetch_array($result)){ 
                  $title = $row['title'];
                  $date = $row['datetime'];

                  // 현재 날짜와 데이터베이스에서 가져온 날짜 사이의 차이를 계산
                  $currentDate = new DateTime(); // 현재 날짜
                  $postDate = new DateTime($date); // 데이터베이스에서 가져온 날짜
                  $interval = $currentDate->diff($postDate); // 날짜 차이 계산
                  $daysDifference = $interval->days; // 차이 일수
                  
                  // 날짜 차이가 3일 이하인 경우에만 'active' 클래스를 표시
                  $activeClass = ($daysDifference <= 3) ? 'active' : '';
              ?>
              <li>
                <span><?php echo (new DateTime($row['datetime']))->format('m.d');?></span>
                <a href="./community.php?comu=커뮤니티&tab=고객센터&Fm=공지사항" title="제목"><?php echo $title; ?></a>
                <span class="<?php echo $activeClass; ?>">N</span>
              </li>
              <?php }?>
            </ul>
          </div>
          <div id="tab2">
            <ul class="list">
              <?php 
                //수강생후기 최신순으로 불러오는곳
                $sql = "SELECT * FROM easycook_review ORDER BY datetime DESC LIMIT 4";
                $result = mysqli_query($conn,$sql);
                $total_review= mysqli_num_rows($result);

                while($row = mysqli_fetch_array($result)){ 
                  $title = $row['review'];
                  $date = $row['datetime'];

                  // 현재 날짜와 데이터베이스에서 가져온 날짜 사이의 차이를 계산
                  $currentDate = new DateTime(); // 현재 날짜
                  $postDate = new DateTime($date); // 데이터베이스에서 가져온 날짜
                  $interval = $currentDate->diff($postDate); // 날짜 차이 계산
                  $daysDifference = $interval->days; // 차이 일수
                  
                  // 날짜 차이가 3일 이하인 경우에만 'active' 클래스를 표시
                  $activeClass = ($daysDifference <= 3) ? 'active' : '';
              ?>
              <li>
                <span><?php echo (new DateTime($row['datetime']))->format('m.d');?></span>
                <a href="./community.php?comu=커뮤니티&tab=후기" title="제목"><?php echo $title; ?></a>
                <span class="<?php echo $activeClass; ?>">N</span>
              </li>
              <?php }?>
            </ul>
          </div>
          <div id="tab3">
            <ul class="list">
              <?php 
                //질문 최신순으로 불러오는곳
                $sql = "SELECT * FROM easycook_question ORDER BY question_time DESC LIMIT 4";
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_array($result)){ 
                  $title = $row['question'];
                  $date = $row['question_time'];

                  // 현재 날짜와 데이터베이스에서 가져온 날짜 사이의 차이를 계산
                  $currentDate = new DateTime(); // 현재 날짜
                  $postDate = new DateTime($date); // 데이터베이스에서 가져온 날짜
                  $interval = $currentDate->diff($postDate); // 날짜 차이 계산
                  $daysDifference = $interval->days; // 차이 일수
                  
                  // 날짜 차이가 3일 이하인 경우에만 'active' 클래스를 표시
                  $activeClass = ($daysDifference <= 3) ? 'active' : '';
              ?>
              <li>
                <span><?php echo (new DateTime($row['question_time']))->format('m.d');?></span>
                <a href="./community.php?comu=커뮤니티&tab=고객센터&Fm=자주묻는질문" title="제목"><?php echo $title; ?></a>
                <span class="<?php echo $activeClass; ?>">N</span>
              </li>
              <?php }?>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>

</body>
</html>