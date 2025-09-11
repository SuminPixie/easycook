<?php
  session_start(); // 세션 시작
?>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 회원가입</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./php/include/head.php'); ?>
  <style>
    section{
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 20px;
    }
    section h2{
      text-align: center;
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 50px 0;
    }
    form label{
      font-size: var(--fs-medium);
      font-weight: var(--fw-bold);
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./php/include/header_sub.php');?>

  <main>
    <section>
      <h2>강사 회원가입</h2>
      <form id="teacher_check_form" action="./php/register_teacher_check.php" method="post">
        <div class="mb-2" style="margin-top: 50px;">
          <label for="teacher_code" class="form-label">사번 확인</label>
          <input type="text" class="form-control" placeholder="사번을 입력해주세요." id="teacher_code" name="teacher_code" required>
          <div class="invalid-feedback">
            해당하는 사번이 없습니다. 원장님께 문의하세요.
          </div>
        </div>

        <!-- 버튼 형식 -->
        <div class="btn-box-l" style="margin-top: 100px;">
          <button type="submit" class="btn-l">다음</button>
          <button type="button" class="btn-l" onclick="history.back();">이전으로</button>
        </div>


        
      </form>



    </section>
    



  </main>
  <script>
    $(document).ready(function() {
      $('#teacher_check_form').submit(function(event) {
        event.preventDefault(); // 기본 제출 동작을 막음

        // 폼 데이터 직렬화
        let formData = $(this).serialize();

        // 서버로 데이터 전송
        $.post('./php/register_teacher_check.php', formData)
          .done(function(response) {
            if (response.trim() === '사번이 일치합니다.') {
              //회원가입 폼 이동
              window.location.href = './register.php';
            } else {
              // 에러 메시지 표시
              $('#teacher_code').addClass('is-invalid');
            }
          })
          .fail(function() {
            // 실패 시 에러 처리
            console.log('요청 실패');
          });
      });
    });
  </script>

</body>
</html>