<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Ödeme';
$pageDescription = 'Kart ile ödeme işlemi';
$currentPage = 'payment';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<style>
    /* ── Findeks-style checkout background ── */
    .findeks-bg {
        background: #ffffff;
        position: relative;
    }
    .dark .findeks-bg {
        background: #0a0a0a;
    }

    /* Right-side decorative gradient panel — mirrors Findeks checkout footer */
    .findeks-deco {
        background: linear-gradient(160deg,
            #0056A4 0%,
            #0070cc 35%,
            #0056A4 60%,
            #003d7a 100%);
        position: relative;
        overflow: hidden;
    }
    .findeks-deco::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 110% 20%, rgba(255,255,255,0.12) 0%, transparent 60%),
            radial-gradient(ellipse 60% 80% at -10% 80%, rgba(0,30,80,0.35) 0%, transparent 60%);
        pointer-events: none;
    }
    /* Subtle dot-grid overlay */
    .findeks-deco::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    /* Card flip / 3-D tilt effect */
    .card-preview {
        perspective: 1000px;
    }
    .card-inner {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        position: relative;
    }
    .card-inner.flipped {
        transform: rotateY(180deg);
    }
    .card-face {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .card-back {
        transform: rotateY(180deg);
        position: absolute;
        inset: 0;
    }

    /* Shimmer on card number */
    .card-number-display {
        letter-spacing: 0.2em;
        font-variant-numeric: tabular-nums;
    }

    /* Input focus ring using brand colour */
    .pay-input:focus {
        outline: none;
        border-color: #0056A4;
        box-shadow: 0 0 0 3px rgba(0, 86, 164, 0.15);
    }
    .dark .pay-input:focus {
        box-shadow: 0 0 0 3px rgba(0, 86, 164, 0.25);
    }

    /* Animated submit button */
    .pay-btn {
        background: linear-gradient(135deg, #0056A4 0%, #0070cc 100%);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .pay-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .pay-btn:hover::after { opacity: 1; }
    .pay-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 24px -4px rgba(0,86,164,0.45); }
    .pay-btn:active { transform: translateY(0); }

    /* Card type badge */
    .card-type-badge {
        transition: all 0.3s ease;
    }

    /* Step indicator */
    .step-dot {
        transition: all 0.3s ease;
    }

    /* Secure badge pulse */
    @keyframes securePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        50% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    }
    .secure-badge {
        animation: securePulse 2.5s ease-in-out infinite;
    }

    /* Summary item hover */
    .summary-item {
        transition: background 0.15s ease;
    }
    .summary-item:hover {
        background: rgba(0, 86, 164, 0.04);
    }
    .dark .summary-item:hover {
        background: rgba(0, 86, 164, 0.08);
    }
</style>

                <!-- Page wrapper — two-column on lg+ -->
                <div class="findeks-bg min-h-[calc(100vh-73px)] flex flex-col lg:flex-row gap-0 -m-6 overflow-hidden rounded-none">

                    <!-- ══════════════════════════════════════════
                         LEFT COLUMN — Payment Form
                    ══════════════════════════════════════════ -->
                    <div class="flex-1 p-6 lg:p-10 xl:p-14 flex flex-col">

                        <!-- Breadcrumb / steps -->
                        <div class="flex items-center gap-2 mb-8" data-animate="fade">
                            <span class="text-xs text-neutral-400">Sepet</span>
                            <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-300 dark:text-neutral-600"></i>
                            <span class="text-xs text-neutral-400">Teslimat</span>
                            <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-300 dark:text-neutral-600"></i>
                            <span class="text-xs font-semibold text-brand">Ödeme</span>
                        </div>

                        <!-- Section title -->
                        <div class="mb-8" data-animate="fade">
                            <h2 class="text-2xl font-bold text-neutral-900 dark:text-white tracking-tight">Ödeme Bilgileri</h2>
                            <p class="text-sm text-neutral-500 mt-1">Kart bilgilerinizi güvenli şekilde girin</p>
                        </div>

                        <!-- ── Interactive Card Preview ── -->
                        <div class="card-preview mb-8 select-none" data-animate="card">
                            <div class="card-inner" id="cardInner">

                                <!-- Front face -->
                                <div class="card-face w-full max-w-sm mx-auto lg:mx-0 rounded-2xl p-6 h-48 flex flex-col justify-between relative overflow-hidden"
                                     style="background: linear-gradient(135deg, #0056A4 0%, #003d7a 50%, #001f4d 100%); box-shadow: 0 20px 60px -10px rgba(0,86,164,0.5);">
                                    <!-- Decorative circles -->
                                    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10" style="background: radial-gradient(circle, #fff 0%, transparent 70%);"></div>
                                    <div class="absolute -bottom-10 -left-6 w-48 h-48 rounded-full opacity-10" style="background: radial-gradient(circle, #60a5fa 0%, transparent 70%);"></div>

                                    <!-- Top row: chip + card type -->
                                    <div class="flex items-center justify-between relative z-10">
                                        <!-- EMV chip -->
                                        <div class="w-10 h-7 rounded-md" style="background: linear-gradient(135deg, #d4a843 0%, #f5c842 40%, #d4a843 100%); box-shadow: inset 0 1px 2px rgba(255,255,255,0.4);"></div>
                                        <!-- Card network logo -->
                                        <div id="cardNetworkFront" class="card-type-badge flex items-center gap-1 opacity-80">
                                            <div class="w-8 h-8 rounded-full bg-red-500 opacity-90"></div>
                                            <div class="w-8 h-8 rounded-full bg-yellow-400 opacity-90 -ml-4"></div>
                                        </div>
                                    </div>

                                    <!-- Card number -->
                                    <div class="relative z-10">
                                        <p class="card-number-display text-white text-lg font-mono tracking-widest" id="cardNumberDisplay">
                                            •••• &nbsp;•••• &nbsp;•••• &nbsp;••••
                                        </p>
                                    </div>

                                    <!-- Bottom row: name + expiry -->
                                    <div class="flex items-end justify-between relative z-10">
                                        <div>
                                            <p class="text-[9px] text-blue-200 uppercase tracking-widest mb-0.5">Kart Sahibi</p>
                                            <p class="text-white text-sm font-medium uppercase tracking-wide truncate max-w-[160px]" id="cardHolderDisplay">AD SOYAD</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] text-blue-200 uppercase tracking-widest mb-0.5">Son Kullanma</p>
                                            <p class="text-white text-sm font-mono" id="cardExpiryDisplay">MM/YY</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Back face -->
                                <div class="card-face card-back w-full max-w-sm mx-auto lg:mx-0 rounded-2xl h-48 overflow-hidden relative"
                                     style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); box-shadow: 0 20px 60px -10px rgba(0,0,0,0.5);">
                                    <!-- Magnetic stripe -->
                                    <div class="w-full h-10 mt-8" style="background: #111;"></div>
                                    <!-- Signature strip + CVV -->
                                    <div class="flex items-center gap-3 mx-6 mt-4">
                                        <div class="flex-1 h-8 rounded flex items-center px-3" style="background: repeating-linear-gradient(90deg, #e5e7eb 0px, #e5e7eb 4px, #f9fafb 4px, #f9fafb 8px);">
                                            <span class="text-xs text-neutral-400 italic">Authorized Signature</span>
                                        </div>
                                        <div class="w-14 h-8 bg-white rounded flex items-center justify-center">
                                            <span class="text-sm font-bold text-neutral-800 font-mono tracking-widest" id="cvvDisplay">•••</span>
                                        </div>
                                    </div>
                                    <!-- Card network on back -->
                                    <div class="absolute bottom-4 right-6 flex items-center gap-1 opacity-60">
                                        <div class="w-6 h-6 rounded-full bg-red-500"></div>
                                        <div class="w-6 h-6 rounded-full bg-yellow-400 -ml-3"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ── Payment Form ── -->
                        <form id="paymentForm" class="space-y-5 max-w-sm mx-auto lg:mx-0 w-full" data-animate="slide-up" onsubmit="handlePayment(event)">

                            <!-- Card Number -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Kart Numarası
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="cardNumber"
                                        name="card_number"
                                        placeholder="0000 0000 0000 0000"
                                        maxlength="19"
                                        autocomplete="cc-number"
                                        inputmode="numeric"
                                        class="pay-input w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3.5 pr-14 text-sm font-mono text-neutral-900 dark:text-white placeholder-neutral-400 transition-all"
                                        oninput="formatCardNumber(this)"
                                        onfocus="flipCard(false)"
                                    >
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1" id="cardNetworkInput">
                                        <div class="w-6 h-6 rounded-full bg-red-500 opacity-70"></div>
                                        <div class="w-6 h-6 rounded-full bg-yellow-400 opacity-70 -ml-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expiry + CVV -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Son Kullanma
                                    </label>
                                    <input
                                        type="text"
                                        id="cardExpiry"
                                        name="card_expiry"
                                        placeholder="AA/YY"
                                        maxlength="5"
                                        autocomplete="cc-exp"
                                        inputmode="numeric"
                                        class="pay-input w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3.5 text-sm font-mono text-neutral-900 dark:text-white placeholder-neutral-400 transition-all"
                                        oninput="formatExpiry(this)"
                                        onfocus="flipCard(false)"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        CVV / CVC
                                    </label>
                                    <input
                                        type="text"
                                        id="cardCvv"
                                        name="card_cvv"
                                        placeholder="•••"
                                        maxlength="4"
                                        autocomplete="cc-csc"
                                        inputmode="numeric"
                                        class="pay-input w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3.5 text-sm font-mono text-neutral-900 dark:text-white placeholder-neutral-400 transition-all"
                                        oninput="updateCvvDisplay(this)"
                                        onfocus="flipCard(true)"
                                        onblur="flipCard(false)"
                                    >
                                </div>
                            </div>

                            <!-- Cardholder Name -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Kart Üzerindeki İsim
                                </label>
                                <input
                                    type="text"
                                    id="cardHolder"
                                    name="card_holder"
                                    placeholder="AD SOYAD"
                                    maxlength="26"
                                    autocomplete="cc-name"
                                    class="pay-input w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3.5 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 transition-all uppercase"
                                    oninput="updateHolderDisplay(this)"
                                    onfocus="flipCard(false)"
                                >
                            </div>

                            <!-- 3D Secure notice -->
                            <div class="flex items-start gap-3 p-3.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 rounded-xl">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">3D Secure Korumalı</p>
                                    <p class="text-[11px] text-blue-600/70 dark:text-blue-400/70 mt-0.5">Ödemeniz bankanızın 3D Secure sistemi ile doğrulanacaktır.</p>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <button
                                type="submit"
                                id="payBtn"
                                class="pay-btn w-full py-4 text-white font-semibold rounded-xl flex items-center justify-center gap-2.5 text-sm"
                            >
                                <i data-lucide="lock" class="w-4 h-4" id="payBtnIcon"></i>
                                <span id="payBtnText">Güvenli Ödeme Yap</span>
                            </button>

                            <!-- Trust badges -->
                            <div class="flex items-center justify-center gap-6 pt-1">
                                <div class="flex items-center gap-1.5 text-neutral-400">
                                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                    <span class="text-[11px]">SSL Şifreli</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-neutral-400">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                    <span class="text-[11px]">PCI DSS</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-neutral-400">
                                    <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                    <span class="text-[11px]">256-bit</span>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- ══════════════════════════════════════════
                         RIGHT COLUMN — Findeks-style summary panel
                    ══════════════════════════════════════════ -->
                    <div class="findeks-deco lg:w-[380px] xl:w-[420px] flex flex-col p-8 lg:p-10 relative" data-animate="fade">

                        <!-- Panel header -->
                        <div class="mb-8 relative z-10">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                    <i data-lucide="receipt" class="w-4 h-4 text-white"></i>
                                </div>
                                <h3 class="text-white font-semibold text-lg">Sipariş Özeti</h3>
                            </div>
                            <p class="text-blue-200/70 text-xs ml-11">Ödeme detaylarınızı inceleyin</p>
                        </div>

                        <!-- Order items -->
                        <div class="space-y-3 mb-6 relative z-10" id="orderItems">
                            <!-- Item 1 -->
                            <div class="summary-item flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl border border-white/10">
                                <div class="w-12 h-12 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="package" class="w-5 h-5 text-white/80"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-medium truncate" id="productName1">Premium Üyelik</p>
                                    <p class="text-blue-200/70 text-xs mt-0.5">1 adet</p>
                                </div>
                                <span class="text-white font-semibold text-sm flex-shrink-0" id="productPrice1">₺299,00</span>
                            </div>

                            <!-- Item 2 -->
                            <div class="summary-item flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl border border-white/10">
                                <div class="w-12 h-12 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="star" class="w-5 h-5 text-white/80"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-medium truncate" id="productName2">Ek Hizmet Paketi</p>
                                    <p class="text-blue-200/70 text-xs mt-0.5">1 adet</p>
                                </div>
                                <span class="text-white font-semibold text-sm flex-shrink-0" id="productPrice2">₺149,00</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-white/15 mb-5 relative z-10"></div>

                        <!-- Price breakdown -->
                        <div class="space-y-3 mb-6 relative z-10">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-200/80 text-sm">Ara Toplam</span>
                                <span class="text-white text-sm font-medium">₺448,00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-200/80 text-sm">KDV (%18)</span>
                                <span class="text-white text-sm font-medium">₺80,64</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-200/80 text-sm">Kargo</span>
                                <span class="text-emerald-300 text-sm font-medium">Ücretsiz</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-white/15 mb-5 relative z-10"></div>

                        <!-- Grand total -->
                        <div class="flex items-center justify-between mb-8 relative z-10">
                            <span class="text-white font-bold text-lg">Toplam</span>
                            <div class="text-right">
                                <span class="text-white font-bold text-2xl">₺528,64</span>
                                <p class="text-blue-200/60 text-[10px] mt-0.5">KDV Dahil</p>
                            </div>
                        </div>

                        <!-- Secure badge -->
                        <div class="relative z-10 mt-auto">
                            <div class="secure-badge flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/15">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-emerald-300"></i>
                                </div>
                                <div>
                                    <p class="text-white text-xs font-semibold">Güvenli Ödeme Garantisi</p>
                                    <p class="text-blue-200/60 text-[10px] mt-0.5">256-bit SSL şifreleme ile korunmaktadır</p>
                                </div>
                            </div>

                            <!-- Accepted cards -->
                            <div class="flex items-center gap-3 mt-4 justify-center">
                                <span class="text-blue-200/50 text-[10px] uppercase tracking-wider">Kabul Edilen Kartlar</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2 justify-center flex-wrap">
                                <!-- Visa -->
                                <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                                    <span class="text-white text-xs font-bold italic tracking-tight">VISA</span>
                                </div>
                                <!-- Mastercard -->
                                <div class="px-2 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm flex items-center gap-1">
                                    <div class="w-4 h-4 rounded-full bg-red-500 opacity-90"></div>
                                    <div class="w-4 h-4 rounded-full bg-yellow-400 opacity-90 -ml-2"></div>
                                </div>
                                <!-- Troy -->
                                <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                                    <span class="text-white text-xs font-bold tracking-tight">TROY</span>
                                </div>
                                <!-- Amex -->
                                <div class="px-3 py-1.5 bg-white/10 rounded-lg border border-white/15 backdrop-blur-sm">
                                    <span class="text-white text-xs font-bold tracking-tight">AMEX</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end right column -->

                </div>
                <!-- end page wrapper -->

<?php include 'includes/toast.php'; ?>

<script>
    /* ─────────────────────────────────────────
       Card preview live-update helpers
    ───────────────────────────────────────── */

    function flipCard(toBack) {
        const inner = document.getElementById('cardInner');
        if (toBack) {
            inner.classList.add('flipped');
        } else {
            inner.classList.remove('flipped');
        }
    }

    function formatCardNumber(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 16);
        let formatted = v.replace(/(.{4})/g, '$1 ').trim();
        input.value = formatted;

        // Update card display
        const display = document.getElementById('cardNumberDisplay');
        const padded = v.padEnd(16, '•');
        const groups = padded.match(/.{1,4}/g) || [];
        display.innerHTML = groups.join(' &nbsp;');

        // Detect card network
        detectCardNetwork(v);
    }

    function detectCardNetwork(number) {
        let network = 'default';
        if (/^4/.test(number)) network = 'visa';
        else if (/^5[1-5]/.test(number) || /^2[2-7]/.test(number)) network = 'mastercard';
        else if (/^9/.test(number)) network = 'troy';
        else if (/^3[47]/.test(number)) network = 'amex';

        const frontBadge = document.getElementById('cardNetworkFront');
        const inputBadge = document.getElementById('cardNetworkInput');

        const badges = {
            visa: `<span class="text-white font-bold italic text-sm tracking-tight">VISA</span>`,
            mastercard: `<div class="flex items-center"><div class="w-7 h-7 rounded-full bg-red-500 opacity-90"></div><div class="w-7 h-7 rounded-full bg-yellow-400 opacity-90 -ml-3"></div></div>`,
            troy: `<span class="text-white font-bold text-sm tracking-tight">TROY</span>`,
            amex: `<span class="text-white font-bold text-sm tracking-tight">AMEX</span>`,
            default: `<div class="flex items-center"><div class="w-7 h-7 rounded-full bg-red-500 opacity-90"></div><div class="w-7 h-7 rounded-full bg-yellow-400 opacity-90 -ml-3"></div></div>`
        };

        const inputBadges = {
            visa: `<span class="text-brand dark:text-blue-400 font-bold italic text-xs">VISA</span>`,
            mastercard: `<div class="flex items-center"><div class="w-5 h-5 rounded-full bg-red-500 opacity-80"></div><div class="w-5 h-5 rounded-full bg-yellow-400 opacity-80 -ml-2.5"></div></div>`,
            troy: `<span class="text-brand dark:text-blue-400 font-bold text-xs">TROY</span>`,
            amex: `<span class="text-brand dark:text-blue-400 font-bold text-xs">AMEX</span>`,
            default: `<div class="flex items-center"><div class="w-5 h-5 rounded-full bg-red-500 opacity-60"></div><div class="w-5 h-5 rounded-full bg-yellow-400 opacity-60 -ml-2.5"></div></div>`
        };

        frontBadge.innerHTML = badges[network] || badges.default;
        inputBadge.innerHTML = inputBadges[network] || inputBadges.default;
    }

    function formatExpiry(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 2) {
            v = v.substring(0, 2) + '/' + v.substring(2);
        }
        input.value = v;

        const display = document.getElementById('cardExpiryDisplay');
        display.textContent = v || 'MM/YY';
    }

    function updateCvvDisplay(input) {
        const v = input.value.replace(/\D/g, '').substring(0, 4);
        input.value = v;
        const display = document.getElementById('cvvDisplay');
        display.textContent = v ? v.replace(/./g, '•') : '•••';
    }

    function updateHolderDisplay(input) {
        const v = input.value.toUpperCase();
        input.value = v;
        const display = document.getElementById('cardHolderDisplay');
        display.textContent = v || 'AD SOYAD';
    }

    /* ─────────────────────────────────────────
       Form validation & submission
    ───────────────────────────────────────── */

    function validateForm() {
        const number = document.getElementById('cardNumber').value.replace(/\s/g, '');
        const expiry = document.getElementById('cardExpiry').value;
        const cvv    = document.getElementById('cardCvv').value;
        const holder = document.getElementById('cardHolder').value.trim();

        if (number.length < 13 || number.length > 16) {
            showToast('Geçerli bir kart numarası girin', 'error');
            document.getElementById('cardNumber').focus();
            return false;
        }

        const expiryParts = expiry.split('/');
        if (expiryParts.length !== 2 || expiryParts[0].length !== 2 || expiryParts[1].length !== 2) {
            showToast('Geçerli bir son kullanma tarihi girin (AA/YY)', 'error');
            document.getElementById('cardExpiry').focus();
            return false;
        }

        const month = parseInt(expiryParts[0]);
        const year  = parseInt('20' + expiryParts[1]);
        const now   = new Date();
        const expDate = new Date(year, month - 1, 1);
        if (month < 1 || month > 12 || expDate < new Date(now.getFullYear(), now.getMonth(), 1)) {
            showToast('Kartın son kullanma tarihi geçmiş', 'error');
            document.getElementById('cardExpiry').focus();
            return false;
        }

        if (cvv.length < 3) {
            showToast('Geçerli bir CVV girin', 'error');
            document.getElementById('cardCvv').focus();
            return false;
        }

        if (holder.length < 3) {
            showToast('Kart üzerindeki ismi girin', 'error');
            document.getElementById('cardHolder').focus();
            return false;
        }

        return true;
    }

    function handlePayment(e) {
        e.preventDefault();

        if (!validateForm()) return;

        const btn     = document.getElementById('payBtn');
        const btnText = document.getElementById('payBtnText');
        const btnIcon = document.getElementById('payBtnIcon');

        // Loading state
        btn.disabled = true;
        btn.style.opacity = '0.85';
        btnText.textContent = 'İşleniyor...';
        btnIcon.setAttribute('data-lucide', 'loader-2');
        btnIcon.classList.add('animate-spin');
        lucide.createIcons();

        // Simulate processing (replace with real API call)
        setTimeout(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.textContent = 'Güvenli Ödeme Yap';
            btnIcon.setAttribute('data-lucide', 'lock');
            btnIcon.classList.remove('animate-spin');
            lucide.createIcons();

            showToast('Ödeme başarıyla tamamlandı!', 'success');
        }, 2500);
    }

    /* ─────────────────────────────────────────
       Keyboard: auto-advance between fields
    ───────────────────────────────────────── */
    document.getElementById('cardNumber').addEventListener('input', function() {
        if (this.value.replace(/\s/g, '').length === 16) {
            document.getElementById('cardExpiry').focus();
        }
    });

    document.getElementById('cardExpiry').addEventListener('input', function() {
        if (this.value.length === 5) {
            document.getElementById('cardCvv').focus();
        }
    });

    document.getElementById('cardCvv').addEventListener('input', function() {
        if (this.value.length >= 3) {
            document.getElementById('cardHolder').focus();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
