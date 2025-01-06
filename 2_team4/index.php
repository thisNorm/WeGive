<?php
// 캐시 무효화 설정
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // 과거 날짜로 설정
header("Pragma: no-cache");

session_start(); // 세션 시작

// 로그인 여부에 따라 헤더를 동적으로 로드
$is_logged_in = isset($_SESSION['user_id']);
?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인 화면</title>
    
    <style>
        /* 기본 페이지 스타일 */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #ffffff; /* 기본 배경색 */

        }
        .header-line {
            border-bottom: 2px solid #0072B8; /* 파란색으로 구분선 추가 */
            margin-bottom: 20px; /* 구분선과 콘텐츠 사이에 간격 추가 */
        }
        /* 메인 컨텐츠 영역 */
        .main-content {
            margin-left: 0;
            padding: 20px;
            background-color: blue;
        }

        /* 슬라이더 컨테이너 스타일 */
        .slider-container {
            background-color: #ffffff; /* 슬라이더 배경색 */
            padding-top: 20px;
            padding-bottom: 20px;
            
        }

        /* 메인 컨텐츠 슬라이더 */
        /* 슬라이더 자체에 border 추가 */
        .slider {
            position: relative;
            width: 80%;
            margin: 0 auto;
            margin-top: 40px;
            height: 20%;
            overflow: hidden;
            border-radius: 30px;
            box-sizing: border-box; /* border가 요소의 크기 안에 포함되도록 */
        }

        /* 슬라이드 이미지 스타일 */
       

        /* 슬라이더 안의 슬라이드 요소 */
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%; /* 양쪽 공백 포함 */
            height: 100%;
        
            box-sizing: border-box;
            
        }

        /* 슬라이드 이미지 스타일 */
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 30px; /* 이미지 모서리 둥글게 */
            display: block; /* 이미지가 container와 일치하도록 */
        }


        /* 슬라이더 하단의 동그라미 인디케이터 */
        .circle-indicators {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .circle {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .circle.active {
            background-color: #0072B8;
        }

        /* 재능기부 과정 섹션 */
        .talent-steps {
            margin-top: 50px;
            text-align: center;
            background-color: #ffffff;
        }

        .talent-steps img {
            width: 90%;
            height:300px;
            margin: 0 auto;
            display: block;
            
            border-radius: 10px;
            color: #f4f4f4;
            margin-bottom: 50px;
        }

        /* 추가 스크롤 영역 */
        .extra-scroll {
            height: 400px;
            background-color: #fff; /* 추가 스크롤 영역도 흰색으로 */
        }

        .slide {
            position: relative;
        }

        /* 팀원 소개 영역 */
        .team-intro {
            display: flex; /* flexbox 사용 */
            justify-content: space-between; /* 가로로 배치 */
            gap: 30px; /* 박스 간 간격 */
            flex-wrap: wrap; /* 화면이 작을 때 자동 줄 바꿈 */
            padding: 20px 20px; /* padding을 조정해서 위 아래 간격 줄이기 */
            background-color: white; /* 배경 색상 */
        }

        .team-h2 {
            font-size: 20px;
            text-align: center; /* 제목을 가운데 정렬 */
            padding-top: 3%;    /* 위쪽 여백 20px */
        }

        /* 개별 팀원 박스 */
        .team-box {
            background-color: #fff; /* 박스 배경 */
            width: 20%; /* 각 박스의 너비 (가로로 4개가 배치되게 설정) */
            padding: 20px;
            border-radius: 10px; /* 둥근 모서리 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 그림자 */
            text-align: center; /* 텍스트 중앙 정렬 */
            overflow: hidden; /* 사진이 박스를 넘지 않도록 */
            transition: transform 0.3s ease-in-out; /* 호버 시 박스 크기 변화 */
            height: 300px;
        }

        /* 팀원 사진 */
        .team-photo {
            width: 50px; /* 사진 크기 */
            height: 50px;
            margin-bottom: 5px;
            object-fit: cover; /* 사진이 비율에 맞게 채워짐 */
        }

        /* 팀원 이름 */
        .team-box h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }

        /* 직무 텍스트 */
        .position {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        /* 정보 텍스트 */
        .info {
            font-size: 14px;
            color: #777;
            line-height: 1.6;
        }

        /* 호버 효과: 마우스를 올리면 박스가 커지도록 */
        .team-box:hover {
            transform: translateY(-5px); /* 박스가 위로 살짝 떠오르는 효과 */
        }
    </style>
</head>
<body>
        <!-- 헤더 -->
    <?php include 'header.php'; ?>
    <!-- 메인 컨텐츠 슬라이더 -->
        <div class="slider">
        <div class="slides" id="slides">
            <!-- 슬라이드 1 -->
            <div class="slide">
                <a href="wegivepage.php">   
                <img src="image/wegivelogomain.png" alt="슬라이드 1">
            </div>
            <!-- 슬라이드 2 -->
            <div class="slide">
                <a href="codingpost.php">   
                    <img src="image/wegivemain2.png" alt="슬라이드 2">
                </a>
            </div>
            <!-- 슬라이드 3 -->
            <div class="slide">
                <a href="videopost.php">   
                    <img src="image/wegivemain3.png" alt="슬라이드 3">
                </a>
            </div>
            <!-- 슬라이드 4 -->
            <div class="slide">
                <a href="designpost.php">   
                    <img src="image/wegivemain4.png" alt="슬라이드 4">
                </a>
            </div>
            <!-- 슬라이드 5 -->
            <div class="slide">
                <a href="marketingpost.php">   
                    <img src="image/wegivemain5.png" alt="슬라이드 5">
                </a>
            </div>
        </div>
    </div>

        <!-- 슬라이드 동그라미 인디케이터 -->
        <div class="circle-indicators" id="circle-container"></div>
    </div>

<!-- 실시간 인기 나눔인 -->
<div class="talent-container">
    <div class="popular-talents">
        <h2>실시간 인기 기버</h2>
        <div class="talent-slider-container">
            <div class="talent-slider" id="popularTalentSlider">
                <?php include 'talent_slider.php'; ?>
            </div>
        </div>

        <!-- 이전/다음 버튼을 슬라이더 외부로 이동 -->
        <div class="slider-buttons">
            <button class="slider-button" id="popularPrevButton">&#60;&#60;</button> <!-- 왼쪽 버튼 -->
            <button class="slider-button" id="popularNextButton">&#62;&#62;</button> <!-- 오른쪽 버튼 -->
        </div>
    </div>    
</div>

    <!-- 새로 오픈한 나눔인 -->
    <!--
    <div class="new-talents">
        <h2>새로 오픈한 나눔인</h2>
        <div class="new-talent-slider-container">
            <div class="new-talent-slider" id="newTalentSlider">
                <?php include 'new_talent_slider.php'; ?>
                <div class="bookmark" data-id="newTalent1"></div>
            </div>
        </div>
    </div>


        <div class="new-slider-buttons">
            <button class="new-slider-button" id="newReceiverPrevButton">이전</button>
            <button class="new-slider-button" id="newReceiverNextButton">다음</button>
        </div>
    </div>
        -->

    <!-- 재능기부 과정 섹션 -->
    <div class="talent-steps">
        <img src="image/Frame 1.png" alt="재능기부 과정">
    </div>

<!-- 팀원 소개 영역 -->
<div class="team-h2">
<h2>Developer</h2>
    <div class="team-intro">
        <div class="team-box">
            <img src="image/back.png" alt="팀원1" class="team-photo">
            <h3>김민규</h3>
            <p class="position">백엔드</p>
            <p class="info">홍익대학교 소프트웨어융합학과<br>C089014 김민규<br>아레스 부회장<br>메타버스 아카데미 2/3기 수료<br></p>
        </div>
    <div class="team-box">
        <img src="image/back.png" alt="팀원2" class="team-photo">
        <h3>김수민</h3>
        <p class="position">백엔드</p>
        <p class="info">홍익대학교 소프트웨어융합학과<br>C089017 김수민<br>HMD 주전 댄서</p>
    </div>
    <div class="team-box">
        <img src="image/front.png" alt="팀원3" class="team-photo">
        <h3>황규범</h3>
        <p class="position">프론트엔드</p>
        <p class="info"> 홍익대학교 소프트웨어융합학과<br>3학년 C093305 황규범<br>WEGIVE frontend<br>담당<br></p>
    </div>
    <div class="team-box">
        <img src="image/front.png" alt="팀원4" class="team-photo">
        <h3>고광우</h3>
        <p class="position">프론트엔드</p>
        <p class="info"> 홍익대학교 소프트웨어융합학과<br>B789002 고광우<br>little frontend<br>Presentation 초안 및 수정<br>먹을 거 사주는 화석</p>
    </div>
</div>

    
    <!-- 푸터를 동적으로 로드 -->
    <?php include 'footer.php'; ?>

<script>
     document.addEventListener("DOMContentLoaded", function () {
    // URL에서 쿼리스트링 제거
    if (window.location.search.includes("code") || window.location.search.includes("state")) {
        const cleanURL = window.location.origin + window.location.pathname;
        window.history.replaceState(null, null, cleanURL);
    }

    // 메인 슬라이더
    const slides = document.getElementById('slides');
    const circleContainer = document.getElementById('circle-container');
    const slideCount = slides.children.length; // 슬라이드의 개수를 얻습니다.
    let currentIndex = 0;

    // 원을 5개 고정으로 생성
    const fixedCircleCount = 5;

    // 기존에 있는 원을 모두 지웁니다 (중복 방지)
    circleContainer.innerHTML = '';

    // 원을 고정 5개로 생성
    for (let i = 0; i < fixedCircleCount; i++) {
        const circle = document.createElement('div');
        circle.className = 'circle';
        if (i === 0) circle.classList.add('active');
        circle.dataset.index = i;
        circle.addEventListener('click', () => {
            clearInterval(slideInterval); // 슬라이드 자동 전환 정지
            changeSlide(i); // 클릭된 원에 해당하는 슬라이드로 이동
            slideInterval = setInterval(autoSlide, 5000); // 자동 전환 다시 시작
        });
        circleContainer.appendChild(circle);
    }

    const circles = document.querySelectorAll('.circle');

    // 슬라이드 변경 함수
    function changeSlide(index) {
        currentIndex = index;
        slides.style.transform = `translateX(${-index * 100}%)`; // 슬라이드 이동

        circles.forEach((circle, i) => {
            circle.classList.toggle('active', i === index); // 활성화된 원 표시
        });
    }

    // 자동 슬라이드 함수
    function autoSlide() {
        currentIndex = (currentIndex + 1) % fixedCircleCount; // 고정된 5개의 원에 맞게 순환
        changeSlide(currentIndex); // 슬라이드 변경
    }

    // 5초마다 자동으로 슬라이드 변경
    let slideInterval = setInterval(autoSlide, 5000);
});


</script>
</body>
</html>