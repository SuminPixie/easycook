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

$kid      = trim((string)($input['id'] ?? ''));            // 카카오 user id (문자열로 보관)
$email    = trim((string)($input['email'] ?? ''));
$name     = trim((string)($input['name'] ?? ''));
$avatar   = trim((string)($input['avatar'] ?? ''));        // 프로필 이미지 URL
//$token  = $input['access_token'] ?? '';                  // (선택) 서버 검증에 사용 가능

if ($kid === '') {
  echo json_encode(['success'=>false, 'message'=>'필수 식별자가 없습니다.']);
  exit;
}

/* 1) 기존 회원 여부 확인 (id 컬럼 = 카카오 id 사용) */
$stmt = $conn->prepare("SELECT id, name, profile, teacher_code, email FROM easycook_register WHERE id = ? LIMIT 1");
$stmt->bind_param('s', $kid);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
  // 기존 회원: 이메일/프로필 갱신(있을 때만)
  $currentProfile = $row['profile'];
  $newProfileFile = $currentProfile ?: 'profile.png';

  // 아바타가 왔다면 다운받아 교체 시도
  if ($avatar !== '') {
    $uploadDir = '../uploads/profile/';
    if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }

    $path = parse_url($avatar, PHP_URL_PATH);
    $ext  = pathinfo($path ?? '', PATHINFO_EXTENSION);
    if ($ext === '') $ext = 'jpg';
    $ext  = preg_replace('/[^a-z0-9]/i', '', strtolower($ext));

    $fileName = uniqid('kakao_', true) . '.' . $ext;
    $data = @file_get_contents($avatar);
    if ($data !== false && @file_put_contents($uploadDir . $fileName, $data) !== false) {
      $newProfileFile = $fileName;
    }
  }

  // 이메일/프로필 업데이트
  if ($email !== '' || $newProfileFile !== $currentProfile) {
    $stmtUpd = $conn->prepare("UPDATE easycook_register SET email = IF(?<>'', ?, email), profile = ? WHERE id = ?");
    $stmtUpd->bind_param('ssss', $email, $email, $newProfileFile, $kid);
    $stmtUpd->execute();
    $stmtUpd->close();
    $row['profile'] = $newProfileFile;
    if ($email !== '') $row['email'] = $email;
  }

  // 세션 세팅 & 리다이렉트
  $_SESSION['id']           = $row['id'];
  $_SESSION['name']         = $row['name'];
  $_SESSION['profile']      = $row['profile'];
  $_SESSION['teacher_code'] = $row['teacher_code'];

  $redirect = !empty($row['teacher_code']) ? '/admin/index.php' : '/index.php';
  echo json_encode(['success'=>true, 'redirect'=>$redirect]);
  exit;
}
$stmt->close();

/* 2) 신규 회원 가입 */
date_default_timezone_set('Asia/Seoul');
$datetime     = date('Y-m-d H:i:s');
$profile_file = 'profile.png';     // 기본 이미지
$phone        = '';                // 소셜가입: 전화번호 없음

// 사번(강사회원) 세션을 사용 (register_teacher 흐름)
$teacher_code = isset($_SESSION['teacher_code']) ? trim((string)$_SESSION['teacher_code']) : '';

// 아바타 저장 시도
if ($avatar !== '') {
  $uploadDir = '../uploads/profile/';
  if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }

  $path = parse_url($avatar, PHP_URL_PATH);
  $ext  = pathinfo($path ?? '', PATHINFO_EXTENSION);
  if ($ext === '') $ext = 'jpg';
  $ext  = preg_replace('/[^a-z0-9]/i', '', strtolower($ext));

  $fileName = uniqid('kakao_', true) . '.' . $ext;
  $data = @file_get_contents($avatar);
  if ($data !== false && @file_put_contents($uploadDir . $fileName, $data) !== false) {
    $profile_file = $fileName;
  }
}

// 소셜계정용 랜덤 패스워드(로그인엔 사용하지 않지만 스키마 충족용)
$randomPass = bin2hex(random_bytes(16));
$hashed     = password_hash($randomPass, PASSWORD_DEFAULT);

if ($teacher_code !== '') {
  // 강사회원
  $stmtIns = $conn->prepare("
    INSERT INTO easycook_register
      (name, id, password, phone, email, profile, datetime, teacher_code)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmtIns->bind_param('ssssssss', $name, $kid, $hashed, $phone, $email, $profile_file, $datetime, $teacher_code);
} else {
  // 일반회원
  $stmtIns = $conn->prepare("
    INSERT INTO easycook_register
      (name, id, password, phone, email, profile, datetime)
    VALUES (?, ?, ?, ?, ?, ?, ?)
  ");
  $stmtIns->bind_param('sssssss', $name, $kid, $hashed, $phone, $email, $profile_file, $datetime);
}

if ($stmtIns->execute()) {
  // 가입 성공 → 세션 세팅
  $_SESSION['id']           = $kid;
  $_SESSION['name']         = $name;
  $_SESSION['profile']      = $profile_file;
  $_SESSION['teacher_code'] = $teacher_code;

  // (선택) 강사코드 1회성이라면 다음 줄 주석 해제해서 제거
  // unset($_SESSION['teacher_code']);

  $redirect = ($teacher_code !== '') ? '/admin/index.php' : '/index.php';
  echo json_encode(['success'=>true, 'redirect'=>$redirect]);
} else {
  echo json_encode(['success'=>false, 'message'=>'회원가입 실패: '.$conn->error]);
}
