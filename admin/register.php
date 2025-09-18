<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 정보 수정</title>
  <?php  include('./header.php'); ?>

<main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 마이페이지 &#x003E; <span style="font-weight:bold">나의 정보 수정</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">나의 정보 수정</h2>
    <p class="text-center mt-4 mb-4">정보가 변경 된 경우 업데이트하여 주십시오.</p>

    <article>
      <h3>정보수정</h3>
      <!-- 선생님 자료는 미리 등록되어 있어서 불러오기가 기본 기능임 -->
      <form action="./register_input.php" method="post" enctype="multipart/form-data" name="나의 정보 수정">

        <?php 
          // 아이디와 일치하는 강사코드 받아오기
          $sql_t = "select * from easycook_register where id='$s_id';";
          $result_t = mysqli_query($conn, $sql_t);
          $t = mysqli_fetch_array($result_t);

          // 선생님 리스트의 정보 받아오기
          $sql_t2 = "select * from easycook_teacher_list where teacher_code='$t[7]';";
          $result_t2 = mysqli_query($conn, $sql_t2);
          $t2 = mysqli_fetch_array($result_t2);
        ?>

        <div class="admin_register1">
          <p class="mb-3 admin_s_title">기본 인적사항</p>

            <!-- 프로필 사진 -->
            <div class="mt-4 mb-2">
              <div class="input-group" style="position: relative;">
                <input type="file" class="form-control" id="img" name="img">
                <label class="input-group-text" for="img">
                  <!-- <i class="bi bi-camera"></i> -->
                  <img id="preview" src="../uploads/profile/<?php echo $t2[3];?>" alt="프로필 미리보기" style="max-width: 300px; ">
                </label>
              </div>
              <a href="./register_img_del.php?teacher_code=<?php echo $t[7];?>" title="프로필 삭제" class="admin_btn admin_btn_del mt-1" style="left:308px; top:419px;">프로필 삭제</a>
            </div>

          <script>
            $(document).ready(function() {
            // -----------프로필 사진 미리보기 기능-----------
            $('#img').change(function(evt) {
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
          });
          </script>

          <div class="mb-3 row">
            <label for="t_code" class="col-sm-2 col-form-label">사원번호</label>
            <div class="col-sm-10">
              <input type="text" class="form-control-plaintext" id="t_code" name="t_code" readonly value="<?php echo $t[7]; ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_name" class="col-sm-2 col-form-label">이름</label>
            <div class="col-sm-10">
              <input type="text" class="form-control-plaintext" id="t_name" name="t_name" readonly value="<?php echo $s_name; ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_birth" class="col-sm-2 col-form-label">생년월일</label>
            <div class="col-sm-10">
              <input type="date" class="form-control" id="t_birth" name="t_birth" value="<?php echo $t2[4];?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_email" class="col-sm-2 col-form-label">이메일</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="t_email" name="t_email" value="<?php echo $t2[5];?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_phone" class="col-sm-2 col-form-label">연락처</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="t_phone" name="t_phone" value="<?php echo $t2[6];?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_address" class="col-sm-2 col-form-label">주소</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="t_address" name="t_address" value="<?php echo $t2[7];?>">
            </div>
          </div>
        </div>

        <div class="admin_register2">
          <p class="mt-5 mb-3 admin_s_title">자격사항</p>
          <div class="mb-3 row">
            <label for="t_carrer" class="col-sm-2 col-form-label">경력</label>
            <div class="col-sm-10">
              <textarea name="t_carrer" id="t_carrer" cols="30" rows="10" class="form-control" ><?php echo $t2[8];?></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t_license" class="col-sm-2 col-form-label">자격증</label>
            <div class="col-sm-10">
              <textarea name="t_license" id="t_license" cols="30" rows="10" class="form-control"><?php echo $t2[9];?></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="t-award" class="col-sm-2 col-form-label">수상이력</label>
            <div class="col-sm-10">
              <textarea name="t-award" id="t-award" cols="30" rows="10" class="form-control" ><?php echo $t2[10];?></textarea>
            </div>
          </div>
        </div>
        <input type="submit" value="수정하기" class="admin_btn admin_btn_del" style="right:0px">
      </form>
    </article>
    <script>
      //텍스트 박스 변수
      let txtbox = document.getElementById('t_carrer');
      let txtbox_2 = document.getElementById('t_license');
      let txtbox_3 = document.getElementById('t-award');

      //텍스트 박스 안의 텍스트값을 변수에 저장
      let txtboxv = txtbox.value;
      let txtboxv_2 = txtbox_2.value;
      let txtboxv_3 = txtbox_3.value;
      
      //텍스트 박스안의 내용을 br 태그 제거하여 다시 저장
      let txtbox2 = txtboxv.replace(/(<br\s*\/?>\s*)+/gi, '\n').trim();
      let txtbox2_2 = txtboxv_2.replace(/(<br\s*\/?>\s*)+/gi, '\n').trim();
      let txtbox2_3 = txtboxv_3.replace(/(<br\s*\/?>\s*)+/gi, '\n').trim();

      console.log(txtbox2);
      txtbox.value= "";
      txtbox.value= txtbox2;
      txtbox_2.value= "";
      txtbox_2.value= txtbox2_2;
      txtbox_3.value= "";
      txtbox_3.value= txtbox2_3;

    </script>
    <div class="copyright">
      Copyright ⓒ Easy Cook, All Rights Reserved.
    </div>
  </section>
  <?php
  include('./footer.php');
  ?>
</body>
</html>