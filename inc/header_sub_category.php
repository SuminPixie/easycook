
<style>

	/* ----------------상단 헤더------------ */
	header{
    position: fixed;
		width: 100%; height: 70px;
		background: var(--white);
		border-bottom: 1px solid var(--gray);
		top:0;
		z-index: 1000;
	}
	header .h_box{
    position: absolute;
		width: 100%; height: 40px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		top: 50%;
		transform: translateY(-50%);
    padding: 0 var(--p_20);
	}
  /* 1400px 이상일때 헤더크기 */
  @media (min-width: 1400px) {
    header .h_box{
      width: 1400px;
      left:50%;
      transform: translate(-50%, -50%);
    }
  }
  header .h_box div{
    width: 40px;
    text-align: center;
    cursor: pointer;
  }
  header .h_box div a{
    display: block;
  }
	header .h_box div i{
    line-height: 40px;
    font-size: 30px;
	}
  header .h_box div .bi-bell::before{
    transform: scale(0.8);
  }

  /* 선택박스 서식 */
  #select{
    width: 150px;
  }
  #category-select{
    border: none;
    width: 100%;
    font-size: var(--fs-large);
    font-weight: var(--fw-semi-bold);
    text-align: center;
    padding: 0;
    cursor: pointer;
  }
  #category-select option{
    font-size: var(--fs-medium-large);
  }

    /*---------------알림창-------------- */
    header .a_side{
      top:0px;
      display:none;
      width: 100%;
      padding: 30px 20px 20px 20px;
      z-index: 5000;
      background: #fff;
    }
    /* 1025px PC/데스크탑 (큰 화면) */
    @media (min-width: 1025px) {
      .a_side{
        width: 600px !important;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        right:0;
      }
      .a_side > div{
        border-bottom: none !important;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
      }
    }
    .a_side > div{
      height:70px;
      border-bottom:1px solid var(--gray);
      background: #fff;
    }
    /*알림과 X 가로 정렬 */
    .a_side h2, #toggle_close2{
      display: inline-block;
    }
    .a_side h2{
      font-size:var(--fs-xlarge);
      font-weight:var(--fw-bold);
      padding:10px;
    }
    #toggle_close2{
      float:right;padding:10px;
    }
    #toggle_close2 i{
      cursor: pointer;
      font-size:var(--fs-xlarge);
      font-weight:var(--fw-bold);
      
    }

    /*알림창 내용 */
    .a_side ul{
      display: inline-block;
      width:100%;
      margin:20px auto 0 auto;
      background: #fff;
      height: 100vh;
    }
    .a_side ul>li{
      width:100%;
      height: 85px;
      text-align:left;
      border:1px solid var(--gray);
      border-radius:5px;
      margin-top:10px;
      
    }
    .a_side ul>li >p:first-child{
      font-size:var(--fs-medium);
      font-weight:var(--fw-bold);
      padding:10px 0px 0px 10px ;
    }
    .a_side ul>li >p:nth-child(2){
      display: inline-block;
      float:right;
      font-size:var(--fs-small);
      font-weight:var(--fw-light);
      transform:translate(-10px,-15px);
    }
    .a_side ul>li >p:last-child{
      font-size:var(--fs-medium);
      font-weight:var(--fw-normal);
      padding:25px 10px 0px 10px;
    }


    .a_side.open{
      position: fixed;
      display:block;
      z-index: 10000;
      background: #fff;
    }





</style>
<header>
  <div class="h_box">
    <div id="left">
      <i class="bi bi-chevron-left"></i>
    </div>

    <div id="select">
      <select id="category-select" class="form-select" onchange="if(this.value) location.href=(this.value);">
        <option value="./academy.php?category1=기능사">요리</option>
        <option value="./academy.php?category1=바리스타">바리스타</option>
        <option value="./academy.php?category1=베이커리">베이커리</option>
        <option value="./intro.php?cata=소개&tab=소개">소개</option>
        <option value="./community.php?comu=커뮤니티&tab=후기">커뮤니티</option>
      </select>
    </div>

    <div id="alram" title="알림">
      <i class="bi bi-bell"></i>
    </div>
  </div>

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
      <li>
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
    //이전 페이지로
    $('#left').click(function(){
      //history.back();
      window.location.href = "./index.php";
    });

    //알림창 열고 닫기
    $('#alram').click(function(){
      $('.a_side').addClass('open');
    })
    $('#toggle_close2 i').click(function(){
      $('.a_side').removeClass('open');
    })




    // 페이지가 로드될 때 URL의 쿼리 파라미터를 분석하고 select 박스를 업데이트합니다.
    function updateSelectBox() {
      // 현재 URL에서 쿼리 파라미터를 가져옵니다.
      var urlParams = new URLSearchParams(window.location.search);
      var category = urlParams.get('category1') || urlParams.get('cata') || urlParams.get('comu');
      
      if (category) {
        // `category-select` 요소를 선택합니다.
        var $selectElement = $('#category-select');

        // 모든 옵션을 순회하여, 쿼리 파라미터와 일치하는 값을 가진 옵션을 선택합니다.
        $selectElement.find('option').each(function() {
          if ($(this).val().includes(category)) {
            $selectElement.val($(this).val());
            return false; // 일치하는 옵션을 찾으면 루프 종료
          }
        });
      }
    }

    // 페이지 로드 시 select 박스를 업데이트합니다.
    updateSelectBox();
    
    // ----------모든 <style> 태그를 찾아서 <head>로 이동---------
    $('style').each(function() {
      // <head>가 존재하는지 확인
      if ($('head').length) {
        // <style> 태그를 <head>로 이동
        $('head').append($(this));
      }
    });
  })
</script>
