<style>
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        height: 80px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        box-sizing: border-box;
        z-index: 1000;
    }

    .logo img {
        height: 100%;
        max-height: 250px;
        cursor: pointer;
    }

    .search-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 2px solid #0072B8;
        padding: 5px 0;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .search-bar input {
        border: none;
        outline: none;
        flex-grow: 1;
        font-size: 16px;
        color: #555;
    }

    .search-bar input::placeholder {
        color: #aaa;
    }

    .logout-button {
        background-color: #FF4B4B; /* 로그아웃 버튼의 색상 */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .logout-button:hover {
        background-color: #e63939;
    }

    .navigation-buttons {
        display: flex;
        flex: 2;
        justify-content: center;
        gap: 10px;
    }

    .nav-button {
        padding: 10px 20px;
        font-size: 14px;
        cursor: pointer;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .nav-button:hover {
        background-color: #e0e0e0;
    }
</style>

<header>
    <div class="logo" onclick="location.href='index.php'">
        <img src="image/LOGO_removed.png" alt="로고">
    </div>
    <div class="search-bar">
    <form action="search_results.php" method="GET">
        <input type="text" name="query" placeholder="제목, 판매자 ID, 스킬 이름으로 검색하세요">
        <button type="submit" style="display: none;">검색</button>
    </form>
</div>

    <div class="navigation-buttons">
        <button class="nav-button" onclick="location.href='about.php'">소개</button>
        <button class="nav-button" onclick="location.href='reviews.php'">리뷰</button>
        <button class="nav-button" onclick="location.href='categories.php'">카테고리</button>
    </div>
    <div class="logout-section">
        <button class="logout-button" onclick="location.href='logout.php'">로그아웃</button>
    </div>
</header>
