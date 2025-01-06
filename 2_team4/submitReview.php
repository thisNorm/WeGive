<?php
include 'db_connection.php'; // 데이터베이스 연결 파일

session_start();
$user_id = $_SESSION['user_id']; // 세션에서 사용자 ID 가져오기
$transaction_id = $_POST['transaction_id']; // 거래 번호
$rating = isset($_POST['rating']) ? (float) $_POST['rating'] : 0; // 별점 값을 숫자로 변환
$review_comment = $_POST['review_comment']; // 리뷰 내용
$review_date = date('Y-m-d'); // 현재 날짜

$response = ['success' => false, 'message' => ''];

header('Content-Type: application/json'); // JSON 응답 형식 설정

// 별점 값 유효성 검사
if ($rating < 0 || $rating > 5.0) {
    $response['message'] = '별점 값이 유효하지 않습니다.';
    echo json_encode($response);
    exit;
}

// 1. 거래 확인 및 GIVER_ID, POST_ID 가져오기
$sql = "SELECT T.GIVER_ID, P.POST_ID 
        FROM transaction T 
        JOIN post P ON T.GIVER_ID = P.GIVER_ID 
        WHERE T.transaction_id = :transaction_id";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':transaction_id', $transaction_id);
oci_execute($stid);
$transaction = oci_fetch_assoc($stid);

if (!$transaction) {
    $response['message'] = '거래 정보를 찾을 수 없습니다.';
    echo json_encode($response);
    exit;
}

$giver_id = $transaction['GIVER_ID']; // 거래와 연결된 기버 ID
$post_id = $transaction['POST_ID']; // 기버 ID와 연결된 POST ID

// 데이터베이스 처리 (리뷰 저장)
$sql = "INSERT INTO review (review_id, post_id, user_id, rating, review_comment, review_date) 
        VALUES (:transaction_id, :post_id, :user_id, :rating, :review_comment, TO_DATE(:review_date, 'YYYY-MM-DD'))";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':transaction_id', $transaction_id); // 거래 번호를 review_id로 사용
oci_bind_by_name($stid, ':post_id', $post_id); // POST ID
oci_bind_by_name($stid, ':user_id', $user_id); // 리뷰 작성자
oci_bind_by_name($stid, ':rating', $rating); // 숫자형 별점
oci_bind_by_name($stid, ':review_comment', $review_comment); // 리뷰 내용
oci_bind_by_name($stid, ':review_date', $review_date); // 작성 날짜

try {
    // 리뷰 저장
    if (!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)) {
        $e = oci_error($stid); // 오류 발생 시 확인
        throw new Exception('에러: ' . $e['message']);
    }

    // 성공적인 리뷰 저장 후 응답 반환
    $response['success'] = true;
    $response['message'] = '리뷰가 성공적으로 저장되었습니다.';
    $response['transaction_id'] = $transaction_id; // transaction_id를 응답에 추가

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = '리뷰 저장 중 오류가 발생했습니다: ' . $e->getMessage();
}

echo json_encode($response);
?>
