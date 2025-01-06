<?php
// 세션 시작
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>팀 소개 - wegive</title>
    <style>
        /* 기본 페이지 스타일 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }

        /* 팀원 소개 영역 */
        .team-intro-page {
            width: 100%;
            margin: 30px auto;
            text-align: center;
        }

        .team-h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            padding-top: 20px; /* 제목 위쪽 여백 */
        }

        /* 팀원 소개 컨테이너 */
        .team-intro {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            flex-wrap: wrap;
            padding: 20px;
            background-color: white;
            justify-content: center; /* 중앙 정렬 */
        }

        /* 팀원 박스 */
        .team-box {
            background-color: #fff;
            width: 20%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            height: 300px;
        }

        /* 팀원 사진 */
        .team-photo {
            width: 50px;
            height: 50px;
            margin-bottom: 5px;
            object-fit: cover;
            border-radius: 50%; /* 원형 사진 */
        }

        /* 팀원 이름 */
        .team-box h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }

        /* 팀원 역할 */
        .position {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        /* 팀원 정보 */
        .info {
            font-size: 14px;
            color: #777;
            line-height: 1.6;
        }

        /* 호버 효과 */
        .team-box:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="team-intro-page">
    <div class="team-h2">
        <h2>Developer</h2>
    </div>
    <div class="team-intro">
        <!-- 팀원1 -->
        <div class="team-box intro-box">
            <img src="image/back.png" alt="팀원1" class="team-photo">
            <h3>김민규</h3>
            <p class="position">백엔드</p>
            <p class="info">홍익대학교 소프트웨어융합학과<br>C089014 김민규<br>아레스 부회장<br>메타버스 아카데미 2/3기 수료</p>
        </div>

        <!-- 팀원2 -->
        <div class="team-box intro-box">
            <img src="image/back.png" alt="팀원2" class="team-photo">
            <h3>김수민</h3>
            <p class="position">백엔드</p>
            <p class="info">홍익대학교 소프트웨어융합학과<br>C089017 김수민<br>HMD 주전 댄서</p>
        </div>

        <!-- 팀원3 -->
        <div class="team-box intro-box">
            <img src="image/front.png" alt="팀원3" class="team-photo">
            <h3>황규범</h3>
            <p class="position">프론트엔드</p>
            <p class="info">홍익대학교 소프트웨어융합학과<br>3학년 C093305 황규범<br>WEGIVE frontend 담당</p>
        </div>

        <!-- 팀원4 -->
        <div class="team-box intro-box">
            <img src="image/front.png" alt="팀원4" class="team-photo">
            <h3>고광우</h3>
            <p class="position">프론트엔드</p>
            <p class="info">팀원4의 간단한 소개 글이 들어갑니다. 역할 및 특기 등 추가 가능.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
