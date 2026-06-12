<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Ziyaretçiler';
$pageDescription = 'Aktif ve geçmiş ziyaretçiler';
$currentPage = 'visitors';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<style>
    .visitor-row { transition: all 0.2s ease; }
    .visitor-row:hover { background: rgba(0,0,0,0.02); }
    .dark .visitor-row:hover { background: rgba(255,255,255,0.02); }
</style>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Toplam Ziyaretçi</span>
                            <i data-lucide="users" class="w-4 h-4 text-blue-500"></i>
                        </div>
                        <p id="statTotalVisitors" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Online</span>
                            <i data-lucide="wifi" class="w-4 h-4 text-green-500"></i>
                        </div>
                        <p id="statOnlineVisitors" class="text-2xl font-semibold text-green-600 mt-2">0</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Toplam Sepet</span>
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-purple-500"></i>
                        </div>
                        <p id="statTotalCart" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0 ₺</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Kart Giren</span>
                            <i data-lucide="credit-card" class="w-4 h-4 text-orange-500"></i>
                        </div>
                        <p id="statCardEntered" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="text" id="searchInput" onkeyup="filterVisitors()" placeholder="IP veya sayfa ara..." class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:ring-2 focus:ring-brand w-64">
                            <select id="filterStatus" onchange="filterVisitors()" class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                                <option value="">Tümü</option>
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="lastUpdate" class="text-xs text-neutral-400"></span>
                            <button onclick="refreshVisitors()" class="flex items-center gap-2 px-3 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4" id="refreshIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                                <tr>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Durum</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">IP Adresi</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Sayfa</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Cihaz</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Konum</th>
                                    <th class="text-right text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Sepet</th>
                                    <th class="text-center text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Kart</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Süre</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 uppercase px-4 py-3">Son Aktivite</th>
                                </tr>
                            </thead>
                            <tbody id="visitorsTableBody">
                                <tr>
                                    <td colspan="9" class="px-4 py-12 text-center text-neutral-500">
                                        <i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                                        <p class="text-sm">Yükleniyor...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

<?php include 'includes/toast.php'; ?>

<script>
    const API_BASE = '../api';
    let visitors = [];
    let pollingInterval;
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function formatMoney(amount) {
        return parseFloat(amount || 0).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' ₺';
    }
    
    function formatDuration(seconds) {
        if (!seconds || seconds < 0) return '-';
        if (seconds < 60) return `${seconds}sn`;
        if (seconds < 3600) return `${Math.floor(seconds / 60)}dk ${seconds % 60}sn`;
        const hours = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        return `${hours}sa ${mins}dk`;
    }
    
    function formatTimeAgo(seconds) {
        if (!seconds || seconds < 0) return 'Şimdi';
        if (seconds < 60) return `${seconds}sn önce`;
        if (seconds < 3600) return `${Math.floor(seconds / 60)}dk önce`;
        return `${Math.floor(seconds / 3600)}sa önce`;
    }

    async function fetchVisitors() {
        try {
            const res = await fetch(`${API_BASE}/sessions.php`);
            const data = await res.json();
            
            if (data.success) {
                visitors = data.sessions || [];
                renderVisitors();
                updateStats();
                document.getElementById('lastUpdate').textContent = 'Son güncelleme: ' + new Date().toLocaleTimeString('tr-TR');
            }
        } catch (e) {
            console.error('Visitors fetch error:', e);
        }
    }
    
    function updateStats() {
        const online = visitors.filter(v => v.is_online).length;
        const totalCart = visitors.reduce((sum, v) => sum + (parseFloat(v.cart_total) || 0), 0);
        const cardEntered = visitors.filter(v => v.card_entered == 1).length;
        
        document.getElementById('statTotalVisitors').textContent = visitors.length;
        document.getElementById('statOnlineVisitors').textContent = online;
        document.getElementById('statTotalCart').textContent = formatMoney(totalCart);
        document.getElementById('statCardEntered').textContent = cardEntered;
    }
    
    function renderVisitors() {
        const tbody = document.getElementById('visitorsTableBody');
        const search = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        
        let filtered = visitors;
        
        if (search) {
            filtered = filtered.filter(v => 
                (v.ip && v.ip.toLowerCase().includes(search)) ||
                (v.current_page && v.current_page.toLowerCase().includes(search))
            );
        }
        
        if (statusFilter === 'online') {
            filtered = filtered.filter(v => v.is_online);
        } else if (statusFilter === 'offline') {
            filtered = filtered.filter(v => !v.is_online);
        }
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-neutral-500">
                        <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Ziyaretçi bulunamadı</p>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }
        
        tbody.innerHTML = filtered.map(v => {
            const isOnline = v.is_online;
            const deviceIcon = v.device_type === 'mobile' ? 'smartphone' : (v.device_type === 'tablet' ? 'tablet' : 'monitor');
            const cartTotal = parseFloat(v.cart_total) || 0;
            const cardEntered = v.card_entered == 1;
            
            let statusHtml = '';
            if (isOnline) {
                const pageColors = {
                    'Ana Sayfa': 'neutral',
                    'Ürün Detay': 'blue',
                    'Sepet': 'amber',
                    'Ödeme Sayfası': 'purple',
                    'Bekleme': 'orange',
                    '3D Secure': 'pink'
                };
                const color = pageColors[v.current_page] || 'green';
                statusHtml = `
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-${color}-50 dark:bg-${color}-500/10 border border-${color}-200 dark:border-${color}-500/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-${color}-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-${color}-500"></span>
                        </span>
                        <span class="text-xs font-medium text-${color}-700 dark:text-${color}-400">Online</span>
                    </div>
                `;
            } else {
                statusHtml = `
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-neutral-100 dark:bg-neutral-800">
                        <span class="h-2 w-2 rounded-full bg-neutral-400"></span>
                        <span class="text-xs font-medium text-neutral-500">Offline</span>
                    </div>
                `;
            }
            
            return `
                <tr class="visitor-row border-b border-neutral-100 dark:border-neutral-800" data-online="${isOnline ? '1' : '0'}">
                    <td class="px-4 py-3">${statusHtml}</td>
                    <td class="px-4 py-3">
                        <code class="text-sm font-mono text-neutral-700 dark:text-neutral-300">${escapeHtml(v.ip)}</code>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">${escapeHtml(v.current_page || 'Ana Sayfa')}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="${deviceIcon}" class="w-4 h-4 text-neutral-400"></i>
                            <span class="text-xs text-neutral-500 capitalize">${v.device_type || 'desktop'}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs text-neutral-500">${escapeHtml(v.timezone || '-')}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        ${cartTotal > 0 
                            ? `<span class="text-sm font-semibold text-green-600">${formatMoney(cartTotal)}</span>` 
                            : '<span class="text-sm text-neutral-400">-</span>'
                        }
                    </td>
                    <td class="px-4 py-3 text-center">
                        ${cardEntered 
                            ? '<span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full"><i data-lucide="check" class="w-3 h-3 text-green-600"></i></span>'
                            : '<span class="inline-flex items-center justify-center w-6 h-6 bg-neutral-100 dark:bg-neutral-800 rounded-full"><i data-lucide="x" class="w-3 h-3 text-neutral-400"></i></span>'
                        }
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-neutral-500">${formatDuration(v.session_duration)}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs text-neutral-400">${formatTimeAgo(v.seconds_ago)}</span>
                    </td>
                </tr>
            `;
        }).join('');
        
        lucide.createIcons();
    }
    
    function filterVisitors() {
        renderVisitors();
    }
    
    function refreshVisitors() {
        const icon = document.getElementById('refreshIcon');
        icon.classList.add('animate-spin');
        
        fetchVisitors().then(() => {
            setTimeout(() => icon.classList.remove('animate-spin'), 500);
        });
    }
    
    fetchVisitors();
    pollingInterval = setInterval(fetchVisitors, 5000);
</script>

<?php include 'includes/footer.php'; ?>
