<?php

include('../inc/dbconn.php');

$no = $_POST['no'];
$question = $_POST['question'];
$question_memo = $_POST['question_memo'];
date_default_timezone_set('Asia/Seoul');
$datetime = date("Y-m-d H:i:s");

$sql = "update easycook_question set question='$question',question_memo='$question_memo' where no='$no'";
$result = mysqli_query($conn,$sql);
if ($result) {
  echo "<script>alert('수정완료');location.replace('../inquire_view.php?no=$no') </script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


?>