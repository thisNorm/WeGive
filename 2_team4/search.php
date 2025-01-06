<?php
// 세션 확인 및 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include 'header.php';
include 'db_connection.php';

// 검색어 가져오기
$searchQuery = trim($_GET['query'] ?? '');

// 검색어가 없을 경우 처리
if (empty($searchQuery)) {
    echo "<script>alert('검색어를 입력해주세요.'); window.history.back();</script>";
    exit;
}

// 검색 쿼리 실행
$sql = "
    SELECT 
        p.post_id AS post_id, 
        p.title AS title, 
        TO_CHAR(p.w_day, 'YYYY-MM-DD') AS w_day, 
        p.image_path AS image_path, 
        g.name AS giver_name
    FROM post p
    Inner JOIN giver g ON p.giver_id = g.giver_id
    WHERE LOWER(p.title) LIKE '%' || LOWER(:searchQuery) || '%'
       OR LOWER(g.name) LIKE '%' || LOWER(:searchQuery) || '%'
    ORDER BY p.w_day DESC
";

$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':searchQuery', $searchQuery);
oci_execute($stid);

// 검색 결과 저장
$results = [];
while ($row = oci_fetch_assoc($stid)) {
    $results[] = $row;
}

// 리소스 해제
oci_free_statement($stid);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색 결과</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .header h1 {
            margin: 0;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px;
        }
        .card-date {
            font-size: 14px;
            color: #888;
        }
        .empty {
            text-align: center;
            font-size: 20px;
            color: #999;
            grid-column: span 3;
        }
        a.post {
            text-decoration: none;
            color: black; /* 기본 색상 검정 */
        }
        a.post:hover {
            color: black; /* 마우스 올릴 때도 검정 */
        }
        a.post:visited {
            color: black; /* 방문한 후에도 검정 */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>"<?php echo htmlspecialchars($searchQuery); ?>" 검색 결과</h1>
        </div>

        <!-- Cards Section -->
        <div class="cards">
            <?php if (empty($results)): ?>
                <div class="empty">"<?php echo htmlspecialchars($searchQuery); ?>"에 대한 검색 결과가 없습니다.</div>
            <?php else: ?>
                <?php foreach ($results as $result): ?>
                    <!-- 게시글 링크 -->
                    <a href="postDetail.php?post_id=<?php echo htmlspecialchars($result['POST_ID']); ?>" class="post">
                        <div class="card">
                            <!-- 게시글 이미지 -->
                            <img src="<?php echo htmlspecialchars($result['IMAGE_PATH'] ?: 'default-image.jpg'); ?>" alt="게시글 이미지">
                            
                            <!-- 카드 내용 -->
                            <div class="card-content">
                                <div class="card-title">
                                    <?php echo htmlspecialchars($result['TITLE']); ?>
                                </div>
                                <div class="card-date">작성일: <?php echo htmlspecialchars($result['W_DAY']); ?></div>
                                <div class="card-author">작성자: <?php echo htmlspecialchars($result['GIVER_NAME'] ?? '알 수 없음'); ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
