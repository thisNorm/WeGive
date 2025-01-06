<?php
session_start();
include 'db_connection.php'; // 데이터베이스 연결

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $_SESSION['user_id'] ?? null; // 사용자 ID 가져오기
    $talentId = $data['id'];
    $action = $data['action'];

    if ($userId && $talentId) {
        if ($action === 'add') {
            // 찜 추가
            $query = $db->prepare("INSERT INTO bookmarks (user_id, talent_id) VALUES (?, ?)");
            $success = $query->execute([$userId, $talentId]);
        } elseif ($action === 'remove') {
            // 찜 제거
            $query = $db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND talent_id = ?");
            $success = $query->execute([$userId, $talentId]);
        }
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false]);
    }
}

<!-- bookmark.php -->

<style>
/* 책갈피 아이콘 스타일 */
.bookmark {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.3s;
    z-index: 10;
}

.bookmark.active {
    background-color: #ff5722; /* 찜한 상태의 색상 */
}

.bookmark svg {
    width: 20px;
    height: 20px;
    fill: #000; /* 기본 아이콘 색상 */
}

.bookmark.active svg {
    fill: #fff; /* 찜한 상태의 아이콘 색상 */
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const bookmarks = document.querySelectorAll(".bookmark");
    const myFavorites = JSON.parse(localStorage.getItem("myFavorites")) || [];

    // 초기 상태 설정
    bookmarks.forEach(bookmark => {
        const id = bookmark.dataset.id;
        if (myFavorites.includes(id)) {
            bookmark.classList.add("active");
        }

        bookmark.addEventListener("click", () => {
            toggleBookmark(bookmark, id);
        });
    });

    function toggleBookmark(bookmark, id) {
        const index = myFavorites.indexOf(id);

        if (index > -1) {
            // 이미 찜한 경우 -> 제거
            myFavorites.splice(index, 1);
            bookmark.classList.remove("active");
        } else {
            // 찜하지 않은 경우 -> 추가
            myFavorites.push(id);
            bookmark.classList.add("active");
        }

        // 저장
        localStorage.setItem("myFavorites", JSON.stringify(myFavorites));
    }
});
</script>
