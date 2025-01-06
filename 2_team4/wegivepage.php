<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회사 소개 - wegive</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            
            margin: 0;
            padding: 0;
        }
        
        .wegive-content {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .wegive-h1 {
            color: #333;
            font-size:40px;
        }
        .wegive-h2 {
            color: #333;
        }
        .wegive-p {
            font-size: 1.1em;
        }
        .wegive-vision, .wegive-mission, .wegive-team {
            margin-bottom: 30px;
        }
        .wegive-footer {
            text-align: center;
            padding: 10px 0;
            background: white;
            color: black;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    

    <div class="wegive-content">
        <section class="wegive-about">
        <img src="image/wegivelogomain.png" alt="wegive 로고">
            <h1 class="wegive-h1">wegive</h1>
            <h2 class="wegive-h2">회사 소개</h2>
            <p class="wegive-p">wegive는 청년들의 경력 개발과 실무 경험을 위한 기회를 제공하는 혁신적인 플랫폼입니다. 우리는 대학생들이 실무 경험을 쌓고, 자유롭게 포트폴리오를 구축할 수 있도록 돕고 있습니다. 우리의 비전은 청년이 자신의 능력을 자유롭게 펼칠 수 있는 기회를 제공하는 것입니다.</p>
        </section>

        <section class="wegive-vision">
            <h2 class="wegive-h2">비전</h2>
            <p class="wegive-p">wegive는 모든 청년들이 실무 경험을 통해 자신의 역량을 증명하고, 업체와의 연결을 통해 더 나은 미래를 준비할 수 있도록 돕고 있습니다. 이를 위해 유연한 경험 쌓기와 재능 공유의 장을 마련하고 있습니다.</p>
        </section>

        <section class="wegive-mission">
            <h2 class="wegive-h2">미션</h2>
            <p class="wegive-p">우리는 자유롭게 경험을 쌓을 수 있는 플랫폼을 제공하여, 학생들이 실제 업무를 경험하고, 그 과정에서 능력을 키울 수 있도록 지원합니다. 또한, 유저들이 비용 부담 없이 다양한 서비스를 요청하고, 학생들은 자신의 능력을 증명할 수 있는 기회를 얻습니다.</p>
        </section>

        <section class="wegive-team">
            <h2 class="wegive-h2">팀</h2>
            <p class="wegive-p">wegive는 대학생들로 구성된 팀입니다. 다양한 분야에서 배운 지식과 경험을 바탕으로 청년들이 실무 경험을 쌓을 수 있도록 돕기 위해 이 플랫폼을 만들었습니다. 우리는 청년들이 재능을 나누며 함께 성장할 수 있도록 항상 노력하고 있습니다.</p>
        </section>
    </div>

    <footer class="wegive-footer">
        <?php include 'footer.php'; ?>    
    </footer>

</body>
</html>
