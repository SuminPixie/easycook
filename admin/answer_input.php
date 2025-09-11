<!-- 공지 작성 및 수정 -->

<?php 

include('../include/dbconn.php');

$no = $_POST['q_num'];
echo $no;

$answer_id = $_SESSION['id'];    
$answer_memo = nl2br($_POST['answer']);
date_default_timezone_set('Asia/Seoul'); //서울시간대
$answer_time = date('y-m-d H:i:s');

echo $answer_id;
echo $answer_memo;
echo $answer_time;

$sql = "select * from question where no='$no'";
$result = mysqli_query($conn, $sql);
$q=mysqli_fetch_row($result);

// 답변 작성
$sql = "
update question
set answer_id= '$answer_id', 
answer = '$answer_memo', 
answer_time='$answer_time'
where no='$no';";

$result = mysqli_query($conn, $sql);

if($result){
  echo "<script>alert('답변 작성/수정 되었습니다.')</script>";
  echo "<script>location.replace('question_1.php');</script>";
}else{
  echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
  mysqli_close($conn);
}


?>