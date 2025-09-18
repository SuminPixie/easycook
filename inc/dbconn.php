<?php
// 세션이 이미 시작되었는지 확인
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

date_default_timezone_set('Asia/Seoul');

// db연결 변수설정
$mysql_host='localhost'; //호스트명
$mysql_user='root'; //로컬 사용자명
$mysql_password=''; //로컬 패스워드
//$mysql_user='pixiesite'; //cafe24사용자명
//$mysql_password='p03010301'; //cafe24패스워드야
$mysql_db='pixiesite'; //데이터베이스명
// $mysql_db='easycook'; //데이터베이스명

$conn = mysqli_connect($mysql_host,$mysql_user,$mysql_password, $mysql_db);

if(!$conn){ //만약 conn이 실패한다면 
  die("연결실패 : ". mysqli_connect_error()); //스크립트를 종료한다
}

ini_set('display_errors','on'); //에러메세지를 띄우지 말라 : off이면 에러메세지가 안보인다.

?>
