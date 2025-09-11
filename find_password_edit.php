<?php
  session_start();
  include('./inc/dbconn.php');

  $id = $_GET['id'];
  
  // 세션 id의 회원정보 불러오기
  $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  mysqli_close($conn); // 연결 종료
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 비밀번호 변경</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <style>
    section {
      max-width: 600px;
      margin: 0 auto;
      padding: 0 20px;
    }
    section > h2 {
      text-align: center;
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 50px 0 10px 0;
    }
    form label {
      font-size: var(--fs-medium);
      font-weight: 600;
    }
  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section>
      <h2>비밀번호 변경</h2>
      <form id="password_change_form" action="./act/find_password_update.php" method="post">
        <!-- 숨겨진 ID 필드 -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <!-- 비밀번호 -->
        <div class="mt-4 mb-2">
          <label for="password" class="form-label">비밀번호<span style="color:var(--red);">*</span></label>
          <small class="d-block text-secondary mb-2" style="font-size: 14px;">8자 이상 20자 이내의 영문, 숫자, 특수문자(!@#$%&amp;) 사용</small>
          <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
          <div class="invalid-feedback">
            영문, 숫자, 특수문자를 조합하여 8~20자로 작성해주세요.
          </div>
        </div>
        <!-- 비밀번호 확인 -->
        <div class="mt-4 mb-2">
          <label for="password2" class="form-label">비밀번호 확인<span style="color:var(--red);">*</span></label>
          <input type="password" class="form-control" id="password2" name="password2" autocomplete="new-password" required>
          <div class="invalid-feedback">
            패스워드가 일치하지 않습니다.
          </div>
        </div>

        <!-- 버튼 형식 -->
        <div class="btn-box-l" style="margin-top: 50px;">
          <button type="submit" class="btn-l">비밀번호 변경</button>
          <button type="button" class="btn-l" onclick="location.href='./mypage.php'">취소</button>
        </div>
      </form>
    </section>
  </main>

  <script>
    $(document).ready(function() {
      // 비밀번호 필드 실시간 검사
      $('#password').on('input', function() {
        var password = $(this).val().trim();
        var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
        if (password === '' || !passwordRegex.test(password)) {
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      // 비밀번호 확인 필드 일치 여부 실시간 검사
      $('#password2').on('input', function() {
        var password = $('#password').val().trim();
        var password2 = $(this).val().trim();
        if (password !== password2) {
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      // 폼 제출 시 빈값 검사
      $('#password_change_form').submit(function(event) {
        event.preventDefault(); // 기본 제출 동작 막기

        var isValid = true;

        // 비밀번호 필드 검사
        var password = $('#password').val().trim();
        var password2 = $('#password2').val().trim();
        if (password === '' || password !== password2) {
          $('#password, #password2').addClass('is-invalid');
          isValid = false;
        } else {
          $('#password, #password2').removeClass('is-invalid');
        }

        if (isValid) {
          // this.submit(); // 유효성 검사 통과 시 폼 제출

          $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                alert(response.message);
                window.location.href = './login.php'; // 비밀번호 변경 성공 후 리디렉션
              } else {
                alert(response.message); // 비밀번호 변경 실패 알림
              }
            },
            error: function() {
              alert('서버 오류. 다시 시도해 주세요.');
            }
          })

        }
      });
    });
  </script>
</body>
</html>
