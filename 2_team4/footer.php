<style>
    /* 푸터 스타일 */
    footer {
        background-color: rgb(31, 41, 55); /* 배경색을 rgb(31, 41, 55)로 설정 */
        display: flex; /* flexbox 사용 */
        flex-direction: column; /* 세로 정렬 */
        justify-content: center; /* 세로 중앙 정렬 */
        align-items: center; /* 가로 중앙 정렬 */
        padding: 20px 0; /* 위아래 여백 */
        width: 100%; /* 푸터 너비 100% */
        box-sizing: border-box; /* 테두리 및 패딩 포함하여 크기 계산 */
        height: 120px; /* 푸터 높이 */
    }

    /* 첫 번째 줄: 저작권 텍스트 스타일 */
    footer .copyright {
        color: white; /* 글씨 색상 검정으로 설정 */
        font-size: 20px; /* 글자 크기 */
        font-weight: normal; /* 폰트 굵기 */
        line-height: 1.0; /* 줄 높이 */
        margin-bottom: 20px;
    }

    /* 두 번째 줄: 링크들 스타일 */
    footer .links {
        display: flex; /* 가로로 배치 */
        justify-content: center; /* 중앙 정렬 */
        gap: 20px; /* 링크 간 간격 */
    }

    /* 두 번째 줄 링크 기본 스타일 */
    footer .links a {
        color: rgb(156, 163, 175); /* 링크 색상을 회색으로 설정 */
        text-decoration: none; /* 밑줄 제거 */
        font-size: 13px; /* 링크 글자 크기 */
        line-height: 1.5; /* 줄 높이 */
    }

    /* 링크 hover 스타일 */
    footer .links a:hover {
        color: white; /* 호버 시 색상을 흰색으로 변경 */
    }
</style>

<footer>
    <!-- 첫 번째 줄: 저작권 표시 -->
    <p class="copyright">© 2024 WEGIVE, All rights reserved.</p>
    
    <!-- 두 번째 줄: 링크들 (Contact Us, Follow us on Instagram) -->
    <div class="links">
        <a href="mailto:kisook2557@gmail.com">Contact Us</a>
        <a href="https://www.instagram.com/hongik_sw_official?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">Follow us on Instagram</a>
    </div>
</footer>
