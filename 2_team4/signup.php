    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>회원가입</title>
        <style>
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
                position: relative;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input, select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            .error-message {
                color: red;
                font-size: 12px;
                margin-top: 5px;
                display: none; /* 기본적으로 숨김 */
            }

            .role-container {
                display: flex;
                flex-direction: column;
                gap: 10px; /* 역할 선택과 다른 요소 간 간격 */
            }

            .role-option {
                display: flex;
                align-items: center; /* 요소 수직 중앙 정렬 */
                gap: 15px; /* 나눔러와 드롭다운 사이 간격 */
            }

            .role-option select {
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #f9f9f9;
                max-width: 100px; /* 드롭다운 최소 너비 */
            }

            .role-option input[type="radio"] {
                width: 16px;
                height: 16px;
                margin-right: 5px;
            }

            .role-option label {
                line-height: 1; /* 라디오 버튼 옆 텍스트와 드롭다운 정렬 */
                margin: 0;
            }

            .button-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }

            .button {
                background-color: #f9c74f;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
            }

            .button:hover {
                background-color: #e8b82d;
            }

            .cancel-button {
                background-color: #ccc;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .cancel-button:hover {
                background-color: #bbb;
            }

            /* 중복 확인 버튼 스타일 */
            #checkUsernameBtn {
                position: absolute;
                top: 70%;
                right: 10px;
                transform: translateY(-50%); /* 버튼을 입력칸 가운데로 정렬 */
                background-color: #ff7f50;
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                z-index: 10; /* 버튼이 입력란 위에 오도록 */
            }

            /* 클릭 후 UI 상에서 움직이지 않게 고정 */
            #checkUsernameBtn:active {
                transform: translateY(-50%); /* 눌러도 움직이지 않게 고정 */
                background-color: #e64a19;
            }

            #checkUsernameBtn:hover {
                background-color: #ff6f00;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>회원가입</h2>
            <form id="signupForm" action="process_signup.php" method="POST">
                <!-- 아이디 입력 -->
                <div class="form-group">
                    <label for="username">아이디</label>
                    <input type="text" id="username" name="username" placeholder="아이디 입력 (6-20자)" required>
                    <button type="button" id="checkUsernameBtn" class="button">중복 확인</button>
                    <div class="error-message" id="usernameError">아이디는 6~20자로 입력해주세요.</div>
                    <div class="success-message" id="usernameSuccess" style="display: none; color: green;">사용 가능한 아이디입니다.</div>
                </div>

                <!-- 비밀번호 입력 -->
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" placeholder="비밀번호 입력" required>
                    <div class="error-message" id="passwordError">비밀번호 조건에 맞지 않습니다.</div>
                </div>

                <!-- 비밀번호 확인 -->
                <div class="form-group">
                    <label for="confirmPassword">비밀번호 확인</label>
                    <input type="password" id="confirmPassword" placeholder="비밀번호 확인" required>
                    <div class="error-message" id="confirmPasswordError">비밀번호가 일치하지 않습니다.</div>
                </div>

                <!-- 이름 입력 -->
                <div class="form-group">
                    <label for="name">이름</label>
                    <input type="text" id="name" name="name" placeholder="이름을 입력해주세요" required>
                    <div class="error-message" id="nameError">이름을 입력해주세요.</div>
                </div>

                <!-- 이메일 입력 -->
                <div class="form-group">
                    <label for="email">이메일</label>
                    <input type="email" id="email" name="email" placeholder="이메일 입력" required>
                    <div class="error-message" id="emailError">유효한 이메일 주소를 입력해주세요.</div>
                </div>

                <!-- 생년월일 입력 -->
                <div class="form-group">
                    <label for="birthdate">생년월일</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                    <div class="error-message" id="birthdateError">생년월일을 선택해주세요.</div>
                </div>

                <div class="form-group role-container">
                    <label>역할</label>
                    <div class="role-option">
                        <input type="radio" id="giver" name="role" value="giver" required>
                        <label for="giver">기버</label>
                        <!-- 나눔러 오른쪽에 재능 선택 -->
                        <select id="category" name="category" disabled>
                            <option value="">재능</option> <!-- 기본값 -->
                        </select>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="receiver" name="role" value="receiver" required>
                        <label for="receiver">유저</label>
                    </div>
                    <div class="error-message" id="roleError">역할을 선택해주세요.</div>
                </div>

                <!-- 버튼 -->
                <div class="button-container">
                    <button type="submit" class="button">가입하기</button>
                    <button type="button" class="cancel-button" onclick="location.href='login.php'">가입취소</button>
                </div>
            </form>
        </div>

        <script>
        // 나눔러 선택 시 드롭다운 활성화 및 서버에서 스킬 목록 가져오기
        document.getElementById('giver').addEventListener('change', function () {
            const categoryDropdown = document.getElementById('category');
            categoryDropdown.disabled = false; // 드롭다운 활성화
            categoryDropdown.classList.add('active'); // 선택 사항: 활성화 시 스타일 적용

            // 서버에서 스킬 목록 가져오기
            fetch('process_signup.php?get_skills=true')
                .then(response => response.json())
                .then(data => {
                    // 드롭다운 초기화
                    categoryDropdown.innerHTML = '<option value="">재능</option>';

                    // 서버에서 받은 스킬 목록 추가
                    data.forEach(skill => {
                        const option = document.createElement('option');
                        option.value = skill.id; // 스킬 ID
                        option.textContent = skill.name; // 스킬 이름
                        categoryDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('스킬 목록 가져오기 실패:', error);
                    alert('스킬 목록을 불러오는 중 문제가 발생했습니다.');
                });
            });

        // 나눔인 선택 시 드롭다운 비활성화
        document.getElementById('receiver').addEventListener('change', function () {
            const categoryDropdown = document.getElementById('category');
            categoryDropdown.disabled = true; // 드롭다운 비활성화
            categoryDropdown.classList.remove('active'); // 선택 사항: 비활성화 시 스타일 제거
            categoryDropdown.innerHTML = '<option value="">재능</option>'; // 선택값 초기화
        });
        
// 유효성 검사 함수
function validateForm() {
    let isValid = true;

    if (!validateUsername()) isValid = false;
    if (!validatePassword()) isValid = false;
    if (!validateConfirmPassword()) isValid = false;
    if (!validateName()) isValid = false;
    if (!validateEmail()) isValid = false;
    if (!validateBirthdate()) isValid = false;

    return isValid;
}

// 아이디 유효성 검사
function validateUsername() {
    const username = document.getElementById('username').value;
    const usernameError = document.getElementById('usernameError');
    if (username.length < 6 || username.length > 20) {
        usernameError.style.display = 'block';
        return false;
    } else {
        usernameError.style.display = 'none';
        return true;
    }
}

// 비밀번호 유효성 검사
function validatePassword() {
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('passwordError');
    if (password.length < 6) {
        passwordError.style.display = 'block';
        return false;
    } else {
        passwordError.style.display = 'none';
        return true;
    }
}

// 비밀번호 확인 유효성 검사
function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    if (password !== confirmPassword) {
        confirmPasswordError.style.display = 'block';
        return false;
    } else {
        confirmPasswordError.style.display = 'none';
        return true;
    }
}

// 이름 유효성 검사
function validateName() {
    const name = document.getElementById('name').value;
    const nameError = document.getElementById('nameError');
    if (!name) {
        nameError.style.display = 'block';
        return false;
    } else {
        nameError.style.display = 'none';
        return true;
    }
}

// 이메일 유효성 검사
function validateEmail() {
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        emailError.style.display = 'block';
        return false;
    } else {
        emailError.style.display = 'none';
        return true;
    }
}

// 생년월일 유효성 검사
function validateBirthdate() {
    const birthdate = document.getElementById('birthdate').value;
    const birthdateError = document.getElementById('birthdateError');
    if (!birthdate) {
        birthdateError.style.display = 'block';
        return false;
    } else {
        birthdateError.style.display = 'none';
        return true;
    }
}

// 각 입력칸의 blur 및 input 이벤트 처리
document.getElementById('username').addEventListener('blur', function() {
    validateUsername();
});
document.getElementById('username').addEventListener('input', function() {
    document.getElementById('usernameError').style.display = 'none';
});

document.getElementById('password').addEventListener('blur', function() {
    validatePassword();
});
document.getElementById('password').addEventListener('input', function() {
    document.getElementById('passwordError').style.display = 'none';
});

document.getElementById('confirmPassword').addEventListener('blur', function() {
    validateConfirmPassword();
});
document.getElementById('confirmPassword').addEventListener('input', function() {
    document.getElementById('confirmPasswordError').style.display = 'none';
});

document.getElementById('name').addEventListener('blur', function() {
    validateName();
});
document.getElementById('name').addEventListener('input', function() {
    document.getElementById('nameError').style.display = 'none';
});

document.getElementById('email').addEventListener('blur', function() {
    validateEmail();
});
document.getElementById('email').addEventListener('input', function() {
    document.getElementById('emailError').style.display = 'none';
});

document.getElementById('birthdate').addEventListener('blur', function() {
    validateBirthdate();
});
document.getElementById('birthdate').addEventListener('input', function() {
    document.getElementById('birthdateError').style.display = 'none';
});

// 중복 확인 버튼 클릭 시
document.getElementById('checkUsernameBtn').addEventListener('click', function() {
    const username = document.getElementById('username').value;
    if (!validateUsername()) {
        alert("아이디 조건을 만족해주세요.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_username.php?check_username=' + encodeURIComponent(username), true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            if (response.exists) {
                alert("이미 사용중인 아이디입니다.");
            } else {
                alert("사용 가능한 아이디입니다.");
            }
        }
    };

    xhr.send();
});

// 폼 제출 시 유효성 검사
document.getElementById('signupForm').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault(); // 유효성 검사 실패 시 폼 제출 방지
    }
});

        </script>
    </body>
    </html>
