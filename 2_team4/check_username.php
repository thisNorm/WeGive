<?php
include 'db_connection.php'; // 데이터베이스 연결

// 아이디 중복 확인
if (isset($_GET['check_username'])) {
    $username = $_GET['check_username'];
    $response = ['exists' => false];

    try {
        // 기버 테이블에서 giver_id 확인
        $sql = "SELECT * FROM giver WHERE giver_id = :username";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_execute($stid);

        if (oci_fetch_assoc($stid)) {
            $response['exists'] = true;
        }

        oci_free_statement($stid);

        // 유저 테이블에서 user_id 확인
        $sql = "SELECT * FROM users WHERE user_id = :username";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_execute($stid);

        if (oci_fetch_assoc($stid)) {
            $response['exists'] = true;
        }

        oci_free_statement($stid);
        oci_close($conn);
    } catch (Exception $e) {
        $response['exists'] = false;
        echo json_encode(['exists' => false, 'message' => '중복 확인 중 오류가 발생했습니다.']);
        exit;
    }

    // 중복 여부 반환
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
