<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 강사페이지</title>
  <?php
    include('./header.php');      
  ?>
  <main>     
    <section class="m-center m-auto mb-5 class_size">
      <!-- 부스러기 -->
      <p class="bread">홈 &#x003E; <span style="font-weight:bold">메인</span></p>

      <!-- 제목 -->
      <h2 class="text-center mt-4 mb-4">강사페이지</h2>
      <article>
        <h3>관리자 메인</h3>
        <div class="board">
          <div class="board_con">
            <div class="board_title">
              <h4>학원소식</h4>
              <span><a href="EC_notice.php" title="학원소식">더보기 <i class="bi bi-chevron-right"></i></a></span>
            </div>
            <div>
              <ul><!-- 학원 소식 부르기 -->
              <?php 
                $sql = "select * from easycook_notice order by no desc limit 4";
                $result = mysqli_query($conn, $sql);              
                while($db=mysqli_fetch_array($result)){                
              ?>
                <li>
                  <a href="EC_notice.php"  title="학원 소식 가기">
                    <ul>
                      <li><input type="hidden" value="<?php echo $db[0]?>"><?php echo $db[1]?></li>
                      <li><span><?php echo date("Y-m-d",strtotime($db['3']))?></span></li>
                    </ul>
                  </a>
                </li>
                <?php }?>
              </ul>
            </div>
          </div>
          <div class="board_con">
            <div class="board_title">
              <h4>나의 공지사항</h4>
              <span><a href="notice_total.php" title="공지사항으로">더보기 <i class="bi bi-chevron-right"></i></a></span>
            </div>
            <div>
              <ul><!-- 나의 공지사항 -->
                <?php
                  // board에서 데이터 가져오기
                  $sql_b = "select * from easycook_board where id='$s_id' order by no desc limit 4";
                  $result_b = mysqli_query($conn, $sql_b);
                  while($db_b = mysqli_fetch_array($result_b)){
  
                  //강의 번호 받아오기
                  $sql_no = "select * from easycook_academy_list where class_no='$db_b[1];'";
                  $result_no = mysqli_query($conn, $sql_no);
                  $db_no = mysqli_fetch_array($result_no);
                ?>
                <li>
                  <a href="notice_total.php"  title="나의 공지사항 바로가기">
                    <ul>
                      <li class="board_sub"><?php echo $db_b[2];?>
                      <span><?php echo $db_no[1];?></span>
                      </li>
                      <li><span><?php echo date("Y-m-d",strtotime($db_b[5]));?></span></li>
                    </ul>
                  </a>
                </li>
                <?php }   ?>
              </ul>
            </div>
          </div>
          <div class="board_con">
            <div class="board_title">
              <h4>퀵메뉴</h4>
            </div>
            <div class="board_q_menu">
              <p><a href="class_create.php"  title="강의등록하기"  ><img src="../images/admin/board1.png" alt="강의등록"><span>강의등록</span></a></p>
              <p><a href="notice_select.php" title="공지등록하기"  ><img src="../images/admin/board2.png" alt="공지등록"><span>공지등록</span></a></p>
              <p><a href="question_1.php"    title="문의게시판가기"><img src="../images/admin/board3.png" alt="문의답변"><span>문의답변</span></a></p>
              <p><a href="EC_notice.php"     title="학원소식가기"  ><img src="../images/admin/board4.png" alt="학원소식"><span>학원소식</span></a></p>
              <p><a href="reserve_list.php"  title="실습실바로가기"><img src="../images/admin/board5.png" alt="실습실"  ><span>실습실</span></a></p>
              <p><a href="review_list.php"   title="수강후기" ><img src="../images/admin/board6.png" alt="수강후기"><span>수강후기</span></a></p>
            </div>
          </div>
          <div class="board_con">
            <div class="board_title">
              <h4>현재 강의</h4>
              <span><a href="./class_3.php" title="나의 강의실 가기">더보기 <i class="bi bi-chevron-right"></i></a></span>
            </div>
            <div>
              <ul><!-- 나의 강의이름만 부르기 -->
              <?php 
                // echo $s_id;
                $sql2 = "select * from easycook_register where id='$s_id'";
                $result2 = mysqli_query($conn, $sql2); 
                $db2 = mysqli_fetch_array($result2);     //echo "안녕".$db2[7];

                $today = (new DateTime())->format('Y-m-d');
                $sql3 = "select * from easycook_academy_list where teacher_code='$db2[7]' and (start_date <= '$today' && end_date >= '$today') limit 4"; 
                $result3 = mysqli_query($conn, $sql3);  
                while($db3=mysqli_fetch_array($result3)){                  
              ?>
                <li>
                  <a href="./student.php?class_no=<?php echo $db3[0];?>"  title="강의실 바로가기">
                    <ul>
                      <li><?php echo $db3[1];?></li>
                      <li><span><?php echo $db3[14]?></span></li>
                    </ul>
                  </a>
                </li>
              <?php } ?>
              </ul>
            </div>
          </div>
          <div class="board_con">
            <div class="board_title">
              <h4>예정 강의</h4>
              <span><a href="./class_4.php" title="예정강의">더보기 <i class="bi bi-chevron-right"></i></a></span>
            </div>
            <div>
              <ul>
                <?php
                $today = new DateTime();
                $teacherCode   = is_array($db2) && isset($db2['teacher_code']) ? $db2['teacher_code'] : ($db2[7] ?? null);
                $startDateParam = $today->format('Y-m-d');

                $stmt = $conn->prepare("
                  SELECT * 
                  FROM easycook_academy_list
                  WHERE teacher_code = ? AND start_date > ?
                  ORDER BY start_date
                  LIMIT 4
                ");

                $stmt->bind_param("ss", $teacherCode, $startDateParam);
                $stmt->execute();
                $result3 = $stmt->get_result();

                while ($db3 = $result3->fetch_assoc()) {
                    $startDate = new DateTime($db3['start_date']);
                    $interval = $today->diff($startDate);
                    $daysRemaining = $interval->days;
                    ?>
                    <li>
                      <a href="./student.php?class_no=<?= htmlspecialchars($db3['id'] ?? '') ?>" title="강의실 바로가기">
                        <ul>
                          <li><?php echo htmlspecialchars($db3['name']); ?></li>
                          <li><span><?php echo htmlspecialchars($db3['start_date']); ?> (<?php echo "D-" . $daysRemaining; ?>)</span></li>
                        </ul>
                      </a>
                    </li>
                  <?php  }  ?>
              </ul>
            </div>
          </div>
          <div class="board_con">
            <div class="board_title">
              <h4>NEW 문의</h4>
              <span><a href="question_1.php" title="문의관리로 바로가기">더보기 <i class="bi bi-chevron-right"></i></a></span>
            </div>
            <div>
              <ul><!-- 문의관리 바로가기 -->
              <?php 
              // question 테이블에서 가져오기
              $sql_q = "select * from easycook_question where answer='' order by no desc limit 4";
              $result_q = mysqli_query($conn, $sql_q);
              while($db_q = mysqli_fetch_array($result_q)){
                //강의 번호 받아오기
                $sql_no2 = "select * from easycook_academy_list where class_no='$db_q[0];'";
                $result_no2 = mysqli_query($conn, $sql_no2);
                $db_no2 = mysqli_fetch_array($result_no2);
                ?>
                <li>
                  <a href="./question_view.php?no=<?php echo $db_q[0];?>"  title="">
                    <ul>
                      <li class="board_sub"><?php echo $db_q[3];?>
                      <span><?php echo $db_no2[1];?></span>
                      </li>
                      <li><span><?php echo date("Y-m-d",strtotime($db_q[5]));?></span></li>
                    </ul>
                  </a>
                </li>
                <?php } ?>
              </ul>
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