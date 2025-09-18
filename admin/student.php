<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('./header.php');      

    // 번호 받아오기
    $class_no = $_GET['class_no'];        // echo $class_no;

    // 강의정보 academy_list에서 데이터 받아오기
    $sql = "SELECT * FROM easycook_academy_list WHERE class_no='$class_no';";
    $result = mysqli_query($conn, $sql);
    $db = mysqli_fetch_array($result);

    // order에서 받아오기
    $sql2 = "SELECT * FROM `easycook_order` WHERE class_no='$class_no';";
    $result2 = mysqli_query($conn, $sql2);
    $student = mysqli_fetch_array($result2);        

    $sql2_c = "SELECT COUNT(*) FROM `easycook_order` WHERE class_no='$class_no';";
    $result2_c = mysqli_query($conn, $sql2_c);
    $student_c = mysqli_fetch_array($result2_c);   

    $sql2_a = "SELECT COUNT(*) FROM `easycook_order` WHERE class_no='$class_no' AND student_status='수강중';";
    $result2_a = mysqli_query($conn, $sql2_a);
    $student_a = mysqli_fetch_array($result2_a);   

    $sql2_b = "SELECT COUNT(*) FROM `easycook_order` WHERE class_no='$class_no' AND student_status='중도포기';";
    $result2_b = mysqli_query($conn, $sql2_b);
    $student_b = mysqli_fetch_array($result2_b);   

    // 출석날짜와 시간 받기 - attendance에서 출석일
    $sql3 = "SELECT * FROM easycook_attendance";    
    $result3 = mysqli_query($conn, $sql3);
    $attendance = mysqli_fetch_array($result3);       
    ?>

<main>
  <section class="m-center m-auto mb-5 class_size">

    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 강의 관리  &#x003E; 나의 강의실 &#x003E; <span style="font-weight:bold">학생관리</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-5 mb-4"> [<?php echo $db['3'];?>][<?php echo $db['4'];?>][<?php echo $db['5'];?>]<?php echo $db['1'];?></h2>

    <!-- 탭컨텐츠 -->
    <article id="tab_con">
      <h3>탭컨텐츠</h3>
      <ul>
        <li><a href="./student.php?class_no=<?php echo $class_no;?>" title="학생관리" class="act">학생관리</a></li>
        <li><a href="./class_info.php?class_no=<?php echo $class_no;?>" title="강의소개">강의소개</a></li>
        <li><a href="./notice_list.php?class_no=<?php echo $class_no;?>" title="공지사항">공지사항</a></li>
      </ul>
    </article>


    <!-- 내용 -->
    <article class="mt-2 mb-2 scrollable table-responsive scrollbar-visible">
      <h3>테이블</h3>
      <?php
        // 기존 변수 그대로 사용
        $class_start = $db[13]; // 시작일
        $class_end   = $db[14]; // 종료일
        $start       = $db[15]; // 시작시간
        $end         = $db[16]; // 종료시간

        date_default_timezone_set('Asia/Seoul');
        $class_day = date('Y-m-d');

        // 진행률 계산(기존 출력 유지)
        $startDate = new DateTime($class_start);
        $endDate   = new DateTime($class_end);
        $toDay     = new DateTime($class_day);

        $interval2 = $toDay->diff($startDate);
        $interval  = $endDate->diff($startDate);
        $ing = ($interval->days > 0) ? ($interval2->days / $interval->days) * 100 : 0;

        echo "<p class='mb-2 mt-2'>";
        if (round($ing, 2) <= 100) {
          echo " 수업 진행률: <b>" . round($ing, 2)  . "</b>%";
          echo " (진행일: " . $interval2->days . "일";
          echo "/총 수업일: " . $interval->days . "일)<br>";
          echo "</p>";
        } else {
          echo "<span style='color:var(--red);'>현재 진행중인 강의가 아닙니다<span>";
        }

        // 총원/수강/중도포기 카운트(기존 변수 그대로)
        echo "<p class='mb-2'>";
        echo "총 인원 <b>".$student_c[0]."</b>명 ";
        echo "( 수강 <b>".$student_a[0]."</b>명 / ";
        echo "중도포기 <b>".$student_b[0]."</b>명)";
        echo "</p>";

        // ====== 성능개선 포인트 1: 날짜 리스트를 한 번만 생성 ======
        $date_list = [];
        $tmpDate = new DateTime($class_start);
        while ($tmpDate <= $endDate) {
          $date_list[] = $tmpDate->format('Y-m-d');
          $tmpDate->modify('+1 day');
        }

        // ====== 성능개선 포인트 2: 모든 출석을 한 번에 로드해서 메모리 맵으로 ======
        // (하루 첫 출석시각만 필요하므로 MIN(TIME))
        $att_map = [];           // [id][Y-m-d] = 'HH:MM:SS'
        $present_cnt_map = [];   // [id] = 출석일수

        $sql_att_all = "
          SELECT id, DATE(`datetime`) AS d, MIN(TIME(`datetime`)) AS first_time
          FROM easycook_attendance
          WHERE class_no='$class_no'
            AND `datetime` BETWEEN '{$class_start} 00:00:00' AND '{$class_end} 23:59:59'
          GROUP BY id, d
        ";
        $result_att_all = mysqli_query($conn, $sql_att_all);
        if ($result_att_all) {
          while ($row = mysqli_fetch_assoc($result_att_all)) {
            $sid = $row['id'];
            $d   = $row['d'];
            $att_map[$sid][$d] = $row['first_time'];
            if (!isset($present_cnt_map[$sid])) $present_cnt_map[$sid] = 0;
            $present_cnt_map[$sid]++;
          }
          mysqli_free_result($result_att_all);
        }

        // ====== 학생 목록 로드(그대로) ======
        $sql2 = "SELECT * FROM `easycook_order` WHERE class_no='$class_no'";
        $result2 = mysqli_query($conn, $sql2);

        echo "<table class='table table-striped text-center student' style='font-size:12px; white-space: nowrap;'>";
        echo "<thead>";
        echo '<tr class="bold_line line50">';
          echo '<th>No.</th>';
          echo '<th>상태</th>';
          echo '<th>학생</th>';
          echo '<th>출석</th>';
          echo '<th>결석</th>';
          echo '<th>지각</th>';

          // 날짜 헤더(한 번 생성한 리스트 재사용)
          foreach ($date_list as $d) {
            echo '<th class="display_none">' . date('n/j', strtotime($d)) . '</th>';
          }
        echo "</tr></thead>";
        echo "<tbody>";

        if ($result2 && $result2->num_rows > 0) {
          $count = 1;

          // 결석 계산용 기준(기존 로직 유지)
          $startObj = new DateTime($class_start);
          $endObj   = new DateTime($class_end);
          $todayObj = new DateTime($class_day);

          while ($student = $result2->fetch_assoc()) {
            $student_id = $student["id"];

            // 출석/지각/결석 계산: 쿼리 없이 맵으로 처리
            $present_days_or_cnt = (int)($present_cnt_map[$student_id] ?? 0);

            if ($todayObj < $startObj) {
              $count_a = 0;
            } else {
              $actual_end_date = ($endObj < $todayObj) ? $endObj : $todayObj;
              $period_days = $startObj->diff($actual_end_date)->days;
              if ($period_days < 0) $period_days = 0;
              $count_a = max(0, $period_days - $present_days_or_cnt);
            }

            $count_late = 0;
            if (!empty($att_map[$student_id])) {
              foreach ($att_map[$student_id] as $d => $first_time) {
                if ($d < $class_start || $d > $class_end) continue;
                if ($first_time !== null && strcmp($first_time, $start) > 0) {
                  $count_late++;
                }
              }
            }

            echo "<tr style='line-height:13px;'>";
              echo "<td>" . $count . "</td>"; // 번호
              echo "<td style='text-align:center;'>";
                if ($student["student_status"] == "수강중") {
                  echo "<span style='color:var(--green); font-size: 16px;'>
                          <i class='bi bi-person-fill'></i>
                        </span>";
                } else {
                  echo "<span style='color:var(--red); font-size: 16px;'>
                          <i class='bi bi-person-fill-slash'></i>
                        </span>";
                }
              echo "</td>";
              echo "<td>" . $student_id."<br>" . $student['name'] . "</td>"; // 아이디/이름

              // (기존 출력 방식 그대로 유지)
              echo "<td>" . ($count_a > 0 ? "<span style='color:var(--green);'>" . $present_days_or_cnt . "</span>" : "0") . "</td>"; // 출석일
              echo "<td>" . ($count_a > 0 ? "<span style='color:var(--red);'>" . $count_a . "</span>" : "0") . "</td>"; // 결석일
              echo "<td>" . ($count_late > 0 ? "<span style='color:var(--yellow);'>" . $count_late . "</span>" : "0") . "</td>"; // 지각일

              // 학생의 출석 날짜들 출력(맵 조회로 O(1))
              foreach ($date_list as $d) {
                $isPresent = isset($att_map[$student_id][$d]);
                $status = $isPresent ? "&#x3000;" : "";  // 전각 공백(기존 유지)
                $bg_color = "transparent";
                if ($isPresent) {
                  $first_time = $att_map[$student_id][$d];
                  if ($first_time !== null && strcmp($first_time, $start) > 0) {
                    $bg_color = "var(--yellow)"; // 지각
                  } else {
                    $bg_color = "var(--green)";  // 정시 출석
                  }
                }
                echo "<td class='display_none' style='background-color: $bg_color;'>$status</td>";
              }

            echo "</tr>";
            $count++;
          }
        } else {
          echo "<tr><td colspan='6'>등록된 학생이 없습니다.</td></tr>";
        }

        echo "</tbody>";
        echo "</table>";
      ?>
    </article>

          <div class="mt-5 mb-3" style="position:relative;">
              <!-- 목록으로 -->
              <a href="./class_1.php" title="목록으로" class="admin_btn admin_btn_yellow position_l_b">목록으로</a>
          </div>

  </section>

<?php include('./footer.php'); ?>
</body>
</html>
