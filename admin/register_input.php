<?php
include('../include/dbconn.php');

    // echo '출력';
    // $profile = $_POST['profile'];
    $t_name = $_POST['t_name'];
    $t_birth = $_POST['t_birth'];
    $t_mail = $_POST['t_email'];
    $t_phone = $_POST['t_phone'];
    $t_address = nl2br($_POST['t_address']);
    $t_carrer = nl2br($_POST['t_carrer']);
    $t_license = nl2br($_POST['t_license']);
    $t_award = nl2br($_POST['t_award']);
    date_default_timezone_set('Asia/Seoul'); //서울시간대
    $datetime = date('y-m-d H:i:s');
    $t_code = $_POST['t_code'];
    
    // echo "<br> 이름".$t_name;  
    // echo "<br> 생일".$t_birth;  
    // echo "<br> 메일".$t_mail;  
    // echo "<br> 폰".$t_phone;  
    // echo "<br> 주소".$t_address;  
    // echo "<br> 경력".$t_carrer;  
    // echo "<br> 자격증".$t_license;  
    // echo "<br> 수상이력".$t_award;  
    // echo "<br> 작성날짜".$datetime;  
    // echo $t_code;

    //임시로 저장된 정보(tmp_name)
    $tempFile = $_FILES['img']['tmp_name'];

    // 파일타입 및 확장자 체크
    $fileTypeExt = explode("/", $_FILES['img']['type']);

    // 파일 타입 
    $fileType = $fileTypeExt[0];

    // 파일 확장자
    $fileExt = $fileTypeExt[1];

    // 확장자 검사
    $extStatus = false;

    switch($fileExt){
      case 'jpeg':
      case 'jpg':
      case 'gif':
      case 'bmp':
      case 'png':
        $extStatus = true;
        break;
      
      default:
        echo "이미지 전용 확장자(jpg, bmp, gif, png)외에는 사용이 불가합니다."; 
        exit;
        break;
    }


    // 이미지 파일이 맞는지 검사. 
    if($fileType == 'image'){
      // 허용할 확장자를 jpg, bmp, gif, png로 정함, 그 외에는 업로드 불가
      if($extStatus){
        // 임시 파일 옮길 디렉토리 및 파일명
        $resFile = "../../uploads/profile/{$_FILES['img']['name']}";
        $resFile_input = "{$_FILES['img']['name']}";
        // 임시 저장된 파일을 우리가 저장할 디렉토리 및 파일명으로 옮김
        $imageUpload = move_uploaded_file($tempFile, $resFile);
        
        // 업로드 성공 여부 확인
        if($imageUpload == true){
          echo "파일이 정상적으로 업로드 되었습니다. <br>";
          // echo "<img src='{$resFile}'>";
          echo "<img src='{$_FILES['img']['name']}'>";
        }else{
          echo "파일 업로드에 실패하였습니다.";
        }
      }	// end if - extStatus
        // 확장자가 jpg, bmp, gif, png가 아닌 경우 else문 실행
      else {
        echo "파일 확장자는 jpg, bmp, gif, png 이어야 합니다.";
        exit;
      }	
    }	// end if - filetype
      // 파일 타입이 image가 아닌 경우 
    else {
      echo "이미지 파일이 아닙니다.";
      exit;
    }

    // 프로필 이미지를 추가했을때 업로드 처리
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
      $upload_folder = '../uploads/profile/';
      $file_name = uniqid() . '_' . $_FILES['profile']['name']; // 파일 이름 중복 방지
      $file_tmp = $_FILES['profile']['tmp_name'];

      if (move_uploaded_file($file_tmp, $upload_folder . $file_name)) {
        $profile_img = $file_name;
      } else {
        echo "파일 업로드 실패";
        exit; // 업로드 실패 시 종료
      }
    } else {
      // 프로필 이미지를 업로드하지 않은 경우
      $profile_img = 'profile.png'; // 기본 프로필 이미지
    }


    // 데이터 수정하기
    $sql = "
    update teacher_list 
    set 
    profile ='$resFile_input',
    birth = '$t_birth',
    email = '$t_mail',
    phone = '$t_phone',
    address = '$t_address',
    carrer = '$t_carrer',
    license = '$t_license',
    award = '$t_award',
    datetime = '$datetime'
    where teacher_code='$t_code';
    ";

    $result = mysqli_query($conn, $sql);


    if($result){
      echo "<script>alert('나의 정보 수정이 완료되었습니다.')</script>";
      echo "<script>location.replace('register.php');</script>";
    }else{
      echo "<script>alert(등록 실패 : ". mysqli_error($conn).")</script>";
      mysqli_close($conn);
};


?>