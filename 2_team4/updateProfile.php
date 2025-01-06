<?php
session_start();

include 'db_connection.php'; // 데이터베이스 연결

// 세션에서 사용자 ID와 역할(role) 가져오기
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("로그인되지 않았습니다.");
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role']; // 'users' 또는 'giver'

// POST 데이터 가져오기
$name = trim($_POST['name'] ?? null);
$email = trim($_POST['email'] ?? null);
$birthDay = trim($_POST['birth_day'] ?? null);

// 확인: 모든 필드가 비어있지 않은지 확인
if ($name && $email && $birthDay) {
    // 이메일 형식 확인
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('유효하지 않은 이메일 주소입니다.'); window.history.back();</script>";
        exit();
    }

    // 생년월일 형식 검증 (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDay)) {
        echo "<script>alert('생년월일 형식이 잘못되었습니다. YYYY-MM-DD 형식으로 입력해주세요.'); window.history.back();</script>";
        exit();
    }

    // 사용자와 제공자에 따라 업데이트 쿼리 분기
    if ($role === 'users') {
        // USERS 테이블 업데이트
        $sql = "UPDATE USERS SET NAME = :name, EMAIL = :email, BIRTH_DAY = TO_DATE(:birth_day, 'YYYY-MM-DD') WHERE USER_ID = :id";
    } elseif ($role === 'giver') {
        // GIVER 테이블 업데이트
        $sql = "UPDATE GIVER SET NAME = :name, EMAIL = :email, BIRTH_DAY = TO_DATE(:birth_day, 'YYYY-MM-DD') WHERE GIVER_ID = :id";
    } else {
        die("잘못된 역할입니다.");
    }

    $stmt = oci_parse($conn, $sql);

    // 쿼리 바인딩
    oci_bind_by_name($stmt, ":name", $name);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":birth_day", $birthDay);
    oci_bind_by_name($stmt, ":id", $userId);

    // 실행 및 결과 처리
    if (oci_execute($stmt)) {
        echo "<script>alert('정보가 성공적으로 업데이트되었습니다.'); window.location.href = 'MyPage.php';</script>";
    } else {
        $e = oci_error($stmt);
        echo "<script>alert('업데이트 중 오류가 발생했습니다: " . htmlspecialchars($e['message']) . "'); window.history.back();</script>";
    }

    oci_free_statement($stmt);
} else {
    echo "<script>alert('모든 필드를 입력해주세요.'); window.history.back();</script>";
}

oci_close($conn);
?>
