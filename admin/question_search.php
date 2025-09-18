<!-- 나의 강의실 중 전체강의 -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 나의 강의실</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 게시판 관리 &#x003E; <span style="font-weight:bold">문의 관리</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-5">전체 문의 관리</h2>

    <div class="class_1 article">
      <!-- 내용 -->
      <div class="mt-4 mb-2" id="content">
        <div id="container">
          <table class="table table-hover question_list table-responsive">
            <caption>Q&A 목록</caption>
            <thead class="text-center">
              <tr class="bold_line line50">
                <th>No.</th>
                <th>제목</th>
                <th>상태</th>
              </tr>
            </thead>    
            <tbody>
              <?php 
                //입력한  search 값 받아오고
                $search = $_POST['search'];    // echo $search;

                //입력한 값이랑 데이터 값을 비교한다
                $sql = "select * from easycook_question where question like '%".$search."%' or question_memo like '%".$search."%' order by no DESC";
                $result = mysqli_query($conn, $sql);                
                while($q=mysqli_fetch_row($result)){
                  $query = "select * from easycook_academy_list where class_no='$q[1]'";
                  $result2 = mysqli_query($conn, $query);
                  $class = mysqli_fetch_array($result2);
                  if(empty($q[7])){
                    print 
                    "<tr>
                    <td><a href='./question_view.php?no=".$q[0]."' title=''>".$q[0]."</a></td>
                    <td>
                      <a href='./question_view.php?no=".$q[0]."' title=''>".$q[3]."
                      <span>".$class[3]." | ".$q[2]." | ".date("Y.m.d",strtotime($q[5]))."</span>
                      </a>
                    </td>
                    <td><a href='./question_view.php?no=".$q[0]."' title=''><span class='question_r1'>질문</span></a></td>
                  </tr>";
                  }else{
                    print 
                    "<tr>
                    <td><a href='./question_view.php?no=".$q[0]."' title=''>".$q[0]."</a></td>
                    <td>
                      <a href='./question_view.php?no=".$q[0]."' title=''>".$q[3]."
                      <span>".$class[3]." | ".$q[2]." | ".date("Y.m.d",strtotime($q[5]))."</span>
                      </a>
                    </td>
                    <td><a href='./question_view.php?no=".$q[0]."' title=''><span class='question_r2'>답변</span></a></td>
                  </tr>";
                };
                };?>
            </tbody>
          </table>      
        </div>
      </div> 
      <div class="mt-5 mb-3" style="position:relative;">
        <a href="./question_1.php" title="다시 검색하기" class="admin_btn admin_btn_yellow position_l_b">다시 검색</a>
      </div>
    </div>

  </section>
<?php
include('./footer.php');
?>
</body>
</html>
