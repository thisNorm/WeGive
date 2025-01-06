<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 페이지</title>
    <style>
        /* 기본 body 스타일 설정 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* 로그인 박스 컨테이너 스타일 */
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        /* 제목 스타일 */
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 20px;
            line-height: 1.2;
        }

        /* 입력 필드 스타일 */
        .input-field {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        /* 입력 필드 포커스 시 효과 */
        .input-field:focus {
            border-color: #f9c74f;
            outline: none;
        }

        /* 로그인 버튼 스타일 */
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #f9c74f;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            margin-top: 10px;
        }

        /* 로그인 버튼 호버 효과 */
        .login-button:hover {
            background-color: #f9b84c;
        }

        /* 회원가입 컨테이너 스타일 */
        .signup-container {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        /* 회원가입 버튼 스타일 */
        .signup-button {
            background: none;
            border: none;
            color: #007BFF;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            padding: 0;
        }

        /* 회원가입 버튼 호버 효과 */
        .signup-button:hover {
            text-decoration: underline;
        }

        /* 비밀번호 찾기 스타일 */
        .find-password {
            margin-top: 10px;
            font-size: 14px;
        }

        /* 비밀번호 찾기 링크 스타일 */
        .find-password a {
            color: #f9c74f;
            font-weight: bold;
            text-decoration: none;
        }

        /* 비밀번호 찾기 링크 호버 효과 */
        .find-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- 로그인 설명 -->
    <h2>활동 참가를 위해 로그인해 주세요</h2>
    <!-- 로그인 폼 -->
    <form action="/2_team/2_team4/2_team4/processLogin.php" method="POST">
        <!-- 이메일 입력 -->
        <input type="text" class="input-field" name="email" placeholder="이메일 혹은 아이디" required>
        <!-- 비밀번호 입력 -->
        <input type="password" class="input-field" name="password" placeholder="비밀번호" required>
        <!-- 로그인 버튼 -->
        <button type="submit" class="login-button">로그인</button>
    </form>

    <!-- 회원가입 섹션 -->
    <div class="signup-container">
        <span>WEGIVES 회원이 아니신가요?</span>
        <a href="signup.php" class="signup-button">회원가입</a>
    </div>

    <!-- 아이디 찾기 섹션 -->
    <div class="find-password">
        <p>아이디를 잊으셨나요? 
            <a href="findAccount.php">찾기</a>
        </p>
    </div>
</div>

</body>
</html>
