<?php
  session_start(); // 세션 시작
?>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 회원가입</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
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
  <?php include('./inc/header_sub.php');?>

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
    event.preventDefault(); // 기본 제출 동작 막기

    // 폼 데이터 직렬화
    let formData = $(this).serialize();

    // 서버로 데이터 전송(사번 검증)
    $.post('./act/register_teacher_check.php', formData)
      .done(function(response) {
        if ($.trim(response) === '사번이 일치합니다.') {
          // ✔ 사번 유효 — kakaoPayload 여부에 따라 분기
          var raw = sessionStorage.getItem('kakaoPayload');

          // 입력한 사번값(teacher_code) 추출
          var staff_no = $('#teacher_code').val().trim();

          // kakaoPayload 없으면 기존대로 일반 회원가입 페이지로
          if (!raw) {
            window.location.href = './register.php';
            return;
          }

          // kakaoPayload 있으면: 간편가입+로그인(강사)
          var kakaoPayload;
          try {
            kakaoPayload = JSON.parse(raw);
          } catch (e) {
            // 파싱 실패 시 안전하게 일반 회원가입으로
            sessionStorage.removeItem('kakaoPayload');
            window.location.href = './register.php';
            return;
          }

          // 카카오 통합 로그인/가입 호출 (role=teacher + staff_no 포함)
          $.ajax({
            url: '/act/kakao_login.php',
            method: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: JSON.stringify($.extend({}, kakaoPayload, {
              role: 'teacher',
              staff_no: staff_no
            })),
            xhrFields: { withCredentials: true }
          })
          .done(function(data) {
            if (data && data.success) {
              sessionStorage.removeItem('kakaoPayload');
              window.location.href = data.redirect || '/';
            } else {
              alert((data && data.message) || '처리 실패');
            }
          })
          .fail(function() {
            alert('네트워크 오류가 발생했습니다.');
          });

        } else {
          // ✖ 사번 불일치 — 에러 표시 그대로 유지
          $('#teacher_code').addClass('is-invalid');
        }
      })
      .fail(function() {
        console.log('요청 실패');
      });
  });
});
  </script>

</body>
</html>