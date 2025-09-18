<?php
  // 세션이 이미 시작되었는지 확인
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
  }else{
    $id = null;
  }
  include('./inc/dbconn.php');
  $class_no = $_GET['class_no'];
  // echo $class_no;
  $sql = "select * from easycook_academy_list where class_no = '$class_no'";
  // $sql = "select * from academy_list where class_no = '14'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 후기</title>
  <!-- 공통 헤드정보 삽입 -->
  <?php include('./inc/head.php'); ?>
  <!-- 서브서식 연결 -->
  <link rel="stylesheet" href="./css/sub.css">
</head>
<body>
  <!-- 공통헤더삽입 -->
  <?php include('./inc/header_sub.php');?>

  <main>
    <section class="review_write">
      <p class="bread_c">
        <a href="./index.php" title="홈">홈</a> &#62; 
        <a href="./mypage.php" title="마이페이지">마이페이지</a> &#62; 
        <b><a href="./review_list.php" title="나의 후기">나의 후기</a></b>
      </p>
      <h2>후기 작성</h2>
      <article class="academy_view">
        <ul class="card-list">
          <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
          <li>
            <div>
              <!-- 강의 썸네일 이미지 -->
              <a href="javascript:void(0);" title="상세페이지로 이동" style="pointer-events: none;">
                <img src="./uploads/class_main/<?php echo $row['thumnail_img']; ?>" alt="강의 썸네일 사진">
              </a>
              <!-- 강의 이름 -->
              <div>
                <h2>
                  <a href="javascript:void(0);" title="강의 이름" style="pointer-events: none;">
                    <?php echo $row['name']; ?>
                  </a>
                </h2>

                <!-- 강의 # 태그 -->
                <p>
                  <span>#<?php echo $row['category2']; ?></span>
                  <span>#<?php echo $row['category1']; ?></span>
                  <span>#<?php echo $row['category3']; ?></span>
                </p>

                <!-- 기간 / 강사이름 -->
                <div>
                  <span><?php echo $row['start_date']; ?> ~ <?php echo $row['end_date']; ?></span>
                  <!-- <span><?php echo $row['teacher']; ?></span> -->
                </div>
              </div>
            </div>

          </li>
        </ul>
      </article>

      <form id="reviewForm" action="./act/review_write_input.php" method="post" enctype="multipart/form-data">
        <!--session로그인정보에서 id 랑 name을 넣기-->
      <input type='hidden' name='student_id' value='<?php echo $id ?>'>
      <input type='hidden' name='student_name' value='<?php echo $name ?>'>
      <!--get방식으로 가져온 class_no를 여기에 넣어서 보내버리기-->
      <input type='hidden' name='class_no' value='<?php echo $class_no?>'>
        <article class="review_star star">
          <h2>별점 등록</h2>
          <!--별점 기능-->
            <input type="hidden" name="code" value="<?php echo $row['code'] ?>">
            <input type="hidden" id="starRating" name="starRating" value="5">
            <input type="checkbox" id="checkbox1" class="hide" onclick="changeFormat(1)">
            <label for="checkbox1"><i class="bi bi-star-fill active star_size" ></i></label>
            <input type="checkbox" id="checkbox2" class="hide" onclick="changeFormat(2)">
            <label for="checkbox2"><i class="bi bi-star-fill star_size"></i></label>
            <input type="checkbox" id="checkbox3" class="hide" onclick="changeFormat(3)">
            <label for="checkbox3"><i class="bi bi-star-fill star_size"></i></label>
            <input type="checkbox" id="checkbox4" class="hide" onclick="changeFormat(4)">
            <label for="checkbox4"><i class="bi bi-star-fill star_size"></i></label>
            <input type="checkbox" id="checkbox5" class="hide" onclick="changeFormat(5)">
            <label for="checkbox5"><i class="bi bi-star-fill star_size"></i></label>
        </article>

        <article class="review_photo">

          <h2>사진 (선택)</h2>
          <label for="file_input" class="photo">
            <i class="bi bi-camera camera"></i>
            <p>
              <span id="fileCount">0</span>/5
            </p>
          </label>
          <input type="file" id="file_input" name="files[]" class="hide" multiple accept="image/*">
          <div id="image_preview"></div>
          <!-- <button type="submit">파일 업로드</button> -->
        </article>

        <article class="re_view">
          <h2>후기 내용</h2>
          <textarea name="re_view"></textarea>
        </article>

        <!-- 등록 버튼 -->
        <p class="btn-box-l">
          <input type="submit" id="submitBtn" value="등록" class="btn-l">
          <input type="button"  value="취소" class="btn-l">
        </p>
      </form>

    </section>
  </main>


  <!-- 공통푸터삽입 -->
  <?php include('./inc/footer.php');?>
  <!-- 공통바텀바삽입 -->
  <?php include('./inc/bottom.php');?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    //별점기능
    $(function() {
      changeFormat(5);
    });

    function changeFormat(lastIndex) {
      for (let i = 1; i <= 5; i++) {
        let $checkbox = $(`#checkbox${i}`);
        let $label = $(`label[for="checkbox${i}"]`);

        if (i <= lastIndex) {
          $checkbox.prop('checked', true);
          $label.children().addClass('active');
        } else {
          $checkbox.prop('checked', false);
          $label.children().removeClass('active');
        }
      }

      // Update the hidden input field with the selected star count
      $('#starRating').val(lastIndex);
    }
    //사진넣기 기능
    function previewImages() {
      var preview = document.querySelector('#image_preview');
      var files = document.querySelector('input[type=file]').files;
      var fileCountSpan = document.querySelector('#fileCount');
      var maxFiles = 5; // 최대 선택 가능한 파일 개수

      // 파일 개수 표시 업데이트
      fileCountSpan.textContent = files.length;

      // 이미지 미리보기 생성
      function readAndPreview(file) {
        // 이미지 파일인지 확인
        if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
          var reader = new FileReader();

          reader.addEventListener("load", function () {
            var image = new Image();
            image.title = file.name;
            image.src = this.result;
            image.classList.add('preview-image');
            preview.appendChild(image);
          }, false);

          reader.readAsDataURL(file);
        }
      }

      // 파일 개수 제한
      if (files.length > maxFiles) {
        alert('최대 ' + maxFiles + '개까지 선택할 수 있습니다.');
        return;
      }

      // 이미지 미리보기 생성 및 파일 개수 제한
      preview.innerHTML = ''; // 이미지 미리보기 초기화
      for (var i = 0; i < files.length; i++) {
        readAndPreview(files[i]);
      }
    }

    document.querySelector('#file_input').addEventListener('change', previewImages);
</script>


</body>
</html>