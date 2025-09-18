<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이지쿡 | 학원소식</title>
  <?php
    include('./header.php');      
  ?>
<main>
  <section class="m-center m-auto mb-5 class_size">
    
    <!-- 부스러기 -->
    <p class="bread">홈 &#x003E; 학원 &#x003E; <span style="font-weight:bold">학원소식</span></p>

    <!-- 제목 -->
    <h2 class="text-center mt-4 mb-5">학원소식</h2>

    <article class="class_1 ">
      <h3>학원소식</h3>
      <!-- 내용 -->
      <div class="mt-4 mb-2" id="content" >
        <div id="container">
          <table class="table table-hover">
            <caption>Q&A 목록</caption>
            <thead class="text-center">
              <tr class="bold_line line50">
                <th class="w-10">No.</th>
                <th class="w-60">제목</th>
                <th class="w-30">작성일</th>
              </tr>
            </thead>    
            <tbody>
              <?php 
              //입력한  search 값 받아오고
              $search = $_POST['search'];    // echo $search;

              //입력한 값이랑 데이터 값을 비교한다
              $sql = "select * from easycook_notice 
              where title like '%".$search."%' or memo like '%".$search."%'
              order by no desc;";
              $result = mysqli_query($conn, $sql);          
              while($db=mysqli_fetch_array($result)){  ?>
              <tr>
                <td class="text-center">
                  <a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="공지보기"><?php echo $db['0'];?></a>
                </td>
                <td >
                  <a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="공지보기"><?php echo  $db['1'];?></a>
                </td>
                <td class="text-center">
                  <a href="./EC_notice_view.php?no=<?php echo $db['0'];?>" title="공지보기"><?php echo date("Y.m.d",strtotime($db['3']));?></a>
                </td>
              </tr>
              <?php     }; ?> 
            </tbody>
          </table>
        </div>
        <div class="mt-5 mb-3" style="position:relative;">
          <a href="./EC_notice.php" title="다시 검색" class="admin_btn admin_btn_yellow position_l_b">다시 검색</a>
        </div>
      </div>
    </article>
  </section>
<?php
include('./footer.php');
?>
</body>
</html>
