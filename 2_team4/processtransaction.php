<?php
include 'db_connection.php'; // 데이터베이스 연결
session_start(); // 세션 시작

// 거래 신청하기 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $transactionId = $_POST['transaction_id'] ?? null;
    $giverId = $_POST['giver_id'] ?? $_SESSION['user_id']; // 세션에서 기버 ID 가져오기
    $userId = $_POST['user_id'] ?? null;
    $postId = $_POST['post_id'] ?? null;

    try {
        // DB 연결 확인
        if (!$conn) {
            $e = oci_error();
            throw new Exception('오라클 연결 오류: ' . htmlspecialchars($e['message']));
        }

        switch ($action) {
            case 'request':
                // 유저가 거래 신청하기 버튼을 눌렀을 때
                $sql = "INSERT INTO transaction (TRANSACTION_ID, GIVER_ID, USER_ID, TRANSACTION_DATE, STATUS)
                        VALUES (TRANSACTION_SEQ.NEXTVAL, :giver_id, :user_id, SYSDATE, 'PENDING')";
                $stid = oci_parse($conn, $sql);
                oci_bind_by_name($stid, ':giver_id', $giverId);
                oci_bind_by_name($stid, ':user_id', $userId);
            
                if (!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)) {
                    $e = oci_error($stid); // 실행 시 오류 가져오기
                    throw new Exception('거래 신청 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                }

                // 자바스크립트를 통해 페이지 이동
                echo "<script>
                        alert('거래가 성공적으로 신청되었습니다.');
                        window.location.href = 'postDetail.php?post_id=" . htmlspecialchars($postId, ENT_QUOTES, 'UTF-8') . "';
                    </script>";

                oci_free_statement($stid);
                break;

            case 'approve':
                // 기버가 거래를 승인할 때
                if ($transactionId) {
                    $sql = "UPDATE transaction SET STATUS = 'IN_PROGRESS'
                            WHERE TRANSACTION_ID = :transaction_id AND GIVER_ID = :giver_id";
                    $stid = oci_parse($conn, $sql);
                    oci_bind_by_name($stid, ':transaction_id', $transactionId);
                    oci_bind_by_name($stid, ':giver_id', $giverId);

                    if (!oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
                        $e = oci_error($stid);
                        throw new Exception('거래 승인 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                    }

                    // 커밋 처리
                    if (!oci_commit($conn)) {
                        $e = oci_error($conn);
                        throw new Exception('커밋 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                    }

                    // 거래 승인 후 MyPage로 리다이렉트
                    echo "<script>alert('거래가 승인되었습니다.'); window.location.href='MyPage.php';</script>";
                    oci_free_statement($stid);
                } else {
                    echo "<script>alert('거래 ID가 누락되었습니다.'); window.history.back();</script>";
                }
                break;

            case 'reject':
                // 거래 취소 처리 (기버 또는 사용자 모두 취소 가능)
                if ($transactionId) {
                    // 거래 상태를 "CANCELLED"로 업데이트
                    $sql = "UPDATE transaction SET STATUS = 'CANCELLED' 
                            WHERE TRANSACTION_ID = :transaction_id";
                    $stid = oci_parse($conn, $sql);
                    oci_bind_by_name($stid, ':transaction_id', $transactionId);
            
                    // 쿼리 실행 및 오류 처리
                    if (!oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
                        $e = oci_error($stid);
                        throw new Exception('거래 취소 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                    }
            
                    // 커밋 처리
                    if (!oci_commit($conn)) {
                        $e = oci_error($conn);
                        throw new Exception('커밋 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                    }
            
                    // 성공적으로 거래 취소된 경우
                    oci_free_statement($stid);
            
                    echo "<script>
                            alert('거래가 취소되었습니다.');
                            window.location.href = 'MyPage.php';
                            </script>";
                } else {
                    echo "<script>alert('거래 ID가 누락되었습니다.'); window.history.back();</script>";
                }
                break;

            case 'complete':
                // 유저와 기버 모두가 거래 완료 버튼을 눌렀을 때
                if ($transactionId) {
                    $sql = "SELECT STATUS FROM transaction 
                            WHERE TRANSACTION_ID = :transaction_id";
                    $stid = oci_parse($conn, $sql);
                    oci_bind_by_name($stid, ':transaction_id', $transactionId);
                    oci_execute($stid);

                    $transaction = oci_fetch_assoc($stid);
                    oci_free_statement($stid);

                    if ($transaction) {
                        $status = $transaction['STATUS'];
                        if ($status === 'IN_PROGRESS') {
                            $sql = "UPDATE transaction SET STATUS = 'COMPLETED' WHERE TRANSACTION_ID = :transaction_id";
                            $stid = oci_parse($conn, $sql);
                            oci_bind_by_name($stid, ':transaction_id', $transactionId);

                            if (!oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
                                $e = oci_error($stid);
                                throw new Exception('거래 완료 처리 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                            }

                            // 커밋 처리
                            if (!oci_commit($conn)) {
                                $e = oci_error($conn);
                                throw new Exception('커밋 중 오류가 발생했습니다: ' . htmlspecialchars($e['message']));
                            }

                            echo "<script>alert('거래가 성공적으로 완료되었습니다.'); window.location.href='MyPage.php';</script>";
                            oci_free_statement($stid);
                        } else {
                            echo "<script>alert('거래가 진행 중이 아닙니다. 완료할 수 없습니다.'); window.location.href='MyPage.php';</script>";
                        }
                    }
                }
                break;

            default:
                echo "<script>alert('알 수 없는 작업입니다.'); window.history.back();</script>";
                break;
        }
    } catch (Exception $e) {
        echo "<p>오류 발생: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    // DB 연결 해제
    oci_close($conn);
}
?>
