<header>
    <div class="header-container">
        <!-- 로고 -->
        <div class="header-logo" onclick="location.href='index.php'">
            <img src="image/jjinlogo.png" alt="로고">
        </div>

        <!-- 네비게이션 버튼 -->
        <nav class="header-nav-buttons">

            <div class="header-nav-item">
                소개
                <div class="header-dropdown">
                    <div class="header-dropdown-column">
                        <h3>위기브 소개</h3>
                        <a href="wegivepage.php">회사 정보</a>
                        <a href="team.php">팀 소개</a>
                    </div>
                </div>
            </div>
            
            <!-- 구분선 사이 -->
            <div class="header-divider"></div>

            <div class="header-nav-item">
                카테고리
                <div class="header-dropdown">
                    <div class="header-dropdown-column">
                        <h3>카테고리</h3>
                        <a href="codingpost.php">코딩</a>
                        <a href="designpost.php">디자인</a>
                        <a href="videopost.php">영상</a>
                        <a href="marketingpost.php">마케팅</a>
                    </div>
                </div>
            </div>

        
        </nav>

        <!-- 검색창 -->
        <div class="header-search-container">
            <form id="searchForm" action="search.php" method="get" onsubmit="return validateSearch()">
                <input type="text" name="query" id="searchQuery" placeholder="검색어를 입력하세요">
                <button type="submit">검색</button>
                <div id="errorMessage" class="header-error-message">한 글자 이상 입력해야 합니다.</div>
            </form>
        </div>

        <!-- 로그인/로그아웃 섹션 -->
        <?php if (isset($_SESSION['user_id'])) { ?>
            <div class="header-user-actions">
                <button class="header-mypage-btn" onclick="location.href='MyPage.php'">마이페이지</button>
                <button class="header-logout-btn" onclick="location.href='logout.php'">로그아웃</button>
            </div>
        <?php } else { ?>
            <div class="header-auth-buttons">
                <button class="header-login-btn" onclick="location.href='login.php'">로그인</button>
                <button class="header-signup-btn" onclick="location.href='signup.php'">회원가입</button>
            </div>
        <?php } ?>
    </div>
</header>


<style>
/* 헤더 스타일 */
header {
    background-color: #ffffff;
    
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 3px solid #E4E5ED;
}

/* 헤더 컨테이너 */
header .header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    height: 80px;
    padding: 0 5px;
}

/* 로고 스타일 */
.header-logo img {
    height: 60px;
    cursor: pointer;
}

/* 네비게이션 버튼 스타일 */
/* 네비게이션 버튼들 사이의 구분선 */
.header-nav-buttons {
    display: flex;
    justify-content: flex-start;
    gap: 60px;
    align-items: center; /* 버튼들이 수평으로 맞춰지도록 추가 */
}

.header-divider {
    width: 2px;
    height: 29px; /* 원하는 높이 */
    background-color: #E4E5ED; /* 구분선 색상 */
    margin: 5 5px; /* 버튼 간격 */
}

.header-nav-item {
    position: relative;
    white-space: nowrap;
    font-size: 17px;
    cursor: pointer;
    z-index: 100;
}

.header-nav-item:hover .header-dropdown {
    height: auto;
    max-height: 300px;
}

/* 드롭다운 전체 스타일 */
.header-dropdown {
    position: absolute;
        top: 51px; / 헤더 바로 아래 /
        left: 50%; / 부모 기준 중앙 /
        transform: translateX(-5%); / 중앙에서 왼쪽으로 이동 /
        width: 100vw;
        height: 0;
        background-color: #2c2a29;
        color: white;
        overflow: hidden;
        transition: height 0.5s ease;
        display: flex;
        gap: 30px;
        padding: 0 50px;
    }
/* 드롭다운 열리는 효과 */
.header-nav-item:hover .header-dropdown {
    display: flex; /* 마우스 호버 시 표시 */
    transition: all 0.3s ease-in-out; /* 부드러운 열림 효과 */
}

/* 드롭다운 내부 레이아웃 */
.header-dropdown-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* 드롭다운 제목 스타일 */
.header-dropdown-column h3 {
    margin-bottom: 10px;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
}

/* 드롭다운 링크 스타일 */
.header-dropdown-column a {
    text-decoration: none;
    color: #ffffff;
    font-size: 14px;
    transition: color 0.3s ease;
}

.header-dropdown-column a:hover {
    color: #01704a;
}

/* 드롭다운 활성화 */
.header-nav-item:hover .header-dropdown {
    height: 200px; /* 드롭다운이 펼쳐질 높이 */
}

/* 검색창 및 오류 메시지 스타일 */
.header-search-container {
    display: flex;
    flex-direction: column; /* 검색창과 메시지를 세로로 정렬 */
    align-items: flex-start; /* 왼쪽 정렬 */
    gap: 8px; /* 검색창과 오류 메시지 사이 간격 */
}

.header-error-message {
    color: red;
    font-size: 12px;
    display: none; /* 기본적으로 숨김 */
}

/* form 기본 여백과 패딩을 제거 */
.header-search-container form {
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    border-bottom: 2px solid #007bff;
    width: 100%;
    min-width: 300px;
    max-width: 600px;
}

/* input 기본 여백 및 패딩 제거 */
.header-search-container input {
    border: none;
    outline: none;
    padding: 5px;
    flex-grow: 1;
    font-size: 14px;
    margin: 0; /* 불필요한 여백 제거 */
}

/* button 기본 여백 및 패딩 제거 */
.header-search-container button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    margin: 0; /* 불필요한 여백 제거 */
}

.header-search-container button:hover {
    background-color: #0056b3;
}

/* 로그인/로그아웃 섹션 */
.header-user-actions {
    display: flex;
    gap: 10px;
}

.header-login-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.header-login-btn:hover {
    background-color: #0056b3;
}

.header-signup-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.header-signup-btn:hover {
    background-color: #0056b3;
}

.header-mypage-btn {
    background-color: #007bff; /* 파란색 */
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.header-mypage-btn:hover {
    background-color: #0056b3;
}

.header-logout-btn {
    background-color: #FF4B4B; /* 붉은색 */
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.header-logout-btn:hover {
    background-color: #e03e3e;
}
</style>
