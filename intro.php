<?php
  include('./inc/dbconn.php');

  $cata = isset($_GET['cata']) ? trim($_GET['cata']) : '';
  $tab = isset($_GET['tab']) ? trim($_GET['tab']) : '';

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡</title>
    <?php include('./inc/head.php'); ?>
    <link rel="stylesheet" href="./css/sub.css" type="text/css">
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub_category.php');?>

  <main>
    <section class="sub_header">
      <h2 class="hide">카테고리 소개페이지</h2>
      <article class="tab_con intro">
        <h2 class="hide">카테고리 소개페이지</h2>
        <ul class="tab_menu">
          <li><a href="?cata=<?php echo $cata; ?>&tab=소개" class="<?php echo ($tab == '소개') ? 'on' : ''; ?>">소개</a></li>
          <li><a href="?cata=<?php echo $cata; ?>&tab=강사진" class="<?php echo ($tab == '강사진') ? 'on' : ''; ?>">강사진</a></li>
        </ul>
      </article>
    </section>

    <article>
      <div class="intro_con">
        <?php if($tab == '소개') {?>
          <img src="./images/sub/intro_1.png" alt="휴게실 사진">
          <div class="intro_text">
            <h2>쉽고 재미있는 요리학원 이지쿡</h2>
            <p>인간의 배움은 비단 학교와 책에서 끝나는 것이 아니기에 이지쿡 아카데미는 쉽고 재미있는 요리를 향한 꿈을 함께할 동반자가 되어 드릴 것 입니다. 믿을 수 있는 멘토와 좋은 환경 그리고 어려운것을 쉽게할 줄 아는 강사진과 함께 한 사람의 상상과 꿈이 현장에서 실현될 수 있도록 평생의 공부를 돕는 것, 그것이 이지쿡 아카데미의 목표입니다.</p>
          </div>
          <img src="./images/sub/intro_2.png" alt="제과제빵강의실 사진">
          <div class="intro_text">                
            <h2>시설</h2>
            <p>자유롭게 예약해서 사용할수있는 실습실 2곳과 수업이 진행이 되는 바리스타 강의실 2곳 요리 강의실 2곳  제과제빵 강의실2곳으로 나뉘어져있습니다 .</p>
            <h2>교육 목적</h2>
            <p>믿을 수 있는 멘토와 좋은 환경 그리고 어려운것을 쉽게할 줄 아는 강사진과 함께 한 사람의 상상과 꿈이 현장에서 실현될 수 있도록 평생의 공부를 돕는 것, 그것이 이지쿡 아카데미의 목표입니다.</p>
            <h2>위치</h2>
          </div>
          <div id="map" style="width:100%;height:400px;"></div>
          
          <?php }else if($tab == '강사진') {?>
            <!-- 준비중페이지 -->
            <?php include('./inc/ready.php');?>
          <?php } ?>
        </div>

    </article>
  </main>

  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>

  <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=4ee70829e487a3d51af14c0335fdbe22"></script>
  <script src="./script/kakaomap_api.js"></script>


  <script>  
    $(document).ready(function() {
      $('.tab_menu a').on('click', function() {
        $('.tab_menu a').removeClass('on');
        $(this).addClass('on');
      });

      $('style').each(function() {
    // <head>가 존재하는지 확인
    if ($('head').length) {
      // <style> 태그를 <head>로 이동
      $('head').append($(this));
    }
  }); 
    });

  </script>

</body>
</html>