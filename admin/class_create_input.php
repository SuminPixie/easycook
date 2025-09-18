<?php

include('../inc/dbconn.php');

$class_no = $_POST['class_no'];

$name = $_POST['name'];
$code = $_POST['code'];
$nth = $_POST['nth'];
$teacher_code = $_POST['teacher_code'];
$teacher_name = $_POST['teacher_name'];
$category1 = $_POST['category1'];
$category2 = $_POST['category2'];
$category3 = $_POST['category3'];
$place = $_POST['place'];
$phone = $_POST['phone'];
$grade = $_POST['grade'];
$start_time = $_POST['start_time'];
$member_num = $_POST['member_num'];
$end_time = $_POST['end_time'];
$end_date = $_POST['end_date'];
$start_date = $_POST['start_date'];
$img = $_POST['img'];
$parent_img = $_POST['parent_img'];
$detail = nl2br($_POST['detail']);
date_default_timezone_set('Asia/Seoul'); //서울시간대
$datetime = date('y-m-d H:i:s');


// 임시로 저장된 정보(tmp_name)
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
	case 'jpeg':	case 'jpg':	case 'gif':	case 'bmp':	case 'png':
		$extStatus = true;
		break;
	
	default:
		echo "<br>이미지 전용 확장자(jpg, bmp, gif, png)외에는 사용이 불가합니다."; 
		exit;
		break;
}

// 이미지 파일이 맞는지 검사. 
if($fileType == 'image'){
	// 허용할 확장자를 jpg, bmp, gif, png로 정함, 그 외에는 업로드 불가
	if($extStatus){
		// 임시 파일 옮길 디렉토리 및 파일명
		$resFile = "../uploads/class_main/{$_FILES['img']['name']}";
		$resFile_input = "{$_FILES['img']['name']}";
		// 임시 저장된 파일을 우리가 저장할 디렉토리 및 파일명으로 옮김
		$imageUpload = move_uploaded_file($tempFile, $resFile);

		// echo "resFile(".$resFile.")";
		// echo "resFile_input(".$resFile_input.")";
		
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

// 임시로 저장된 정보(tmp_name)
$tempFile2 = $_FILES['parent_img']['tmp_name'];
// 파일타입 및 확장자 체크
$fileTypeExt2 = explode("/", $_FILES['parent_img']['type']);
// 파일 타입 
$fileType2 = $fileTypeExt2[0];
// 파일 확장자
$fileExt2 = $fileTypeExt2[1];
// 확장자 검사
$extStatus2 = false;

switch($fileExt2){
	case 'jpeg':	case 'jpg':	case 'gif':	case 'bmp':	case 'png':
		$extStatus2 = true;
		break;
	
	default:
		echo "<br>이미지 전용 확장자(jpg, bmp, gif, png)외에는 사용이 불가합니다."; 
		exit;
		break;
}

// 이미지 파일이 맞는지 검사. 
if($fileType2 == 'image'){
	// 허용할 확장자를 jpg, bmp, gif, png로 정함, 그 외에는 업로드 불가
	if($extStatus2){
		// 임시 파일 옮길 디렉토리 및 파일명
		$resFile2 = "../uploads/class_detail/{$_FILES['parent_img']['name']}";
		$resFile_input2 = "{$_FILES['parent_img']['name']}";
		// 임시 저장된 파일을 우리가 저장할 디렉토리 및 파일명으로 옮김
		$imageUpload2 = move_uploaded_file($tempFile2, $resFile2);

		// echo "resFile(".$resFile.")";
		echo "resFile_input2(".$resFile_input2.")";
		
		// 업로드 성공 여부 확인
		if($imageUpload2 == true){
			echo "파일이 정상적으로 업로드 되었습니다. <br>";
			// echo "<img src='{$resFile}'>";
			echo "<img src='{$_FILES['parent_img']['name']}'>";
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

// 개설 버튼 값 가져오기
$input = $_POST['input'];
// 수정버튼
$update = $_POST['update'];


// 수정버튼이냐?
if(empty($update)){
	// echo "강의 개설";
	$sql = "insert into easycook_academy_list set
	name = '$name',
	code = '$code',
	category1 = '$category1',
	category2 = '$category2',
	category3 = '$category3',
	teacher_code = '$teacher_code',
	teacher = '$teacher_name',
	place = '$place',
	phone = '$phone',
	grade = '$grade',
	member_num = '$member_num',
	nth = '$nth',
	start_date = '$start_date',
	end_date = '$end_date',
	start_time = '$start_time',
	end_time= '$end_time',
	class_status = '',
	thumnail_img = '$resFile_input',
	img = '$resFile_input2',
	detail = '$detail',
	datetime = '$datetime';
	";
	$result = mysqli_query($conn,$sql);

	if($result){
		echo "<script>alert('글 작성이 완료 되었습니다.');</script>";
		echo "<script>location.replace('class_1.php');</script>";
	}else{
		echo "글 입력 실패 : ". mysqli_error($conn);
	};
	mysqli_close($conn); 

}else{
		// // echo $name;
		// echo "강의 수정";
		$sql = "
		update easycook_academy_list set
		name = '$name',
		code = '$code',
		category1 = '$category1',
		category2 = '$category2',
		category3 = '$category3',
		teacher_code = '$teacher_code',
		teacher = '$teacher_name',
		place = '$place',
		phone = '$phone',
		grade = '$grade',
		member_num = '$member_num',
		nth = '$nth',
		start_date = '$start_date',
		end_date = '$end_date',
		start_time = '$start_time',
		end_time= '$end_time',
		thumnail_img = '$resFile_input',
		img = '$resFile_input2',
		detail = '$detail',
		datetime = '$datetime'
		where class_no = '$class_no';
		";
		$result = mysqli_query($conn,$sql);
	
		if($result){
			echo "<script>alert('글 수정이 완료 되었습니다.');</script>";
			echo "<script>location.replace('class_1.php');</script>";
		}else{
			echo "글 입력 실패 : ". mysqli_error($conn);
		};
		mysqli_close($conn); 
}
?>