<?php
include 'db_connection.php'; // 데이터베이스 연결

// 스킬 목록 반환
if (isset($_GET['get_skills'])) {
    $sql = "SELECT skill_id, skill_name FROM skill";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    $skills = [];
    while ($row = oci_fetch_assoc($stid)) {
        $skills[] = [
            'id' => $row['SKILL_ID'], // 스킬 ID
            'name' => $row['SKILL_NAME'] // 스킬 이름
        ];
    }
    oci_free_statement($stid);
    oci_close($conn);

    header('Content-Type: application/json');
    echo json_encode($skills);
    exit;
}

// 회원가입 처리
$response = ['success' => false, 'message' => ''];

// 아이디 중복 확인
if (isset($_POST['checkUsername']) && $_POST['checkUsername'] === true) {
    $username = $_POST['username'];

    // 데이터베이스 연결 (이 예시는 OCI를 사용)
    try {
        $sql = "SELECT COUNT(*) FROM users WHERE user_id = :username";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);

        if ($row['COUNT(*)'] == 0) {
            echo json_encode(['isAvailable' => true]);
        } else {
            echo json_encode(['isAvailable' => false]);
        }
        oci_free_statement($stid);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// 일반 회원가입 처리
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$category = $_POST['category'] ?? null;

// 입력값 검증
if (strlen($username) < 6 || strlen($username) > 20) {
    $response['message'] = '아이디는 6~20자여야 합니다.';
    echo json_encode($response);
    exit;
}

if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[!@#$%^&*]/', $password) || strlen($password) < 10) {
    $response['message'] = '비밀번호는 문자, 숫자, 특수문자를 포함해 10자 이상이어야 합니다.';
    echo json_encode($response);
    exit;
}

// 비밀번호 해싱
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    if ($role === 'giver') {
        $sql = "INSERT INTO giver (giver_id, password, name, birth_day, email, skill_id) 
                VALUES (:username, :password, :name, TO_DATE(:birthdate, 'YYYY-MM-DD'), :email, :category)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_bind_by_name($stid, ':password', $hashedPassword);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':birthdate', $birthdate);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':category', $category);

        // 쿼리 실행
        if (!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)) {
            $e = oci_error($stid); // 오류 발생 시 확인
            throw new Exception('에러: ' . $e['message']);
        }

        $response['success'] = true;
        $response['message'] = '회원가입 성공! 나눔러!';
    } elseif ($role === 'receiver') {
        $sql = "INSERT INTO users (user_id, password, name, birth_day, email) 
                VALUES (:username, :password, :name, TO_DATE(:birthdate, 'YYYY-MM-DD'), :email)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_bind_by_name($stid, ':password', $hashedPassword);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':birthdate', $birthdate);
        oci_bind_by_name($stid, ':email', $email);

        // 쿼리 실행
        if (!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)) {
            $e = oci_error($stid); // 오류 발생 시 확인
            throw new Exception('에러: ' . $e['message']);
        }

        $response['success'] = true;
        $response['message'] = '회원가입 성공! 유저!';
    }

    // 회원가입 성공 후 로그인 페이지로 리다이렉트
    echo "<script type='text/javascript'>
            alert('".$response['message']."');  // 알림창으로 회원가입 성공 메시지 출력
            window.location.href = 'login.php';  // 로그인 페이지로 리다이렉트
          </script>";

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = '회원가입 중 오류가 발생했습니다: ' . $e->getMessage();
    echo json_encode($response);  // 오류 메시지를 반환
}
?>
