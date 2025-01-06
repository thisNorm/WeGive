<?php
include 'db_connection.php'; // 데이터베이스 연결
session_start(); // 세션 시작

// 로그인한 사용자가 giver인지 확인
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'giver') {
    echo "<script>alert('게시글 수정은 나눔러(giver)만 가능합니다.'); window.location.href='login.php';</script>";
    exit;
}

// 게시글 ID 가져오기
$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo "<script>alert('잘못된 접근입니다.'); window.location.href='designpost.php';</script>";
    exit;
}

// 게시글 데이터 가져오기
$sql = "SELECT TITLE, TEXT, IMAGE_PATH, SKILL_ID, GIVER_ID 
        FROM post 
        WHERE POST_ID = :post_id";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':post_id', $post_id);
oci_execute($stid, OCI_NO_AUTO_COMMIT);
$post = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);

// 게시글이 없거나 작성자가 아니면 접근 불가
if (!$post || $_SESSION['user_id'] !== $post['GIVER_ID']) {
    echo "<script>alert('수정 권한이 없습니다.'); window.location.href='designpost.php';</script>";
    exit;
}

// 게시글 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $text = $_POST['text'] ?? null;
    $skill_id = $_POST['skill_id'] ?? null;
    $image_path = $post['IMAGE_PATH']; // 기존 이미지 유지

    // 입력값 검증
    if (empty($title) || empty($text) || empty($skill_id)) {
        echo "<script>alert('모든 필수 입력값을 입력해주세요.'); window.history.back();</script>";
        exit;
    }

    // 이미지 업로드 처리
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // 업로드 디렉토리 설정
        $image_name = uniqid() . "_" . basename($_FILES['image']['name']);
        $new_image_path = $upload_dir . $image_name;

        // 디렉토리가 없으면 생성
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // 이미지 파일 이동
        if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path)) {
            $image_path = $new_image_path; // 새 이미지 경로 저장
        } else {
            echo "<script>alert('이미지 업로드 실패.'); window.history.back();</script>";
            exit;
        }
    }

    // 데이터 업데이트
    $update_sql = "UPDATE post 
                   SET TITLE = :title, SKILL_ID = :skill_id, IMAGE_PATH = :image_path, TEXT = :text
                   WHERE POST_ID = :post_id";
    $update_stid = oci_parse($conn, $update_sql);

    // SQL 바인딩
    oci_bind_by_name($update_stid, ':title', $title);
    oci_bind_by_name($update_stid, ':skill_id', $skill_id);
    oci_bind_by_name($update_stid, ':image_path', $image_path);
    oci_bind_by_name($update_stid, ':text', $text, -1); // TEXT 데이터를 직접 바인딩
    oci_bind_by_name($update_stid, ':post_id', $post_id);

    // 쿼리 실행
    if (oci_execute($update_stid, OCI_NO_AUTO_COMMIT)) {
        if (oci_commit($conn)) {
            echo "<script>alert('게시글이 수정되었습니다!'); window.location.href='postDetail.php?post_id=" . htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8') . "';</script>";
        } else {
            echo "<script>alert('게시글 수정 중 오류가 발생했습니다.');</script>";
            oci_rollback($conn);
        }
    } else {
        $e = oci_error($update_stid);
        echo "<pre>쿼리 실행 오류: " . htmlspecialchars($e['message']) . "</pre>";
        oci_rollback($conn);
    }

    oci_free_statement($update_stid);
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 수정</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="file"] {
            border: none;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>게시글 수정</h2>
    <form id="postForm" action="postEdit.php?post_id=<?php echo htmlspecialchars($post_id); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['TITLE']); ?>" maxlength="20" required>
        </div>
        <div class="form-group">
            <label for="text">본문 (마크다운 지원)</label>
            <textarea id="markdown-editor" name="text" rows="10"><?php echo htmlspecialchars($post['TEXT']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="skill_id">스킬 선택</label>
            <select id="skill_id" name="skill_id" required>
                <option value="A" <?php echo $post['SKILL_ID'] === 'A' ? 'selected' : ''; ?>>코딩</option>
                <option value="B" <?php echo $post['SKILL_ID'] === 'B' ? 'selected' : ''; ?>>영상편집</option>
                <option value="C" <?php echo $post['SKILL_ID'] === 'C' ? 'selected' : ''; ?>>디자인</option>
                <option value="D" <?php echo $post['SKILL_ID'] === 'D' ? 'selected' : ''; ?>>마케팅</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">이미지 업로드</label>
            <input type="file" id="image" name="image" accept="image/*">
            <p>현재 이미지: <?php echo htmlspecialchars($post['IMAGE_PATH']); ?></p>
        </div>
        <button type="submit">수정 완료</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    const simplemde = new SimpleMDE({ element: document.getElementById("markdown-editor") });

    function validateForm() {
        const title = document.getElementById("title").value.trim();
        if (!title) {
            alert("제목을 입력하세요.");
            return false;
        }
        const markdownContent = simplemde.value().trim();
        if (!markdownContent) {
            alert("본문을 입력하세요.");
            return false;
        }
        document.getElementById("markdown-editor").value = markdownContent;
        return true;
    }
</script>
</body>
</html>
