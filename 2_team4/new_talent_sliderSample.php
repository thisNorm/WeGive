
<?php
// 슬라이더에 표시될 콘텐츠 배열 정의
$new_talents = [
    ['image' => 'image/1.png', 'number' => '1', 'title' => '노트북 하나로 평생 돈 버는 법', 'description' => '창업·부업 | 1'],
    ['image' => 'image/2.png', 'number' => '2', 'title' => '나만의 노래 만들기', 'description' => '음악 | 2'],
    ['image' => 'image/3.png', 'number' => '3', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 3'],
    ['image' => 'image/4.png', 'number' => '4', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 4'],
    ['image' => 'image/5.png', 'number' => '5', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 5'],
    ['image' => 'image/6.png', 'number' => '6', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 6'],
    ['image' => 'image/7.png', 'number' => '7', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 7'],
    ['image' => 'image/8.png', 'number' => '8', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 8'],
    ['image' => 'image/9.png', 'number' => '9', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 9'],
    ['image' => 'image/10.png', 'number' => '10', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 10'],
    ['image' => 'image/11.png', 'number' => '11', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 11'],
    ['image' => 'image/12.png', 'number' => '12', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 12'],
    ['image' => 'image/13.png', 'number' => '13', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 13'],
    ['image' => 'image/14.png', 'number' => '14', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 14'],
    ['image' => 'image/15.png', 'number' => '15', 'title' => '유튜브 영상 편집 꿀팁', 'description' => '영상 편집 | 15'],
];

// 데이터를 동적으로 출력
foreach ($new_talents as $talents): ?>
    <div class="talent-item" data-talent-id="<?php echo htmlspecialchars($talents['number']); ?>">
    <!-- 책갈피 버튼 -->
    <div class="bookmark-icon" onclick="toggleBookmarkNew(<?php echo htmlspecialchars($talents['number']); ?>)"></div>
    <!-- 이미지 출력 -->    
    <img src="<?php echo htmlspecialchars($talents['image']); ?>" alt="<?php echo htmlspecialchars($talents['title']); ?>">
    <!-- 번호 출력 -->
    <div class="talent-number"><?php echo htmlspecialchars($talents['number']); ?></div>
    <!-- 제목 출력 -->
    <div class="talent-title"><?php echo htmlspecialchars($talents['title']); ?></div>
    <!-- 설명 출력 -->
    <div class="talent-description"><?php echo htmlspecialchars($talents['description']); ?></div>
    </div>
<?php endforeach; ?>

<style>
/* 새로 오픈한 나눔러 컨테이너 */
.new-talents {
    margin-top: 50px;
    text-align: center;
}

/* 새로 오픈한 나눔러 제목 스타일 */
.new-talents h2 {
    font-size: 18px;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
    position: relative;
}

/* 새로 오픈한 나눔러 슬라이더 컨테이너 */
.new-talent-slider-container {
    display: flex;
    justify-content: center; /* 슬라이더 중앙 정렬 */
    overflow: hidden;
    width: 65%; /* 전체 슬라이더 너비 */
    margin: 0 auto; /* 중앙 정렬 */
    position: relative;
    height: auto; /* 높이를 자동 조정 */
    padding-bottom: 20px; /* 하단 여백 추가 */
}

/* 슬라이더 내부 */
.new-talent-slider {
    display: flex;
    gap: 10px; /* 이미지 사이의 간격 */
    transition: transform 0.5s ease-in-out;
    width: calc(100% * 5); /* 슬라이더가 5개의 이미지만 보이도록 설정 */
    max-width: 100%; /* 부모 크기를 넘지 않도록 제한 */
    height: auto; /* 높이를 자동 조정 */
}

/* 각 슬라이더 아이템 */
.new-talent-item {
    flex: 0 0 calc(20% - 10px);
    max-width: calc(20% - 10px);
    box-sizing: border-box;
}

/* 책갈피 아이콘 */
.new-talent-item .bookmark-icon {
    position: absolute;
    top: 10px; /* 이미지 내부 상단 여백 */
    right: 10px; /* 이미지 내부 오른쪽 여백 */
    width: 24px; /* 아이콘 크기 */
    height: 24px;
    cursor: pointer;
    background-image: url('/2_team/2_team4/2_team4/image/bookmark_empty.png'); /* 기본 책갈피 */
    background-size: contain; /* 아이콘 크기를 유지하며 비율 조정 */
    background-repeat: no-repeat;
    z-index: 10; /* 이미지 위로 표시 */
    transition: background-image 0.3s ease;
}

/* 찜 상태의 책갈피 */
.new-talent-item .bookmark-icon.bookmarked {
    background-image: url('/2_team/2_team4/2_team4/image/bookmark_filled.png');
}

/* 항목 이미지 스타일 */
.new-talent-item img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
}

/* 항목 번호 스타일 */
.new-talent-item .new-talent-number {
    font-size: 20px;
    font-weight: bold;
    color: #0072B8;
    margin-top: 10px;
}

/* 항목 제목 스타일 */
.new-talent-item .new-talent-title {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    line-height: 1.4;
    text-align: center;
    margin-top: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* 항목 설명 스타일 */
.new-talent-item .new-talent-description {
    font-size: 12px;
    color: #666;
    text-align: center;
    margin-top: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* 슬라이더 버튼 스타일 */
.new-slider-buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.new-slider-button {
    padding: 10px 20px;
    background-color: #0072B8;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin: 0 10px;
}

.new-slider-button:hover {
    background-color: #005f8a;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookmarks = JSON.parse(localStorage.getItem('bookmarks')) || [];

    // 초기화: 모든 receiver-item에 대해 상태 설정
    document.querySelectorAll('.receiver-item').forEach(item => {
        const receiverId = item.getAttribute('data-receiver-id');
        const bookmarkIcon = item.querySelector('.bookmark-icon');
        if (bookmarks.includes(String(receiverId))) {
            bookmarkIcon.classList.add('bookmarked');
        }
    });

    // 찜 버튼 클릭 이벤트
    window.toggleBookmarkNew = function (receiverId, type) {
        const item = document.querySelector(`.receiver-item[data-receiver-id="${receiverId}"]`);
        if (!item) {
            console.error(`Element with receiverId "${receiverId}" not found.`);
            return;
        }

        const bookmarkIcon = item.querySelector('.bookmark-icon');
        const isBookmarked = bookmarkIcon.classList.contains('bookmarked');
        const action = isBookmarked ? 'removeFavorite' : 'addFavorite';

        // 서버와 동기화
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                receiverId,
                type, // type: 'newReceiver'
                [action]: true // 동적으로 addFavorite 또는 removeFavorite 설정
            }),
        })
        .then(response => {
            if (response.ok) {
                // UI 업데이트
                bookmarkIcon.classList.toggle('bookmarked');
                if (isBookmarked) {
                    // 로컬 스토리지에서 제거
                    const index = bookmarks.indexOf(String(receiverId));
                    if (index > -1) bookmarks.splice(index, 1);
                } else {
                    // 로컬 스토리지에 추가
                    if (!bookmarks.includes(String(receiverId))) {
                        bookmarks.push(String(receiverId));
                    }
                }
                localStorage.setItem('bookmarks', JSON.stringify(bookmarks));
            } else {
                console.error('Failed to update bookmark on the server.');
            }
        })
        .catch(err => {
            console.error('Error while syncing bookmark:', err);
        });
    };
});

<script>
// 슬라이더 동작을 위한 JavaScript
document.addEventListener('DOMContentLoaded', function () {
    const popularSlider = document.getElementById("popularTalentSlider"); // 슬라이더 컨테이너
    const popularPrevButton = document.getElementById("popularPrevButton"); // 이전 버튼
    const popularNextButton = document.getElementById("popularNextButton"); // 다음 버튼

    const popularItems = popularSlider.querySelectorAll(".talent-item"); // 슬라이더 아이템들
    const itemsPerPage = 5; // 한 페이지에 표시할 아이템 수
    const itemWidth = popularItems[0]?.offsetWidth + 10 || 0; // 아이템의 너비 + 간격 계산
    const totalPages = Math.ceil(popularItems.length / itemsPerPage); // 총 페이지 계산

    let currentPage = 0;

    // 슬라이더 상태 업데이트 함수
    function updateSlider() {
        const offset = -currentPage * itemsPerPage * itemWidth; // 슬라이더 이동 거리 계산
        popularSlider.style.transform = translateX(${offset}px); // 슬라이더 이동
    }

    // 이전 버튼 클릭 이벤트
    popularPrevButton.addEventListener("click", () => {
        currentPage = (currentPage - 1 + totalPages) % totalPages; // 이전 페이지로 이동
        updateSlider();
    });

    // 다음 버튼 클릭 이벤트
    popularNextButton.addEventListener("click", () => {
        currentPage = (currentPage + 1) % totalPages; // 다음 페이지로 이동
        updateSlider();
    });

    // 초기 슬라이더 상태 업데이트
    updateSlider();

    // **자동 이동 기능 추가**
    let popularAutoSlideInterval = setInterval(() => {
        currentPage = (currentPage + 1) % totalPages; // 다음 페이지로 이동
        updateSlider();
    }, 30000); // 30초마다 자동 이동
});
</script>