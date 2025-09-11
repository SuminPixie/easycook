<?php
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  // 사용자가 로그인한 경우, 값을 세션에서 가져옴
  if (isset($_SESSION['id'])){
    $name = htmlspecialchars($_SESSION['name']);
    $profile = htmlspecialchars($_SESSION['profile']);
    $teacher_code = isset($_SESSION['teacher_code']) ? $_SESSION['teacher_code'] : null;
  }
?>

<head>
  <style>
    /* ----------------상단 헤더------------ */
    header {
      position: fixed;
      width: 100%;
      height: 70px;
      background: var(--white);
      top: 0;
      z-index: 1000;
    }
    header .h_box {
      position: absolute;
      width: 100%;
      top: 0;
      padding: 15px var(--p_20);
      background: var(--white);
      height: 70px;
      overflow: hidden;
      transition: height 0.5s ease;
      border-bottom: 1px solid var(--gray);
    }
    header .h_border {
      position: fixed;
      top: 69px;
      width: 100%;
      height: 1px;
      background: var(--gray);
      z-index: 1001;
    }
    header .h_box > div {
      display: flex;
      justify-content: space-between;
      margin: 0 auto;
    }
    header .h_box h1 {
      height: 40px;
      width: 120px;
    }
    header .h_box h1 > a {
      display: block;
      height: 100%;
    }
    header .h_box h1 > a img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    header .h_box > div > ul {
      display: flex;
      justify-content: center;
    }
    header .h_box > div > ul li {
      width: 40px;
      text-align: center;
      cursor: pointer;
    }
    header .h_box > div > ul li a {
      display: block;
    }
    header .h_box > div > ul li i {
      line-height: 40px;
      font-size: 30px;
    }
    header .h_box > div > ul .bi-bell::before {
      transform: scale(0.8);
    }
    /* ----------------사이드바 메뉴------------ */
    .side {
      width: 100%;
      height: 100vh;
      position: absolute;
      z-index: 1002;
      right: -100%;
      background: var(--white);
      transition: 0.5s ease-in-out;
      display:none; /* 일단 숨겨놓음 */
      overflow-y: auto; /* 사이드 아래로 잘릴때 스크롤 되게 */
      background: var(--light-gray);
    }
    .side::before {
      content: '';
      position: absolute;
      top: 0;
      left: 40%; /* 가상 배경을 left 40% 위치에 배치 */
      width: 60%;
      height: 100%;
      background: #fff;
      z-index: -1;
    }
    .side.open {
      right: 0;
    }
    .a_side.open {
      position: fixed;
      display: block;
      z-index: 10000;
      background: var(--white);
    }
    /* 닫기 버튼 */
    .toggle_close {
      position: absolute;
      top: 30px;
      right: 30px;
      cursor: pointer;
    }
    .toggle_close i {
      font-size: var(--fs-xlarge);
    }
    /* -------------로그인 타이틀 부분--------- */
    .side .info {
      padding: 80px 20px 30px 20px;
      border-bottom: 1px solid var(--light-gray);
      background: #fff;
    }
    .side .info > div {
      display: flex;
      gap: 10px;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    .side .info img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
    .side .info a {
      color: #666;
      white-space: nowrap;
    }
    .side .info > div > a:first-of-type {
      color: var(--black);
      font-weight: var(--fw-bold);
      font-size: var(--fs-large);
      margin-right: auto;
    }
    /* -------------카테고리 부분------------ */
    .side .depth {
      position: relative;
      padding: 30px 20px;
      height: 100%;
    }
    /* 큰 카테고리 */
    .side .depth1 {
      margin-bottom: 40px;
    }
    .side .depth1 > a {
      position: relative;
      z-index: 500;
      font-size: var(--fs-medium-large);
      background: none;
      color: var(--black);
      padding: 15px 20px;
      width: calc(40% + 20px);
      border-radius: 50px;
    }
    .side .depth1 > a.active {
      background: var(--red);
      color: var(--white);
      display: block;
    }
    /* 작은 카테고리 */
    .side .depth2 {
      display: none;
      position: absolute;
      height: 100%;
      top: 0;
      right: 0;
      left: 40%;
      padding: 45px 0 0 60px;
      background: var(--white);
    }
    .side .depth2 li {
      margin-bottom: 40px;
    }
    .side .depth2 a {
      padding: 15px 0;
      font-size: var(--fs-medium-large);
    }
    .side .depth2 a:hover {
      color: var(--red);
    }

    /* -----------로그인 밑에 아이콘들----------- */
    .icon_menu {
      width: 100%;
      background: var(--white);
      margin-top: 20px;
    }
    .icon_menu ul {
      display: flex;
      justify-content: space-evenly;
      width: 100%;
      padding: 0;
    }
    .icon_menu ul li {
      text-align: center;
      width: 60px;
      height: 60px;
      list-style: none;
    }
    .icon_menu a {
      display: flex;
      flex-direction: column;
      text-decoration: none;
      width: 100%;
      height: 100%;
      color: #666;
      justify-content: center;
      align-items: center;
      gap: 5px;
    }
    /* 아이콘 크기 조정 */
    .icon_menu ul li i {
      font-size: var(--fs-xlarge);
      color: var(--black);
    }
    .bi-heart::before {
      transform: scale(0.9);
    }
    .bi-person::before {
      transform: scale(1.1);
    }
    /* 글씨 스타일 조정 */
    .icon_menu ul li span {
      text-align: center;
      font-size: var(--fs-small);
      font-weight: var(--fw-light);
      color: var(--black);
      white-space: nowrap;
      margin-top: 5px;
    }
    /*---------------알림창-------------- */
    header .a_side {
      top: 0;
      display: none;
      width: 100%;
      padding: 30px 20px 20px 20px;
    }
    .a_side > div {
      height: 70px;
      border-bottom: 1px solid var(--gray);
      background: var(--white);
    }
    /* 알림과 X 가로 정렬 */
    .a_side h2, #toggle_close2 {
      display: inline-block;
    }
    .a_side h2 {
      font-size: var(--fs-xlarge);
      font-weight: var(--fw-bold);
      padding: 10px;
    }
    #toggle_close2 {
      float: right;
      padding: 10px;
    }
    #toggle_close2 i {
      cursor: pointer;
      font-size: var(--fs-xlarge);
      font-weight: var(--fw-bold);
    }
    /* 알림창 내용 */
    .a_side ul {
      display: inline-block;
      width: 100%;
      margin: 20px auto 0 auto;
      background: var(--white);
      height: 100vh;
    }
    .a_side ul > li {
      width: 100%;
      height: 85px;
      text-align: left;
      border: 1px solid var(--gray);
      border-radius: 5px;
      margin-top: 10px;
    }
    .a_side ul > li > p:first-child {
      font-size: var(--fs-medium);
      font-weight: var(--fw-bold);
      padding: 10px 0 0 10px;
    }
    .a_side ul > li > p:nth-child(2) {
      display: inline-block;
      float: right;
      font-size: var(--fs-small);
      font-weight: var(--fw-light);
      transform: translate(-10px, -15px);
    }
    .a_side ul > li > p:last-child {
      font-size: var(--fs-medium);
      font-weight: var(--fw-normal);
      padding: 25px 10px 0 10px;
    }
    /* -------------------PC 버전------------------ */
    /* GNB 메뉴바 */
    .gnb_box {
      width: 70%;
      display: none;
    }
    .gnb {
      display: flex;
      height: 100%;
    }
    .gnb > li {
      text-align: center;
      width: 100%;
      margin: 0 auto;
    }
    .gnb > li > a {
      line-height: 40px;
      white-space: nowrap;
      color: var(--black);
      font-weight: bolder;
      text-align: center;
      display: block;
      width: 100%;
      margin: 0 auto;
    }
    /* 호버시 밑줄 나오게 */
    .gnb > li > a::after {
      content: " ";
      display: block;
      margin-top: 12px;
      width: 100%;
      transform: scaleX(0);
      transition: 0.8s;
    }
    .gnb > li:nth-of-type(1) > a::after,
    .gnb > li:nth-of-type(2) > a::after,
    .gnb > li:nth-of-type(3) > a::after,
    .gnb > li:nth-of-type(4) > a::after,
    .gnb > li:nth-of-type(5) > a::after {
      border-bottom: 3px solid var(--red);
    }
    .gnb > li:hover > a::after {
      transform: scaleX(1);
    }
    /* 서브메뉴 */
    .lnb {
      margin: 30px 0;
    }
    .lnb > li {
      line-height: 40px;
      width: 100%;
    }
    .lnb > li > a {
      white-space: nowrap;
      text-align: center;
      width: 180px;
      color: #666;
    }
    /* 호버시 색상 */
    .gnb > li:hover .lnb > li > a {
      color: var(--black);
      font-weight: 550;
    }
    .lnb > li > a:hover {
      color: var(--red) !important;
    }
    /* 마이페이지 */
    #my {
      display: none;
    }
    #my .bi-person::before {
      transform: scale(1);
    }
    .desktop-version {
      display: none;
      margin-top: 70px;
    }
    /* -------------마이페이지 기존서식--------- */
    #my_side{
      padding-top: 70px;
      background: #fff;
    }
    #my_side .mytitle{
      display: flex;
      align-items:center;
      cursor: pointer;
      margin: 0 20px;
      gap: 20px;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    #my_side .mytitle > i{
      color: #aaa;
      font-size: var(--fs-large);
    }
    #my_side .mytitle > div{
      display: flex;
      align-items:center;
      gap: 10px;
      margin-left: 10px;
    }
    #my_side .mytitle > div > div{
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    #my_side .mytitle > div > div p:nth-of-type(1){
      font-size: var(--fs-large);
      font-weight: var(--fw-semi-bold);
    }
    #my_side .mytitle > div > div p:nth-of-type(2){
      font-size: var(--fs-medium);
      font-weight: var(--fw-normal);
      color: #888;
    }
    /* 로그아웃/강사페이지 버튼 */
    #my_side .mytitle_btn{
      display: flex;
      gap: 10px;
      margin: 20px;
    }
    /* 프로필이미지 서식 */
    #my_side #profile_img{
      width: 50px; height: 50px;
      border-radius:50%;
      border: 2px solid #ccc;
    }
    /* 아이콘 서식 */
    #my_side .icon_menu{
      width: 100%;
      background: #fff;
      margin-top: 20px;
    }
    #my_side .icon_menu ul{
      display: flex;
      justify-content: space-evenly;
      width: 100%;
      padding: 0;
    }
    #my_side .icon_menu ul li{
      text-align: center;
      width: 60px; height: 60px;
      list-style: none;
    }
    #my_side .icon_menu a{
      display: flex;
      flex-direction: column;
      text-decoration: none;
      width: 100%;
      height: 100%;
      color: #666;
      justify-content: center;
      align-items: center;
      gap: 5px;
    }
    /* 아이콘 크기 조정 */
    #my_side .icon_menu ul li i{
      font-size: var(--fs-xlarge);
      color: var(--black);
    }
    #my_side .bi-heart::before{
      transform: scale(0.9);
    }
    #my_side .bi-person::before{
      transform: scale(1.1);
    }
    /* 글씨 스타일 조정 */
    #my_side .icon_menu ul li span{
      text-align: center;
      font-size: var(--fs-small);
      font-weight: var(--fw-light);
      color: var(--black);
      text-wrap: nowrap;
      margin-top: 5px;
    }
    /* 강의 리스트 서식 */
    #my_side .my_class{
      margin-top: 30px;
      padding: 0;
    }
    #my_side .my_class .tab > ul{
      gap: 0;
    }
    #my_side .my_class .tab > ul li button{
      border: none;
      border-bottom: 3px solid #ccc;
      background: #fff;
      color: #888;
    }
    #my_side .my_class .tab > ul li button.active{
      border-bottom: 3px solid var(--red);
      color: var(--red);
    }
    #my_side .tab_con{
      padding: 20px;
      height: 100%;
    }
    #my_side .tab_con .btn-s.line{
      border: 1px solid #888;
      color: #888;
    }
    /* 로그아웃 버튼 */
    #my_side .btn-outline-secondary:hover,
    #my_side .btn-outline-secondary:focus,
    #my_side .btn-outline-secondary:active {
      box-shadow: none;
      background-color: transparent;
      border-color: #000;
      color: #000;
    }
    /* 검색창 */
    #search_form{
      width: 200px; height: 35px;
      border: 1px solid var(--red);
      border-radius: 20px;
    }
    .gnb_box .search_box{
      position: absolute;
      top: 90px; left:0;
    }
    .gnb_box .search_box .search_bar{
      width:170px;
      height: 30px;
      text-indent: 10px;
      border: none;
      border-radius: 20px;
    }
    .search_box i {
      float: right;
      transform: translate(-12px, 8px);
      color: var(--red);
      cursor: pointer;
      z-index: 100;
    }
    .search_box .search_bar:focus{
      outline: none;
    }
    /* -------------1025px 이상일 때 PC 헤더------------ */
    @media (min-width: 1025px) {
      header .h_box > div {
        min-width: 1025px;
        max-width: 1200px;
        position: relative;
      }
      header .h_box:hover {
        height: 350px;
      }
      .gnb_box {
        display: block;
      }
      #list {
        display: none;
      }
      #my {
        display: block;
      }
      .side {
        width: 500px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }
      .a_side{
        width: 500px !important;
        right: 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      } 
    }
    /* 사이드바 */
    @media (max-width: 1024px) {
      #menu {
        display: block;
      }
    }
    @media (min-width: 1025px) {
      #my_side {
        display: block;
      }
    }
  </style>
</head>

<header>
  <div class="h_border"></div>
  <div class="h_box">
    <div>
      <h1>
        <a href="./index.php" title="메인페이지로 이동">
          <img src="./images/common/logo.png" alt="로고">
        </a>
      </h1>
  
      <!-- pc버전일때 보이는 gnb -->
      <nav class="gnb_box">
        <div class="search_box">
          <form action="./search_output.php" name="강의 검색" method="post" id="search_form">
            <input type="search" class="search_bar" name="search_key">
            <i class="bi bi-search" onclick="submitForm()"></i>
          </form>
        </div>
        <ul class="gnb">
          <li>
            <a href="./academy.php?category1=기능사" title="요리">요리</a>
            <ul class="lnb">
              <li><a href="./academy.php?category1=기능사&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=기능사&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=기능사&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=기능사&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=기능사&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </li>
          <li>
            <a href="./academy.php?category1=바리스타" title="바리스타">바리스타</a>
            <ul class="lnb">
              <li><a href="./academy.php?category1=바리스타&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=바리스타&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=바리스타&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=바리스타&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=바리스타&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </li>
          <li>
            <a href="./academy.php?category1=베이커리" title="베이커리">베이커리</a>
            <ul class="lnb">
              <li><a href="./academy.php?category1=베이커리&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=베이커리&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=베이커리&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=베이커리&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=베이커리&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </li>
          <li>
            <a href="./intro.php?cata=소개&tab=소개" title="소개">소개</a>
            <ul class="lnb">
              <li><a href="./intro.php?cata=소개&tab=소개" title="소개">소개</a></li>
              <li><a href="./intro.php?cata=소개&tab=강사진" title="강사진">강사진</a></li>
            </ul>
          </li>
          <li>
            <a href="./community.php?comu=커뮤니티&tab=후기" title="커뮤니티">커뮤니티</a>
            <ul class="lnb">
              <li><a href="./community.php?comu=커뮤니티&tab=후기" title="후기">수강생후기</a></li>
              <li><a href="./community.php?comu=커뮤니티&tab=상담신청" title="상담신청">상담신청</a></li>
              <li><a href="./community.php?comu=커뮤니티&tab=고객센터&Fm=공지사항" title="고객센터">고객센터</a></li>
            </ul>
          </li>
        </ul>
      </nav>
  
      <ul>
        <li id="alram">
          <div title="알림">
            <i class="bi bi-bell"></i>
          </div>
        </li>
        <li id="list">
          <i class="bi bi-list"></i>
        </li>
        <li id="my">
          <i class="bi bi-person"></i>
        </li>
      </ul>
    </div>
  </div>

  <!-- 토글메뉴 사이드바 -->
  <nav class="side" id="menu">
    <div class="clearfix">
      <div>
        <div class="toggle_close">
          <i class="bi bi-x-lg"></i>
        </div>
        <div class="info">
          <div>
            <?php
              if (isset($_SESSION['id'])) {
                echo "
                <img src='./uploads/profile/$profile' alt='프로필이미지'>
                <a href='./mypage.php' title='마이페이지'>$name 님 환영합니다.</a>
                <span>
                  <a style='margin-right: 10px;' href='./php/logout.php' title='로그아웃'>로그아웃</a>
                  ";
                  // $teacher_code가 존재하면 강사페이지 링크 추가
                  if ($teacher_code) {
                    echo "<a style='margin-right: 10px;' href='./php/admin/index.php' title='강사페이지'>강사페이지</a>";
                  }
                "</span>";
              } else {
                echo "
                <img src='./uploads/profile/profile.png' alt='프로필이미지'>
                <a href='./login.php' title='로그인'>로그인을 해주세요.</a>
                <span>
                  <a style='margin-right: 10px;' href='./login.php' title='로그인'>로그인</a>
                  <a style='margin-right: 10px;' href='./register_pre.php' title='회원가입'>회원가입</a>
                </span>
                ";
              }
            ?>
          </div>
          <?php
            if (isset($_SESSION['id'])) {
              echo "
              <div class='icon_menu'>
                <ul>
                  <li>
                    <a href='./mypage.php' title='나의 정보'>
                      <i class='bi bi-person'></i>
                      <span class=''>나의 정보</span>
                    </a>
                  </li>
                  <li>
                    <a href='./order_list.php' title='신청목록'>
                      <i class='bi bi-bag-check'></i>
                      <span>신청목록</span>
                    </a>
                  </li>
                  <li>
                    <a href='./cart.php' title='찜목록'>
                      <i class='bi bi-heart'></i>
                      <span>찜목록</span>
                    </a>
                  </li>
                  <li>
                    <a href='./inquire_list.php' title='나의 문의'>
                      <i class='bi bi-chat-square-dots'></i>
                      <span>나의 문의</span>
                    </a>
                  </li>
                  <li>
                    <a href='./review_list.php' title='나의 후기'>
                      <i class='bi bi-pencil-square'></i>
                      <span>나의 후기</span>
                    </a>
                  </li>
                </ul>
              </div>";
            }
          ?>
        </div>
      </div>
      <!-- 카테고리 목록 -->
      <ul class="depth">
        <li class="depth1">
          <a href="./academy.php?category1=기능사" title="depth1" class="active">요리</a>
          <div class="depth2">
            <ul>
              <!-- 카테고리 페이지로 이동 -->
              <li><a href="./academy.php?category1=기능사&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=기능사&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=기능사&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=기능사&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=기능사&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </div>
        </li>
        <li class="depth1">
          <a href="./academy.php?category1=바리스타" title="바리스타">바리스타</a>
          <div class="depth2">
            <ul>
              <!-- 카테고리 페이지로 이동 -->
              <li><a href="./academy.php?category1=바리스타&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=바리스타&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=바리스타&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=바리스타&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=바리스타&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </div>
        </li>
        <li class="depth1">
          <a href="./academy.php?category1=베이커리" title="베이커리">베이커리</a>
          <div class="depth2">
            <ul>
              <!-- 카테고리 페이지로 이동 -->
              <li><a href="./academy.php?category1=베이커리&type=국비" title="국비">국비</a></li>
              <li><a href="./academy.php?category1=베이커리&type=일반" title="일반">일반</a></li>
              <li><a href="./academy.php?category1=베이커리&type=창업" title="창업">창업</a></li>
              <li><a href="./academy.php?category1=베이커리&type=취미" title="취미">취미</a></li>
              <li><a href="./academy.php?category1=베이커리&type=자격증" title="자격증">자격증</a></li>
            </ul>
          </div>
        </li>
        <li class="depth1">
          <a href="./intro.php?cata=소개&tab=소개" title="소개">소개</a>
          <div class="depth2">
            <ul>
              <!-- 카테고리 페이지로 이동 -->
              <li><a href="./intro.php?cata=소개&tab=소개" title="소개">소개</a></li>
              <li><a href="./intro.php?cata=소개&tab=강사진" title="강사진">강사진</a></li>
            </ul>
          </div>
        </li>
        <li class="depth1">
          <a href="./community.php?comu=커뮤니티&tab=후기" title="커뮤니티">커뮤니티</a>
          <div class="depth2">
            <ul>
              <!-- 카테고리 페이지로 이동 -->
              <li><a href="./community.php?comu=커뮤니티&tab=후기" title="수강생 후기">수강생 후기</a></li>
              <li><a href="./community.php?comu=커뮤니티&tab=상담신청" title="상담신청">상담신청</a></li>
              <li><a href="./community.php?comu=커뮤니티&tab=고객센터&Fm=공지사항" title="고객센터">고객센터</a></li>
            </ul>
          </div>
        </li>
        <li style="padding: 30px 20px; border-top:1px solid #ccc; width: calc(40% - 20px);">
          <a href="./academy_all.php" title="전체강의" style="color:#666;">전체강의</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- 마이페이지 사이드바 -->
  <nav class="side" id="my_side">
    <?php
      include('./inc/dbconn.php');

      //사용자가 로그인한 경우, 데이터베이스에서 정보 가져옴
      if (isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        
        $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $name = htmlspecialchars($row['name']);
        $profile = htmlspecialchars($row['profile']);

        // --------------내 강의 정보 불러오는 부분----------------
        // order 테이블에서 세션ID와 일치하는 '수강중'인 class_no 값을 가져오기
        $sql = "SELECT class_no FROM `easycook_order` WHERE id='$id' AND student_status='수강중'";
        $result = mysqli_query($conn, $sql);

        // class_no 값을 배열로 저장
        $class_no = array();
        while ($row = mysqli_fetch_array($result)) {
          $class_no[] = $row['class_no'];
        }

        // 현재 날짜
        $today = date('Y-m-d');

        // 현재 강의와 지난 강의를 구분하여 배열에 저장
        $class_no_list = implode(",", $class_no);

        // 현재 강의 쿼리
        $now_class_list = [];
        if (!empty($class_no_list)) {
          $now_class = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_list) AND start_date <= '$today' AND end_date >= '$today'";
          $now_class_result = mysqli_query($conn, $now_class);
          while ($row = mysqli_fetch_array($now_class_result)) {
            $now_class_list[] = $row;
          }

          // 지난 강의 쿼리
          $past_class_list = [];
          $past_class = "SELECT * FROM easycook_academy_list WHERE class_no IN ($class_no_list) AND end_date < '$today'";
          $past_class_result = mysqli_query($conn, $past_class);
          while ($row = mysqli_fetch_array($past_class_result)) {
            $past_class_list[] = $row;
          }
        }

        // 출석 여부 확인
        $attend_check = [];
        if (!empty($class_no_list)) {
          $check_attendance = "SELECT class_no FROM easycook_attendance WHERE id='$id' AND DATE(datetime)='$today'";
          $check_result = mysqli_query($conn, $check_attendance);
          while ($row = mysqli_fetch_array($check_result)) {
            $attend_check[] = $row['class_no'];
          }
        }

      } else {
        $now_class_list = [];
        $past_class_list = [];
        $attend_check = [];
      }

    ?>
    <div class="clearfix">
      <div class="toggle_close">
        <i class="bi bi-x-lg"></i>
      </div>
      <div>
        <?php
          if (isset($_SESSION['id'])) {
            echo "
            <div class='mytitle'>
              <div>
                <img id='profile_img' src='./uploads/profile/$profile' alt='프로필이미지'>
                <div>
                  <p>$name 님 환영합니다!</p>
                  <p>오늘 하루도 화이팅:)</p>
                </div>
              </div>

              <i class='bi bi-chevron-right'></i>
            </div>
            <div class='mytitle_btn'>
              <a class='btn btn-outline-secondary' href='./php/logout.php' title='로그아웃'>로그아웃</a>";
              // $teacher_code가 존재하면 강사페이지 링크 추가
              if ($teacher_code) {
                echo "<a class='btn btn-outline-secondary' href='./php/admin/index.php' title='강사페이지'>강사페이지</a>";
              }
              echo "</div>";

          } else {
            echo "
            <div class='mytitle'>
              <div>
                <img id='profile_img' src='./uploads/profile/profile.png' alt='프로필이미지'>
                <div>
                  <p class='fs-5'>로그인이 필요합니다.</p>
                </div>
              </div>
              <i class='bi bi-chevron-right'></i>
            </div>";
          }
        ?>
        <nav class='icon_menu'>
          <ul>
            <li>
              <a href='./order_list.php' title='신청목록'>
                <i class='bi bi-bag-check'></i>
                <span>신청목록</span>
              </a>
            </li>
            <li>
              <a href='./cart.php' title='찜목록'>
                <i class='bi bi-heart'></i>
                <span>찜목록</span>
              </a>
            </li>
            <li>
              <a href='./reserve_list.php' title='실습실'>
                <i class='bi bi-door-closed'></i>
                <span>실습실</span>
              </a>
            </li>
            <li>
              <a href='./inquire_list.php' title='나의 문의'>
                <i class='bi bi-chat-square-dots'></i>
                <span>나의 문의</span>
              </a>
            </li>
            <li>
              <a href='./review_list.php' title='나의 후기'>
                <i class='bi bi-pencil-square'></i>
                <span>나의 후기</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="my_class">
        <!-- 탭 컨텐츠 형식 -->
        <div class="tab">
          <ul>
            <li class="tab-item">
              <button class="tab-link active" data-tab-target="#tab1_my" type="button">현재 강의</button>
            </li>
            <li class="tab-item">
              <button class="tab-link" data-tab-target="#tab2_my" type="button">지난 강의</button>
            </li>
          </ul>
          <div class="tab_con">
            <div class="active" id="tab1_my">
              <!-- 상품목록 카드 스타일 -->
              <ul class="card-list">
                <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
                <?php if (isset($_SESSION['id'])): ?>
                  <?php if (empty($now_class_list)): ?>
                    <li>현재 강의가 없습니다.</li>
                  <?php else: ?>
                    <?php foreach ($now_class_list as $row): ?>
                      <li>
                        <div onclick="location.href='./myclass_info.php?class_no=<?= $row['class_no']; ?>'" style="cursor:pointer;">
                          <!-- 강의 썸네일 이미지 -->
                          <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="나의 강의정보">
                            <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
                          </a>
                          <!-- 강의 이름 -->
                          <div>
                            <h2>
                              <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="강의이름">
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
                            <a href="./reserve.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">실습실 예약</a>
                            <a href="./inquire.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">문의하기</a>
                          </div>
                          <div class="btn-box-l mt-2 mb-2">
                            <button class="btn-l attend" data-class-no="<?= $row['class_no']; ?>" 
                            <?php if (in_array($row['class_no'], $attend_check)): ?> 
                              disabled style="background:#aaa;">출석완료
                            <?php else: ?>
                              >출석체크
                            <?php endif; ?>
                            </button>
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
            </div>
            <div id="tab2_my">
              <!-- 상품목록 카드 스타일 -->
              <ul class="card-list">
                <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
                <?php if (isset($_SESSION['id'])): ?>
                  <?php if (empty($past_class_list)): ?>
                    <li>지난 강의가 없습니다.</li>
                  <?php else: ?>
                    <?php foreach ($past_class_list as $row): ?>
                      <?php
                        // 전에 쓴 리뷰가 존재하는지 확인
                        $classNo = $row['class_no'];
                        $query = "SELECT 1 FROM easycook_review WHERE class_no = '$classNo' AND id = '$id'";
                        $result = mysqli_query($conn, $query);
                        $isReviewed = mysqli_num_rows($result) > 0;
                      ?>
                      <li>
                        <div onclick="location.href='./myclass_info.php?class_no=<?= $row['class_no']; ?>'" style="cursor:pointer;">
                          <!-- 강의 썸네일 이미지 -->
                          <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="나의 강의정보">
                            <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
                          </a>
                          <!-- 강의 이름 -->
                          <div>
                            <h2>
                              <a href="./myclass_info.php?class_no=<?= $row['class_no']; ?>" title="강의 이름">
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
                            <a href="./reserve.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">실습실 예약</a>
                            <a href="./inquire.php?class_no=<?php echo $row['class_no'] ?>" class="btn-s line">문의하기</a>
                          </div>
                          <div class="btn-box-l mt-2 mb-2">
                            <!--후기작성으로 이동-->
                            <a href="./review_write.php?class_no=<?= $row['class_no']; ?>" 
                            class="btn-l" 
                            <?= $isReviewed ? 'style="background:#aaa; pointer-events:none;"' : ''; ?>>
                            <?= $isReviewed ? '후기작성 완료' : '후기작성'; ?>
                            </a>
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
            </div>
          </div>
        </div>
      </div>

    </div>
  </nav>


  <!--알림창-->
  <div class="a_side">
    <!--여기 높이 70 아래보더 1px 색상은 그레이-->
    <h2>알림</h2>
    <!--X 버튼 displya:inline요소 주기 p 사이즈는 padding이나 i에 폰트사이즈로-->
    <p id="toggle_close2">
      <i class="bi bi-x-lg"></i>
    </p>

    <!--알림 내용-->
    <ul>
    `<li>
        <p>추석연휴 안내</p>
        <p>2024-07-30</p>
        <p>9월달 추석 휴강일 안내드립니다. </p>
      </li>
      <li>
        <p>여름방학 안내</p>
        <p>2024-07-30</p>
        <p>8월달 학원 여름방학 안내드립니다. </p>
      </li>
      <li>
        <p>실습실 고장 안내</p>
        <p>2024-06-10</p>
        <p>현재 101호 실습실이 고장이나 안내드립니다.</p>
      </li>
      <li>
        <p>5월 휴강일 안내</p>
        <p>2024-05-01</p>
        <p>5월달 휴강일 안내드립니다. </p>
      </li>
    </ul>
  </div>
</header>

<script>
  $(document).ready(function(){
    
    //사이드바 열고 닫기
    $('#list').click(function(){
      $('#menu').addClass('open');
      $('#topButton').hide();
    })
    $('.toggle_close').click(function(){
      $('.side').removeClass('open');
    })

    $('#my').click(function(){
      $('#my_side').addClass('open');
    })

    //알림창 열고 닫기
    $('#alram').click(function(){
      $('.a_side').addClass('open');
    })
    $('#toggle_close2 i').click(function(){
      $('.a_side').removeClass('open');
    })



    // depth1 클릭 이벤트
    $('.depth1 > a').click(function(e) {
      e.preventDefault();

      $('.depth1 > a').removeClass('active');
      $(this).addClass('active');
      
      $('.depth2').hide();
      var nextDepth2 = $(this).next('.depth2');
      nextDepth2.show();

      //depth2 글씨 숨김
      nextDepth2.find('a').hide();
      // depth2 글씨 반짝임 효과
      nextDepth2.find('a').fadeOut(50).fadeIn(50);
    });

    // 초기 활성화된 depth2 표시
    $('.depth1 > a.active').next('.depth2').show();


    // ----------모든 <style> 태그를 찾아서 <head>로 이동---------
    $('style').each(function() {
      // <head>가 존재하는지 확인
      if ($('head').length) {
        // <style> 태그를 <head>로 이동
        $('head').append($(this));
      }
    });

    //------------새로고침시 헤더위치 변경----------(수정)
    let bwLeft = $(document).scrollLeft();
    $('.h_box > div').css('left',`-${bwLeft}px`)

    //스크롤 할때마다 자동으로 헤더위치 변경
    $(window).scroll(function(){
      bwLeft = $(document).scrollLeft();
      $('.h_box > div').css('left',`-${bwLeft}px`)
    })


    //---------------탭 콘텐츠 버튼-----------
    $('#my_side .tab-link').on('click', function() {
      $('#my_side .tab-link').removeClass('active');
      $(this).addClass('active');
      
      // 모든 탭 콘텐츠 숨기기
      $('#my_side .tab_con > div').removeClass('active');
      // 데이터 속성으로 타겟팅된 탭 콘텐츠 보이기
      var target = $(this).data('tab-target');
      $(target).addClass('active');
    });


      //----------프로필 타이틀 눌렀을때 페이지 이동-----------

      // $_SESSION['id'] 값 가져오기
      var sessionId = '<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>';

      // 프로필 타이틀 클릭 이벤트
      $('.mytitle').click(function() {
        if (sessionId) {
          // 로그인된 경우 비밀번호 확인 페이지로
          window.location.href = './register_edit_password.php';
        } else {
          // 로그인 안된경우 로그인페이지로
          window.location.href = './login.php';
        }
      });

      // 아이콘 메뉴 클릭 이벤트
      $('.icon_menu a').click(function(e) {
        e.preventDefault(); // 기본 클릭 이벤트 방지

        if (sessionId) {
          // 로그인된 경우 클릭한 링크의 href 속성 값으로 페이지 이동
          window.location.href = $(this).attr('href');
        } else {
          // 로그인 안된경우 로그인페이지로 이동
          alert('로그인이 필요합니다.');
          window.location.href = './login.php';
        }
      });


      // ----------출석체크 버튼 클릭 이벤트 처리---------
      $('.attend').click(function() {
        var button = $(this);
        var classNo = button.data('class-no');


        if (sessionId && (button.text().trim() === '출석체크')) {
          // AJAX 요청 보내기
          $.ajax({
            url: './php/attendance_input.php',
            type: 'POST',
            data: {
              class_no: classNo,
              id: sessionId
            },
            success: function(response) {
              // JSON 응답을 파싱
              var php = JSON.parse(response);

              // 서버 응답 처리
              if (php.status === 'success') {
                alert(php.message);
                button.text('출석완료').attr('disabled', true).css('background', '#aaa');
              } else if(php.status === 'done') {
                alert(php.message);
                button.text('출석완료').attr('disabled', true).css('background', '#aaa');
              } else {
                alert(php.message);
              }
            },
            error: function(xhr, status, error) {
              alert('서버 오류: ' + error);
            }
          });

        } else {
          alert('로그인이 필요합니다.');
          window.location.href = './login.php';
        }
      });
      
    })
    

    //pc 검색 아이콘 클릭시 폼제출
    function submitForm() {
      document.getElementById('search_form').submit();
    }




</script>
