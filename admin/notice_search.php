<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 공지관리</title>
  <?php include('header.php');  ?>
</head>
<body>
  <main>
    <section class="m-center m-auto mb-5 class_size">
      <!-- 부스러기 -->
      <p class="bread">홈 &#x003E; 게시판 관리 &#x003E; <span style="font-weight:bold">공지관리</span></p>

      <!-- 제목 -->
      <h2 class="text-center mt-5 mb-5" >나의 전체 공지</h2>

      <!-- 내용 -->
      <div class="con mt-2 mb-2 article position_relative">
        <table class="table table-hover notice table-responsive">
          <caption>Q&A 목록</caption>
          <thead class="text-center">
            <tr class="bold_line line50">
              <th>No.</th>
              <th>제목</th>
              <th class="display_none">날짜</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //입력한  search 값 받아오고
              $search = $_POST['search'];    // echo $search;

              //입력한 값이랑 데이터 값을 비교한다
              $sql = "select * from board where 
              (title like '%".$search."%' or memo like '%".$search."%')
              and id='$s_id' order by title desc;";
              $result = mysqli_query($conn, $sql);  

              //내용출력
              while($row = mysqli_fetch_array($result)){
                // 아카데미 이름 가져오기
                $sql_n = "select * from academy_list where class_no='$row[1]'";
                $result_n = mysqli_query($conn, $sql_n);
                $db_n = mysqli_fetch_array($result_n); //                echo $db_n[1];
                echo "<tr>";
                  echo "<td class='text-center'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>".$row[0]."</a></td>";
                  echo "<td position:relative;'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>
                          <span>".$row[2]."</span>
                          <span class='span_subtitle'>".$db_n[1]."</span></a></td>";
                  echo "<td class='text-center display_none'><a href='notice_list.php?class_no=".$db_b[1]."' title='강의실 바로가기'>".date("y-m-d",strtotime($row[5]))."</a></td>";
                echo "</tr>";
              };  ?>  
          </tbody>
        </table>

        <div class="mt-5 mb-3" style="position:relative;">
          <a href="notice_total.php" title="다시 검색하기" class="admin_btn admin_btn_yellow position_l_b">다시 검색</a>
        </div>

      </div>
    </section>
<?php
  include('footer.php');
?>
</body>
</html>
