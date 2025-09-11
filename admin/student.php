<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('header.php');      

    // 번호 받아오기
    $class_no = $_GET['class_no'];        // echo $class_no;

    // 강의정보 academy_list에서 데이터 받아오기
    $sql = "SELECT * FROM academy_list WHERE class_no='$class_no';";
    $result = mysqli_query($conn, $sql);
    $db = mysqli_fetch_array($result);

    // order에서 받아오기
    $sql2 = "SELECT * FROM `order` WHERE class_no='$class_no';";
    $result2 = mysqli_query($conn, $sql2);
    $student = mysqli_fetch_array($result2);        

    $sql2_c = "SELECT COUNT(*) FROM `order` WHERE class_no='$class_no';";
    $result2_c = mysqli_query($conn, $sql2_c);
    $student_c = mysqli_fetch_array($result2_c);   

    $sql2_a = "SELECT COUNT(*) FROM `order` WHERE class_no='$class_no' AND student_status='수강중';";
    $result2_a = mysqli_query($conn, $sql2_a);
    $student_a = mysqli_fetch_array($result2_a);   

    $sql2_b = "SELECT COUNT(*) FROM `order` WHERE class_no='$class_no' AND student_status='중도포기';";
    $result2_b = mysqli_query($conn, $sql2_b);
    $student_b = mysqli_fetch_array($result2_b);   

    // 출석날짜와 시간 받기 - attendance에서 출석일
    $sql3 = "SELECT * FROM attendance";    
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
        <li><a href="student.php?class_no=<?php echo $class_no;?>" title="학생관리" class="act">학생관리</a></li>
        <li><a href="class_info.php?class_no=<?php echo $class_no;?>" title="강의소개">강의소개</a></li>
        <li><a href="notice_list.php?class_no=<?php echo $class_no;?>" title="공지사항">공지사항</a></li>
      </ul>
    </article>


    <!-- 내용 -->
    <article class="mt-2 mb-2 scrollable" >
      <h3>테이블</h3>
      <?php
        $class_start = $db[13]; // 시작일 
        $class_end = $db[14];   // 종료일
        $start = $db[15]; // 시작시간
        $end = $db[16];   // 종료시간
        $datetime = $attendance[5];   // 출석시간    echo $datetime;

        date_default_timezone_set('Asia/Seoul'); // 서울 시간대
        $class_day = date('Y-m-d');// 오늘 날짜

        // DateTime 객체 생성
        $startDate = new DateTime($class_start); // 시작일
        $endDate = new DateTime($class_end); // 종료일
        $toDay = new DateTime($class_day); // 오늘
        $attendance_time = new DateTime($datetime); // 출석시간
        
        // 날짜 차이 계산
        $interval2 = $toDay->diff($startDate); // 진행 수업 일수 = 오늘날짜 - 시작날짜
        $interval  = $endDate->diff($startDate); // 총 수업 일수
        $ing = ($interval2->days / $interval->days ) * 100;  // 진행률 = 진행수업일수 / 총수업일수
        
        // 출석시간 받아오기 
        $attendance_t = $attendance_time->format('H:i:s'); // '16:45:00'      echo $attendance_t;

        echo "<p class='mb-2 mt-2'>";
        if(round($ing, 2)<=100){
        echo " 수업 진행률: <b>" . round($ing, 2)  . "</b>%";  //5%가 나와야 한다
        echo " (진행일: " . $interval2->days . "일";
        echo "/총 수업일: " . $interval->days . "일)<br>";
        echo "</p>";}
        else{
          echo "<span style='color:var(--red);'>현재 진행중인 강의가 아닙니다<span>";
        }

        echo "<p class='mb-2'>";
        echo "총 인원 <b>".$student_c[0]."</b>명 ";
        echo "( 수강 <b>".$student_a[0]."</b>명 / ";
        echo "중도포기 <b>".$student_b[0]."</b>명)";
        echo "</p>";
      ?>
      <table class="table table-striped text-center student table-hover" style="font-size:12px;">
        <?php
        $sql2 = "SELECT * FROM `order` WHERE class_no='$class_no'";
        $result2 = mysqli_query($conn, $sql2);
        echo "<thead>"; // 테이블 헤더 출력
          echo '<tr class="bold_line line50">';
            echo '<th>No.</th>';
            echo '<th>상태</th>';
            echo '<th>학생</th>';
            echo '<th>출석</th>';
            echo '<th>결석</th>';
            echo '<th>지각</th>';
            
            // 날짜 헤더 출력
            $currentDate = new DateTime($class_start);
            while ($currentDate <= $endDate) {
                echo '<th class="display_none">' . $currentDate->format('m
                d') . '</th>';
                $currentDate->modify('+1 day'); // 날짜 증가
            }
        echo "</tr>
            </thead>";
        if ($result2->num_rows > 0) {
          // 학생번호를 카운트 하자
          $count = 1;
          while ($student = $result2->fetch_assoc()) {
            $student_id = $student["id"];

            // 그룹별로 아이디와 카운트를 해보자(출석률 구하기 위해)
            $sql_1 = "
              SELECT id, COUNT(*) FROM attendance 
              WHERE id = '$student_id'
                    AND class_no = '$class_no'
                    AND datetime BETWEEN '$class_start' AND '$class_end'
                    GROUP BY id;
              ";
            $result_4 = mysqli_query($conn, $sql_1);
            $a_count = mysqli_fetch_array($result_4, MYSQLI_NUM);
            $attend_percent = ($a_count[1] / $interval->days ) * 100; // 출석률 계산

            // 해당 날짜에 학생의 출석 데이터 확인
            $sql_attendance2 = " 
              SELECT COUNT(*) FROM attendance  
              WHERE id = '$student_id' 
                    AND class_no = '$class_no' 
                    AND datetime BETWEEN '$class_start' AND '$class_end'

                    ";
            $result_attendance2 = mysqli_query($conn, $sql_attendance2);
            $row_att2 = mysqli_fetch_array($result_attendance2);

            // 강의 종료일 이후로는 결석일 계산에서 제외
            $actual_end_date = min(new DateTime($class_end), new DateTime($class_day));
            $count_a = $actual_end_date->diff(new DateTime($class_start))->days - $row_att2[0] + 1;

            // 지각일 계산
            $count_late = 0;
            $attendance_times = array(); // 출석 시간을 저장할 배열
            $sql_attendance = "
              SELECT datetime FROM attendance
              WHERE id = '$student_id'
                    AND class_no = '$class_no'
                    AND datetime BETWEEN '$class_start' AND '$class_end';
            ";

            $result_attendance = mysqli_query($conn, $sql_attendance);
            while ($row_attendance = mysqli_fetch_array($result_attendance)) {
                $attend_time = new DateTime($row_attendance['datetime']);
                $attendance_times[] = $attend_time;
                if ($attend_time > $start_time) {
                    $count_late++;
                }
            }

            echo "<tbody>";

            echo "<tr style='line-height:13px;'>";
              echo "<td>" . $count . "</td>"; //번호
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
              echo "<td>" . $student_id."<br>" . $student['name'] . "</td>"; //아이디
              echo "<td>" . round($attend_percent, 0) . "%</td>"; //출석률
              echo "<td>" . ($count_a > 0 ? "<span style='color:var(--red);'>" . $count_a . "</span>" : "") . "</td>"; //결석일
              echo "<td>" . ($count_late > 0 ? "<span style='color:var(--yellow);'>" . $count_late . "</span>" : "") . "</td>"; //지각일

              // 학생의 출석 날짜들을 출력
              $currentDate = new DateTime($class_start);
              while ($currentDate <= $endDate) {
                  $date_str = $currentDate->format('Y-m-d');
                  $sql_attendance = "
                    SELECT * FROM attendance 
                    WHERE id = '$student_id' AND class_no = '$class_no'  AND DATE(datetime) = '$date_str';
                  ";
                  $result_attendance = mysqli_query($conn, $sql_attendance);
                  $row_attendance = mysqli_fetch_array($result_attendance);

                  // 출석 상태를 확인
                  $status = $row_attendance[0] > 0 ? "&#x3000;" : "";
                  
                  // 지각 상태로 색상 변경
                  $bg_color = "var(--red)"; // 기본 색상은 빨간색 (결석)
                  if ($status == "&#x3000;") {
                      $bg_color = "var(--green)"; // 출석이면 초록색
                      foreach ($attendance_times as $attend_time) {
                          if ($attend_time->format('Y-m-d') == $date_str && $attend_time > $start_time) {
                              $bg_color = "var(--yellow)"; // 지각이면 노란색
                              break;
                          }
                      }
                  }

                  echo "<td class='display_none' style='background-color: $bg_color;'>$status</td>";
                  $currentDate->modify('+1 day'); // 날짜 증가
              }
            echo "</tr>";
            $count++;
            }
            } else {
              echo "<tr><td colspan='100%'>등록된 학생이 없습니다.</td></tr>";
            }
            ?>
            </tbody>
            </table>
            <div class="mt-5 mb-3" style="position:relative;">
                <!-- 목록으로 -->
                <a href="class_1.php" title="목록으로" class="admin_btn admin_btn_yellow position_l_b">목록으로</a>
            </div>
    </article>

  </section>

<?php include('footer.php'); ?>
</body>
</html>
