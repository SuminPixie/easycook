<?php

include('./include/dbconn.php');
$starRating = $_POST['starRating'];
$review = $_POST['re_view'];
$code = $_POST['code'];
$class_no = $_POST['class_no'];
$student_id = $_POST['student_id'];
$student_name = $_POST['student_name'];
date_default_timezone_set('Asia/Seoul');
$datetime = date("Y-m-d H:i:s");
// echo $starRating;
// echo $review;


$targetDir = "../uploads/review/";

// 파일 업로드 처리
$uploadedFiles = [];
foreach ($_FILES['files']['name'] as $key => $name) {
    if ($_FILES['files']['error'][$key] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['files']['tmp_name'][$key];
        $targetFile = uniqid().'_'. basename($name);
        if (move_uploaded_file($tmp_name, $targetDir . $targetFile)) {
            $uploadedFiles[] = $targetFile;
        }
    }
}

$photo_files = implode(",",$uploadedFiles);
echo $photo_files;


// /*쿼리문 여기 */
$sql = "INSERT INTO review (class_no,code,star,img,review,id,name,datetime) VALUES ('$class_no','$code','$starRating','$photo_files','$review','$student_id','$student_name','$datetime')";
$result = mysqli_query($conn,$sql);

if ($result) {
  echo "<script>alert('등록완료');location.replace('../review_list.php') </script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


?>