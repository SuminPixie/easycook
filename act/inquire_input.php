<?php

include('./include/dbconn.php');


$id = trim($_POST['id']);
$class_no = $_POST['class_no'];
$question = $_POST['question'];
$question_memo = $_POST['question_memo'];
date_default_timezone_set('Asia/Seoul');
$datetime = date("Y-m-d H:i:s");


// echo $id.'<br>';
// echo $class_no.'<br>';
// echo $question.'<br>';
// echo $question_memo.'<br>';

$sql = "INSERT INTO question (class_no,question_id,question,question_memo,question_time) VALUES ('$class_no','$id','$question','$question_memo','$datetime')";

$result = mysqli_query($conn,$sql);

if ($result) {
  echo "<script>alert('등록완료');location.replace('../inquire_list.php') </script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>