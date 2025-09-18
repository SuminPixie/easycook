<meta name="keywords" content="요리 자격증, 요리 강의, 커피 자격증, 커피 강의, 베이커리 자격증, 베이커리 강의, 온라인 요리 수업, 요리 학원, 커피 학원, 베이커리 학원, 이지쿡, easycook, 요리 수강, 커피 수강, 베이킹 수강, 요리 온라인 강의, 커피 온라인 강의, 베이킹 온라인 강의, 요리 레시피, 베이킹 레시피">
<meta name="description" content="이지쿡에서 요리, 커피, 베이커리 자격증과 수강, 온라인 강의를 배워보세요. 전문 강사진의 체계적인 교육으로 전문가가 되는 길을 안내합니다.">
<meta name="robots" content="index, follow">
<!-- 파비콘 -->
<link rel="apple-touch-icon" href="../favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="48x48" href="../favicon/favicon-48x48.png">
<link rel="icon" type="image/png" sizes="192x192" href="../favicon/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="../favicon/android-chrome-512x512.png">
<link rel="shortcut icon" href="../favicon/favicon.ico">
<!-- 부트스트랩  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- 스와이퍼  -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.5/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11.1.5/swiper-bundle.min.js"></script>
<!-- 제이쿼리 -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- css -->
<link rel="stylesheet" href="../css/admin/reset.css" type="text/css">
<link rel="stylesheet" href="../css/admin/base.css" type="text/css">
<link rel="stylesheet" href="../css/admin/main.css" type="text/css">
<link rel="stylesheet" href="../css/admin/common.css" type="text/css">
<link rel="stylesheet" href="../css/admin/style.css">

<!-- admin 스크립트 연결 -->
<script src="../script/admin/admin.js"></script>

</head>
<body>
  <?php
  include('../inc/dbconn.php');
  $s_id = $_SESSION['id'];   
  $s_name = $_SESSION['name'];
  
  // 로그인 없이 강사페이지로 가지 못하게 하기
  if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
    // 강사코드가 있다면 강사페이지로 이동한다.
    if (!empty($_SESSION['teacher_code'])) {

        } else { // 강사코드가 없다면 메인페이지로 이동한다.
        echo "<script>alert('로그인 성공');</script>";
        echo "<script>location.replace('../index.php');</script>";
    }
  } else {
    // 로그인 오류
    echo "<script>alert('로그인이 필요한 페이지 입니다.');</script>";
    echo "<script>location.replace('../login.php');</script>";
  }

  // echo $s_id;
  ?>
  <!-- 헤더영역 :  로고, 로그아웃 -->
  <!-- 로그인, 회원가입 끝나면 데이터 연결하기 -->
  <header>
    <!-- width: 427px모바일 이하에서의 토글메뉴 -->
    <h1>
      <a href="index.php" title="관리자 홈">
        <img src="../images/common/logo.png" alt="로고">
      </a>
    </h1>
    <div>
      <!-- 토글 -->
      <i class="bi bi-list" id="m_list"></i>
      <!-- 로그아웃 -->
      <a href="../act/logout.php" title="로그아웃" class="admin_logout">
        <img src="../images/admin/logout.png" alt="로그아웃 그림"><br>
        로그아웃
      </a>
    </div>

    <!-- 네비게이션 : 메뉴 -->
    <nav class="left_nav" id="left_nav">
      <div>
        <i class="bi bi-x-lg" id="close_m_list"></i>
        <?php echo $s_name;?>님 안녕하세요.
      </div>
      <ul class="nav_inner" >
        <li>         
          <img src="../images/admin/nav01.png" alt="마이페이지 그림">
          <span>마이페이지</span>
          <ul>
            <li><a href="register.php" title="정보수정">정보수정</a></li>
            <li><a href="vacation.php" title="휴가신청">휴가신청</a></li>
          </ul>
        </li>
        <li>
          <img src="../images/admin/nav02.png" alt="학원 그림">
          <span>학원</span>
          <ul>
            <li><a href="EC_notice.php" title="학원소식">학원소식</a></li>
            <li><a href="reserve_list.php" title="실습실">실습실</a></li>
            <li><a href="../index.php" title="학생사이트로 이동">학원사이트</a></li>
          </ul>
        </li>
        <li>
          <img src="../images/admin/nav03.png" alt="강의관리 그림">
          <span>강의관리</span>
          <ul>
            <li><a href="class_1.php" title="나의 강의실">나의 강의실</a></li>
            <li><a href="class_create.php" title="강의 개설">강의 개설</a></li>
          </ul>
        </li>
        <li>
          <img src="../images/admin/nav04.png" alt="학생관리 그림">
          <span>학생관리</span>
          <ul>
            <li><a href="student_total.php" title="학생관리">학생 관리</a></li>
            <li><a href="review_list.php" title="수강후기">수강 후기</a></li>
          </ul>
        </li>
        <li>
          <img src="../images/admin/nav05.png" alt="게시판관리 그림">
          <span>게시판관리</span>
          <ul>
            <li><a href="question_1.php" title="문의관리">문의관리</a></li>
            <li><a href="notice_total.php" title="공지사항">공지사항</a></li>
          </ul>
        </li>
      </ul>
      <p class="copyright mt-5"> Copyright ⓒ Easy Cook, All Rights Reserved.</p>
    </nav>

    <!-- width: 1025이하에서의 네비게이션 : 메뉴 -->
    <nav class="lnb left_nav2">
      <ul class="nav_inner2">
        <li>        
          <a href="javascript:void(0);" title=""><img src="../images/admin/nav01.png" alt="마이페이지 그림"></a>
            <ul class="subMenu">
              <li><span class="subMenu_title">마이페이지</span></li>
              <li><a href="register.php" title="정보수정">정보수정</a></li>
              <li><a href="vacation.php" title="휴가신청">휴가신청</a></li>
            </ul>
        </li>
        <li>
          <a href="javascript:void(0);"><img src="../images/admin/nav02.png" alt="학원 그림"></a>
          <ul class="subMenu">
            <li><span class="subMenu_title">학원</span></li>
            <li><a href="EC_notice.php" title="학원소식">학원소식</a></li>
            <li><a href="reserve_list.php" title="실습실">실습실</a></li>
            <li><a href="../index.php" title="학생사이트로 이동">학원사이트</a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:void(0);"><img src="../images/admin/nav03.png" alt="강의관리 그림"></a>
          <ul class="subMenu">
            <li><span class="subMenu_title">강의관리</span></li>
            <li><a href="class_1.php" title="나의 강의실">나의 강의실</a></li>
            <li><a href="class_create.php" title="강의 개설">강의 개설</a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:void(0);"><img src="../images/admin/nav04.png" alt="학생관리 그림"></a>
          <ul class="subMenu">
            <li><span class="subMenu_title">학생관리</span></li>
            <li><a href="student_total.php" title="학생관리">학생 관리</a></li>
            <li><a href="review_list.php" title="수강 후기">수강 후기</a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:void(0);"><img src="../images/admin/nav05.png" alt="게시판관리 그림"></a>
          <ul class="subMenu">
            <li><span class="subMenu_title">게시판 관리</span></li>
            <li><a href="question_1.php" title="문의관리">문의관리</a></li>
            <li><a href="notice_total.php" title="공지사항">공지사항</a></li>
        </ul>
        </li>
      </ul>
    </nav>

    <!-- 탑버튼 -->
      <button id="topButton">
        <i class="bi bi-chevron-up"></i>
      </button>
  </header>