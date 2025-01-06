<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;

    if (!$post_id) {
        echo "<script>alert('잘못된 요청입니다.'); window.location.href='index.php';</script>";
        exit;
    }

    // 게시글 삭제 SQL
    $sql = "DELETE FROM post WHERE POST_ID = :post_id";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ':post_id', $post_id);

    if (oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        echo "<script>alert('게시글이 삭제되었습니다.'); window.location.href='index.php';</script>";
    } else {
        $e = oci_error($stid);
        oci_rollback($conn);
        echo "<script>alert('게시글 삭제 중 오류가 발생했습니다: " . htmlspecialchars($e['message']) . "'); window.history.back();</script>";
    }

    oci_free_statement($stid);
    oci_close($conn);
} else {
    echo "<script>alert('잘못된 접근입니다.'); window.location.href='index.php';</script>";
    exit;
}
?>