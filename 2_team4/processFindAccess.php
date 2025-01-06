<?php
include 'db_connection.php'; // Oracle DB 연결

// POST 요청으로 데이터 받기
$email = $_POST['email'] ?? null;
$name = $_POST['name'] ?? null;
$role = $_POST['role'] ?? null; // 나눔러(giver) 또는 유저(user)

// 입력값 검증
if (empty($email) || empty($name) || empty($role)) {
    echo "<script>
        alert('이메일, 이름, 그리고 역할(기버 또는 유저)을 모두 입력 또는 선택해주세요.');
        window.history.back();
    </script>";
    exit();
}

try {
    // 결과 저장 변수 초기화
    $ids = []; // 다수의 ID를 저장할 배열

    if ($role === 'giver') {
        // givers 테이블 검색
        $sql_givers = "SELECT GIVER_ID FROM giver WHERE UPPER(EMAIL) = UPPER(:email) AND UPPER(NAME) = UPPER(:name)";
        $stid_givers = oci_parse($conn, $sql_givers);
        oci_bind_by_name($stid_givers, ':email', $email);
        oci_bind_by_name($stid_givers, ':name', $name);
        oci_execute($stid_givers);

        // 결과 반복 처리
        while ($row_givers = oci_fetch_assoc($stid_givers)) {
            $ids[] = $row_givers['GIVER_ID'];
        }
    } elseif ($role === 'user') {
        // users 테이블 검색
        $sql_users = "SELECT USER_ID FROM users WHERE UPPER(EMAIL) = UPPER(:email) AND UPPER(NAME) = UPPER(:name)";
        $stid_users = oci_parse($conn, $sql_users);
        oci_bind_by_name($stid_users, ':email', $email);
        oci_bind_by_name($stid_users, ':name', $name);
        oci_execute($stid_users);

        // 결과 반복 처리
        while ($row_users = oci_fetch_assoc($stid_users)) {
            $ids[] = $row_users['USER_ID'];
        }
    }

    // 결과 HTML 출력
    echo "<!DOCTYPE html>
    <html lang='ko'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>계정 찾기 결과</title>
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
                text-align: center;
            }
            h2 {
                margin-bottom: 20px;
            }
            p {
                font-size: 16px;
                margin: 10px 0;
            }
            .button {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                margin-top: 20px;
                text-decoration: none;
            }
            .button:hover {
                background-color: #0056b3;
            }
            li {
                font-size: 16px;
                margin: 10px 0;
                text-align: left; /* 왼쪽 정렬 */
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>계정 찾기 결과</h2>";

    if (!empty($ids)) {
        echo "<p><strong>찾으신 ID:</strong></p>";
        echo "<ul>";
        foreach ($ids as $id) {
            echo "<li>$id</li>";
        }
        echo "</ul>";
        echo "<a href='login.php' class='button'>로그인 페이지로 이동</a>";
    } else {
        echo "<p>입력한 정보와 일치하는 계정을 찾을 수 없습니다.</p>";
        echo "<a href='findAccount.php' class='button'>다시 찾기</a>";
    }

    echo "</div>
    </body>
    </html>";
} catch (Exception $e) {
    echo "<script>
        alert('오류가 발생했습니다. 다시 시도해주세요.');
        window.history.back();
    </script>";
} finally {
    // 자원 정리
    if (isset($stid_users)) oci_free_statement($stid_users);
    if (isset($stid_givers)) oci_free_statement($stid_givers);
    oci_close($conn);
}
?>
