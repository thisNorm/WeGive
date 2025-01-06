<?php
session_start();

include 'db_connection.php'; // Oracle 데이터베이스 연결 파일

// 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 사용자 ID 가져오기
$user_id = $_SESSION['user_id'];

// 트랜잭션 시작
oci_execute(oci_parse($conn, 'BEGIN'));

try {
    // `users` 테이블에서 사용자 확인
    $check_users_sql = "SELECT COUNT(*) AS count FROM users WHERE user_id = :user_id";
    $check_users_stmt = oci_parse($conn, $check_users_sql);
    oci_bind_by_name($check_users_stmt, ':user_id', $user_id);
    oci_execute($check_users_stmt);
    $users_result = oci_fetch_assoc($check_users_stmt);

    if ($users_result['COUNT'] > 0) {
        // `users` 테이블에서 데이터 삭제
        $delete_users_sql = "DELETE FROM users WHERE user_id = :user_id";
        $delete_users_stmt = oci_parse($conn, $delete_users_sql);
        oci_bind_by_name($delete_users_stmt, ':user_id', $user_id);
        oci_execute($delete_users_stmt);
    } else {
        // `giver` 테이블에서 사용자 확인 및 삭제
        $check_giver_sql = "SELECT COUNT(*) AS count FROM giver WHERE giver_id = :user_id";
        $check_giver_stmt = oci_parse($conn, $check_giver_sql);
        oci_bind_by_name($check_giver_stmt, ':user_id', $user_id);
        oci_execute($check_giver_stmt);
        $giver_result = oci_fetch_assoc($check_giver_stmt);

        if ($giver_result['COUNT'] > 0) {
            // `giver` 테이블에서 데이터 삭제
            $delete_giver_sql = "DELETE FROM giver WHERE giver_id = :user_id";
            $delete_giver_stmt = oci_parse($conn, $delete_giver_sql);
            oci_bind_by_name($delete_giver_stmt, ':user_id', $user_id);
            oci_execute($delete_giver_stmt);
        } else {
            throw new Exception("사용자를 찾을 수 없습니다.");
        }
    }

    // 관련 데이터 삭제 (예: 찜한 나눔러)
    $delete_favorites_sql = "DELETE FROM favorites WHERE user_id = :user_id";
    $delete_favorites_stmt = oci_parse($conn, $delete_favorites_sql);
    oci_bind_by_name($delete_favorites_stmt, ':user_id', $user_id);
    oci_execute($delete_favorites_stmt);

    // 트랜잭션 커밋
    oci_execute(oci_parse($conn, 'COMMIT'));

    // 세션 종료 및 알림
    session_destroy();
    echo "<script>alert('회원 탈퇴가 완료되었습니다. 그동안 이용해주셔서 감사합니다.'); window.location.href = 'index.php';</script>";
} catch (Exception $e) {
    // 트랜잭션 롤백
    oci_execute(oci_parse($conn, 'ROLLBACK'));
    echo "<script>alert('회원 탈퇴 중 문제가 발생했습니다: " . $e->getMessage() . "'); history.back();</script>";
} finally {
    // 자원 정리
    oci_free_statement($check_users_stmt);
    oci_free_statement($check_giver_stmt);
    oci_free_statement($delete_users_stmt ?? null);
    oci_free_statement($delete_giver_stmt ?? null);
    oci_free_statement($delete_favorites_stmt);
    oci_close($conn);
}
?>
