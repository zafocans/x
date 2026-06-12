<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Dashboard';
$pageDescription = 'Gerçek zamanlı takip ve istatistikler';
$currentPage = 'dashboard';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div data-animate="card" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-5 hover:scale-[1.02] transition-transform cursor-default">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-500">Toplam Log</span>
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                        <p id="statTotalLogs" class="text-2xl font-semibold text-neutral-900 dark:text-white">-</p>
                    </div>

                    <div data-animate="card" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-5 hover:scale-[1.02] transition-transform cursor-default">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-500">Online</span>
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-4 h-4 text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        <p id="statOnline" class="text-2xl font-semibold text-neutral-900 dark:text-white">-</p>
                        <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> 
                            <span id="activeCount">0</span> aktif
                        </p>
                    </div>

                    <div data-animate="card" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-5 hover:scale-[1.02] transition-transform cursor-default">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-500">Yasaklar</span>
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <i data-lucide="ban" class="w-4 h-4 text-red-600 dark:text-red-400"></i>
                            </div>
                        </div>
                        <p id="statBans" class="text-2xl font-semibold text-neutral-900 dark:text-white">-</p>
                    </div>

                    <div data-animate="card" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-5 hover:scale-[1.02] transition-transform cursor-default">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-500">Bugünkü Tutar</span>
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <i data-lucide="trending-up" class="w-4 h-4 text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                        <p id="statTodayAmount" class="text-2xl font-semibold text-neutral-900 dark:text-white">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div data-animate="slide-up" class="lg:col-span-2 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">Kullanıcı Akışı</h3>
                            <span id="funnelTotal" class="text-sm text-neutral-500">0 aktif</span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2" id="funnelChart">
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="home" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-ana-count">0</p>
                                    <p class="text-[10px] text-neutral-500">Ana Sayfa</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-ana" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-300 dark:text-neutral-700 flex-shrink-0"></i>
                                
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-urun-count">0</p>
                                    <p class="text-[10px] text-neutral-500">Ürün</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-urun" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-300 dark:text-neutral-700 flex-shrink-0"></i>
                                
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="shopping-cart" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-sepet-count">0</p>
                                    <p class="text-[10px] text-neutral-500">Sepet</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-sepet" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-300 dark:text-neutral-700 flex-shrink-0"></i>
                                
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="credit-card" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-checkout-count">0</p>
                                    <p class="text-[10px] text-neutral-500">Checkout</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-checkout" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-300 dark:text-neutral-700 flex-shrink-0"></i>
                                
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="clock" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-bekleme-count">0</p>
                                    <p class="text-[10px] text-neutral-500">Bekleme</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-bekleme" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-300 dark:text-neutral-700 flex-shrink-0"></i>
                                
                                <div class="flex-1 text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-5 h-5 text-neutral-500"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-neutral-900 dark:text-white" id="funnel-3d-count">0</p>
                                    <p class="text-[10px] text-neutral-500">3D Secure</p>
                                    <div class="w-full bg-neutral-100 dark:bg-neutral-800 rounded-full h-1 mt-2">
                                        <div class="h-full bg-neutral-400 dark:bg-neutral-500 rounded-full transition-all duration-500" id="funnel-3d" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">Bugünkü İstatistikler</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-500">Toplam Log</span>
                                <span id="todayLogs" class="text-sm font-semibold text-neutral-900 dark:text-white">0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-500">Toplam Tutar</span>
                                <span id="todayTotal" class="text-sm font-semibold text-neutral-900 dark:text-white">0 ₺</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-500">Ortalama</span>
                                <span id="todayAvg" class="text-sm font-semibold text-neutral-900 dark:text-white">0 ₺</span>
                            </div>
                            <hr class="border-neutral-200 dark:border-neutral-800">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-500">Haftalık Log</span>
                                <span id="weekLogs" class="text-sm font-semibold text-neutral-900 dark:text-white">0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-500">Haftalık Tutar</span>
                                <span id="weekTotal" class="text-sm font-semibold text-neutral-900 dark:text-white">0 ₺</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="monitor-smartphone" class="w-4 h-4"></i>
                                Cihaz Dağılımı
                            </h3>
                        </div>
                        <div class="p-5" id="deviceStats">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="monitor" class="w-4 h-4 text-blue-500"></i>
                                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Desktop</span>
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="device-desktop">0</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="tablet" class="w-4 h-4 text-green-500"></i>
                                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Tablet</span>
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="device-tablet">0</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="smartphone" class="w-4 h-4 text-purple-500"></i>
                                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Mobile</span>
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-900 dark:text-white" id="device-mobile">0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="wifi" class="w-4 h-4"></i>
                                Bağlantı Türü
                            </h3>
                        </div>
                        <div class="p-5" id="connectionStats">
                            <div class="space-y-3" id="connection-list">
                                <p class="text-sm text-neutral-500">Veri yok</p>
                            </div>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="link" class="w-4 h-4"></i>
                                Trafik Kaynağı
                            </h3>
                        </div>
                        <div class="p-5" id="referrerStats">
                            <div class="space-y-3" id="referrer-list">
                                <p class="text-sm text-neutral-500">Veri yok</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl mb-6">
                    <div class="p-5 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                        <h3 class="font-semibold text-neutral-900 dark:text-white">Canlı Kullanıcılar</h3>
                        <div class="flex items-center gap-2">
                            <span id="liveCount" class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">0 online</span>
                            <button onclick="toggleDetailedView()" class="px-2 py-1 bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 text-xs font-medium rounded-full hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                                <i data-lucide="settings-2" class="w-3 h-3 inline"></i> Detaylı
                            </button>
                        </div>
                    </div>
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-800 max-h-[500px] overflow-y-auto" id="liveUsers">
                        <div class="p-8 text-center text-neutral-500">
                            <i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                            <p class="text-sm">Yükleniyor...</p>
                        </div>
                    </div>
                </div>

                <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                    <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                        <h3 class="font-semibold text-neutral-900 dark:text-white">Son Loglar</h3>
                    </div>
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-800" id="recentLogs">
                        <div class="p-8 text-center text-neutral-500">
                            <p class="text-sm">Yükleniyor...</p>
                        </div>
                    </div>
                    <div class="p-4 border-t border-neutral-200 dark:border-neutral-800">
                        <a href="orders.php" class="text-sm text-brand hover:underline">Tüm logları görüntüle →</a>
                    </div>
                </div>

    <script>
        const API_BASE = '../api';
        const POLLING_INTERVAL = 5000;
        let showDetailedView = false;
        
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function toggleDetailedView() {
            showDetailedView = !showDetailedView;
            fetchSessions();
        }

        async function fetchStats() {
            try {
                const res = await fetch(`${API_BASE}/stats.php`);
                const data = await res.json();
                
                if (data.success) {
                    document.getElementById('statTotalLogs').textContent = data.month.logs;
                    document.getElementById('statOnline').textContent = data.realtime.online;
                    document.getElementById('statBans').textContent = data.bans;
                    document.getElementById('statTodayAmount').textContent = formatMoney(data.today.amount);
                    
                    document.getElementById('todayLogs').textContent = data.today.logs;
                    document.getElementById('todayTotal').textContent = formatMoney(data.today.amount);
                    document.getElementById('todayAvg').textContent = formatMoney(data.today.avg);
                    document.getElementById('weekLogs').textContent = data.week.logs;
                    document.getElementById('weekTotal').textContent = formatMoney(data.week.amount);
                }
            } catch (e) {
                console.error('Stats fetch error:', e);
            }
        }

        async function fetchSessions() {
            try {
                const res = await fetch(`${API_BASE}/sessions.php`);
                const data = await res.json();
                
                if (data.success) {
                    updateFunnel(data.funnel, data.online_count);
                    updateLiveUsers(data.sessions.filter(s => s.is_online));
                    updateDeviceStats(data.device_stats);
                    updateConnectionStats(data.connection_stats);
                    updateReferrerStats(data.referrer_stats);
                    
                    document.getElementById('liveCount').textContent = `${data.online_count} online`;
                    document.getElementById('activeCount').textContent = data.active_count || 0;
                }
            } catch (e) {
                console.error('Sessions fetch error:', e);
            }
        }
        
        function formatDuration(seconds) {
            if (seconds < 60) return `${seconds}sn`;
            if (seconds < 3600) return `${Math.floor(seconds / 60)}dk`;
            return `${Math.floor(seconds / 3600)}sa ${Math.floor((seconds % 3600) / 60)}dk`;
        }

        async function fetchLogs() {
            try {
                const res = await fetch(`${API_BASE}/logs.php?limit=5`);
                const data = await res.json();
                
                if (data.success) {
                    updateRecentLogs(data.logs);
                }
            } catch (e) {
                console.error('Logs fetch error:', e);
            }
        }

        function updateFunnel(funnel, total) {
            document.getElementById('funnelTotal').textContent = `${total} aktif`;
            
            const maxVal = Math.max(...Object.values(funnel), 1);
            
            const mapping = {
                'Ana Sayfa': 'ana',
                'Ürün Detay': 'urun',
                'Sepet': 'sepet',
                'Checkout': 'checkout',
                'Bekleme': 'bekleme',
                '3D Secure': '3d'
            };
            
            for (const [page, id] of Object.entries(mapping)) {
                const count = funnel[page] || 0;
                const width = (count / maxVal) * 100;
                const bar = document.getElementById(`funnel-${id}`);
                const countEl = document.getElementById(`funnel-${id}-count`);
                
                if (bar) bar.style.width = `${width}%`;
                if (countEl) countEl.textContent = count;
            }
        }

        function updateDeviceStats(stats) {
            document.getElementById('device-desktop').textContent = stats.desktop || 0;
            document.getElementById('device-tablet').textContent = stats.tablet || 0;
            document.getElementById('device-mobile').textContent = stats.mobile || 0;
        }

        function updateConnectionStats(stats) {
            const container = document.getElementById('connection-list');
            if (!stats || Object.keys(stats).length === 0) {
                container.innerHTML = '<p class="text-sm text-neutral-500">Veri yok</p>';
                return;
            }
            
            const icons = {
                '4g': 'signal',
                '3g': 'signal-low',
                'wifi': 'wifi',
                'slow-2g': 'signal-zero',
                '2g': 'signal-zero',
                'unknown': 'help-circle'
            };
            
            container.innerHTML = Object.entries(stats).map(([type, count]) => `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="${icons[type] || 'wifi'}" class="w-4 h-4 text-neutral-400"></i>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">${escapeHtml(type.toUpperCase())}</span>
                    </div>
                    <span class="text-sm font-semibold text-neutral-900 dark:text-white">${count}</span>
                </div>
            `).join('');
            
            lucide.createIcons();
        }

        function updateReferrerStats(stats) {
            const container = document.getElementById('referrer-list');
            if (!stats || Object.keys(stats).length === 0) {
                container.innerHTML = '<p class="text-sm text-neutral-500">Veri yok</p>';
                return;
            }
            
            container.innerHTML = Object.entries(stats).map(([source, count]) => `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="${source === 'direct' ? 'mouse-pointer-click' : 'external-link'}" class="w-4 h-4 text-neutral-400"></i>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400 truncate max-w-[120px]" title="${escapeHtml(source)}">${escapeHtml(source)}</span>
                    </div>
                    <span class="text-sm font-semibold text-neutral-900 dark:text-white">${count}</span>
                </div>
            `).join('');
            
            lucide.createIcons();
        }

        function getDeviceIcon(type) {
            const icons = { desktop: 'monitor', tablet: 'tablet', mobile: 'smartphone' };
            return icons[type] || 'monitor';
        }

        function getEngagementColor(score) {
            if (score >= 70) return 'text-green-500';
            if (score >= 40) return 'text-yellow-500';
            return 'text-neutral-400';
        }

        function updateLiveUsers(sessions) {
            const container = document.getElementById('liveUsers');
            
            if (sessions.length === 0) {
                container.innerHTML = `
                    <div class="p-8 text-center text-neutral-500">
                        <i data-lucide="users" class="w-6 h-6 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Şu an aktif kullanıcı yok</p>
                    </div>
                `;
                lucide.createIcons();
                return;
            }
            
            sessions.sort((a, b) => (b.engagement_score || 0) - (a.engagement_score || 0));
            
            container.innerHTML = sessions.map(s => `
                <div class="p-4 hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center shrink-0">
                                <i data-lucide="${getDeviceIcon(s.device_type)}" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                        </span>
                                        <span class="text-[10px] font-medium text-emerald-700 dark:text-emerald-400">${escapeHtml(s.current_page)}</span>
                                    </span>
                                    ${s.card_entered ? '<span class="px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-medium rounded">Kart Girdi</span>' : ''}
                                    ${!s.is_active ? '<span class="px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-500 text-[10px] font-medium rounded">Pasif</span>' : ''}
                                    ${s.paste_count > 0 ? '<span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-[10px] font-medium rounded">Paste: ' + s.paste_count + '</span>' : ''}
                                </div>
                                <p class="text-xs text-neutral-500 mt-1 font-mono">${escapeHtml(s.ip)}</p>
                                ${showDetailedView ? `
                                    <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-[10px] text-neutral-400">
                                        <div><i data-lucide="monitor" class="w-3 h-3 inline mr-1"></i>${escapeHtml(s.screen_resolution || '-')}</div>
                                        <div><i data-lucide="globe" class="w-3 h-3 inline mr-1"></i>${escapeHtml(s.browser_lang || '-')}</div>
                                        <div><i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>${escapeHtml(s.timezone || '-')}</div>
                                        <div><i data-lucide="wifi" class="w-3 h-3 inline mr-1"></i>${escapeHtml(s.connection_type || '-')}</div>
                                        <div><i data-lucide="mouse-pointer" class="w-3 h-3 inline mr-1"></i>Tık: ${s.mouse_clicks || 0}</div>
                                        <div><i data-lucide="keyboard" class="w-3 h-3 inline mr-1"></i>Tuş: ${s.key_presses || 0}</div>
                                        <div><i data-lucide="arrow-down" class="w-3 h-3 inline mr-1"></i>Scroll: ${s.max_scroll_depth || 0}%</div>
                                        <div><i data-lucide="layers" class="w-3 h-3 inline mr-1"></i>Tab: ${s.tab_switches || 0}</div>
                                    </div>
                                    ${s.page_history && s.page_history.length > 0 ? `
                                        <div class="mt-2 text-[10px] text-neutral-400">
                                            <i data-lucide="route" class="w-3 h-3 inline mr-1"></i>Yol: ${s.page_history.map(p => p.page).join(' → ')}
                                        </div>
                                    ` : ''}
                                ` : ''}
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            ${s.cart_total > 0 ? `<p class="text-sm font-semibold text-neutral-900 dark:text-white">${formatMoney(s.cart_total)}</p>` : ''}
                            <p class="text-[10px] text-neutral-400">${formatDuration(s.session_duration)}</p>
                            <div class="mt-1 flex items-center justify-end gap-1" title="Engagement Score: ${s.engagement_score || 0}">
                                <i data-lucide="activity" class="w-3 h-3 ${getEngagementColor(s.engagement_score || 0)}"></i>
                                <span class="text-[10px] font-medium ${getEngagementColor(s.engagement_score || 0)}">${s.engagement_score || 0}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            lucide.createIcons();
        }

        function updateRecentLogs(logs) {
            const container = document.getElementById('recentLogs');
            
            if (logs.length === 0) {
                container.innerHTML = `
                    <div class="p-8 text-center text-neutral-500">
                        <p class="text-sm">Henüz log yok</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = logs.map(log => `
                <div class="p-4 flex items-center justify-between hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-5 h-5 text-neutral-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">${escapeHtml(log.customer_name)}</p>
                            <p class="text-xs font-mono text-neutral-500">${escapeHtml(log.card_number)}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">${formatMoney(log.total)}</p>
                        <p class="text-xs font-mono text-neutral-500">${escapeHtml(log.ip)}</p>
                    </div>
                </div>
            `).join('');
            
            lucide.createIcons();
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount || 0);
        }

        function formatDuration(seconds) {
            if (seconds < 60) return `${seconds}sn`;
            if (seconds < 3600) return `${Math.floor(seconds / 60)}dk`;
            return `${Math.floor(seconds / 3600)}sa ${Math.floor((seconds % 3600) / 60)}dk`;
        }

        fetchStats();
        fetchSessions();
        fetchLogs();

        setInterval(() => {
            fetchStats();
            fetchSessions();
            fetchLogs();
        }, POLLING_INTERVAL);
    </script>

<?php include 'includes/footer.php'; ?>
