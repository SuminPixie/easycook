<?php
  include('../inc/dbconn.php');

  $no = $_POST['no'];
  $starRating = $_POST['starRating'];
  $review = $_POST['re_view'];
  date_default_timezone_set('Asia/Seoul');
  $datetime = date("Y-m-d H:i:s");
  $photos = $_POST['files_2'];


  $targetDir = "../uploads/review/";

  echo '번호 : '.$no . '<br>';
  echo '별점 : '.$starRating . '<br>';
  echo '후기 내용 :'.$review . '<br>';
  echo '날짜 : '.$datetime . '<br>';

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
  // echo '사진' . $photo_files.'<br>';
  // echo '사진2' . $photos;

  //$photo_files의 값이 있다면 
  if(empty($photo_files)){
    // /*쿼리문 여기 */
    $sql = "update easycook_review set star='$starRating', img='$photos' ,review='$review',datetime='$datetime' where no = '$no'";
  }else{
    // /*쿼리문 여기 */
    $sql = "update easycook_review set star='$starRating', img='$photo_files' ,review='$review',datetime='$datetime' where no = '$no'";
  }


  $result = mysqli_query($conn,$sql);

  if ($result) {
    echo "<script>alert('등록완료');location.replace('../review_list.php') </script>";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }


?>