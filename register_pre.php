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
  var userLink = document.querySelector('a[href="./register.php"]');

  if (userLink) {
    userLink.addEventListener('click', async function (e) {
      var raw = sessionStorage.getItem('kakaoPayload');
      if (!raw) return; // kakaoPayload 없으면 원래 페이지 이동

      e.preventDefault(); // kakaoPayload 있으면 가로채서 소셜 가입/로그인

      let kakaoPayload;
      try {
        kakaoPayload = JSON.parse(raw);
      } catch (err) {
        sessionStorage.removeItem('kakaoPayload');
        location.href = './register.php';
        return;
      }

      const body = Object.assign({}, kakaoPayload, { role: 'user' });

      try {
        const resp = await fetch('./act/kakao_login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include'
        , body: JSON.stringify(body)
        });

        // 네트워크 자체 실패만 catch로 가고, HTTP 4xx/5xx는 여기서 처리
        const text = await resp.text();
        let data = null;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error('서버가 JSON이 아닌 응답을 보냄:', text);
          alert('서버 응답 형식 오류. 콘솔을 확인하세요.');
          return;
        }

        if (!resp.ok || !data.success) {
          console.error('서버 오류 응답:', resp.status, data);
          alert(data.message || '처리 실패');
          return;
        }

        
        // 성공
        if (data.message) alert(data.message); 
        sessionStorage.removeItem('kakaoPayload');
        location.href = data.redirect || './index.php';
      } catch (err) {
        console.error('Fetch 네트워크 오류:', err);
        alert('네트워크 오류가 발생했습니다.');
      }
    });
  }
</script>


</body>
</html>