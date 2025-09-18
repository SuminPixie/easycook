<?php
session_start();
include('../inc/dbconn.php');

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || ($input['provider'] ?? '') !== 'kakao') {
  http_response_code(400);
  echo json_encode(['success'=>false, 'message'=>'잘못된 요청입니다.']);
  exit;
}

$id = (string)($input['id'] ?? '');
$email    = trim($input['email'] ?? '');
$name     = trim($input['name'] ?? '');
$avatar   = trim($input['avatar'] ?? '');
$token    = $input['access_token'] ?? '';

// (선택) 토큰 검증: 카카오에 토큰 확인 요청
// 안전을 위해 주석 해제 권장
/*
$ch = curl_init('https://kapi.kakao.com/v1/user/access_token_info');
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$token],
  CURLOPT_RETURNTRANSFER => true,
]);
$verify = json_decode(curl_exec($ch), true);
curl_close($ch);
if (empty($verify['id']) || (string)$verify['id'] !== $id) {
  echo json_encode(['success'=>false, 'message'=>'토큰 검증 실패']);
  exit;
}
*/

// 1) id로 기존 회원 탐색
$stmt = $conn->prepare("SELECT * FROM easycook_register WHERE id = '$id' LIMIT 1");
$stmt->bind_param('s', $id);
$stmt->execute();
$stmt->bind_result($id);
if ($stmt->fetch()) {
  $stmt->close();
  // 로그인 처리
  $_SESSION['id'] = $id;           // 당신의 세션 키 이름과 일치시키세요 ($s_id 등)
  $_SESSION['login_type'] = 'kakao';
  $conn->query("UPDATE easycook_member SET last_login_at = NOW() WHERE id = ".$id);
  echo json_encode(['success'=>true, 'redirect'=>'/admin/index.php']);
  exit;
}
$stmt->close();

// 2) 이메일로 기존 일반 회원이 있는지 확인 → 있으면 계정 연결
if ($email !== '') {
  $stmt = $conn->prepare("SELECT id FROM easycook_member WHERE email = ? LIMIT 1");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $stmt->bind_result($byEmailId);
  if ($stmt->fetch()) {
    $stmt->close();
    // 해당 계정에 id 연결
    $stmt2 = $conn->prepare("UPDATE easycook_member SET id = ?, avatar = ?, login_type='kakao' WHERE id = ?");
    $stmt2->bind_param('ssi', $id, $avatar, $byEmailId);
    $stmt2->execute();
    $stmt2->close();

    $_SESSION['id'] = $byEmailId;
    $_SESSION['login_type'] = 'kakao';
    echo json_encode(['success'=>true, 'redirect'=>'/admin/index.php']);
    exit;
  }
  $stmt->close();
}

// 3) 없다면 간편회원가입 → 즉시 로그인
$stmt = $conn->prepare("
  INSERT INTO easycook_member (login_type, id, email, name, avatar, created_at, last_login_at)
  VALUES ('kakao', ?, ?, ?, ?, NOW(), NOW())
");
$stmt->bind_param('ssss', $id, $email, $name, $avatar);

if ($stmt->execute()) {
  $newId = $stmt->insert_id;
  $stmt->close();
  $_SESSION['id'] = $newId;
  $_SESSION['login_type'] = 'kakao';
  echo json_encode(['success'=>true, 'redirect'=>'/admin/index.php']);
} else {
  $stmt->close();
  echo json_encode(['success'=>false, 'message'=>'회원가입 실패']);
}
