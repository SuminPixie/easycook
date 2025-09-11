<?php
  include('./php/include/dbconn.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 검색창</title>
    <!-- 공통 헤드정보 삽입 -->
    <?php include('./php/include/head.php'); ?>

    <!-- 서식 연결 -->
    <link rel="stylesheet" type="text/css" href="./css/search.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
      .search_main{
        height: 630px;
      }
    </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header.php');?>

  <main class="search_main">
    <!--검색 창-->
    <section class="search">
      <h2 class="hide">검색창</h2>
      <article >
        <form action="search_output.php" name="강의 검색" method="post">
          <input type="search" class="search_bar" name="search_key">
          <h2 class="hide">검색창</h2>
          <i class="bi bi-search"></i>
        </form>
      </article>
    </section>
    <!--최근검색어-->
    <section class="new_search"> 
      <h2>최근 검색어</h2>
      <article>
        <h2 class="hide">최근 검색어</h2>
        <div class="swiper mySwiper6">
          <div class="swiper-wrapper">
            <div class="swiper-slide">#<a href="search_output.php?search_key2=자격증" title="자격증(으)로 검색하기">자격증</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=베이커리" title="베이커리(으)로 검색하기">베이커리</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=바리스타" title="바리스타(으)로 검색하기">바리스타</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=한식조리" title="한식조리(으)로 검색하기">한식조리</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=중식조리" title="중식조리(으)로 검색하기">중식조리</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=국비" title="국비(으)로 검색하기">국비반</a></div>
            <div class="swiper-slide">#<a href="search_output.php?search_key2=주말" title="주말(으)로 검색하기">주말반</a></div>
          </div>
        </div>
      </article>
    </section>
    <!--광고배너-->
    <section id="sec05">
      <h2 style="display:none;">광고배너</h2>
      <!-- Swiper -->
      <div class="swiper mySwiper4">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img src="./images/main/ad_1.jpg" alt="이미지">
          </div>
          <div class="swiper-slide">
            <img src="./images/main/ad_2.jpg" alt="이미지">
          </div>
          <div class="swiper-slide">
            <img src="./images/main/ad_3.jpg" alt="이미지">
          </div>
          <div class="swiper-slide">
            <img src="./images/main/ad_4.jpg" alt="이미지">
          </div>
        </div>
      </div>
    </section>
    <!--가장많이 검색중-->
    <section class="many_search">
      <h2>가장 많이 검색하고 있어요</h2>
      <article class="">
        <h2 class="hide">가장 많이 검색하고 있어요</h2>
        <ul>
          <li><span>1</span><a href="search_output.php?search_key2=한식조리기능사" title="한식조리기능사로 검색하기"> 한식조리기능사</a></li>
          <li><span>2</span><a href="search_output.php?search_key2=한식조리" title="한식조리로 검색하기"> 한식조리</a></li>
          <li><span>3</span><a href="search_output.php?search_key2=홈베이킹" title="홈베이킹로 검색하기"> 홈베이킹</a></li>
          <li><span>4</span><a href="search_output.php?search_key2=디저트" title="디저트로 검색하기"> 디저트</a></li>
          <li><span>5</span><a href="search_output.php?search_key2=양식조리" title="양식조리로 검색하기"> 양식조리</a></li>
        </ul>
        <ul>
          <li><span>6</span><a href="search_output.php?search_key2=자격증" title="자격증으로 검색하기"> 자격증</a></li>
          <li><span>7</span><a href="search_output.php?search_key2=국비" title="국비로 검색하기"> 국비</a></li>
          <li><span>8</span><a href="search_output.php?search_key2=취미" title="취미로 검색하기"> 취미</a></li>
          <li><span>9</span><a href="search_output.php?search_key2=자격증" title="자격증으로 검색하기"> 자격증</a></li>
          <li><span>10</span><a href="search_output.php?search_key2=중식조리" title="중식조리로 검색하기">중식조리</a></li>
        </ul>
      </article>
    </section>
  </main>

  <!-- 공통바텀바삽입 -->
  <?php include('./php/include/bottom.php');?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    var swiper4 = new Swiper(".mySwiper4", {
      spaceBetween: 0,
      centeredSlides: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        type: "fraction", // 페이지네이션을 번호로 표시
        clickable: true,
      },
  });


    var swiper = new Swiper(".mySwiper6", {
      slidesPerView: 3,
      spaceBetween: 30,
      freeMode: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });


          //--------1025px 이상일때 메인배너 이미지 변경------------
          function updateImageSources() {
          // 섹션별 이미지 업데이트
          const updateImages = (selector, imageType) => {
            const images = document.querySelectorAll(selector);
            images.forEach(img => {
                let src = img.getAttribute('src');
                if (window.innerWidth >= 1025) {
                  // _pc가 붙은 경로로 변경
                  if (!src.includes('_pc')) {
                    img.src = src.replace('.jpg', '_pc.jpg');
                  }
                } else {
                  // _pc가 붙은 경로를 제거
                  if (src.includes('_pc')) {
                    img.src = src.replace('_pc.jpg', '.jpg');
                  }
                }
              });
          }
          // 각 섹션에 대한 이미지 소스 업데이트
          updateImages('#sec05 .swiper-slide img');
        }
          // 페이지 로드 시 이미지 업데이트
          window.addEventListener('load', updateImageSources);
          // 화면 크기 변경 시 이미지 업데이트
          window.addEventListener('resize', updateImageSources);






  </script>
</body>
</html>