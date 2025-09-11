<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 공지관리</title>
  <?php include('header.php');    ?>
  <main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; <span style="font-weight:bold">공지작성</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-4">공지작성</h2>

    <!-- 입력폼 -->
    <form action="notice_input.php" name="답변하기" method="post">
      <!-- 공지 입력  -->
      <?php
        $class_no = $_GET['class_no'];

        // 강의정보 가져오기
        $query = "select * from academy_list where class_no='$class_no'";
        $result = mysqli_query($conn, $query);
        $class = mysqli_fetch_array($result);
      ?>
      <article>
        <div class="mb-3 mt-5">
          <label for="title" class="form-label">제목</label>
          <input type="text" name="title" id="title" class="form-control">
        </div>
        <div class="mb-3">
          <label for="class" class="form-label">강의명</label>
            <select name="class_no" id="class" class="form-select" required>
              <option value="">강의를 선택하세요</option>
              <?php 
                //아이디를 찾아라
                $s_id = $_SESSION['id'];  

                //나의 강사코드를 찾아라
                $query2 = "select * from register where id='$s_id'";
                $result2 = mysqli_query($conn, $query2);
                $class2 = mysqli_fetch_array($result2);

                // 강사코드가 일치하는 강의명을 가져와라
                $sql = "select * from academy_list where teacher_code='$class2[7]'";
                $result2=mysqli_query($conn, $sql);    
                          
                //반복해라
                while($teacher=mysqli_fetch_array($result2)){
                ?>
                <option value="<?php echo $teacher[0];?>"><?php echo $teacher[1];?></option>
                <?php }   ?>                    
            </select>
        </div>
        <div class="mb-3">
          <label for="memo" class="form-label">공지내용 (<span id="remainingChars" class="p01">255</span>자 남음)</label>
          <textarea name="memo" id="memo" class="form-control" cols="50" rows="10"oninput="txtCount(this, 255)"><?php echo htmlspecialchars($q[7], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <script>
          function txtCount(memoElement, maxlimit) {
            const currentLength = memoElement.value.length;
            if (currentLength > maxlimit) {
              memoElement.value = memoElement.value.substring(0, maxlimit);
            }
            document.getElementById('remainingChars').textContent = maxlimit - memoElement.value.length;
            }
            window.onload = function() {
              txtCount(document.getElementById('memo'), 255);
            }
        </script>

        <div class="mt-5" style="position:relative;">
          <a href="class_1.php" title="이전페이지로" class="admin_btn admin_btn_gray position_l_b">이전페이지</a>
          <a href="notice_total.php" title="공지목록으로" class="admin_btn admin_btn_yellow position_c_b">공지목록</a>
          <input type="submit" value="작성완료" class="admin_btn admin_btn_red position_r_b">        
        </div>
      </article>
    </form>
  </section>
<?php
include('footer.php');
?>
</body>
</html>
