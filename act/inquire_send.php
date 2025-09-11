<?php

$academy_name = $_POST['academy_name'];
$inquire_name = strip_tags(htmlspecialchars($_POST['inquire_name']));
$inquire_phone = strip_tags(htmlspecialchars($_POST['inquire_phone']));

$to = 'sumin.pixie@gmail.com'; //관리자 이메일 주소
$email_subject = "FROM:  $inquire_name"; // 메일 제목에 해당하는 부분
$email_body = "본 메일은 홈페이지 폼메일로부터 전송된 이메일입니다..\n\n"."세부정보는 다음과 같습니다.\n\nName: $inquire_name\n\n 전화번호: $inquire_phone\n\n강의 이름:$academy_name\r";


$headers = "From: easycook@mycafe24.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($to,'=?UTF-8?B?'.base64_encode($email_subject).'?=',$email_body);
echo "<script>alert('상담신청이 완료 되었습니다. 해당번호로 메세지가 갈 예정입니다.');history.back();</script>";
return true;	



?>