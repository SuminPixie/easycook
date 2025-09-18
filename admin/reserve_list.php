<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 실습실</title>
  <?php include('./header.php'); ?>
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
          // 오늘 날짜
          $today = new DateTime();
          $todayTime = $today->format('Y-m-d');

          // 시간 문자열에서 '시'만 안전하게 추출하는 헬퍼
          function ec_extract_hour($raw) {
            $s = trim((string)($raw ?? ''));
            if ($s === '') return 0;
            // "13:00" 또는 "13:00:00"
            if (strpos($s, ':') !== false) {
              $parts = explode(':', $s, 2);
              return max(0, min(23, (int)trim($parts[0])));
            }
            // "13시"
            if (preg_match('/^\s*(\d{1,2})\s*시/u', $s, $m)) {
              return max(0, min(23, (int)$m[1]));
            }
            // 숫자만
            if (is_numeric($s)) {
              return max(0, min(23, (int)$s));
            }
            // 기타 문자열은 strtotime 시도
            $ts = strtotime($s);
            if ($ts !== false) return (int)date('H', $ts);
            return 0;
          }
        ?>
        <div class="mb-3">
          <label for="select_time" class="col-sm-2 col-form-label">날짜</label>
          <div>
            <form action="reserve_time.php" method="post" name="날짜 선택하기" id="reserve_time">
              <input type="date" class="form-control" name="select_time" id="select_time" value="<?php echo htmlspecialchars($todayTime, ENT_QUOTES, 'UTF-8'); ?>">
            </form>
          </div>
        </div>

        <script>
          $(document).ready(function(){
            $('#select_time').on('change', function(){
              const d = $(this).serialize(); // select_time=YYYY-MM-DD
              $.ajax({
                url: "reserve_time.php",
                type: "post",
                data: d,
                success: function(result){
                  $('#txt1').html(result);
                }
              });
              return false; // 폼 action으로의 전환 방지
            });
          });
        </script>

        <!-- 데이터 출력 -->
        <div id="txt1">
          <?php
            // 101호 예약 현황
            $sql = "SELECT `start` AS s, COUNT(*) AS cnt
                    FROM easycook_room
                    WHERE room_date = '$todayTime' AND room = '101'
                    GROUP BY `start`
                    ORDER BY `start`";
            $result = mysqli_query($conn, $sql);

            // 102호 예약 현황
            $sql2 = "SELECT `start` AS s, COUNT(*) AS cnt
                     FROM easycook_room
                     WHERE room_date = '$todayTime' AND room = '102'
                     GROUP BY `start`
                     ORDER BY `start`";
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
              if ($result && mysqli_num_rows($result) > 0) {
                while ($db = mysqli_fetch_assoc($result)) {
                  // 시간 계산
                  $h = ec_extract_hour($db['s']);
                  $start = sprintf('%02d:00', $h);
                  $end   = sprintf('%02d:00', ($h + 1) % 24);
                  $cnt   = (int)$db['cnt'];
                  $timeId = 'time101_' . $h; // 중복 방지용 (방번호+시)
              ?>
                <div class="reserve_time">
                  <input type="checkbox" id="<?php echo $timeId; ?>">
                  <label for="<?php echo $timeId; ?>">
                    <ul>
                      <li><?php echo $start; ?> ~ <?php echo $end; ?></li>
                      <li><span style="color:var(--red); font-weight:bold;"><?php echo $cnt; ?></span> / 8</li>
                    </ul>
                  </label>
                  <div class="reserve_p">
                    <ul>
                      <?php
                        $sval = mysqli_real_escape_string($conn, $db['s']);
                        $sql3 = "SELECT * FROM easycook_room
                                 WHERE `start` = '$sval' AND room_date = '$todayTime' AND room = '101'
                                 ORDER BY `start`";
                        $result3 = mysqli_query($conn, $sql3);
                        while ($db3 = mysqli_fetch_array($result3)) {
                          // 출력은 기존 인덱스 사용 유지
                          $name = htmlspecialchars($db3[7] ?? '', ENT_QUOTES, 'UTF-8');
                          $idv  = htmlspecialchars($db3[6] ?? '', ENT_QUOTES, 'UTF-8');
                          echo "<li>{$name} ({$idv})</li>";
                        }
                      ?>
                    </ul>
                  </div>
                </div>
              <?php
                }
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
              if ($result2 && mysqli_num_rows($result2) > 0) {
                while ($db2 = mysqli_fetch_assoc($result2)) {
                  $h = ec_extract_hour($db2['s']);
                  $start = sprintf('%02d:00', $h);
                  $end   = sprintf('%02d:00', ($h + 1) % 24);
                  $cnt   = (int)$db2['cnt'];
                  $timeId = 'time102_' . $h;
              ?>
                <div class="reserve_time">
                  <input type="checkbox" id="<?php echo $timeId; ?>">
                  <label for="<?php echo $timeId; ?>">
                    <ul>
                      <li><?php echo $start; ?> ~ <?php echo $end; ?></li>
                      <li><span style="color:var(--red); font-weight:bold;"><?php echo $cnt; ?></span> / 8</li>
                    </ul>
                  </label>
                  <div class="reserve_p">
                    <ul>
                      <?php
                        $sval2 = mysqli_real_escape_string($conn, $db2['s']);
                        $sql3 = "SELECT * FROM easycook_room
                                 WHERE `start` = '$sval2' AND room_date = '$todayTime' AND room = '102'
                                 ORDER BY `start`";
                        $result3 = mysqli_query($conn, $sql3);
                        while ($db3 = mysqli_fetch_array($result3)) {
                          $name = htmlspecialchars($db3[7] ?? '', ENT_QUOTES, 'UTF-8');
                          $idv  = htmlspecialchars($db3[6] ?? '', ENT_QUOTES, 'UTF-8');
                          echo "<li>{$name} ({$idv})</li>";
                        }
                      ?>
                    </ul>
                  </div>
                </div>
              <?php
                }
              } else {
                echo "<div class='text-center mt-1 mb-5 border rounded-1 p-3'>예약내역이 없습니다</div>";
              }
              ?>
            </div>
          </div>

        </div>

      </article>
    </section>
    <?php include('./footer.php'); ?>
</body>
</html>
