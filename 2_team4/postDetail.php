<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}


include 'db_connection.php';
require 'vendor/autoload.php';

// 게시글 ID 가져오기
$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo "<script>alert('잘못된 접근입니다.'); window.location.href='designpost.php';</script>";
    exit;
}

// 게시글 정보 가져오기
$sql = "SELECT POST_ID, TITLE, DBMS_LOB.SUBSTR(TEXT, 4000, 1) AS TEXT, IMAGE_PATH, TO_CHAR(W_DAY, 'YYYY-MM-DD') AS W_DAY, GIVER_ID 
        FROM post 
        WHERE POST_ID = :post_id";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':post_id', $post_id);

if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "<p>SQL 실행 오류: " . htmlspecialchars($e['message']) . "</p>";
    exit;
}

$post = oci_fetch_assoc($stid);
require_once 'vendor/autoload.php'; // PHP Markdown 라이브러리 로드
use Michelf\Markdown;

if (!empty($post['TEXT'])) {
    $markdownText = $post['TEXT'];
    $renderedText = Markdown::defaultTransform($markdownText);
} else {
    $renderedText = '';
}

if (!$post) {
    echo "<script>alert('게시글을 찾을 수 없습니다.'); window.location.href='designpost.php';</script>";
    exit;
}
oci_free_statement($stid);

$giver_id = $post['GIVER_ID'];

// 리뷰 데이터 가져오기
$review_sql = "SELECT USER_ID, RATING, REVIEW_COMMENT, TO_CHAR(REVIEW_DATE, 'YYYY-MM-DD') AS REVIEW_DATE 
               FROM review 
               WHERE POST_ID = :post_id";
$review_stid = oci_parse($conn, $review_sql);
oci_bind_by_name($review_stid, ':post_id', $post_id);

if (!oci_execute($review_stid)) {
    $e = oci_error($review_stid);
    echo "<p>리뷰 데이터 로드 중 오류 발생: " . htmlspecialchars($e['message']) . "</p>";
    exit;
}

$reviews = [];
while ($row = oci_fetch_assoc($review_stid)) {
    $reviews[] = $row;
}
oci_free_statement($review_stid);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['TITLE']); ?></title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container1 {
    max-width: 800px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
    color: #333;
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.post-date, 
.giver-id {
    font-size: 14px;
    color: #666;
}

.post-text {
    font-size: 16px;
    margin-bottom: 20px;
    line-height: 1.6; /* 가독성을 위한 줄 간격 */
    color: #333;
}

.postimg {
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
    display: block;
}

.review-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #ddd; /* 리뷰 섹션 분리 */
}

.review-section h2 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #444;
}

.review {
    border-bottom: 1px solid #ddd;
    padding: 15px 0;
    margin-bottom: 10px;
}

.review-rating {
        font-size: 20px; /* 별 크기 */
    }

    .star {
        color: #ccc; /* 기본 색상 (빈 별의 색상) */
        font-size: 20px;
    }

    .filled {
        color: #ffc107; /* 채워진 별의 색상 (노란색) */
    }

    .empty {
        color: #ddd; /* 빈 별의 색상 (회색) */
    }


.review .review-comment {
    margin: 10px 0;
    font-size: 14px;
    color: #555;
    line-height: 1.5;
}

.review .review-date {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

.edit-button, 
.delete-button {
    padding: 8px 12px; /* 버튼 크기 표준화 */
    font-size: 14px; /* 버튼 글씨 크기 */
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-right: 5px;
}

.edit-button {
    background-color: #007bff;
    color: white;
    border: none;
}

.delete-button {
    background-color: #dc3545;
    color: white;
    border: none;
}

.edit-button:hover {
    background-color: #0056b3;
}

.delete-button:hover {
    background-color: #c82333;
}

.apply-button {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    margin-top: 20px;
}

.apply-button:hover {
    background-color: #218838;
}

.post-image img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

/* 추가 스타일링 */
.review-user {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.review-comment {
    font-size: 14px;
    color: #555;
    margin: 10px 0;
    line-height: 1.6;
}

.review-rating {
    font-size: 18px;
    color: gold;
    margin-bottom: 8px;
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container1">
        <!-- 게시글 -->
        <h1><?php echo htmlspecialchars($post['TITLE']); ?></h1>
        <div class="post-header">
            <p class="post-date">작성일: <?php echo htmlspecialchars($post['W_DAY']); ?></p>
            <p class="giver-id">작성자: <?php echo htmlspecialchars($giver_id); ?></p>
        </div>
        <?php if (!empty($post['IMAGE_PATH'])): ?>
            <div class="post-image">
                <img src="<?php echo htmlspecialchars($post['IMAGE_PATH']); ?>" alt="게시글 이미지">
            </div>
        <?php endif; ?>
        <?php if (!empty($renderedText)): ?>
            <div class="post-text">
                <?php echo $renderedText; ?>
            </div>
        <?php endif; ?>

        <!-- 수정 및 삭제 버튼 (본문 아래에 배치) -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $giver_id): ?>
            <div style="margin-top: 20px;">
                <!-- 수정하기 버튼 -->
                <form action="postEdit.php" method="GET" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
                    <button type="submit" class="edit-button">수정하기</button>
                </form>
                
                <!-- 삭제하기 버튼 -->
                <form action="postDelete.php" method="POST" style="display: inline;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
                    <button type="submit" class="delete-button">삭제하기</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- 거래 신청하기 버튼 (사용자가 로그인했을 때만) -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'users'): ?>
            <form method="POST" action="processtransaction.php">
                <input type="hidden" name="giver_id" value="<?php echo htmlspecialchars($giver_id); ?>">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
                <button type="submit" name="action" value="request">거래 신청하기</button>
            </form>
        <?php endif; ?>
    
        <!-- 리뷰 섹션 -->
        <div class="review-section">
        <h2>리뷰
            <?php 
            if (!empty($reviews)): 
                // 리뷰 평점의 평균 계산
                $totalRating = 0;
                foreach ($reviews as $review) {
                    $totalRating += $review['RATING'];  // RATING 컬럼에서 평점 추출
                }
                $averageRating = $totalRating / count($reviews);
            ?>
                (<?php echo count($reviews); ?>) - 평점: <?php echo round($averageRating, 1); ?>
            <?php endif; ?>
        </h2>

    <?php if (empty($reviews)): ?>
        <p>리뷰가 없습니다. 첫 번째 리뷰를 작성해보세요!</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <!-- 리뷰 작성자 ID -->
                <div class="review-user">
                    작성자: <?php echo htmlspecialchars($review['USER_ID'] ?? '익명 사용자'); ?>
                </div>

                <!-- 별점 표시 -->
                <div class="review-rating">
                    <?php
                    $rating = round($review['RATING']); // 별점을 반올림하여 정수로 만듦
                    $filledStars = $rating; // 채워진 별
                    $emptyStars = 5 - $filledStars; // 빈 별

                    // 채워진 별
                    for ($i = 0; $i < $filledStars; $i++) {
                        echo '<span class="star filled">★</span>';
                    }

                    // 빈 별
                    for ($i = 0; $i < $emptyStars; $i++) {
                        echo '<span class="star empty">★</span>';
                    }
                    ?>
                </div>

                <div class="review-comment">
                <?php 
                // CLOB 데이터 처리
                $comment = $review['REVIEW_COMMENT'];
                if ($comment instanceof OCILob) {
                    $comment = $comment->read($comment->size());
                }
                echo nl2br(htmlspecialchars($comment ?? '내용 없음'));
                ?>
            </div>
            <div class="review-date">
                작성일: <?php echo htmlspecialchars(date('Y-m-d', strtotime($review['REVIEW_DATE'] ?? '날짜 없음'))); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
