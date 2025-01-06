<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}


include 'header.php'; 
include 'db_connection.php'; 
// 데이터베이스 연결

// 게시글 불러오기
$sql = "SELECT POST_ID, TITLE, TO_CHAR(W_DAY, 'YYYY-MM-DD') AS W_DAY, IMAGE_PATH 
        FROM post 
        WHERE SKILL_ID = 'C' 
        ORDER BY W_DAY DESC";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

// 게시글 데이터를 배열로 저장
$posts = [];
while ($row = oci_fetch_assoc($stid)) {
    $posts[] = $row;
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
    <title>디자인 게시판</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .post-container1 {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .post-header h1 {
            margin: 0;
        }

        .post-header .write-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .post-header .write-btn:hover {
            background-color: #0056b3;
        }

        .post-nav-buttons {
            text-align: center;
            margin: 20px 0 40px 0;
        }

        .post-nav-buttons a {
            padding: 5px 18px;
            margin: 5px;
            font-size: 14px;
            background-color: white;
            color: black;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 20px;
            display: inline-block;
            transition: background-color 0.3s, color 0.3s;
        }

        .post-nav-buttons a.active {
            background-color: #0056b3;
            color: white;
            pointer-events: none;
            border: 1px solid #0056b3;
        }

        .post-nav-buttons a:hover {
            background-color: #f1f1f1;
            color: black;
        }

        .post-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .post-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .post-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .post-card-content {
            padding: 15px;
        }

        .post-card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px;
        }

        .post-card-date {
            font-size: 14px;
            color: #888;
        }

        .post-empty {
            text-align: center;
            font-size: 20px;
            color: #999;
            grid-column: span 3;
        }

        /* 링크 색상을 항상 검정색으로 고정 */
        a.post {
            text-decoration: none;
            color: black;
        }

        a.post:hover {
            color: black;
        }

        a.post:visited {
            color: black;
        }
    </style>
</head>
<body>
    <div class="post-container1">
        <!-- Header Section -->
        <div class="post-header">
            <h1>디자인 게시판</h1>
            <a href="postWrite.php" class="write-btn">글쓰기</a>
        </div>

        <!-- Navigation Buttons -->
        <div class="post-nav-buttons">
            <a href="codingpost.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'codingpost.php' ? 'active' : ''; ?>">코딩</a>
            <a href="designpost.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'designpost.php' ? 'active' : ''; ?>">디자인</a>
            <a href="videopost.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'videopost.php' ? 'active' : ''; ?>">영상</a>
            <a href="marketingpost.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'marketingpost.php' ? 'active' : ''; ?>">마케팅</a>
        </div>

        <!-- Cards Section -->
        <div class="post-cards">
            <?php if (empty($posts)): ?>
                <div class="post-empty">게시글이 없습니다.</div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <!-- 게시글 링크 -->
                    <a href="postDetail.php?post_id=<?php echo htmlspecialchars($post['POST_ID']); ?>" class="post">
                        <div class="post-card">
                            <!-- 게시글 이미지 -->
                            <img src="<?php echo htmlspecialchars($post['IMAGE_PATH'] ?: 'default-image.jpg'); ?>" alt="게시글 이미지">
                            
                            <!-- 카드 내용 -->
                            <div class="post-card-content">
                                <div class="post-card-title">
                                    <?php echo htmlspecialchars($post['TITLE']); ?>
                                </div>
                                <div class="post-card-date"><?php echo htmlspecialchars($post['W_DAY']); ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
