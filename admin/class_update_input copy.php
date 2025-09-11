<?php

include('../include/dbconn.php');

$no = $_GET['no'];
$class_status = $_GET['class_status'];
echo $no."<br>";
echo $class_status."<br>";

$sql_date = "
select * from academy_list 
where class_no = '$no'
";
$qeury_date = mysqli_query($conn, $sql_date);
$result_date = mysqli_fetch_array($qeury_date);

echo "<br>".$result_date[13];

if($class_status == '1주 미루기'){
  // 날짜와 데이터 클래스를 업데이트 하자
  $sql = "
  update academy_list set 
  start_date ='$start7' 
  and end_date = '$end7'
  where class_no = '$no'";
  $result = mysqli_query($conn, $sql);
  
  if($result){
    echo "<script>alert('1주 미루기가 완료 되었습니다.');</script>";
    // echo "<script>location.replace('class_1.php');</script>";
  }else{
    echo "글 입력 실패 : ". mysqli_error($conn);
  };
}else{
    // 날짜와 데이터 클래스를 업데이트 하자
    $sql = "
    update academy_list set 
    start_date ='$start7' 
    and end_date = '$end7'
    where class_no = '$no'";
    $result = mysqli_query($conn, $sql);
    
    if($result){
      echo "<script>alert('1주 당기기가 완료 되었습니다.');</script>";
      // echo "<script>location.replace('class_1.php');</script>";
    }else{
      echo "글 입력 실패 : ". mysqli_error($conn);
    };
}
?>