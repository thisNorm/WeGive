<?php
include 'db_connection.php';
session_start();

// 로그인한 사용자가 giver인지 확인
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'giver') {
    echo "<script>alert('게시글 작성은 나눔러(giver)만 가능합니다.'); window.location.href='login.php';</script>";
    exit;
}

// giver의 재능아이디 확인 (세션에서 가져옴)
if (!isset($_SESSION['skill_id'])) {
    $query = "SELECT skill_id FROM giver WHERE giver_id = :giver_id";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':giver_id', $_SESSION['user_id']);
    oci_execute($stid);
    $result = oci_fetch_assoc($stid);

    if ($result && isset($result['SKILL_ID'])) {
        $_SESSION['skill_id'] = $result['SKILL_ID']; // 세션에 저장
    } else {
        echo "<script>alert('회원님의 재능 정보를 가져오지 못했습니다. 관리자에게 문의하세요.'); window.location.href='index.php';</script>";
        exit;
    }
    oci_free_statement($stid);
}

$giver_skill_id = $_SESSION['skill_id'];
$giver_id = $_SESSION['user_id'];

// giver가 이미 게시글을 작성했는지 확인
$check_sql = "SELECT COUNT(*) AS post_count FROM post WHERE giver_id = :giver_id";
$check_stid = oci_parse($conn, $check_sql);
oci_bind_by_name($check_stid, ':giver_id', $giver_id);
oci_execute($check_stid);
$check_result = oci_fetch_assoc($check_stid);

if ($check_result['POST_COUNT'] > 0) {
    echo "<script>alert('이미 작성한 게시글이 있습니다.'); window.location.href='index.php';</script>";
    exit;
}

// 게시글 작성 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $text = $_POST['text'] ?? null;
    $skill_id = $_POST['skill_id'] ?? null;
    $image_path = null;

    // 입력값 검증
    if (empty($title) || empty($text) || empty($skill_id)) {
        echo "<script>alert('모든 필수 입력값을 입력해주세요.'); window.history.back();</script>";
        exit;
    }

    // giver의 재능아이디와 게시판 재능아이디 일치 여부 확인
    if (strcasecmp($skill_id, $giver_skill_id) !== 0) {
        echo "<script>alert('선택한 재능이 회원님의 재능과 일치하지 않습니다.'); window.history.back();</script>";
        exit;
    }

    // 이미지 업로드 처리
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // 업로드 디렉토리 설정
        $image_name = uniqid() . "_" . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        // 디렉토리가 없으면 생성
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // 이미지 파일 이동
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "<script>alert('이미지 업로드 실패.'); window.history.back();</script>";
            exit;
        }
    } else {
        // 업로드된 이미지가 없을 경우 기본 이미지 설정
        $image_path = 'image/jjinlogo.png'; // 기본 이미지 경로
    }

    // CLOB 데이터 준비
    $clob = oci_new_descriptor($conn, OCI_D_LOB);

    // SQL 작성
    $sql = "INSERT INTO post (POST_ID, GIVER_ID, SKILL_ID, TITLE, TEXT, W_DAY, IMAGE_PATH)
            VALUES (POST_SEQ.NEXTVAL, :giver_id, :skill_id, :title, EMPTY_CLOB(), SYSDATE, :image_path)";
    $stid = oci_parse($conn, $sql);

    // SQL 바인딩
    oci_bind_by_name($stid, ':giver_id', $giver_id);
    oci_bind_by_name($stid, ':skill_id', $skill_id);
    oci_bind_by_name($stid, ':title', $title);
    oci_bind_by_name($stid, ':image_path', $image_path);

    // SQL 실행
    if (oci_execute($stid, OCI_NO_AUTO_COMMIT)) {
        // CLOB 업데이트 처리
        $update_sql = "UPDATE post SET TEXT = :text WHERE POST_ID = (SELECT MAX(POST_ID) FROM post)";
        $update_stid = oci_parse($conn, $update_sql);
        $clob->writeTemporary($text, OCI_TEMP_CLOB);
        oci_bind_by_name($update_stid, ':text', $clob, -1, OCI_B_CLOB);

        if (oci_execute($update_stid, OCI_NO_AUTO_COMMIT)) {
            oci_commit($conn);
            echo "<script>alert('게시글이 작성되었습니다!'); window.location.href='index.php';</script>";
        } else {
            $e = oci_error($update_stid);
            echo "<pre>";
            echo "OCI ERROR (CLOB Update): " . htmlspecialchars($e['message']) . "\n";
            echo "</pre>";
            oci_rollback($conn);
        }
    } else {
        $e = oci_error($stid);
        echo "<pre>";
        echo "OCI ERROR (Insert): " . htmlspecialchars($e['message']) . "\n";
        echo "</pre>";
        oci_rollback($conn);
    }

    // 리소스 정리
    $clob->free();
    oci_free_statement($stid);
    oci_free_statement($update_stid);
    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 작성</title>
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
    <h2>게시글 작성</h2>
    <form id="postForm" action="postWrite.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" maxlength="20" required>
        </div>
        <div class="form-group">
            <label for="text">본문 (마크다운 지원)</label>
            <textarea id="markdown-editor" name="text" rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="skill_id">스킬 선택</label>
            <select id="skill_id" name="skill_id" required>
                <option value="A">코딩</option>
                <option value="B">영상편집</option>
                <option value="C">디자인</option>
                <option value="D">마케팅</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">이미지 업로드</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit">게시글 작성</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    // SimpleMDE 초기화
    const simplemde = new SimpleMDE({ element: document.getElementById("markdown-editor") });

    // 폼 검증 함수
    function validateForm() {
        const title = document.getElementById("title").value.trim();
        if (!title) {
            alert("제목을 입력하세요.");
            return false;
        }

        if (title.length > 20) {  
            alert("제목은 20자를 초과할 수 없습니다.");
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
