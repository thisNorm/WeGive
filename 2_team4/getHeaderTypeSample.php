<?php
// 세션 시작
session_start();

// 디버깅용 로그: 세션 상태 출력
error_log("세션 상태 확인: " . print_r($_SESSION, true));

// 세션에 user_id 값이 존재하는지 확인
if (isset($_SESSION['user_id'])) {
    // 로그인 상태일 경우
    echo "/2_team/2_team4/2_team4/loginHeader.php"; // 로그인 헤더 파일 경로 반환
} else {
    // 비로그인 상태일 경우
    echo "/2_team/2_team4/2_team4/unloginHeader.php"; // 비로그인 헤더 파일 경로 반환
}
?>
