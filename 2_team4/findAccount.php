<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>아이디/비밀번호 찾기</title>
    <style>
        /* 기본적인 페이지 스타일 설정 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="email"], input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .radio-group {
            display: flex;
            justify-content: flex-start; /* 왼쪽 정렬 */
            gap: 10px; /* 요소 간 간격 조정 */
            margin-top: 10px; /* 상단 간격 줄이기 */
        }

        .radio-group label {
            display: flex;
            align-items: center;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>아이디 찾기</h2>
        <form id="findAccountForm" action="/2_team/2_team4/2_team4/processFindAccess.php" method="POST">
            <div class="form-group">
                <label for="email">이메일 주소</label>
                <input type="email" id="email" name="email" placeholder="가입 시 사용한 이메일 주소" required>
            </div>
            <div class="form-group">
                <label for="name">이름</label>
                <input type="text" id="name" name="name" placeholder="가입 시 사용한 이름" required>
            </div>
            <div class="form-group">
                <label>역할 선택</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="role" value="giver" required> 기버
                    </label>
                    <label>
                        <input type="radio" name="role" value="user" required> 유저
                    </label>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="button">찾기</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('findAccountForm').addEventListener('submit', function(event) {
            const email = document.getElementById('email').value.trim();
            const name = document.getElementById('name').value.trim();
            const roleSelected = document.querySelector('input[name="role"]:checked');

            if (!email || !name || !roleSelected) {
                event.preventDefault(); // 기본 동작 중단
                alert('이메일, 이름, 역할을 모두 입력해주세요.');
            }
        });
    </script>
</body>
</html>
