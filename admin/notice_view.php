<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 공지관리</title>
  <?php include('header.php');  ?>
  <main>
    <section class="m-center m-auto mb-5 class_size">
      <?php
        //세션정보
        $s_id = $_SESSION['id']; 

        // 번호 받아오기
        $no = $_GET['no'];   
      
        //board 공지사항에서 데이터 받아오기
        $sql = "select * from board where no='$no';";
        $result = mysqli_query($conn, $sql);
        $db = mysqli_fetch_array($result); // echo $db[1];

        //academy_list에서 데이터 받아오기
        $sql2 = "select * from academy_list where class_no='$db[1]';";
        $result2 = mysqli_query($conn, $sql2);
        $class = mysqli_fetch_array($result2);
      ?>
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리 &#x003E; 나의 강의실 &#x003E; <span style="font-weight:bold">공지보기</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-5 mb-4"> [<?php echo $class['3'];?>][<?php echo  $class['4'];?>][<?php echo $class['5'];?>]<?php echo  $class['1'];?></h2>

    <!-- 공지보기 -->
    <article>
      <h3>공지보기, 수정하기</h3>
      <form action="notice_input.php" name="공지 수정하기" method="post">
        <table class="table m-auto mt-5 text-center">
          <caption>질문과 답변</caption>
          <tr style="border-top: 3px solid black;">
            <td><input type="text" class="form-control" name="notice_title" value="<?php echo $db[2];?>"></td>
          </tr>
          <tr style="border-top: 1px solid black;">
            <td>
              <textarea name="notice_memo" class="form-control" cols="30" rows="10" id="notice_memo"><?php echo $db[3];?></textarea>
              <input type="hidden" value="<?php echo $db[1];?>" name="class_no">
              <input type="hidden" value="<?php echo $no;?>" name="no">
              <input type="hidden" value="<?php echo $s_id?>" name="teacher">
            </td>
          </tr>
        </table>
        <div class="mt-5 mb-3" style="position:relative;">
          <!-- 목록으로 -->
          <a href="notice_list.php?class_no=<?php echo $db[1];?>" title="목록으로" class="admin_btn admin_btn_yellow position_l_b">목록으로</a>
          <!-- 공지 수정/작성 하기 -->
          <input type="submit" value="수정완료" class="admin_btn admin_btn_red position_r_b">
          <!-- 공지 삭제 하기 -->
          <input type="submit" value="삭제" name="delete" class="admin_btn admin_btn_gray position_c_b">
        </div>
      </form>
    </article>
    <script>
      //텍스트 박스 변수
      let txtbox = document.getElementById('notice_memo');

      //텍스트 박스 안의 텍스트값을 변수에 저장
      let txtboxv = txtbox.value;
      
      //텍스트 박스안의 내용을 br 태그 제거하여 다시 저장
      let txtbox2 = txtboxv.replace(/(<br>|<br\/>|<br \/>)/g,'\r\n');

      console.log(txtbox2);
      txtbox.value= "";
      txtbox.value= txtbox2;

    </script>
    <?php
    include('footer.php');
    ?>
</body>
</html>