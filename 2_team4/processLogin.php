<?php
include 'db_connection.php'; // 데이터베이스 연결

session_start(); // 세션 시작

// 로그인 폼에서 전달된 데이터 가져오기
$email_or_id = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (empty($email_or_id) || empty($password)) {
    echo "<script>alert('아이디/이메일과 비밀번호를 입력해주세요.'); window.history.back();</script>";
    exit;
}

// 사용자 정보를 users 테이블에서 확인
$sql_users = "SELECT user_id, password, 'users' AS role FROM users WHERE email = :email_or_id OR user_id = :email_or_id";
$stid_users = oci_parse($conn, $sql_users);
oci_bind_by_name($stid_users, ':email_or_id', $email_or_id);
oci_execute($stid_users);
$user = oci_fetch_assoc($stid_users);

// 사용자 정보를 giver 테이블에서 확인
$sql_giver = "SELECT giver_id AS user_id, password, 'giver' AS role FROM giver WHERE email = :email_or_id OR giver_id = :email_or_id";
$stid_giver = oci_parse($conn, $sql_giver);
oci_bind_by_name($stid_giver, ':email_or_id', $email_or_id);
oci_execute($stid_giver);
$giver = oci_fetch_assoc($stid_giver);

if ($user && password_verify($password, $user['PASSWORD'])) {
    // users 테이블에서 로그인 성공
    $_SESSION['user_id'] = $user['USER_ID'];
    $_SESSION['role'] = $user['ROLE'];
    echo "<script>alert('로그인 성공! (사용자)'); window.location.href='/2_team/2_team4/2_team4/index.php';</script>";
    exit;
} elseif ($giver && password_verify($password, $giver['PASSWORD'])) {
    // giver 테이블에서 로그인 성공
    $_SESSION['user_id'] = $giver['USER_ID'];
    $_SESSION['role'] = $giver['ROLE'];
    echo "<script>alert('로그인 성공! (나눔러)'); window.location.href='/2_team/2_team4/2_team4/index.php';</script>";
    exit;
} else {
    // 로그인 실패
    echo "<script>alert('아이디/이메일 또는 비밀번호가 잘못되었습니다.'); window.history.back();</script>";
    exit;
}

// 리소스 해제
oci_free_statement($stid_users);
oci_free_statement($stid_giver);
oci_close($conn);
?>
