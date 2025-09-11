<?php
include('../include/dbconn.php');
$select_time = $_POST['select_time'];

// 101호 예약 현황
$sql = "SELECT start, COUNT(*) FROM room WHERE room_date = '$select_time' AND room = '101' GROUP BY start ORDER BY start";
$result = mysqli_query($conn, $sql);

// 102호 예약 현황
$sql2 = "SELECT start, COUNT(*) FROM room WHERE room_date = '$select_time' AND room = '102' GROUP BY start ORDER BY start";
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
    if (mysqli_num_rows($result2) > 0) {
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
          $sql3 = "SELECT * FROM room WHERE `start` = '$db[0]' and room_date = '$select_time' AND room = '101'";
          $result3 = mysqli_query($conn, $sql3);
          while($db3 = mysqli_fetch_array($result3)){?>
          <li><?php echo $db3[7]." (".$db3[6].")";?></li>                
          <?php }?>                     
        </ul>
      </div>
    </div>
    <?php }
    } else {
      echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>예약내역이 없습니다</div>";
    }
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
                $sql3 = "SELECT * FROM room WHERE `start` = '$db2[0]' and room_date = '$select_time' AND room = '102' ORDER BY start";
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
