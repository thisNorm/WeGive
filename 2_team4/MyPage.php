<?php
include 'db_connection.php'; // 데이터베이스 연결

session_start(); // 세션 시작

// 로그인 여부 확인
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php'); // 로그인 페이지로 리다이렉트
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role']; // 'users' 또는 'giver'

// 역할에 따라 쿼리를 결정
if ($role === 'users') {
    $sql = "SELECT
        USER_ID, 
        NAME,
         EMAIL,
          TO_CHAR(BIRTH_DAY, 'YYYY-MM-DD') AS BIRTH_DAY 
        FROM
             USERS 
        WHERE 
            USER_ID = :id";

} elseif ($role === 'giver') {
    $sql = "SELECT 
            G.GIVER_ID AS USER_ID, 
            G.NAME, 
            G.EMAIL, 
            TO_CHAR(G.BIRTH_DAY, 'YYYY-MM-DD') AS BIRTH_DAY, 
            S.SKILL_NAME
        FROM 
            GIVER G
        LEFT JOIN 
            SKILL S
        ON 
            G.SKILL_ID = S.SKILL_ID 
        WHERE 
            G.GIVER_ID = :id";
} else {
    die("잘못된 역할입니다.");
}

// 데이터 가져오기
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':id', $userId);
oci_execute($stmt);

// 결과 처리
$user = oci_fetch_assoc($stmt);
if (!$user) {
    die("사용자 정보를 찾을 수 없습니다.");
}

// 리소스 해제
oci_free_statement($stmt);

// --------------- Fetch User's Transactions --------------- //
$transactionSql = "SELECT 
                    T.TRANSACTION_ID, 
                    T.GIVER_ID, 
                    T.USER_ID, 
                    TO_CHAR(T.TRANSACTION_DATE, 'YYYY-MM-DD') AS TRANSACTION_DATE, 
                    T.STATUS 
                   FROM 
                    TRANSACTION T
                   WHERE 
                    (T.USER_ID = :userId OR T.GIVER_ID = :userId)
                   ORDER BY T.TRANSACTION_DATE DESC";

$transactionStmt = oci_parse($conn, $transactionSql);
oci_bind_by_name($transactionStmt, ':userId', $userId);
oci_execute($transactionStmt);

// 결과 처리
$transactions = [];
while ($transaction = oci_fetch_assoc($transactionStmt)) {
    $transactions[] = $transaction;
}

// 리소스 해제
oci_free_statement($transactionStmt);

// 거래 정보 등은 여기에 포함되어 있어야 합니다.
$transaction_id = $transaction['TRANSACTION_ID']; 

// 데이터베이스 연결 종료
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
    <style>
        /* 기본 페이지 스타일 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #fff; /* 기본 배경색 */
        }
        
        .mypage-container1 {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            margin-top: 40px;
            border-radius: 15px;
        }
        .mypage-h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .mypage-form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .mypage-input1 {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        input:disabled {
            background-color: #f9f9f9;
        }
        .mypage-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .mypage-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .mypage-button:hover {
            background-color: #0056b3;
        }
        .mypage-danger-button {
            background-color: #FF4B4B;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .mypage-danger-button:hover {
            background-color: #e63939;
        }
        .mypage-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .mypage-table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .mypage-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .mypage-cancel-btn {
            background-color: #FF4B4B;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .mypage-accept-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .mypage-complete-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .mypage-review-btn {
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 5px;
        }
        .mypage-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="mypage-container1">
    <h2 class="mypage-h2">마이페이지 - <?php echo $role === 'giver' ? '기버' : '사용자'; ?></h2>

    <form id="updateForm" method="POST" action="updateProfile.php">
        <!-- User Profile Information -->
        <div class="mypage-form-group">
            <label for="username">아이디</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['USER_ID'] ?? ''); ?>" class="mypage-input1" disabled>
        </div>
        <div class="mypage-form-group">
            <label for="name">이름</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['NAME'] ?? ''); ?>" class="mypage-input1" disabled>
        </div>
        <div class="mypage-form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL'] ?? ''); ?>" class="mypage-input1" disabled>
        </div>
        <div class="mypage-form-group">
            <label for="birth_day">생일</label>
            <input type="date" id="birth_day" name="birth_day" value="<?php echo htmlspecialchars($user['BIRTH_DAY'] ?? ''); ?>" class="mypage-input1" disabled>
        </div>
        <?php if ($role === 'giver'): ?>
            <div class="mypage-form-group">
                <label for="skill_name">기술</label>
                <input type="text" id="skill_name" name="skill_name" value="<?php echo htmlspecialchars($user['SKILL_NAME'] ?? ''); ?>" class="mypage-input1" disabled>
            </div>
        <?php endif; ?>

    </form>

    <!-- 사용자 정보 수정 및 회원 탈퇴 버튼 -->
    <div class="mypage-button-container">
        <button type="button" class="mypage-button" id="editButton">수정하기</button>
        <button type="submit" form="updateForm" class="mypage-button" id="saveButton" style="display: none;">저장</button>
        <button type="button" class="mypage-danger-button" id="deleteButton">회원 탈퇴</button>
    </div>
</div>

<!-- 거래 리스트 섹션 -->
<div class="mypage-container1">
    <h3 class="mypage-h2">나의 거래</h3>
    <?php 
    // "CANCELLED" 상태가 아닌 거래만 필터링
    $validTransactions = array_filter($transactions, function ($transaction) {
        return $transaction['STATUS'] !== 'CANCELLED';
    });

    // 거래 상태를 한글로 변환하는 함수
    function getStatusInKorean($status) {
        switch ($status) {
            case 'PENDING':
                return '대기 중';
            case 'IN_PROGRESS':
                return '진행 중';
            case 'COMPLETED':
                return '완료';
            case 'CANCELLED':
                return '취소됨';
            default:
                return $status;
        }
    }
    ?>
        

        <?php if (count($validTransactions) > 0): ?>
    <table class="mypage-table">
        <thead>
            <tr>
                <th>거래 번호</th>
                <th>거래일</th>
                <th>기버 ID</th>
                <th>유저 ID</th>
                <th>상태</th>
                <th>작업</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($validTransactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['TRANSACTION_ID']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['TRANSACTION_DATE']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['GIVER_ID']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['USER_ID']); ?></td>
                    <td><?php echo htmlspecialchars(getStatusInKorean($transaction['STATUS'])); ?></td>
                    <td>
                        <?php if ($transaction['STATUS'] === 'PENDING'): ?>
                            <!-- 거래 취소 버튼 -->
                            <form method="POST" action="processtransaction.php" style="display: inline-block;">
                                <input type="hidden" name="transaction_id" value="<?php echo $transaction['TRANSACTION_ID']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="mypage-cancel-btn">거래 취소</button>
                            </form>
                            <!-- 거래 수락 버튼 (기버인 경우) -->
                            <?php if ($role === 'giver'): ?>
                                <form method="POST" action="processtransaction.php" style="display: inline-block;">
                                    <input type="hidden" name="transaction_id" value="<?php echo $transaction['TRANSACTION_ID']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="mypage-accept-btn">거래 수락</button>
                                </form>
                            <?php endif; ?>
                        <?php elseif ($transaction['STATUS'] === 'IN_PROGRESS'): ?>
                            <!-- 거래 취소 버튼 -->
                            <form method="POST" action="processtransaction.php" style="display: inline-block;">
                                <input type="hidden" name="transaction_id" value="<?php echo $transaction['TRANSACTION_ID']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="mypage-cancel-btn">거래 취소</button>
                            </form>
                            <?php if ($role === 'users'): ?>
                            <!-- 거래 완료 버튼 -->
                            <form method="POST" action="processtransaction.php" style="display: inline-block;">
                                <input type="hidden" name="transaction_id" value="<?php echo $transaction['TRANSACTION_ID']; ?>">
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="mypage-complete-btn">거래 완료</button>
                            </form>
                            <?php endif; ?>
                            <?php elseif ($transaction['STATUS'] === 'COMPLETED' && $role === 'users'): ?>
                            <!-- 리뷰가 작성된 경우 버튼을 비활성화하도록 JavaScript로 관리 -->
                            <button type="button" class="mypage-review-btn" data-toggle="modal" data-target="#reviewModal" 
                                    data-transaction_id="<?php echo $transaction['TRANSACTION_ID']; ?>"
                                    id="writeReviewButton-<?php echo $transaction['TRANSACTION_ID']; ?>">
                                리뷰 쓰기
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php include 'reviewModal.php'; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>현재 진행 중인 거래가 없습니다.</p>
<?php endif; ?>


    <script>
        const editButton = document.getElementById('editButton');
        const saveButton = document.getElementById('saveButton');
        const deleteButton = document.getElementById('deleteButton');
        const inputs = document.querySelectorAll('input:not([type="hidden"])');

        // "수정하기" 버튼 클릭 시 입력 필드 활성화
        editButton.addEventListener('click', function () {
            inputs.forEach(input => input.disabled = false);
            deleteButton.style.display = 'none';
            editButton.style.display = 'none';
            saveButton.style.display = 'inline-block';
        });

        // "회원 탈퇴" 버튼 클릭 시 확인
        deleteButton.addEventListener('click', function () {
            if (confirm("정말로 탈퇴하시겠습니까? 이 작업은 되돌릴 수 없습니다.")) {
                window.location.href = "deleteAccount.php";
            }
        });

        // 모달 창 열릴 때 Transaction ID 전달
// MyPage.js
$('#reviewModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // 버튼 클릭 이벤트에서 데이터 추출
    var transaction_id = button.data('transaction_id'); // transaction_id 가져오기
    var modal = $(this); // 현재 모달 참조
    modal.find('.modal-body input[name="transaction_id"]').val(transaction_id); // 모달 내부 hidden input에 값 설정
});

xhr.onload = function () {
    if (xhr.status === 200) {
        const response = xhr.responseText;  // 서버 응답 (메시지만 받을 것임)
        
        // 성공 메시지 출력
        alert(response);  // 성공 또는 실패 메시지 출력

        // 모달 닫기
        $('#reviewModal').modal('hide'); // 모달을 숨김
        $('body').removeClass('modal-open'); // Bootstrap modal-open 클래스 초기화
        $('.modal-backdrop').remove(); // 회색 오버레이 제거
    } else {
        // 네트워크 오류 시
        alert('리뷰 저장 중 오류가 발생했습니다.');  // 네트워크 오류 시 메시지
    }
};

    </script>
</body>
</html>
