<!-- 리뷰 쓰기 모달 -->
<div id="reviewModal" class="modal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">리뷰 작성</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm" action="submitReview.php" method="POST">
                    <div>
                        <label for="rating">별점:</label>
                        <div id="rating-stars">
                            <!-- 0점부터 5점까지 별 생성 -->
                            <div class="star-container" data-value="1">
                                <span class="star">★</span>
                            </div>
                            <div class="star-container" data-value="2">
                                <span class="star">★</span>
                            </div>
                            <div class="star-container" data-value="3">
                                <span class="star">★</span>
                            </div>
                            <div class="star-container" data-value="4">
                                <span class="star">★</span>
                            </div>
                            <div class="star-container" data-value="5">
                                <span class="star">★</span>
                            </div>
                            <input type="hidden" name="rating" id="rating"> <!-- 별점 값 저장 -->
                        </div>
                    </div>
                    <div>
                        <label for="review_comment">리뷰 내용:</label>
                        <textarea name="review_comment" id="review_comment" rows="4" placeholder="리뷰를 작성하세요" required></textarea>
                    </div>

                    <div>
                        <input type="hidden" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>">
                        <button type="submit" class="btn btn-primary">리뷰 제출</button>
                    </div>
                </form>

                <!-- 성공/실패 메시지 -->
                <div id="messageDiv" class="mt-3 text-center"></div>
            </div>
        </div>
    </div>
</div>

<!-- 성공 메시지 모달 -->
<div id="successModal" class="modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">리뷰 작성 완료</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="successMessage">
                <!-- 성공 메시지가 여기에 표시됩니다 -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="successModalClose" data-bs-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>

<style>
#rating-stars {
    display: inline-block;
    cursor: pointer;
}

.star-container {
    display: inline-block;
    position: relative;
    width: 20px; /* 별 하나의 너비 */
    height: 30px; /* 별 하나의 높이 */
    margin-right: 5px; /* 별 간 간격 */
}

.star {
    font-family: Arial, sans-serif; /* 폰트를 명시적으로 지정 */
    font-size: 30px; /* 별 크기 */
    color: #ccc; /* 기본 회색 */
    cursor: pointer;
}

.star.selected {
    color: #ffc107; /* 선택된 별의 노란색 */
}

#review_comment {
    width: 100%;
    min-height: 100px; /* 최소 높이 설정 */
    font-size: 14px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical; /* 수직으로 크기 조정 가능 */
    overflow-y: auto; /* 텍스트가 길어질 때 스크롤이 생기도록 */
}

.modal-body form button {
    background-color: #f9c74f;
    color: white;
    padding: 12px 30px;
    font-size: 16px; /* 글자 크기 늘리기 */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px; /* 버튼 위 간격 추가 */
}

.modal-body form button:hover {
    background-color: #e8b82d;
}

label {
    margin-bottom: 5px; /* 레이블과 입력 칸 사이 간격 줄임 */
}

/* 비활성화된 버튼 스타일 */
button.disabled {
    background-color: #ccc;  /* 회색 배경 */
    color: #666;             /* 회색 글자 */
    border: 1px solid #ccc;  /* 회색 테두리 */
    cursor: not-allowed;     /* 클릭할 수 없다는 커서 표시 */
}
</style>

<script>
document.querySelectorAll('.star-container').forEach(star => {
    star.addEventListener('mouseover', function () {
        const value = parseInt(this.getAttribute('data-value'));
        highlightStars(value);
    });

    star.addEventListener('mouseout', function () {
        resetStars();
        highlightStars(parseInt(document.getElementById('rating').value || 0));
    });

    star.addEventListener('click', function () {
        const value = parseInt(this.getAttribute('data-value'));
        document.getElementById('rating').value = value;
        highlightStars(value);
        console.log(`Selected Rating: ${value}`);
    });
});

function highlightStars(value) {
    resetStars();
    document.querySelectorAll('.star-container').forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'));
        if (starValue <= value) {
            star.querySelector('.star').classList.add('selected');
        }
    });
}

function resetStars() {
    document.querySelectorAll('.star').forEach(star => {
        star.classList.remove('selected');
    });
}

// 리뷰 폼 제출 이벤트 처리
document.getElementById('reviewForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submitReview.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // 성공 메시지 표시
                const messageDiv = document.getElementById('messageDiv');
                messageDiv.textContent = response.message;

                // 모달 닫기 - jQuery 사용 (Bootstrap 4)
                $('#reviewModal').modal('hide');

                // 리뷰 제출 후 '리뷰 쓰기' 버튼을 비활성화
                const reviewButton = document.querySelector(`button[data-transaction_id="${response.transaction_id}"]`);
                if (reviewButton) {
                    reviewButton.disabled = true;  // 버튼 비활성화
                    reviewButton.classList.add('disabled');  // 'disabled' 클래스 추가
                    reviewButton.textContent = '작성 완료';  // 버튼 텍스트 변경

                    // 상태를 localStorage에 저장
                    localStorage.setItem(`review_button_${response.transaction_id}`, 'disabled');
                }
            } else {
                alert(response.message); // 오류 메시지 표시
            }
        } else {
            alert('리뷰 저장 중 오류가 발생했습니다.');
        }
    };

    xhr.onerror = function () {
        alert('네트워크 오류가 발생했습니다.');
    };

    xhr.send(formData);
});

window.addEventListener('load', function () {
    // 페이지에 있는 모든 리뷰 버튼을 확인
    const reviewButtons = document.querySelectorAll('button[data-transaction_id]');

    reviewButtons.forEach(function (button) {
        const transactionId = button.getAttribute('data-transaction_id');
        
        // localStorage에서 저장된 버튼 상태 확인
        const buttonState = localStorage.getItem(`review_button_${transactionId}`);

        if (buttonState === 'disabled') {
            button.disabled = true;
            button.classList.add('disabled');
            button.textContent = '작성 완료';  // 버튼 텍스트 변경
        }
    });
});

</script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS, jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- jQuery full 버전 사용 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
