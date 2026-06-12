<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Yasaklı IP\'ler';
$pageDescription = 'Engellenen IP adresleri';
$currentPage = 'bans';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <input type="text" id="searchInput" onkeyup="filterBans()" placeholder="IP ara..." class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 placeholder-neutral-500 focus:ring-2 focus:ring-brand focus:border-transparent w-48">
                        <select id="statusFilter" onchange="filterBans()" class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 focus:ring-2 focus:ring-brand focus:border-transparent">
                            <option value="">Tüm Durumlar</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Kaldırılmış</option>
                        </select>
                    </div>
                    <button onclick="openBanModal()" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i data-lucide="ban" class="w-4 h-4"></i>
                        IP Yasakla
                    </button>
                </div>

                <div data-animate="card" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-4 flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white" id="activeBansCount">0</p>
                        <p class="text-xs text-neutral-500">Aktif Yasak</p>
                    </div>
                </div>

                <div data-animate="fade" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-800/50">
                                    <th class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider px-5 py-3">IP Adresi</th>
                                    <th class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider px-5 py-3">Sebep</th>
                                    <th class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider px-5 py-3">Tarih</th>
                                    <th class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider px-5 py-3">Durum</th>
                                    <th class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider px-5 py-3">İşlem</th>
                                </tr>
                            </thead>
                            <tbody id="bansTableBody" class="divide-y divide-neutral-200 dark:divide-neutral-800">
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-neutral-500">
                                        <i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                                        <p class="text-sm">Yükleniyor...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

    <div id="banModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 opacity-0 invisible transition-opacity duration-200">
        <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-xl w-full max-w-sm transform scale-95 transition-transform duration-200" id="banModalContent">
            <div class="border-b border-neutral-200 dark:border-neutral-800 px-6 py-4 flex items-center justify-between">
                <h3 class="font-semibold text-neutral-900 dark:text-white">IP Yasakla</h3>
                <button onclick="closeBanModal()" class="p-2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="banForm" onsubmit="submitBan(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">IP Adresi</label>
                    <input type="text" id="banIP" required placeholder="192.168.1.1" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white font-mono focus:ring-2 focus:ring-brand focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Sebep</label>
                    <input type="text" id="banReason" placeholder="Şüpheli aktivite" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeBanModal()" class="flex-1 px-4 py-2.5 border border-neutral-200 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Yasakla
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php include 'includes/toast.php'; ?>

<script>
    const API_BASE = '../api';
    let bans = [];
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function fetchBans() {
        try {
            const res = await fetch(`${API_BASE}/bans.php`);
            const data = await res.json();
            if (data.success) {
                bans = data.bans;
                renderBans();
                updateStats();
            }
        } catch (e) {
            console.error('Bans fetch error:', e);
        }
    }

    function updateStats() {
        const activeBans = bans.filter(b => b.status === 'active').length;
        document.getElementById('activeBansCount').textContent = activeBans;
    }

    function renderBans() {
        const tbody = document.getElementById('bansTableBody');
        const search = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        let filtered = bans;
        
        if (search) {
            filtered = filtered.filter(b => b.ip.toLowerCase().includes(search));
        }
        
        if (statusFilter) {
            filtered = filtered.filter(b => b.status === statusFilter);
        }
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-neutral-500">
                        <i data-lucide="shield-check" class="w-6 h-6 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Yasaklı IP bulunamadı</p>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }
        
        tbody.innerHTML = filtered.map(ban => `
            <tr class="ban-row hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                <td class="px-5 py-4">
                    <span class="text-sm font-mono text-neutral-900 dark:text-white">${escapeHtml(ban.ip)}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">${escapeHtml(ban.reason) || '-'}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">${formatDate(ban.created_at)}</span>
                </td>
                <td class="px-5 py-4">
                    ${ban.status === 'active' 
                        ? `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            Aktif
                        </span>`
                        : `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400">
                            <span class="w-1.5 h-1.5 bg-neutral-400 rounded-full"></span>
                            Kaldırılmış
                        </span>`
                    }
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-1">
                        ${ban.status === 'active' 
                            ? `<button onclick="removeBan(${ban.id})" class="p-2 text-neutral-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="Yasağı Kaldır">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </button>`
                            : ''
                        }
                        <button onclick="deleteBan(${ban.id})" class="p-2 text-neutral-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Sil">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    function filterBans() {
        renderBans();
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    }

    function openBanModal() {
        document.getElementById('banForm').reset();
        const modal = document.getElementById('banModal');
        const content = document.getElementById('banModalContent');
        modal.classList.remove('opacity-0', 'invisible');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        lucide.createIcons();
    }

    function closeBanModal() {
        const modal = document.getElementById('banModal');
        const content = document.getElementById('banModalContent');
        modal.classList.add('opacity-0', 'invisible');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    async function submitBan(e) {
        e.preventDefault();
        
        const ip = document.getElementById('banIP').value;
        const reason = document.getElementById('banReason').value;
        
        try {
            const res = await fetch(`${API_BASE}/bans.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ip, reason })
            });
            const data = await res.json();
            
            if (data.success) {
                showToast('IP başarıyla yasaklandı', 'success');
                closeBanModal();
                fetchBans();
            } else {
                showToast(data.error || 'Yasaklama başarısız', 'error');
            }
        } catch (e) {
            showToast('Bir hata oluştu', 'error');
        }
    }

    async function removeBan(id) {
        try {
            const res = await fetch(`${API_BASE}/bans.php`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, status: 'inactive' })
            });
            const data = await res.json();
            
            if (data.success) {
                showToast('Yasak kaldırıldı', 'success');
                fetchBans();
            } else {
                showToast('İşlem başarısız', 'error');
            }
        } catch (e) {
            showToast('Bir hata oluştu', 'error');
        }
    }

    async function deleteBan(id) {
        if (!confirm('Bu kaydı silmek istediğinize emin misiniz?')) return;
        
        try {
            const res = await fetch(`${API_BASE}/bans.php?id=${id}`, {
                method: 'DELETE'
            });
            const data = await res.json();
            
            if (data.success) {
                showToast('Kayıt silindi', 'success');
                fetchBans();
            } else {
                showToast('Silme başarısız', 'error');
            }
        } catch (e) {
            showToast('Bir hata oluştu', 'error');
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeBanModal();
    });

    fetchBans();
</script>

<?php include 'includes/footer.php'; ?>
