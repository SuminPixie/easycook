
  <style>
    /* 하단 바텀바 서식 */
    footer > nav{
      position: fixed;
      width: 100%;
      bottom: 0;
      border-top: 1px solid #ccc;
      background: #fff;
      z-index: 100;
    }
    footer > nav ul{
      display: flex;
      justify-content: space-evenly;
      width: 100%;
      padding: 0;
      max-width: 1200px;
      margin: 0 auto;
    }
    footer > nav ul li{
      text-align: center;
      width: 60px; height: 60px;
      list-style: none;
    }
    footer > nav a{
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
    footer > nav ul li i{
      font-size: 1.5rem;
      color: var(--black);
    }
    .bi-heart::before{
      transform: scale(0.9);
    }
    .bi-person::before{
      transform: scale(1.1);
    }

    /* 글씨 스타일 조정 */
    footer > nav ul li span{
      text-align: center;
      font-size: var(--fs-small);
      font-weight: var(--fw-light);
      color: var(--black);
      text-wrap: nowrap;
    }

    /* 활성화 버튼 컬러 */
    footer a.active >*{
      color: var(--red);
    }

    /* PC버전 바텀바 숨기기 */
    @media (min-width: 1025px) {
      #bottom{
        display:none !important;
      }
    }

    /* 탑버튼 서식 */
    #topButton {
      position: fixed; 
      bottom: 70px;
      right: 20px;
      display: none;
      background-color: var(--darkbrown);
      color: white;
      border: none;
      box-shadow: 0 0 4px rgba(255, 255, 255, 0.7);
      border-radius: 50%;
      padding: 15px;
      cursor: pointer; 
      font-size: 20px;
      z-index: 1000;
    }
    #topButton:hover {
      filter: brightness(2);
    }

  </style>


  <!-- 하단 바텀바 -->
  <footer id="bottom">
    <nav>
      <ul>
        <li>
          <a href="index.php" title="홈">
            <i class="bi bi-house"></i>
            <span class="">홈</span>
          </a>
        </li>
        <li>
          <a href="search.php" title="검색">
            <i class="bi bi-search"></i>
            <span>검색</span>
          </a>
        </li>
        <li>
          <a href="community.php?comu=커뮤니티&tab=상담신청" title="상담">
            <i class="bi bi-chat-dots"></i>
            <span>상담</span>
          </a>
        </li>
        <li>
          <a href="cart.php" title="찜">
            <i class="bi bi-heart"></i>
            <span>찜</span>
          </a>
        </li>
        <li>
          <a href="mypage.php" title="마이페이지">
            <i class="bi bi-person"></i>
            <span>마이페이지</span>
          </a>
        </li>
      </ul>
    </nav>
  </footer>


  <!-- 탑버튼 -->
  <button id="topButton">
    <i class="bi bi-chevron-up"></i>
  </button>






  <script>
    $(document).ready(function() {
      // 현재 페이지의 URL 경로 가져오기
      const currentPath = decodeURIComponent(window.location.pathname + window.location.search);

      // 각 링크에 대해 반복
      $("footer nav ul li a").each(function() {
        // 링크의 href 속성에서 경로를 가져오기
        const href = decodeURIComponent($(this).attr("href"));

        // 현재 페이지 URL과 링크의 href 비교
        if (currentPath.endsWith(href)) {
          $(this).addClass("active");
        } else {
          $(this).removeClass("active");
        }
      });


      //--------------탑버튼 서식-----------
      var $topButton = $('#topButton');
      $(window).scroll(function() {
        if ($(this).scrollTop() > 20) {
          $topButton.fadeIn();
        } else {
          $topButton.fadeOut();
        }
      });
      $topButton.click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'fast');
      });



    });


  </script>
