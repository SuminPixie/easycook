<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; <span style="font-weight:bold">강의 수정</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">강의 수정</h2>

    <article>
      <!-- 굵은 선 -->
      <div class="text-center mb-4 title_bold_line"></div>
      
      <form action="class_create_input.php" method="post" name="강의 개설하기" enctype="multipart/form-data">  
        <?php            
          $sql_t = "select * from register where id='$s_id';";
          $result_t = mysqli_query($conn, $sql_t);
          $t = mysqli_fetch_array($result_t);
          $teacher_code = $t['teacher_code'];

          // 번호 받아오기
          $class_no = $_GET['no'];    

          //academy_list에서 데이터 받아오기
          $sql = "select * from academy_list where class_no='$class_no';";
          $result = mysqli_query($conn, $sql);
          $db = mysqli_fetch_array($result);
        ?>
        <!-- 클래스 번호 -->
        <input type="hidden" name="class_no" id="class_no" value="<?php echo $db[0];?>">
        <!-- teacher_id -->
        <input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo $s_id;?>">
        <!-- teacher_code -->
        <input type="hidden" name="teacher_code" id="teacher_code" value="<?php echo $teacher_code;?>">
        <!-- teacher name -->
        <input type="hidden" name="teacher_name" id="teacher_name" value="<?php echo $s_name;?>">  
        <div class="mb-3 row">
          <label for="name" class="col-sm-2 col-form-label">과목명</label>
          <div class="col-sm-10">
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $db[1];?>" required>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="code" class="col-sm-2 col-form-label">과목코드</label>
          <div class="col-sm-10">
            <select name="code" id="code" class="form-select" required>
              <option value="" disabled>선택하세요</option> <!-- 플레이스홀더 옵션 추가 -->
              <option value="20240710A01" <?php echo ($db[2] == '20240710A01') ? 'selected' : ''; ?>>20240710A01</option>
              <option value="20240710A02" <?php echo ($db[2] == '20240710A02') ? 'selected' : ''; ?>>20240710A02</option>
              <option value="20240710A03" <?php echo ($db[2] == '20240710A03') ? 'selected' : ''; ?>>20240710A03</option>
              <option value="20240710A04" <?php echo ($db[2] == '20240710A04') ? 'selected' : ''; ?>>20240710A04</option>
              <option value="20240710A05" <?php echo ($db[2] == '20240710A05') ? 'selected' : ''; ?>>20240710A05</option>
              <option value="20240710A06" <?php echo ($db[2] == '20240710A06') ? 'selected' : ''; ?>>20240710A06</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="category1" class="col-sm-2 col-form-label">과정명</label>
          <div class="col-sm-10">
            <select name="category1" id="category1" class="form-select" required>
              <option value="" disabled>선택하세요</option> <!-- 플레이스홀더 옵션 추가 -->
              <option value="한식조리기능사" <?php echo ($db[3] == '한식조리기능사') ? 'selected' : ''; ?>>한식조리기능사</option>
              <option value="양식조리기능사" <?php echo ($db[3] == '양식조리기능사') ? 'selected' : ''; ?>>양식조리기능사</option>
              <option value="일식조리기능사" <?php echo ($db[3] == '일식조리기능사') ? 'selected' : ''; ?>>일식조리기능사</option>
              <option value="중식조리기능사" <?php echo ($db[3] == '중식조리기능사') ? 'selected' : ''; ?>>중식조리기능사</option>
              <option value="제과제빵" <?php echo ($db[3] == '제과제빵') ? 'selected' : ''; ?>>제과제빵</option>
              <option value="바리스타" <?php echo ($db[3] == '바리스타') ? 'selected' : ''; ?>>바리스타</option>
            </select>
          </div>
        </div>        
        <div class="mb-3 row">
          <label for="category2" class="col-sm-2 col-form-label">구분</label>
          <div class="col-sm-10">
            <select name="category2" id="category2" class="form-select" required>
              <option value="" disabled>선택하세요</option> <!-- 플레이스홀더 옵션 추가 -->
              <option value="국비" <?php echo ($db[4] == '국비') ? 'selected' : ''; ?>>국비</option>
              <option value="일반" <?php echo ($db[4] == '일반') ? 'selected' : ''; ?>>일반</option>
              <option value="창업" <?php echo ($db[4] == '창업') ? 'selected' : ''; ?>>창업</option>
              <option value="취미" <?php echo ($db[4] == '취미') ? 'selected' : ''; ?>>취미</option>
              <option value="자격증" <?php echo ($db[4] == '자격증') ? 'selected' : ''; ?>>자격증</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="category3" class="col-sm-2 col-form-label">카테고리</label>
          <div class="col-sm-10">
            <select name="category3" id="category3" class="form-select" required>
              <option value="" disabled>선택하세요</option> <!-- 플레이스홀더 옵션 추가 -->
              <option value="평일" <?php echo ($db[5] == '평일') ? 'selected' : ''; ?>>평일</option>
              <option value="주말" <?php echo ($db[5] == '주말') ? 'selected' : ''; ?>>주말</option>
              <option value="여름학기" <?php echo ($db[5] == '여름학기') ? 'selected' : ''; ?>>여름학기</option>
              <option value="원데이" <?php echo ($db[5] == '원데이') ? 'selected' : ''; ?>>원데이</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="nth" class="col-sm-2 col-form-label">차수</label>
          <div class="col-sm-10">
            <select name="nth" id="nth" class="form-select" required>
              <option value="" disabled>선택하세요</option> <!-- 플레이스홀더 옵션 추가 -->
              <option value="1" <?php echo ($db[11] == '1') ? 'selected' : ''; ?>>1차</option>
              <option value="2" <?php echo ($db[11] == '2') ? 'selected' : ''; ?>>2차</option>
              <option value="3" <?php echo ($db[11] == '3') ? 'selected' : ''; ?>>3차</option>
              <option value="4" <?php echo ($db[11] == '4') ? 'selected' : ''; ?>>4차</option>
              <option value="5" <?php echo ($db[11] == '5') ? 'selected' : ''; ?>>5차</option>
              <option value="6" <?php echo ($db[11] == '6') ? 'selected' : ''; ?>>6차</option>
              <option value="7" <?php echo ($db[11] == '7') ? 'selected' : ''; ?>>7차</option>
              <option value="8" <?php echo ($db[11] == '8') ? 'selected' : ''; ?>>8차</option>
              <option value="9" <?php echo ($db[11] == '9') ? 'selected' : ''; ?>>9차</option>
              <option value="10" <?php echo ($db[11] == '10') ? 'selected' : ''; ?>>10차</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="place" class="col-sm-2 col-form-label">장소</label>
          <div class="col-sm-10">
          <input type="text" class="form-control" value="서울시 강남구 00로 00빌딩 3층, 이지쿡" name="place" id="place">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="start_date" class="col-sm-2 col-form-label">시작일</label>
          <div class="col-sm-10">
            <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $db[13];?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="end_date" class="col-sm-2 col-form-label">종료일</label>
          <div class="col-sm-10">
            <input type="date" name="end_date" id="end_date" class="form-control" required value="<?php echo $db[14];?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="start_time" class="col-sm-2 col-form-label">시작시간</label>
          <div class="col-sm-10">
            <input type="time" name="start_time" id="start_time" class="form-control" required value="<?php echo $db[15];?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="end_time" class="col-sm-2 col-form-label">종료시간</label>
          <div class="col-sm-10">
            <input type="time" name="end_time" id="end_time" class="form-control" required value="<?php echo $db[16];?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="phone" class="col-sm-2 col-form-label">연락처</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="phone" id="phone" required value="<?php echo $t['phone']?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="grade" class="col-sm-2 col-form-label">난이도</label>
          <div class="col-sm-10">
            <select name="grade" id="grade" class="form-select" required>
              <option value="" disabled <?php echo empty($db[10]) ? 'selected' : ''; ?>>선택하세요</option>
              <option value="상" <?php echo ($db[10] == '상') ? 'selected' : ''; ?>>상</option>
              <option value="중" <?php echo ($db[10] == '중') ? 'selected' : ''; ?>>중</option>
              <option value="하" <?php echo ($db[10] == '하') ? 'selected' : ''; ?>>하</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="member_num" class="col-sm-2 col-form-label">정원</label>
          <div class="col-sm-10">
            <input type="number" name="member_num" id="member_num" class="form-control" required value="<?php echo $db[11];?>">
          </div>
        </div>
        <div class="mb-5 row">
          <h4>강의정보</h4>
          <div class="mb-4">
            <input type="file" id="input-file" class="upload-hidden form-control mb-1" name="img" required> 
            <p>썸네일 : 강의를 잘 표현 하는 사진을 첨부하여 주십시오.</p>
          </div>
          <div class="mb-4">
            <input type="file" id="parent_img" class="upload-hidden form-control mb-1" name="parent_img" required> 
            <p>상세이미지 : 강의 설명은 이미지로 첨부하여 주십시오.</p>
          </div>
          <div class="mb-4">
            <textarea name="detail" id="detail" cols="30" rows="10" placeholder="추가 설명" class="form-control mb-1" required><?php echo $db[20];?></textarea>
            <p>강의에 대한 부가 설명을 작성해 주십시오.</p>
          </div>
        </div>
        <div class="mb-3 mt-3" style="position:relative;">
          <a href="class_1.php" title="강의목록으로" class="admin_btn admin_btn_yellow position_l_b">강의목록</a>
          <input type="reset"  value="초기화"        class="admin_btn admin_btn_gray position_c_b">
          <input type="submit" value="강의수정" name="update" class="admin_btn admin_btn_red position_r_b">
        </div>  
      </form>
    </article>
</section>
<?php
include('footer.php');
?>
</body>
</html>
