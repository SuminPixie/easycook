<?php
  include('./inc/dbconn.php');
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
  }else{
    $id = null;
  }

  // question count
  $query = "select count(*) from question WHERE question_id='$id'";
  $result = mysqli_query($conn, $query);
  $max_Num = mysqli_fetch_array($result);

  $num = $max_Num[0];
  $list_num = 5;    
  $page_num =3;    
  $page = isset($_GET["page"])? $_GET["page"] : 1;    
  $total_page = ceil($num / $list_num); 
  $total_block = ceil($total_page / $page_num);    
  $now_block = ceil($page / $page_num);    
  $s_pageNum = ($now_block - 1) * $page_num + 1;    
  if($s_pageNum <= 0){ $s_pageNum = 1; };    
  $e_pageNum = $now_block * $page_num;    
  if($e_pageNum > $total_page){ $e_pageNum = $total_page; };

  $start = ($page - 1) * $list_num;
  $cnt = $start + 1;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 - 이용약관</title>
  <!-- head.php -->
  <?php include('./inc/head.php'); ?>
  <!-- main.css -->
  <link rel="stylesheet" href="./css/main.css">
  <style>
    .promise {
      max-width: 1025px;
      margin: 0 auto;
      padding: 0 20px;
      margin-top: 80px;
      line-height: 160%;
    }
    .promise h2 {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: #333;
      border-bottom: 2px solid var(--red);
      padding-bottom: 10px;
      cursor: pointer;
    }
    .promise .tab-content.active {
      display: block;
    }

    /* 탭 헤더 스타일 */
    .promise .tab-headers {
      display: flex;
      align-items: center;
    }
    .promise .tab-headers h3 {
      margin: 0;
      padding: 10px 20px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-bottom: none;
      text-align: center;
      flex: 1;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .promise .tab-headers h3.active {
      background-color: #e0e0e0;
      border-bottom: 1px solid #ddd;
    }
    .promise .tab-headers h3:not(:last-child) {
      border-right: 1px solid #ddd;
    }
    /* 탭 콘텐츠 스타일 */
    .promise .tab-content {
      display: none;
      padding: 20px;
      border: 1px solid #ddd;
      border-top: none;
    }
    .promise .tab-content.active {
      display: block;
    }
  </style>
</head>
<body>
  <!-- 공통 헤더 삽입 -->
  <?php include('./inc/header.php'); ?>
  <main class="promise mb-5">
    <p class="bread_c">
      <a href="./index.php" title="홈">홈</a> &#62; <b><a href="./promise.php" title="이용약관">이용약관</a></b>
    </p>
    <h2 class="mt-5">이용약관</h2>
    <!-- 탭컨텐츠 -->
    <div class="tab-headers">
      <h3 id="tab1-header">회원약관</h3>
      <h3 id="tab2-header">개인정보처리방침</h3>
    </div>
    <div id="tab1-content" class="tab-content">
      <?php include('./terms/terms_member.php'); ?>
    </div>
    <!-- 탭2: 개인정보처리방침 -->
    <div id="tab2-content" class="tab-content">
      <?php include('./terms/terms_privacy.php'); ?>
    </div>
  </main>
  <!-- 공통 푸터 삽입 -->
  <?php include('./inc/footer.php'); ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const tabHeaders = document.querySelectorAll('h3');
      const tabContents = document.querySelectorAll('.tab-content');
      tabHeaders.forEach(header => {
        header.addEventListener('click', () => {
          tabContents.forEach(content => content.classList.remove('active'));
          tabHeaders.forEach(h => h.style.backgroundColor = '');

          const contentId = header.id.replace('header', 'content');
          document.getElementById(contentId).classList.add('active');
          header.style.backgroundColor = 'var(--admin)';
        });
      });
      tabHeaders[0].click();
    });
  </script>
</body>
</html>