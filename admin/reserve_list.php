<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 실습실</title>
  <?php include('header.php');   ?>
  <main>
    <section class="m-center m-auto mb-5 class_size">
      <!-- 부스러기 -->
      <p class="bread">홈 &#x003E; 학원 &#x003E; <span style="font-weight:bold">실습실</span></p>

      <!-- 제목 -->
      <h2 class="text-center mt-4 mb-4">실습실 예약 현황</h2>
      <p class="text-center mt-4 mb-4">예약이 필요하시면 원장님께 문의하십시오</p>
      
      <!-- 실습실 예약 현황 보기 -->
      <article>
        <h3>예약현황 보기</h3>
        <!-- 날짜 선택 -->
        <?php
            // 현재 날짜와 시간 객체 생성
            $today = new DateTime();
            // 'Y-m-d' 형식으로 오늘 날짜 출력
            $todayTime = $today->format('Y-m-d'); //echo $todayTime;
        ?>
        <div class="mb-3">
          <label for="select_time" class="col-sm-2 col-form-label">날짜</label>
          <div>
            <form action="reserve_time.php" method="post" name="날짜 선택하기" id="reserve_time">
              <input type="date" class="form-control" name="select_time" id="select_time" value="<?php echo $todayTime;?>">
            </form>
          </div>
        </div>

        <script>
          $(document).ready(function(){
            //전송버튼 눌렀을 때 내용이 실행
            $('#select_time').on('change',function(){
              // alert('전송되었습니다.');
              let d = $(this).serialize();//데이터를 보내기 위해 폼 요소 집합을 문자열로 인코딩
              $.ajax({
                url:"reserve_time.php",
                type:"post",
                data:d,
                // dataType:"요청한데이터타입(html, xml, json, text, script)",
                success:function(result){
                  $('#txt1').html(result);
                }
                })
                return false; //action페이지로 전환되는 것을 방지
              });
          });
        </script>

        <!-- 데이터 출력 -->
        <div id="txt1">
          <?php
            // 101호 예약 현황
            $sql = "SELECT start, COUNT(*) FROM room WHERE room_date = '$todayTime' AND room = '101' GROUP BY start ORDER BY start";
            $result = mysqli_query($conn, $sql);

            // 102호 예약 현황
            $sql2 = "SELECT start, COUNT(*) FROM room WHERE room_date = '$todayTime' AND room = '102' GROUP BY start ORDER BY start";
            $result2 = mysqli_query($conn, $sql2);
          ?>

          <div class="admin_reserve">
            <!-- 실습실1 -->
            <div class="mb-3 admin_reserve_con">
              <p>실습실 101호</p>
              <div>
                <p>사용시간</p>
                <p>예약현황</p>
              </div>

              <?php 
                if (mysqli_num_rows($result) > 0) {
                  while ($db = mysqli_fetch_array($result)) { ?>
                  <div class="reserve_time">
                    <input type="checkbox" id="time<?php echo $db[0]*1 ?>">
                    <label for="time<?php echo $db[0]*1 ?>">
                      <ul>
                        <li><?php echo $db[0]*1 ?>:00 ~ <?php echo $db[0] + 1 ?>:00</li>
                        <li><span style="color:var(--red); font-weight:bold;"><?php echo $db[1] ?></span> / 8</li>
                      </ul>
                    </label>
                    <div class="reserve_p">
                      <ul>
                        <?php
                        $sql3 = "SELECT * FROM room WHERE `start` = '$db[0]' and room_date = '$todayTime' AND room = '101' ORDER BY start";
                        $result3 = mysqli_query($conn, $sql3);
                        while($db3 = mysqli_fetch_array($result3)){
                          ?>
                        <li><?php echo $db3['7']." (".$db3['6'].")";?></li>                
                        <?php }?>             
                      </ul>
                    </div>
                  </div>
                <?php
                  }} else {
                  echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>예약내역이 없습니다</div>";}
                ?>
            </div>

            <!-- 실습실2 -->
            <div class="mb-3 admin_reserve_con">
              <p>실습실 102호</p>
              <div>
                <p>사용시간</p>
                <p>예약현황</p>
              </div>

              <?php
              if (mysqli_num_rows($result2) > 0) {
                while ($db2 = mysqli_fetch_array($result2)) { ?>
                  <div class="reserve_time">
                    <input type="checkbox" id="time<?php echo $db2[0]*1+9 ?>">
                    <label for="time<?php echo $db2[0]*1+9 ?>">
                      <ul>
                        <li><?php echo $db2[0]*1 ?>:00 ~ <?php echo $db2[0] + 1 ?>:00</li>
                        <li><span style="color:var(--red); font-weight:bold;"><?php echo $db2[1] ?></span> / 8</li>
                      </ul>
                    </label>
                    <div class="reserve_p">
                      <ul>
                      <?php
                        $sql3 = "SELECT * FROM room WHERE `start` = '$db2[0]' and room_date = '$todayTime' AND room = '102' ORDER BY start";
                        $result3 = mysqli_query($conn, $sql3);
                        while($db3 = mysqli_fetch_array($result3)){?>
                        <li><?php echo $db3[7]." (".$db3[6].")";?></li>                
                      <?php }?>         
                      </ul>
                    </div>
                  </div>
              <?php
                }} else { echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>예약내역이 없습니다</div>";}
              ?>
            </div>
          </div>

        </div>

      </article>
  </section>
  <?php
  include('footer.php');
  ?>
</body>
</html>
