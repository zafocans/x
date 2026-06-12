<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Ürünler';
$pageDescription = 'Ürün ve kategori yönetimi';
$currentPage = 'products';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<style>
    .product-card {
        transition: all 0.2s ease;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1);
    }
</style>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <select id="categoryFilter" onchange="filterProducts()" class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                            <option value="">Tüm Kategoriler</option>
                        </select>
                        <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Ürün ara..." class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 focus:ring-2 focus:ring-brand w-64">
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="openImportExportModal()" class="flex items-center gap-2 px-4 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                            <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                            İçe/Dışa Aktar
                        </button>
                        <button onclick="openProductModal()" class="flex items-center gap-2 px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Yeni Ürün
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="productsGrid">
                    <div class="col-span-full text-center py-12 text-neutral-500">
                        <i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                        <p class="text-sm">Ürünler yükleniyor...</p>
                    </div>
                </div>

<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 opacity-0 invisible transition-opacity duration-200">
    <div class="bg-white dark:bg-neutral-900 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200" id="productModalContent">
        <div class="sticky top-0 bg-white dark:bg-neutral-900 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white" id="productModalTitle">Yeni Ürün</h3>
            <button onclick="closeProductModal()" class="p-2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="productForm" onsubmit="saveProduct(event)" class="p-6 space-y-4">
            <input type="hidden" id="productId" name="id">
            
            <div id="urlImportSection" class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4 mb-4">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    <i data-lucide="link" class="w-4 h-4 inline mr-1"></i>
                    Migros'tan Otomatik Ürün Çek
                </label>
                <div class="flex gap-2">
                    <input type="url" id="importUrl" placeholder="https://www.migros.com.tr/..." class="flex-1 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                    <button type="button" onclick="importFromUrl()" id="importBtn" class="px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="download" class="w-4 h-4" id="importIcon"></i>
                        <span id="importBtnText">Çek</span>
                    </button>
                </div>
                <p class="text-[11px] text-neutral-400 mt-2">Ürün linkini yapıştırın, bilgiler otomatik doldurulacak</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ürün Adı</label>
                <input type="text" id="productName" name="name" required class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Açıklama</label>
                    <button type="button" onclick="formatWithAI()" id="aiFormatBtn" class="flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-[11px] font-medium rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">
                        <i data-lucide="sparkles" class="w-3 h-3" id="aiIcon"></i>
                        <span id="aiBtnText">AI ile Düzenle</span>
                    </button>
                </div>
                <textarea id="productDescription" name="description" rows="4" class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand resize-none font-mono"></textarea>
                <p class="text-[10px] text-neutral-400 mt-1">Markdown destekler: **kalın**, *italik*, - liste</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Fiyat (TL)</label>
                    <input type="number" step="0.01" id="productPrice" name="price" required class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Eski Fiyat (TL)</label>
                    <input type="number" step="0.01" id="productOldPrice" name="old_price" class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kategori</label>
                    <select id="productCategory" name="category_id" required class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Stok</label>
                    <input type="number" id="productStock" name="stock" value="0" class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Birim</label>
                    <select id="productUnit" name="unit" class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                        <option value="adet">Adet</option>
                        <option value="kg">Kilogram</option>
                        <option value="lt">Litre</option>
                        <option value="paket">Paket</option>
                        <option value="kutu">Kutu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Etiket</label>
                    <input type="text" id="productBadge" name="badge" placeholder="Fırsat, İndirim..." class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ana Görsel URL</label>
                <input type="url" id="productImage" name="image" placeholder="https://..." class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ek Görseller (Opsiyonel)</label>
                <div id="additionalImagesContainer" class="space-y-2">
                </div>
                <button type="button" onclick="addImageField()" class="mt-2 text-sm text-brand hover:text-blue-700 flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i> Görsel Ekle
                </button>
            </div>
            
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Varyantlar (Opsiyonel)</label>
                    <button type="button" onclick="addVariantField()" class="text-sm text-brand hover:text-blue-700 flex items-center gap-1">
                        <i data-lucide="plus" class="w-4 h-4"></i> Varyant Ekle
                    </button>
                </div>
                <div id="variantsContainer" class="space-y-3">
                </div>
                <p class="text-[11px] text-neutral-400 mt-2">Renk veya model varyantları ekleyin. Her varyant için ayrı görsel belirleyebilirsiniz.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="productActive" name="is_active" checked class="w-4 h-4 rounded border-neutral-300 text-brand focus:ring-brand">
                <label for="productActive" class="text-sm text-neutral-700 dark:text-neutral-300">Aktif</label>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeProductModal()" class="flex-1 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                    İptal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 opacity-0 invisible transition-opacity duration-200">
    <div class="bg-white dark:bg-neutral-900 rounded-xl max-w-sm w-full p-6 transform scale-95 transition-transform duration-200" id="deleteModalContent">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="trash-2" class="w-6 h-6 text-red-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Ürünü Sil</h3>
            <p class="text-sm text-neutral-500 mb-6" id="deleteProductName">Bu ürünü silmek istediğinize emin misiniz?</p>
            <input type="hidden" id="deleteProductId">
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                    İptal
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Sil
                </button>
            </div>
        </div>
    </div>
</div>

<div id="importExportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 opacity-0 invisible transition-opacity duration-200">
    <div class="bg-white dark:bg-neutral-900 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-hidden transform scale-95 transition-transform duration-200" id="importExportModalContent">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">İçe/Dışa Aktar</h3>
            <button onclick="closeImportExportModal()" class="p-2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="flex gap-4 mb-6">
                <button onclick="switchImportExportTab('export')" id="tabExport" class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-colors bg-brand text-white">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Dışa Aktar (Export)
                </button>
                <button onclick="switchImportExportTab('import')" id="tabImport" class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-colors bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300">
                    <i data-lucide="upload" class="w-4 h-4 inline mr-2"></i>
                    İçe Aktar (Import)
                </button>
            </div>
            
            <div id="exportSection">
                <p class="text-sm text-neutral-500 mb-4">Tüm ürünleri <code class="bg-neutral-200 dark:bg-neutral-700 px-1.5 py-0.5 rounded text-xs">.lincoln</code> formatında dışa aktarın. Bu dosyayı yedek olarak saklayabilir veya başka bir sisteme aktarabilirsiniz.</p>
                <div class="flex gap-3 mb-4">
                    <button onclick="exportProducts('lincoln')" class="flex-1 px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        .lincoln İndir
                    </button>
                    <button onclick="exportProducts('clipboard')" class="flex-1 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="clipboard-copy" class="w-4 h-4"></i>
                        Panoya Kopyala
                    </button>
                </div>
                <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-neutral-500">Önizleme</span>
                        <span id="exportCount" class="text-xs text-neutral-400">0 ürün</span>
                    </div>
                    <pre id="exportPreview" class="text-xs text-neutral-600 dark:text-neutral-400 max-h-48 overflow-auto font-mono bg-white dark:bg-neutral-900 rounded p-3 border border-neutral-200 dark:border-neutral-700"></pre>
                </div>
            </div>
            
            <div id="importSection" class="hidden">
                <p class="text-sm text-neutral-500 mb-4"><code class="bg-neutral-200 dark:bg-neutral-700 px-1.5 py-0.5 rounded text-xs">.lincoln</code> formatındaki ürün verilerini içe aktarın. Yeni ürünler eklenir.</p>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">.lincoln Dosyası Seç veya Yapıştır</label>
                    <input type="file" id="importFile" accept=".lincoln,.json" onchange="handleFileSelect(event)" class="hidden">
                    <div class="flex gap-2 mb-3">
                        <button onclick="document.getElementById('importFile').click()" class="flex-1 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors flex items-center justify-center gap-2 border-2 border-dashed border-neutral-300 dark:border-neutral-600">
                            <i data-lucide="file-up" class="w-4 h-4"></i>
                            Dosya Seç
                        </button>
                    </div>
                    <textarea id="importData" rows="8" placeholder='[{"name": "Ürün Adı", "price": 99.99, ...}]' class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-3 text-sm text-neutral-900 dark:text-white font-mono focus:ring-2 focus:ring-brand"></textarea>
                </div>
                <div id="importPreviewSection" class="hidden mb-4">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                            <span class="text-sm font-medium text-green-700 dark:text-green-400" id="importPreviewText">0 ürün içe aktarılacak</span>
                        </div>
                        <ul id="importPreviewList" class="text-xs text-green-600 dark:text-green-400 space-y-1 max-h-32 overflow-auto"></ul>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="validateImport()" class="flex-1 px-4 py-3 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                        <i data-lucide="search" class="w-4 h-4 inline mr-2"></i>
                        Doğrula
                    </button>
                    <button onclick="executeImport()" id="importBtn2" class="flex-1 px-4 py-3 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i data-lucide="upload" class="w-4 h-4 inline mr-2"></i>
                        İçe Aktar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/toast.php'; ?>

<script>
    const API_BASE = '../api';
    let products = [];
    let categories = [];
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function fetchCategories() {
        try {
            const res = await fetch(`${API_BASE}/categories.php`);
            const data = await res.json();
            if (data.success) {
                categories = data.categories;
                renderCategoryOptions();
            }
        } catch (e) {
            console.error('Categories fetch error:', e);
        }
    }

    async function fetchProducts() {
        try {
            const res = await fetch(`${API_BASE}/products.php`);
            const data = await res.json();
            if (data.success) {
                products = data.products;
                renderProducts();
            }
        } catch (e) {
            console.error('Products fetch error:', e);
        }
    }

    function renderCategoryOptions() {
        const filterSelect = document.getElementById('categoryFilter');
        const formSelect = document.getElementById('productCategory');
        
        const options = categories.map(c => `<option value="${c.id}">${escapeHtml(c.icon)} ${escapeHtml(c.name)}</option>`).join('');
        
        filterSelect.innerHTML = '<option value="">Tüm Kategoriler</option>' + options;
        formSelect.innerHTML = options;
    }

    function renderProducts() {
        const grid = document.getElementById('productsGrid');
        const search = document.getElementById('searchInput').value.toLowerCase();
        const categoryId = document.getElementById('categoryFilter').value;
        
        let filtered = products;
        
        if (search) {
            filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
        }
        
        if (categoryId) {
            filtered = filtered.filter(p => p.category_id == categoryId);
        }
        
        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-12 text-neutral-500">
                    <i data-lucide="package-x" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">Ürün bulunamadı</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }
        
        grid.innerHTML = filtered.map(product => {
            const category = categories.find(c => c.id == product.category_id);
            return `
                <div class="product-card bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl overflow-hidden">
                    <div class="aspect-square bg-neutral-100 dark:bg-neutral-800 relative">
                        ${product.image ? `<img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center"><i data-lucide="image" class="w-12 h-12 text-neutral-300"></i></div>`}
                        ${product.badge ? `<span class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-[10px] font-semibold rounded">${escapeHtml(product.badge)}</span>` : ''}
                        ${!product.is_active ? `<div class="absolute inset-0 bg-black/50 flex items-center justify-center"><span class="text-white text-xs font-medium">Pasif</span></div>` : ''}
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-neutral-400 mb-1">${category ? escapeHtml(category.icon) + ' ' + escapeHtml(category.name) : ''}</p>
                        <h3 class="font-medium text-neutral-900 dark:text-white text-sm line-clamp-2 mb-2">${escapeHtml(product.name)}</h3>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-lg font-bold text-brand">${formatMoney(product.price)}</span>
                            ${product.old_price ? `<span class="text-sm text-neutral-400 line-through">${formatMoney(product.old_price)}</span>` : ''}
                        </div>
                        <div class="flex items-center justify-between text-xs text-neutral-500 mb-3">
                            <span>Stok: ${product.stock}</span>
                            <span>${product.unit}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editProduct(${product.id})" class="flex-1 px-3 py-2 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-xs font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                                <i data-lucide="edit-2" class="w-3 h-3 inline mr-1"></i> Düzenle
                            </button>
                            <button onclick="deleteProduct(${product.id})" class="px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        lucide.createIcons();
    }

    function filterProducts() {
        renderProducts();
    }

    function formatMoney(amount) {
        return parseFloat(amount).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' TL';
    }

    function openProductModal(product = null) {
        const modal = document.getElementById('productModal');
        const content = document.getElementById('productModalContent');
        const title = document.getElementById('productModalTitle');
        const form = document.getElementById('productForm');
        const urlSection = document.getElementById('urlImportSection');
        
        form.reset();
        document.getElementById('productId').value = '';
        document.getElementById('productActive').checked = true;
        document.getElementById('importUrl').value = '';
        clearImagesAndVariants();
        
        if (product) {
            title.textContent = 'Ürünü Düzenle';
            urlSection.style.display = 'none';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productDescription').value = product.description || '';
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productOldPrice').value = product.old_price || '';
            document.getElementById('productCategory').value = product.category_id;
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productUnit').value = product.unit;
            document.getElementById('productBadge').value = product.badge || '';
            document.getElementById('productImage').value = product.image || '';
            document.getElementById('productActive').checked = product.is_active == 1;
            loadImagesAndVariants(product);
        } else {
            title.textContent = 'Yeni Ürün';
            urlSection.style.display = 'block';
        }
        
        modal.classList.remove('opacity-0', 'invisible');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        lucide.createIcons();
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        const content = document.getElementById('productModalContent');
        modal.classList.add('opacity-0', 'invisible');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    function editProduct(id) {
        const product = products.find(p => p.id == id);
        if (product) {
            openProductModal(product);
        }
    }

    async function saveProduct(e) {
        e.preventDefault();
        
        const id = document.getElementById('productId').value;
        const data = {
            name: document.getElementById('productName').value,
            description: document.getElementById('productDescription').value,
            price: parseFloat(document.getElementById('productPrice').value),
            old_price: document.getElementById('productOldPrice').value ? parseFloat(document.getElementById('productOldPrice').value) : null,
            category_id: parseInt(document.getElementById('productCategory').value),
            stock: parseInt(document.getElementById('productStock').value) || 0,
            unit: document.getElementById('productUnit').value,
            badge: document.getElementById('productBadge').value || null,
            image: document.getElementById('productImage').value || null,
            is_active: document.getElementById('productActive').checked ? 1 : 0,
            images: getFormImages(),
            variants: getFormVariants()
        };
        
        try {
            let res;
            if (id) {
                data.id = parseInt(id);
                res = await fetch(`${API_BASE}/products.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            } else {
                res = await fetch(`${API_BASE}/products.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            }
            
            const result = await res.json();
            
            if (result.success) {
                showToast(id ? 'Ürün güncellendi' : 'Ürün eklendi', 'success');
                closeProductModal();
                fetchProducts();
            } else {
                showToast(result.error || 'Bir hata oluştu', 'error');
            }
        } catch (e) {
            showToast('Bir hata oluştu', 'error');
        }
    }

    function deleteProduct(id) {
        const product = products.find(p => p.id == id);
        if (!product) return;
        
        document.getElementById('deleteProductId').value = id;
        document.getElementById('deleteProductName').textContent = `"${product.name}" ürününü silmek istediğinize emin misiniz?`;
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        modal.classList.remove('opacity-0', 'invisible');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        lucide.createIcons();
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        modal.classList.add('opacity-0', 'invisible');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    async function confirmDelete() {
        const id = document.getElementById('deleteProductId').value;
        
        try {
            const res = await fetch(`${API_BASE}/products.php?id=${id}`, {
                method: 'DELETE'
            });
            const data = await res.json();
            
            if (data.success) {
                showToast('Ürün silindi', 'success');
                fetchProducts();
            } else {
                showToast(data.error || 'Silme işlemi başarısız', 'error');
            }
        } catch (e) {
            showToast('Bir hata oluştu', 'error');
        }
        
        closeDeleteModal();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeProductModal();
            closeDeleteModal();
            closeImportExportModal();
        }
    });
    
    function openImportExportModal() {
        const modal = document.getElementById('importExportModal');
        const content = document.getElementById('importExportModalContent');
        
        updateExportPreview();
        switchImportExportTab('export');
        
        modal.classList.remove('opacity-0', 'invisible');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        lucide.createIcons();
    }
    
    function closeImportExportModal() {
        const modal = document.getElementById('importExportModal');
        const content = document.getElementById('importExportModalContent');
        modal.classList.add('opacity-0', 'invisible');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        document.getElementById('importData').value = '';
        document.getElementById('importPreviewSection').classList.add('hidden');
        document.getElementById('importBtn2').disabled = true;
    }
    
    function switchImportExportTab(tab) {
        const tabExport = document.getElementById('tabExport');
        const tabImport = document.getElementById('tabImport');
        const exportSection = document.getElementById('exportSection');
        const importSection = document.getElementById('importSection');
        
        if (tab === 'export') {
            tabExport.classList.remove('bg-neutral-100', 'dark:bg-neutral-800', 'text-neutral-700', 'dark:text-neutral-300');
            tabExport.classList.add('bg-brand', 'text-white');
            tabImport.classList.remove('bg-brand', 'text-white');
            tabImport.classList.add('bg-neutral-100', 'dark:bg-neutral-800', 'text-neutral-700', 'dark:text-neutral-300');
            exportSection.classList.remove('hidden');
            importSection.classList.add('hidden');
            updateExportPreview();
        } else {
            tabImport.classList.remove('bg-neutral-100', 'dark:bg-neutral-800', 'text-neutral-700', 'dark:text-neutral-300');
            tabImport.classList.add('bg-brand', 'text-white');
            tabExport.classList.remove('bg-brand', 'text-white');
            tabExport.classList.add('bg-neutral-100', 'dark:bg-neutral-800', 'text-neutral-700', 'dark:text-neutral-300');
            importSection.classList.remove('hidden');
            exportSection.classList.add('hidden');
        }
        lucide.createIcons();
    }
    
    function updateExportPreview() {
        document.getElementById('exportCount').textContent = `${products.length} ürün`;
        document.getElementById('exportPreview').textContent = `LINCOLN:1.0:WkdGc1lYTnBabWxsWkE9PQ...

Dosya şifreli olarak indirilecek.
Toplam ${products.length} ürün.`;
    }
    
    const LINCOLN_KEY = 'LincolnBurrows2026!@#';
    
    async function formatWithAI() {
        const textarea = document.getElementById('productDescription');
        const btn = document.getElementById('aiFormatBtn');
        const icon = document.getElementById('aiIcon');
        const btnText = document.getElementById('aiBtnText');
        const productName = document.getElementById('productName').value;
        
        const text = textarea.value.trim();
        if (!text && !productName) {
            showToast('Ürün adı veya açıklama gerekli', 'error');
            return;
        }
        
        btn.disabled = true;
        icon.classList.add('animate-spin');
        btnText.textContent = 'Düzenleniyor...';
        
        try {
            const prompt = `Sen bir e-ticaret ürün açıklaması yazarısın. Ürün adına ve varsa mevcut açıklamaya bakarak profesyonel bir ürün açıklaması oluştur.

GÖREV:
- Ürün adından ürünün ne olduğunu anla
- Mevcut açıklama eksik veya boşsa, ürün adına göre mantıklı özellikler ekle
- Ürünün muhtemel özelliklerini, faydalarını yaz

MARKDOWN FORMATI:
- Özellikler için madde işareti (-) kullan  
- Önemli kelimeleri **kalın** yap
- Kısa ve öz tut (3-5 satır)
- Başlık kullanma, direkt açıklamaya başla
- Sadece markdown çıktısı ver

Ürün Adı: ${productName || 'Belirtilmemiş'}
Mevcut Açıklama: ${text || 'Yok'}

Markdown açıklama:`;

            const response = await puter.ai.chat(prompt, {
                model: 'gpt-5.2'
            });
            
            if (response && response.message && response.message.content) {
                textarea.value = response.message.content.trim();
                showToast('Açıklama düzenlendi', 'success');
            } else {
                throw new Error('AI yanıt vermedi');
            }
        } catch (e) {
            console.error('AI format error:', e);
            showToast('AI hatası: ' + e.message, 'error');
        } finally {
            btn.disabled = false;
            icon.classList.remove('animate-spin');
            btnText.textContent = 'AI ile Düzenle';
            lucide.createIcons();
        }
    }
    
    function lincolnEncrypt(text) {
        let result = '';
        for (let i = 0; i < text.length; i++) {
            result += String.fromCharCode(text.charCodeAt(i) ^ LINCOLN_KEY.charCodeAt(i % LINCOLN_KEY.length));
        }
        return btoa(unescape(encodeURIComponent(result)));
    }
    
    function lincolnDecrypt(encoded) {
        try {
            const decoded = decodeURIComponent(escape(atob(encoded)));
            let result = '';
            for (let i = 0; i < decoded.length; i++) {
                result += String.fromCharCode(decoded.charCodeAt(i) ^ LINCOLN_KEY.charCodeAt(i % LINCOLN_KEY.length));
            }
            return result;
        } catch (e) {
            return null;
        }
    }
    
    function exportProducts(type) {
        const exportData = {
            _format: 'lincoln',
            _version: '1.0',
            _exported: new Date().toISOString(),
            _count: products.length,
            products: products.map(p => ({
                name: p.name,
                description: p.description,
                price: parseFloat(p.price),
                old_price: p.old_price ? parseFloat(p.old_price) : null,
                category_id: parseInt(p.category_id),
                stock: parseInt(p.stock),
                unit: p.unit,
                badge: p.badge,
                image: p.image,
                is_active: p.is_active == 1,
                images: (p.images || []).map(img => img.image_url),
                variants: (p.variants || []).map(v => ({
                    type: v.variant_type,
                    name: v.variant_name,
                    image: v.image_url
                }))
            }))
        };
        
        const jsonStr = JSON.stringify(exportData);
        
        if (type === 'lincoln') {
            const encrypted = lincolnEncrypt(jsonStr);
            const fileContent = `LINCOLN:1.0:${encrypted}`;
            const blob = new Blob([fileContent], { type: 'application/lincoln' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `products_${new Date().toISOString().split('T')[0]}.lincoln`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('Şifreli dosya indirildi', 'success');
        } else {
            navigator.clipboard.writeText(jsonStr).then(() => {
                showToast('Panoya kopyalandı (şifresiz)', 'success');
            }).catch(() => {
                showToast('Kopyalama başarısız', 'error');
            });
        }
    }
    
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            let content = e.target.result;
            
            if (content.startsWith('LINCOLN:')) {
                const parts = content.split(':');
                if (parts.length >= 3) {
                    const encrypted = parts.slice(2).join(':');
                    const decrypted = lincolnDecrypt(encrypted);
                    if (decrypted) {
                        content = decrypted;
                        showToast('Dosya şifresi çözüldü', 'success');
                    } else {
                        showToast('Şifre çözme başarısız', 'error');
                        return;
                    }
                }
            }
            
            document.getElementById('importData').value = content;
            validateImport();
        };
        reader.readAsText(file);
    }
    
    let validatedProducts = [];
    
    function validateImport() {
        const data = document.getElementById('importData').value.trim();
        const previewSection = document.getElementById('importPreviewSection');
        const previewText = document.getElementById('importPreviewText');
        const previewList = document.getElementById('importPreviewList');
        const importBtn = document.getElementById('importBtn2');
        
        if (!data) {
            showToast('Lütfen .lincoln verisi girin', 'error');
            return;
        }
        
        try {
            const parsed = JSON.parse(data);
            
            let productList = [];
            if (parsed._format === 'lincoln' && Array.isArray(parsed.products)) {
                productList = parsed.products;
            } else if (Array.isArray(parsed)) {
                productList = parsed;
            } else {
                showToast('Geçersiz .lincoln formatı', 'error');
                return;
            }
            
            validatedProducts = productList.filter(p => p.name && p.price);
            
            if (validatedProducts.length === 0) {
                showToast('Geçerli ürün bulunamadı', 'error');
                return;
            }
            
            previewText.textContent = `${validatedProducts.length} ürün içe aktarılacak`;
            previewList.innerHTML = validatedProducts.slice(0, 10).map(p => 
                `<li>• ${escapeHtml(p.name)} - ${formatMoney(p.price)}</li>`
            ).join('') + (validatedProducts.length > 10 ? `<li class="text-neutral-400">... ve ${validatedProducts.length - 10} ürün daha</li>` : '');
            
            previewSection.classList.remove('hidden');
            importBtn.disabled = false;
            
            showToast(`${validatedProducts.length} ürün doğrulandı`, 'success');
            lucide.createIcons();
        } catch (e) {
            showToast('Geçersiz JSON formatı: ' + e.message, 'error');
            previewSection.classList.add('hidden');
            importBtn.disabled = true;
        }
    }
    
    async function executeImport() {
        if (validatedProducts.length === 0) {
            showToast('Önce verileri doğrulayın', 'error');
            return;
        }
        
        const importBtn = document.getElementById('importBtn2');
        importBtn.disabled = true;
        importBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 inline mr-2 animate-spin"></i> İçe aktarılıyor...';
        lucide.createIcons();
        
        let successCount = 0;
        let errorCount = 0;
        
        for (const product of validatedProducts) {
            try {
                const data = {
                    name: product.name,
                    description: product.description || '',
                    price: parseFloat(product.price),
                    old_price: product.old_price ? parseFloat(product.old_price) : null,
                    category_id: product.category_id || categories[0]?.id || 1,
                    stock: parseInt(product.stock) || 0,
                    unit: product.unit || 'adet',
                    badge: product.badge || null,
                    image: product.image || null,
                    is_active: product.is_active !== false ? 1 : 0,
                    images: Array.isArray(product.images) ? product.images.map(img => typeof img === 'string' ? img : img.image_url) : [],
                    variants: Array.isArray(product.variants) ? product.variants.map(v => ({
                        type: v.type || v.variant_type || 'color',
                        name: v.name || v.variant_name,
                        image: v.image || v.image_url || ''
                    })) : []
                };
                
                const res = await fetch(`${API_BASE}/products.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await res.json();
                
                if (result.success) {
                    successCount++;
                } else {
                    errorCount++;
                }
            } catch (e) {
                errorCount++;
            }
        }
        
        importBtn.disabled = false;
        importBtn.innerHTML = '<i data-lucide="upload" class="w-4 h-4 inline mr-2"></i> İçe Aktar';
        lucide.createIcons();
        
        if (successCount > 0) {
            showToast(`${successCount} ürün eklendi${errorCount > 0 ? `, ${errorCount} hata` : ''}`, successCount > errorCount ? 'success' : 'warning');
            fetchProducts();
            closeImportExportModal();
        } else {
            showToast('Hiçbir ürün eklenemedi', 'error');
        }
    }

    let imageFieldCount = 0;
    let variantFieldCount = 0;
    
    function addImageField(value = '') {
        const container = document.getElementById('additionalImagesContainer');
        const id = imageFieldCount++;
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.id = `imageField_${id}`;
        div.innerHTML = `
            <input type="url" name="images[]" value="${escapeHtml(value)}" placeholder="https://..." class="flex-1 bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
            <button type="button" onclick="removeImageField(${id})" class="p-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        `;
        container.appendChild(div);
        lucide.createIcons();
    }
    
    function removeImageField(id) {
        const field = document.getElementById(`imageField_${id}`);
        if (field) field.remove();
    }
    
    function addVariantField(variant = null) {
        const container = document.getElementById('variantsContainer');
        const id = variantFieldCount++;
        const div = document.createElement('div');
        div.className = 'bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-3 space-y-2';
        div.id = `variantField_${id}`;
        div.innerHTML = `
            <div class="flex gap-2">
                <select name="variant_type_${id}" class="bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-3 py-2 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                    <option value="color" ${variant?.type === 'color' ? 'selected' : ''}>Renk</option>
                    <option value="model" ${variant?.type === 'model' ? 'selected' : ''}>Model</option>
                </select>
                <input type="text" name="variant_name_${id}" value="${escapeHtml(variant?.name || '')}" placeholder="Varyant adı (örn: Kırmızı, 128GB)" class="flex-1 bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-3 py-2 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
                <button type="button" onclick="removeVariantField(${id})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <input type="url" name="variant_image_${id}" value="${escapeHtml(variant?.image || '')}" placeholder="Varyant görseli URL (opsiyonel)" class="w-full bg-neutral-100 dark:bg-neutral-800 border-0 rounded-lg px-3 py-2 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand">
        `;
        container.appendChild(div);
        lucide.createIcons();
    }
    
    function removeVariantField(id) {
        const field = document.getElementById(`variantField_${id}`);
        if (field) field.remove();
    }
    
    function getFormImages() {
        const inputs = document.querySelectorAll('input[name="images[]"]');
        return Array.from(inputs).map(input => input.value).filter(v => v);
    }
    
    function getFormVariants() {
        const container = document.getElementById('variantsContainer');
        const variants = [];
        container.querySelectorAll('[id^="variantField_"]').forEach(div => {
            const id = div.id.split('_')[1];
            const type = div.querySelector(`[name="variant_type_${id}"]`)?.value;
            const name = div.querySelector(`[name="variant_name_${id}"]`)?.value;
            const image = div.querySelector(`[name="variant_image_${id}"]`)?.value;
            if (name) {
                variants.push({ type, name, image });
            }
        });
        return variants;
    }
    
    function clearImagesAndVariants() {
        document.getElementById('additionalImagesContainer').innerHTML = '';
        document.getElementById('variantsContainer').innerHTML = '';
        imageFieldCount = 0;
        variantFieldCount = 0;
    }
    
    function loadImagesAndVariants(product) {
        clearImagesAndVariants();
        if (product.images && product.images.length > 0) {
            product.images.forEach(img => addImageField(img.image_url));
        }
        if (product.variants && product.variants.length > 0) {
            product.variants.forEach(v => addVariantField({
                type: v.variant_type,
                name: v.variant_name,
                image: v.image_url
            }));
        }
    }

    async function importFromUrl() {
        const url = document.getElementById('importUrl').value.trim();
        
        if (!url) {
            showToast('Lütfen bir URL girin', 'error');
            return;
        }
        
        if (!url.includes('migros.com')) {
            showToast('Sadece Migros linkleri destekleniyor', 'error');
            return;
        }
        
        const btn = document.getElementById('importBtn');
        const icon = document.getElementById('importIcon');
        const btnText = document.getElementById('importBtnText');
        
        btn.disabled = true;
        icon.classList.add('animate-spin');
        btnText.textContent = 'Çekiliyor...';
        
        try {
            const res = await fetch(`${API_BASE}/scraper.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url })
            });
            
            const data = await res.json();
            
            if (data.success && data.product) {
                const p = data.product;
                
                if (p.name) document.getElementById('productName').value = p.name;
                if (p.description) document.getElementById('productDescription').value = p.description;
                if (p.price) document.getElementById('productPrice').value = p.price;
                if (p.old_price) document.getElementById('productOldPrice').value = p.old_price;
                if (p.image) document.getElementById('productImage').value = p.image;
                
                document.getElementById('importUrl').value = '';
                
                showToast('Ürün bilgileri alındı!', 'success');
            } else {
                showToast(data.error || 'Ürün bilgileri alınamadı', 'error');
            }
        } catch (e) {
            console.error('Import error:', e);
            showToast('Bir hata oluştu', 'error');
        } finally {
            btn.disabled = false;
            icon.classList.remove('animate-spin');
            btnText.textContent = 'Çek';
        }
    }
    
    document.getElementById('importUrl').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            importFromUrl();
        }
    });
    
    document.getElementById('importUrl').addEventListener('paste', function(e) {
        setTimeout(() => {
            if (this.value.includes('migros.com')) {
                importFromUrl();
            }
        }, 100);
    });

    fetchCategories().then(() => fetchProducts());
</script>

<?php include 'includes/footer.php'; ?>
