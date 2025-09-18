<!-- 768px  이상일때 양쪽 여백 다른 문제 해결하기 -->

<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 회원가입</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <style>
    section{
      max-width: 1025px;
      margin: 0 auto;
      padding: 0 20px;
    }
    section h2{
      text-align: center;
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 50px 0;
    }
    section .row{
      text-align: center;
      margin: 0;
      display: flex;
      width: 100%;
      gap: 10px;
    }
    section .row img{
      width: 100px;
    }
    section .row>*{
      padding-right: 0;
      padding-left: 0;
    }
    section .row .card-body{
      border: 1px solid #ccc;
      padding: 20px;

    }

    @media screen and (min-width: 768px) {
      section .row{
        flex-wrap: nowrap;
        justify-content: space-between; 
      }
      section .row .card-body{
        padding: 60px;
      }
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section>
      <h2>회원가입</h2>
      <div class="row">
        <a href="./register.php" class="col-md-6 mb-md-0">
          <div class="card-body">
            <img src="./images/sub/register_pre_1.png" alt="일반 회원">
            <p class="mt-4">일반 회원</p>
          </div>
        </a>
        <a href="./register_teacher.php" class="col-md-6">
          <div class="card-body">
            <img src="./images/sub/register_pre_2.png" alt="강사 회원">
            <p class="mt-4">강사 회원</p>
          </div>
        </a>
      </div>

    </section>




  </main>

  <script>

  // "일반 회원" 앵커 (href로 식별)
  var userLink = document.querySelector('a[href="./register.php"]');
  // "강사 회원" 앵커 (그대로 이동: register_teacher.php에서 사번 입력)
  var teacherLink = document.querySelector('a[href="./register_teacher.php"]');

  // 1) 일반회원
  if (userLink) {
    userLink.addEventListener('click', function (e) {
      var raw = sessionStorage.getItem('kakaoPayload');

      // kakaoPayload 없으면 기본 href로 이동(일반 회원가입)
      if (!raw) return; // preventDefault 안 함

      // kakaoPayload 있으면 가로채서 가입+로그인
      e.preventDefault();

      var kakaoPayload;
      try {
        kakaoPayload = JSON.parse(raw);
      } catch (err) {
        // 파싱 실패 시 안전하게 일반 회원가입으로
        sessionStorage.removeItem('kakaoPayload');
        location.href = './register.php';
        return;
      }

      var body = Object.assign({}, kakaoPayload, { role: 'user' });

      fetch('/act/kakao_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify(body)
      })
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (data.success) {
          sessionStorage.removeItem('kakaoPayload');
          location.href = data.redirect || '/';
        } else {
          alert(data.message || '처리 실패');
        }
      })
      .catch(function(err){
        console.error(err);
        alert('네트워크 오류가 발생했습니다.');
      });
    });
  }
  </script>

</body>
</html>