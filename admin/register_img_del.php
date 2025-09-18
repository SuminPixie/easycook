<?php
include('../inc/dbconn.php');

$t_code = $_GET['teacher_code'];
echo $t_code;

// 데이터 수정하기
$sql = "
update easycook_teacher_list 
set 
profile ='profile.png'
where teacher_code='$t_code';
";

$result = mysqli_query($conn, $sql);

if($result){
  echo "<script>location.replace('./register.php');</script>";
}else{
  echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
  mysqli_close($conn);}

?>