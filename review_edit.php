<?php
  include('./inc/dbconn.php');

  $no = $_GET['no'];

  $sql = "SELECT * FROM easycook_review WHERE `no`='$no'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);

  $class_no = $row['class_no'];
  
  $sql2 = "SELECT * FROM easycook_academy_list WHERE class_no ='$class_no'";
  $result2 = mysqli_query($conn,$sql2);
  $row2 = mysqli_fetch_array($result2);

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
      <h2>후기 수정</h2>
      <article class="academy_view">
        <ul class="card-list">
          <!-- 태그에 맞는 강의 가져와서 리스트로 넣기 -->
          <li>
            <div>
              <!-- 강의 썸네일 이미지 -->
              <a href="#" title="상세페이지로 이동" style="pointer-events: none;">
                <img src="./uploads/class_main/<?php echo $row2['thumnail_img']; ?>" alt="강의 썸네일 사진">
              </a>
              <!-- 강의 이름 -->
              <div>
                <h2>
                  <a href="#" title="상세페이지로 이동" style="pointer-events: none;">
                    <?php echo $row2['name']; ?>
                  </a>
                </h2>

                <!-- 강의 # 태그 -->
                <p>
                  <span>#<?php echo $row2['category2']; ?></span>
                  <span>#<?php echo $row2['category1']; ?></span>
                  <span>#<?php echo $row2['category3']; ?></span>
                </p>

                <!-- 기간 / 강사이름 -->
                <div>
                  <span><?php echo $row2['start_date']; ?> ~ <?php echo $row2['end_date']; ?></span>
                  <!-- <span><?php echo $row2['teacher']; ?></span> -->
                </div>
              </div>
            </div>

          </li>
        </ul>
      </article>

      <form id="reviewForm" action="./act/review_edit_input.php" method="post" enctype="multipart/form-data">
          <!--session로그인정보에서 id 랑 name을 넣기-->
        <input type='hidden' name='no' value='<?php echo $no ?>'>
        <input type='hidden' name='student_id' value='<?php echo $id ?>'>
        <input type='hidden' name='student_name' value='<?php echo $name ?>'>
        <input type='hidden' name='files_2' value='<?php echo $row['img'] ?>'>
        <!--get방식으로 가져온 class_no를 여기에 넣어서 보내버리기-->
        <input type='hidden' name='class_no' value='<?php echo $class_no?>'>
          <article class="review_star star">
            <h2>별점 등록</h2>
            <!--별점 기능-->
            <!--데이터베이스에서 가져온 별점값-->
            <!-- <input type="hidden" id="starRating_db" value="<?php echo $row['star'] ?>"> -->

              <input type="hidden" id="starRating" name="starRating" value="<?php echo $row['star'] ?>">
              <input type="checkbox" id="checkbox1" class="hide" onclick="changeFormat(1)">
              <label for="checkbox1"><i class="bi bi-star-fill star_size" ></i></label>
              <input type="checkbox" id="checkbox2" class="hide" onclick="changeFormat(2)">
              <label for="checkbox2"><i class="bi bi-star-fill star_size"></i></label>
              <input type="checkbox" id="checkbox3" class="hide" onclick="changeFormat(3)">
              <label for="checkbox3"><i class="bi bi-star-fill star_size"></i></label>
              <input type="checkbox" id="checkbox4" class="hide" onclick="changeFormat(4)">
              <label for="checkbox4"><i class="bi bi-star-fill star_size"></i></label>
              <input type="checkbox" id="checkbox5" class="hide" onclick="changeFormat(5)">
              <label for="checkbox5"><i class="bi bi-star-fill star_size"></i></label>
              <p></p>
          </article>

          <article class="review_photo">

            <h2>사진 (선택)</h2>
            <label for="file_input" class="photo"></label>
            <input type="file" id="file_input" name="files[]" class="hide" multiple accept="image/*">
            <i class="bi bi-camera camera"></i>
            <p>
              <span id="fileCount">0</span>/5
            </p>
            <div id="image_preview">
              <?php foreach (explode(",",$row['img']) as $file) { ?>
      
                <?php if($file == ''){ ?>
                  <img src="./uploads/review/<?php echo $file; ?>" alt="" class="hide">
                <?php }else{ ?>
                  <img src="./uploads/review/<?php echo $file; ?>" alt="">
                <?php } ?>

                <!--이건 이미지 값 보내기-->
                <!-- <?php foreach (explode(",", $photo_files) as $file) { ?>
                <input type="hidden" name="photos[]" value="<?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>">
              <?php } ?> -->

              <?php } ?>
            </div>
          </article>

          <article class="re_view">

            <h2>후기 내용</h2>
            <textarea name="re_view"><?php echo nl2br($row['review']) ?></textarea>
          </article>




          <!-- 등록 버튼 -->
          <p class="btn-box-l">
            <input type="submit" id="submitBtn" value="수정" class="btn-l">
            <a href="./review_list.php" class="btn-l">취소</a>
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
    function changeFormat(lastIndex) {
      for (let i = 1; i <= 5; i++) {
        let $checkbox = $(`#checkbox${i}`);
        let $label = $(`label[for="checkbox${i}"]`);

        //여기에 만약 lastIndex = php star 이다 하면 자동으로 될것같은데
        //해본결과 한번클릭하면 lastIndex값까지만

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

    //수정하기에서 별점값 불러오는기능
    function changestar(){
      let lastIndex = $('#starRating').val();

      for(let j = 1; j <=lastIndex; j++){
        $('.star label').eq(j-1).find('i').addClass('active');
      }
    }
    changestar();

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