<?php

$inquire_name = strip_tags(htmlspecialchars($_POST['inquire_name']));
$inquire_phone = strip_tags(htmlspecialchars($_POST['inquire_phone']));
$inquire_memo = strip_tags(htmlspecialchars($_POST['inquire_memo']));
$inquire_date = strip_tags(htmlspecialchars($_POST['selected_date']));
$inquire_time = strip_tags(htmlspecialchars($_POST['inquire_time']));

// echo $inquire_name;
// echo $inquire_phone;
// echo $inquire_memo;
// echo $inquire_date;
// echo $inquire_time;

$to = 'allblue0121@naver.com'; //관리자 이메일 주소
$email_subject = "FROM:  $inquire_name"; // 메일 제목에 해당하는 부분
$email_body = "본 메일은 홈페이지 폼메일로부터 전송된 이메일입니다..\n\n"."세부정보는 다음과 같습니다.\n\nName: $inquire_name\n\n 전화번호: $inquire_phone\n\n상담 내용:$inquire_memo\n\n상담날짜:$inquire_date\n\n 상담시간:$inquire_time\r";
mail($to,'=?UTF-8?B?'.base64_encode($email_subject).'?=',$email_body);

echo "<script>alert('상담신청이 완료 되었습니다. 해당번호로 메세지가 갈 예정입니다.');location.replace('../community.php?comu=커뮤니티&tab=상담신청') </script>";

return true;



?>