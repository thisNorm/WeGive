<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}


// DB 연결
include 'db_connection.php';



$sql = $sql = "SELECT 
    p.POST_ID, 
    p.TITLE, 
    p.IMAGE_PATH, 
    COUNT(r.REVIEW_ID) AS REVIEW_COUNT
FROM 
    post p
LEFT JOIN 
    review r ON p.POST_ID = r.POST_ID
GROUP BY 
    p.POST_ID, p.TITLE, p.IMAGE_PATH
ORDER BY 
    REVIEW_COUNT DESC
";
$stid = oci_parse($conn, $sql);

if (!$stid) {
    $error = oci_error($conn);
    die("SQL 준비 실패: " . htmlspecialchars($error['message']));
}

if (!oci_execute($stid)) {
    $error = oci_error($stid);
    die("SQL 실행 실패: " . htmlspecialchars($error['message']));
}

// 데이터를 배열로 가져오기
$talents = [];
while ($row = oci_fetch_assoc($stid)) {
    $talents[] = $row;
}





// 동적으로 슬라이더 항목 출력
if (count($talents) > 0) {
    foreach ($talents as $talent): ?>
        <div class="talent-item" data-talent-id="<?php echo htmlspecialchars($talent['POST_ID']); ?>">
            <img src="<?php echo htmlspecialchars($talent['IMAGE_PATH']); ?>" alt="<?php echo htmlspecialchars($talent['TITLE']); ?>">
            <div class="talent-number"><?php echo htmlspecialchars($talent['POST_ID']); ?></div>
            <div class="talent-title"><?php echo htmlspecialchars($talent['TITLE']); ?></div>
        </div>
    <?php endforeach;
} else {
    echo "데이터가 없습니다."; // 데이터가 없을 경우 메시지 출력
}
?>
<div class="talent-slider-container">
    <!-- 실시간 인기 나눔 텍스트 -->

    </div>

    <!-- 슬라이더 영역 -->
    <div id="popularTalentSlider" class="talent-slider">
        <?php
        if (count($talents) > 0) {
            foreach ($talents as $talent): ?>
                <div class="talent-item" data-talent-id="<?php echo htmlspecialchars($talent['POST_ID']); ?>">
                    <img src="<?php echo htmlspecialchars($talent['IMAGE_PATH']); ?>" alt="<?php echo htmlspecialchars($talent['TITLE']); ?>">
                    <div class="talent-number"><?php echo htmlspecialchars($talent['POST_ID']); ?></div>
                    <div class="talent-title"><?php echo htmlspecialchars($talent['TITLE']); ?></div>
                </div>
            <?php endforeach;
        } else {
            echo "데이터가 없습니다."; // 데이터가 없을 경우 메시지 출력
        }
        ?>
    </div>

    <!-- 이전/다음 버튼 -->
    <div class="slider-buttons">
        <button id="popularPrevButton" class="talentslider-button">&#8249;</button>
        <button id="popularNextButton" class="talentslider-button">&#8250;</button>
    </div>
</div>

<style>
/* 슬라이더 컨테이너 중앙 정렬 및 너비 설정 */
.talent-slider-container {
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    width: 80%; /* 슬라이더 너비 */
    margin: 0 auto;
    position: relative;
    height: 300px; /* 슬라이더 높이 설정 */
    flex-direction: column; /* 컨텐츠 세로 정렬 */
}

/* 실시간 인기 나눔 텍스트 중앙 상단 배치 */
.slider-title {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

/* 슬라이더 항목들이 가로로 나열되도록 */
.talent-slider {
    display: flex;
    gap: 10px;
    transition: transform 0.5s ease-in-out;
    width: calc(100% * 5); /* 슬라이더가 5개의 이미지만 보이도록 설정 */
    max-width: 100%; /* 부모 크기를 넘지 않도록 제한 */
    height: 100%;
}

/* 각 슬라이더 항목 스타일 */
.talent-item {
    position: relative;
    flex: 0 0 calc(20% - 10px);
    max-width: calc(20% - 10px);
    box-sizing: border-box;
    height: auto; /* 항목의 높이를 늘림 (원하는 값으로 조정) */
    display: flex;
    flex-direction: column; /* 콘텐츠를 세로로 정렬 */
    justify-content: space-between; /* 콘텐츠 간 간격 조정 */
}

/* 항목 이미지 스타일 */
.talent-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

/* 제목 스타일 */
.talent-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-top: 10px;
    text-align: center;
}

/* 번호 스타일 */
.talent-number {
    font-size: 14px;
    color: #777;
    text-align: center;
}

/* 슬라이더의 이전/다음 버튼 스타일 */
.talentslider-buttons {
    display: flex;
    justify-content: space-between;
    position: absolute;
    bottom: 10px; /* 중앙 하단에 위치 */
    width: 100%;
    transform: translateY(-50%);
    z-index: 2;
}

.talentslider-button {
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
}

.talentslider-button:hover {
    background-color: rgba(0, 0, 0, 0.8);
}
</style>


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
        popularSlider.style.transform = `translateX(${offset}px)`; // 슬라이더 이동
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
