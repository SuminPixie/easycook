<?php
  session_start();
  include('./inc/dbconn.php');

  $id = $_SESSION['id'];
  
  // 세션id의 회원정보 불러오기
  $sql = "SELECT * FROM easycook_register WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  $teacher_code = $row['teacher_code'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <style>
    section{
      max-width: 600px;
      margin: 0 auto;
      padding: 0 20px;
      margin-bottom: 50px;
    }
    section > h2{
      text-align: center;
      font-size: var(--fs-large);
      font-weight: var(--fw-bold);
      padding: 50px 0 10px 0;
    }
    form label{
      font-size: var(--fs-medium);
      font-weight: 600;
    }

    /* 프로필 서식 */
    #profile{
      display:none;
    }
    .input-group-text {
      background-color: #fff !important;
      font-size: 1.5rem;
      color: #ccc;
      border-radius: 50% !important;
      width: 60px; height: 60px;
      justify-content: center;
      overflow: hidden;
      cursor: pointer;
      position: relative;
    }
    #preview {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit:cover;
    }



    /* 이메일 서식 */
    #email::placeholder {
      font-size: var(--fs-small);
      font-weight: var(--fs-light);;
    }

    /* 이용약관 서식 */
    .accordion{
      font-size: var(--fs-small);
    }
    .accordion-button{
      padding: 10px;
      font-size: var(--fs-small);
    }
    .accordion-button::after{
      transform:scale(0.8);
    }
    .accordion-button:not(.collapsed)::after{
      transform: rotate(-180deg) scale(0.8);
    }

  </style>
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section>
      <h2>회원정보 수정</h2>
      <form id="register_form" action="./act/register_edit_update.php" method="post" enctype="multipart/form-data">
        <!-- 프로필 사진 -->
        <div class="mt-4 mb-2">
          <label for="profile" class="form-label">프로필 사진</label>
          <div class="input-group">
            <input type="file" class="form-control" id="profile" name="profile">
            <label class="input-group-text" for="profile">
              <i class="bi bi-camera"></i>
              <img id="preview" src="./uploads/profile/<?php echo $row['profile']; ?>" alt="프로필 미리보기" style="max-width: 80px; display: block;">
            </label>
          </div>
        </div>
        <!-- 이름 -->
        <div class="mt-4 mb-2">
          <label for="name" class="form-label">이름<span style="color:var(--red);">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>">
          <div class="invalid-feedback">
            이름을 입력해주세요.
          </div>
        </div>
        <!-- 강사코드 -->
        <?php if (!empty($teacher_code)): ?>
          <div class="mt-4 mb-2">
            <label for="teacher_code" class="form-label">강사코드<span style="color:var(--red);">*</span></label>
            <input type="text" class="form-control text-secondary" id="teacher_code" name="teacher_code" value="<?php echo $row['teacher_code']; ?>" disabled>
            <!-- 폼 전송용 숨겨진 필드 추가 -->
            <input type="hidden" name="teacher_code" value="<?php echo $row['teacher_code']; ?>">
            <div class="invalid-feedback">
              올바른 강사코드를 입력해주세요.
            </div>
          </div>
        <?php endif; ?>
        <!-- 아이디 -->
        <div class="mt-4 mb-2">
          <label for="id" class="form-label">아이디<span style="color:var(--red);">*</span></label>
          <small class="d-block text-secondary mb-2" style="font-size: 14px;">4자 이상 20자 이내의 영문,숫자 사용</small>
          <input type="text" class="form-control text-secondary" id="id" name="id" value="<?php echo $row['id']; ?>" disabled>
          <!-- 폼 전송용 숨겨진 필드 추가 -->
          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
          <div class="invalid-feedback">
            영문, 숫자를 조합하여 4~20자로 작성해주세요.
          </div>
        </div>
        <!-- 비밀번호 -->
        <div class="mt-4 mb-2">
          <label for="password" class="form-label">비밀번호<span style="color:var(--red);">*</span></label>
          <small class="d-block text-secondary mb-2" style="font-size: 14px;">8자 이상 20자 이내의 영문,숫자,특수문자(!@#$%&amp;)사용</small>
          <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
          <div class="invalid-feedback">
            영문, 숫자, 특수문자를 조합하여 8~20자로 작성해주세요.
          </div>
        </div>
        <!-- 비밀번호 확인 -->
        <div class="mt-4 mb-2">
          <label for="password2" class="form-label">비밀번호 확인<span style="color:var(--red);">*</span></label>
          <input type="password" class="form-control" id="password2" name="password2" autocomplete="new-password">
          <div class="invalid-feedback">
            패스워드가 일치하지 않습니다.
          </div>
        </div>
        <!-- 전화번호 -->
        <div class="mt-4 mb-2">
          <label for="phone" class="form-label">전화번호<span style="color:var(--red);">*</span></label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>">
          <div class="invalid-feedback">
            전화번호를 올바르게 입력해주세요.
          </div>
        </div>
        <!-- 이메일 -->
        <div class="mt-4 mb-2">
          <label for="email" class="form-label">이메일 (선택입력)</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" value="<?php echo $row['email']; ?>">
        </div>

        <!-- 버튼 형식 -->
        <div class="btn-box-l" style="margin-top: 50px;">
          <button type="submit" class="btn-l">수정완료</button>
          <button type="button" class="btn-l" onclick="location.href='./mypage.php'">수정취소</button>
        </div>
      </form>

    </section>

  </main>
  <script>
    $(document).ready(function() {
      // -----------프로필 사진 미리보기 기능-----------
      $('#profile').change(function(evt) {
        var tgt = evt.target || window.event.srcElement,
            files = tgt.files;
        if (FileReader && files && files.length) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#preview').attr('src', e.target.result).show();
          };
          reader.readAsDataURL(files[0]);
        }
      });


      // -----------회원가입 입력 폼 실시간 검사--------------
      var isValid = true;

      // 이름 필드 실시간 검사
      $('#name').on('input', function() {
        var name = $(this).val().trim();
        if (name === '') {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }
      });

      // 아이디 필드 실시간 검사
      $('#id').on('input', function() {
        var id = $(this).val().trim();
        var idRegex = /^[a-zA-Z0-9]{4,20}$/;
        if (id === '' || !idRegex.test(id)) {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }

        // AJAX를 이용한 아이디 중복 확인
        $.ajax({
          url: './act/register_id_check.php',
          type: 'POST',
          data: { id: id },
          success: function(response) {
            if (response === '사용중') {
              $('#id').addClass('is-invalid');
              $('#id').next('.invalid-feedback').text('이미 사용중인 아이디입니다.');
              isValid = false;
            } else {
              isValid = true;
            }
          }
        });
      });

      // 패스워드 필드 실시간 검사
      $('#password').on('input', function() {
        var password = $(this).val().trim();
        var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
        if (password === '' || !passwordRegex.test(password)) {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }
      });

      // 패스워드 확인 필드 일치 여부 실시간 검사
      $('#password2').on('input', function() {
        var password = $('#password').val().trim();
        var password2 = $(this).val().trim();
        if (password !== password2) {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true;
        }
      });

      // 전화번호 필드 실시간 검사
      $('#phone').on('input', function() {
        var phone = $(this).val().trim().replace(/-/g, ''); // '-' 제거
        var phoneRegex = /^010\d{8}$/; // 010으로 시작하고, 8자리 숫자
        if (phone === '' || !phoneRegex.test(phone)) {
          $(this).addClass('is-invalid');
          isValid = false; 
        } else {
          $(this).removeClass('is-invalid');
          isValid = true; 
        }
      });

      // -----------------폼 제출 시 빈값 검사--------------------
      $('#register_form').submit(function(event) {
        event.preventDefault(); // 기본 제출 동작 막기

        var isFormValid = true;

        // 각 필드를 검사하여 빈값이면 is-invalid 클래스 추가
        $('#name, #id, #password, #password2, #phone').each(function() {
          var $this = $(this);
          if ($this.val().trim() === '') {
            $this.addClass('is-invalid');
            isFormValid = false;
          } else {
            $this.removeClass('is-invalid');
          }
        });

        if (isFormValid) {
          this.submit(); // 유효성 검사 통과 시 폼 제출
        }
      });
  });
</script>


<!-- 공통푸터삽입 -->
<?php include('./inc/footer.php');?>
<!-- 공통바텀바삽입 -->
<?php include('./inc/bottom.php');?>

</body>
</html>