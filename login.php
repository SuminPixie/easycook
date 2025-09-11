<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>로그인페이지</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>

  <!-- 자바스크립트 쿠키파일 연결 -->
  <script defer src="./script/javascript_cookie.js"></script>

  <style>
    /* 로그인페이지 서식 */
    main{
      padding: 0 20px;
    }
    section{
      max-width: 600px;
      margin: 0 auto;
    }
    #Login > h2{
      text-align: center;
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 50px 0 20px 0;
    }
    form label{
      font-size: var(--fs-medium);
      font-weight: 600;
      display: none;
    }
    input{
      height: 40px;
    }
    input::placeholder {
      font-size: var(--fs-small);
      font-weight: var(--fs-light);;
    }

    /* 간편 로그인 서식 */
    #easyLogin{
      border-top: 1px solid #eee;
    }
    #easyLogin > h2{
      text-align: center;
      font-size: var(--fs-medium-large);
      font-weight: var(--fw-bold);
      padding: 30px 0;
    }
    #easyLogin .btn-l{
      display: flex;
      justify-content: center;
      align-items:center;
      gap: 10px;
      color: #000;
      font-weight: 400;
      font-size: var(--fs-small);
    }
    #easyLogin .btn-l:nth-of-type(1){
      background: #FAE300;
    }
    #easyLogin .btn-l:nth-of-type(2){
      background: #fff;
      border: 1px solid #ccc;
      color: #888;
    }
    #easyLogin .btn-l:nth-of-type(3){
      background: #03C75A;
    }
    #easyLogin img{
      height: 100%;
    }


  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section id="Login">
      <h2>로그인</h2>
      <form action="./act/login_check.php" id="loginForm" name="loginForm" method="post">
        <div class="login_form">
          <div class="mt-3 mb-2">
            <label for="id" class="form-label">아이디</label>
            <input type="text" class="form-control" name="id" id="id" placeholder="아이디를 입력해주세요">
            <div class="invalid-feedback">
              아이디를 입력해주세요.
            </div>
          </div>
          <div class="mt-3 mb-2">
            <label for="password" class="form-label">비밀번호</label>
            <div style="position: relative;">
              <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호를 입력해 주세요">
              <div class="invalid-feedback">
                비밀번호를 입력해주세요.
              </div>
            </div>
          </div>
        </div>
        <!-- 아이디 저장 체크 -->
        <input type="checkbox" id="id_save" name="id_save" class="form-check-input mt-2">
        <label for="id_save" class="form-check-label mt-2 mb-3" style="font-size: var(--fs-small); font-weight: var(--fw-light); display: inline-block;">아이디 저장</label>
        <!-- 로그인 버튼 -->
        <div class="btn-box-l">
          <button id="login" class="btn-l" type="submit">로그인</button>
        </div>
        <!-- 아이디 찾기 -->
        <div class="container text-center mt-4 mb-5">
          <div class="row" style="text-wrap: nowrap; font-size:var(--fs-small);">
            <div class="col border-end"><a href="./find_id.php" class="link-secondary">아이디 찾기</a></div>
            <div class="col border-end"><a href="./find_password.php" class="link-secondary">비밀번호 찾기</a></div>
            <div class="col"><a href="./register_pre.php" class="link-secondary">회원가입</a></div>
          </div>
        </div>
      </form>
    </section>
      
    <!-- 간편 로그인 -->
    <section id="easyLogin">
      <h2>간편 로그인</h2>
      <div class="btn-box-l">
        <button class="btn-l" onclick="kakaoLogin()">
          <img src="./images/common/icon_kakao.png" alt="카카오 로그인" >
          <span>
            <a href="javascript:void(0)" title="카카오로 로그인">
              카카오로 로그인
            </a>
          </span>
        </button>
        <button class="btn-l">
          <img src="./images/common/icon_google.png" alt="구글 로그인">
          <span>구글로 로그인</span>
        </button>
        <button class="btn-l">
          <img src="./images/common/icon_naver.png" alt="네이버 로그인">
          <span>네이버로 로그인</span>
        </button>

      </div>

    </section>





  </main>


  <script>
    $(document).ready(function(){

      // -----------폼 제출 시 빈값 검사----------
      $('#loginForm').submit(function(event) {
        event.preventDefault(); // 기본 제출 동작 막기

        var isFormValid = true;

        // 각 필드를 검사하여 빈값이면 is-invalid 클래스 추가
        $('#id, #password').each(function() {
          var $this = $(this);
          if ($this.val().trim() === '') {
            $this.addClass('is-invalid');
            isFormValid = false;
          } else {
            $this.removeClass('is-invalid');
          }
        });
        // 유효성 검사 통과 시 폼 제출
        if (isFormValid) {
          this.submit();
        }
      });

      // ------------빈칸 실시간 검사-----------
      $('#id').on('input', function() {
        var id = $(this).val().trim();
        var isValid = true;
        if (id === '') {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }
      });
      $('#password').on('input', function() {
        var password = $(this).val().trim();
        var isValid = true;
        if (password === '') {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }
      });



      // 빈칸 유효성 검사 함수
      function validateCheck() {
        const id = $('#id').val();
        const password = $('#password').val();
        let isValid = true;

        if (id === '') {
          $('#id').addClass('is-invalid');
          isValid = false;
        } else {
          $('#id').removeClass('is-invalid');
        }

        if (password === '') {
          $('#password').addClass('is-invalid');
          isValid = false;
        } else {
          $('#password').removeClass('is-invalid');
        }
        return isValid;
      }


      //--------아이디 저장하는 쿠키 함수----------
      let key = getCookie('idCookie'); //쿠키이름 저장

      if(key!=""){ //만약에 key값이 있다면
        $('#id').val(key); //id값을 저장
      }

      if($('#id').val() !=""){ //만약에 id값이 있다면
        $('#id_save').attr('checked',true); //체크박스에 체크를 해준다.
      }

      $('#id_save').change(function(){ //체크박스의 상태가 바뀌면 아래내용 실행
        if($('#id_save').is(':checked')){ //체크박스에 체크가 된 경우라면
          setCookie('idCookie', $('#id').val(), 7); //쿠키를 생성하고
        }else{ //그렇지 않으면
          deleteCookie('idCookie'); //쿠키를 삭제한다.
        }
      })
      
      $('#id').keyup(function(){ //아이디 입력창에 키를 눌렀을 경우
        if($('#id_save').is(':checked')){ //체크박스에 체크가 된 경우라면
          setCookie('idCookie', $('#id').val(), 7); //쿠키를 생성한다.
        }
      })

    })
  </script>



  <!-- 카카오 로그인 -->
  <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
  <script>
    Kakao.init('4ee70829e487a3d51af14c0335fdbe22');
    // sdk초기화여부판단 
    console.log(Kakao.isInitialized());
    //카카오로그인 
    function kakaoLogin() {
      //Kakao.Auth.authorize()
      Kakao.Auth.login({
        success: function (response) {
          Kakao.API.request({ 
          url: '/v2/user/me', success: function (response) {
              console.log(response)
              }, fail: function (error) { 
              console.log(error)
              }, 
            })
            }, fail: function (error) { 
            console.log(error) 
          }, 
        }) 
      } 
    //카카오로그아웃 
    function kakaoLogout() {
    if (Kakao.Auth.getAccessToken()) { 
      Kakao.API.request({
      url: '/v1/user/unlink',
    })
    .then(function(response) {
      console.log(response);
    })
    .catch(function(error) {
      console.log(error);
    });
    // Kakao.API.request({ 
    // url: '/v1/user/unlink', success: function (response) { 
    // console.log(response) 
    // }, fail: function (error) { 
    //   console.log(error) }, 
    //});
      Kakao.Auth.setAccessToken(undefined) 
    }}
  </script>


</body>
</html>