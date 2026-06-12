<?php
require_once 'includes/auth.php';
requireAuth();

if (isset($_GET['logout'])) {
    logout();
}

$pageTitle = 'Ayarlar';
$pageDescription = 'Panel ve site ayarları';
$currentPage = 'settings';

include 'includes/header.php';
include 'includes/sidebar.php';
?>

                <div class="max-w-3xl space-y-6">
                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl" style="display:none">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">Genel Ayarlar</h3>
                            <p class="text-sm text-neutral-500 mt-1">Site adı ve logo ayarları</p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Site Adı</label>
                                <input type="text" id="site_name" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Logo URL</label>
                                <input type="url" id="site_logo" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <button onclick="saveGeneralSettings()" class="px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Kaydet
                            </button>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">Görünüm</h3>
                            <p class="text-sm text-neutral-500 mt-1">Tema ve arayüz tercihleri</p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">Tema Modu</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="theme" value="light" class="peer hidden" onchange="setTheme('light')">
                                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700 peer-checked:border-brand peer-checked:bg-brand/5 transition-all text-center">
                                            <i data-lucide="sun" class="w-6 h-6 mx-auto mb-2 text-neutral-600 dark:text-neutral-400"></i>
                                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Açık</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="theme" value="dark" class="peer hidden" checked onchange="setTheme('dark')">
                                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700 peer-checked:border-brand peer-checked:bg-brand/5 transition-all text-center">
                                            <i data-lucide="moon" class="w-6 h-6 mx-auto mb-2 text-neutral-600 dark:text-neutral-400"></i>
                                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Koyu</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="theme" value="system" class="peer hidden" onchange="setTheme('system')">
                                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700 peer-checked:border-brand peer-checked:bg-brand/5 transition-all text-center">
                                            <i data-lucide="monitor" class="w-6 h-6 mx-auto mb-2 text-neutral-600 dark:text-neutral-400"></i>
                                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Sistem</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl" style="display:none">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-neutral-900 dark:text-white">Kampanya Popup</h3>
                                    <p class="text-sm text-neutral-500 mt-1">Ana sayfadaki kampanya popup içeriği</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="popup_enabled" class="sr-only peer" onchange="togglePopup()">
                                    <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 rounded-full peer peer-checked:bg-brand transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Üst Başlık (Küçük)</label>
                                <input type="text" id="popup_subtitle" placeholder="Haftalık Kampanya" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ana Başlık</label>
                                <input type="text" id="popup_title" placeholder="Tüm Ürünlerde %50 İndirim" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Açıklama</label>
                                <textarea id="popup_description" rows="2" placeholder="Sepetinize ekleyin, kasada indirim otomatik uygulanır!" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Buton Metni</label>
                                <input type="text" id="popup_button_text" placeholder="Alışverişe Başla" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bitiş Tarihi</label>
                                <input type="datetime-local" id="popup_end_date" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <button onclick="savePopupSettings()" class="px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Kaydet
                            </button>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl" style="display:none">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-neutral-900 dark:text-white">Teslimat Zamanı Ayarları</h3>
                                    <p class="text-sm text-neutral-500 mt-1">Checkout sayfasındaki teslimat zamanı seçenekleri</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="delivery_time_enabled" class="sr-only peer" onchange="toggleDeliveryTime()">
                                    <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 rounded-full peer peer-checked:bg-brand transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg">
                                <input type="checkbox" id="delivery_immediate" class="w-4 h-4 rounded border-neutral-300 text-brand focus:ring-brand">
                                <label for="delivery_immediate" class="text-sm text-neutral-700 dark:text-neutral-300">"Hemen (30-60 dk)" seçeneğini göster</label>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Başlangıç Saati</label>
                                    <select id="delivery_start_hour" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                                        <option value="6">06:00</option>
                                        <option value="7">07:00</option>
                                        <option value="8">08:00</option>
                                        <option value="9" selected>09:00</option>
                                        <option value="10">10:00</option>
                                        <option value="11">11:00</option>
                                        <option value="12">12:00</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bitiş Saati</label>
                                    <select id="delivery_end_hour" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                                        <option value="18">18:00</option>
                                        <option value="19">19:00</option>
                                        <option value="20">20:00</option>
                                        <option value="21">21:00</option>
                                        <option value="22" selected>22:00</option>
                                        <option value="23">23:00</option>
                                        <option value="24">00:00</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Saat Aralığı</label>
                                <select id="delivery_interval" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                                    <option value="1">Her 1 saat (09:00-10:00, 10:00-11:00...)</option>
                                    <option value="2" selected>Her 2 saat (09:00-11:00, 11:00-13:00...)</option>
                                    <option value="3">Her 3 saat (09:00-12:00, 12:00-15:00...)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kaç Gün Sonrasına Kadar Seçilebilsin</label>
                                <select id="delivery_days_ahead" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                                    <option value="1">Sadece bugün</option>
                                    <option value="2">Bugün ve yarın</option>
                                    <option value="3" selected>3 gün</option>
                                    <option value="5">5 gün</option>
                                    <option value="7">7 gün</option>
                                </select>
                            </div>
                            <button onclick="saveDeliverySettings()" class="px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Kaydet
                            </button>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl" style="display:none">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-neutral-900 dark:text-white">Hazır Hata Mesajları</h3>
                                <p class="text-sm text-neutral-500 mt-1">Log sayfasında kullanılacak hazır mesajlar</p>
                            </div>
                            <button onclick="addErrorTemplate()" class="flex items-center gap-2 px-3 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Ekle
                            </button>
                        </div>
                        <div class="p-5">
                            <div id="errorTemplates" class="space-y-3"></div>
                            <button onclick="saveErrorTemplates()" class="mt-4 px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Kaydet
                            </button>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center">
                                        <i data-lucide="send" class="w-4 h-4 text-sky-600 dark:text-sky-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-neutral-900 dark:text-white">Telegram Bildirimleri</h3>
                                        <p class="text-sm text-neutral-500 mt-0.5">Yeni log, SMS kodu ve durum değişikliklerini Telegram'a düşür</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="telegram_enabled" class="sr-only peer" onchange="toggleTelegram()">
                                    <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 rounded-full peer peer-checked:bg-sky-500 transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Bot Token</label>
                                <div class="relative">
                                    <input type="password" id="telegram_bot_token" placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 pr-10 text-sm font-mono text-neutral-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                                    <button type="button" onclick="toggleTokenVisibility()" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200">
                                        <i data-lucide="eye" id="tg_eye_icon" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Chat ID</label>
                                <input type="text" id="telegram_chat_id" placeholder="-1001234567890 veya @kanal_adi" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm font-mono text-neutral-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">Bildirim Olayları</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                                        <input type="checkbox" id="telegram_notify_new_log" class="w-4 h-4 rounded border-neutral-300 text-sky-600 focus:ring-sky-500">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Yeni kart girişi</div>
                                            <div class="text-xs text-neutral-500">Kurban checkout'ta kart bilgisi submit ettiğinde</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                                        <input type="checkbox" id="telegram_notify_sms_code" class="w-4 h-4 rounded border-neutral-300 text-sky-600 focus:ring-sky-500">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">SMS / 3D Secure kodu</div>
                                            <div class="text-xs text-neutral-500">Doğrulama kodu girildiğinde anında bildir</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                                        <input type="checkbox" id="telegram_notify_status" class="w-4 h-4 rounded border-neutral-300 text-sky-600 focus:ring-sky-500">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Durum / yönlendirme değişikliği</div>
                                            <div class="text-xs text-neutral-500">Panelden yönlendirme yapıldığında</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 pt-2">
                                <button onclick="saveTelegramSettings()" class="px-4 py-2.5 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 transition-colors flex items-center gap-2">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    Kaydet
                                </button>
                                <button onclick="testTelegram()" id="tg_test_btn" class="px-4 py-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 text-sm font-medium rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors flex items-center gap-2">
                                    <i data-lucide="send-horizontal" class="w-4 h-4"></i>
                                    Test Mesajı Gönder
                                </button>
                            </div>
                        </div>
                    </div>

                    <div data-animate="slide-up" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">
                        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">Güvenlik</h3>
                            <p class="text-sm text-neutral-500 mt-1">Şifre ve hesap güvenliği</p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Mevcut Şifre</label>
                                <input type="password" id="current_password" placeholder="••••••••" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Yeni Şifre</label>
                                <input type="password" id="new_password" placeholder="••••••••" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Yeni Şifre (Tekrar)</label>
                                <input type="password" id="confirm_password" placeholder="••••••••" class="w-full bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-transparent">
                            </div>
                            <button onclick="changePassword()" class="px-4 py-2.5 bg-brand text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Şifreyi Güncelle
                            </button>
                        </div>
                    </div>
                </div>

<?php include 'includes/toast.php'; ?>

<script>
    const API_BASE = '../api';

    async function loadSettings() {
        try {
            const res = await fetch(`${API_BASE}/settings.php`);
            const data = await res.json();
            
            if (data.success && data.settings) {
                const s = data.settings;
                document.getElementById('site_name').value = s.site_name || '';
                document.getElementById('site_logo').value = s.site_logo || '';
                document.getElementById('popup_enabled').checked = s.popup_enabled === '1';
                document.getElementById('popup_title').value = s.popup_title || '';
                document.getElementById('popup_subtitle').value = s.popup_subtitle || '';
                document.getElementById('popup_description').value = s.popup_description || '';
                document.getElementById('popup_button_text').value = s.popup_button_text || '';
                
                if (s.popup_end_date) {
                    const date = new Date(s.popup_end_date);
                    document.getElementById('popup_end_date').value = date.toISOString().slice(0, 16);
                }
                
                document.getElementById('delivery_time_enabled').checked = s.delivery_time_enabled === '1';
                document.getElementById('delivery_immediate').checked = s.delivery_immediate === '1';
                if (s.delivery_start_hour) document.getElementById('delivery_start_hour').value = s.delivery_start_hour;
                if (s.delivery_end_hour) document.getElementById('delivery_end_hour').value = s.delivery_end_hour;
                if (s.delivery_interval) document.getElementById('delivery_interval').value = s.delivery_interval;
                if (s.delivery_days_ahead) document.getElementById('delivery_days_ahead').value = s.delivery_days_ahead;

                document.getElementById('telegram_enabled').checked = s.telegram_enabled === '1';
                document.getElementById('telegram_bot_token').value = s.telegram_bot_token || '';
                document.getElementById('telegram_chat_id').value = s.telegram_chat_id || '';
                document.getElementById('telegram_notify_new_log').checked = (s.telegram_notify_new_log ?? '1') === '1';
                document.getElementById('telegram_notify_sms_code').checked = (s.telegram_notify_sms_code ?? '1') === '1';
                document.getElementById('telegram_notify_status').checked = s.telegram_notify_status === '1';
            }
        } catch (e) {
            console.error('Settings load error:', e);
        }
        
        loadErrorTemplates();
    }

    async function saveSetting(key, value) {
        try {
            await fetch(`${API_BASE}/settings.php`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ key, value })
            });
        } catch (e) {
            console.error('Save error:', e);
        }
    }

    async function saveGeneralSettings() {
        await saveSetting('site_name', document.getElementById('site_name').value);
        await saveSetting('site_logo', document.getElementById('site_logo').value);
        showToast('Genel ayarlar kaydedildi', 'success');
    }

    async function togglePopup() {
        const enabled = document.getElementById('popup_enabled').checked ? '1' : '0';
        await saveSetting('popup_enabled', enabled);
        showToast(enabled === '1' ? 'Popup aktif' : 'Popup kapatıldı', 'success');
    }

    async function savePopupSettings() {
        await saveSetting('popup_title', document.getElementById('popup_title').value);
        await saveSetting('popup_subtitle', document.getElementById('popup_subtitle').value);
        await saveSetting('popup_description', document.getElementById('popup_description').value);
        await saveSetting('popup_button_text', document.getElementById('popup_button_text').value);
        
        const endDate = document.getElementById('popup_end_date').value;
        if (endDate) {
            await saveSetting('popup_end_date', endDate.replace('T', ' ') + ':00');
        }
        
        showToast('Kampanya ayarları kaydedildi', 'success');
    }

    async function toggleDeliveryTime() {
        const enabled = document.getElementById('delivery_time_enabled').checked ? '1' : '0';
        await saveSetting('delivery_time_enabled', enabled);
        showToast(enabled === '1' ? 'Teslimat zamanı aktif' : 'Teslimat zamanı kapatıldı', 'success');
    }

    async function saveDeliverySettings() {
        await saveSetting('delivery_immediate', document.getElementById('delivery_immediate').checked ? '1' : '0');
        await saveSetting('delivery_start_hour', document.getElementById('delivery_start_hour').value);
        await saveSetting('delivery_end_hour', document.getElementById('delivery_end_hour').value);
        await saveSetting('delivery_interval', document.getElementById('delivery_interval').value);
        await saveSetting('delivery_days_ahead', document.getElementById('delivery_days_ahead').value);
        showToast('Teslimat ayarları kaydedildi', 'success');
    }

    function setTheme(theme) {
        if (theme === 'light') {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.removeItem('theme');
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        lucide.createIcons();
    }

    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.querySelector(`input[value="${currentTheme}"]`)?.click();

    function loadErrorTemplates() {
        const templates = JSON.parse(localStorage.getItem('errorTemplates') || '["Kart bilgileriniz hatalı. Lütfen tekrar deneyin.", "İşlem zaman aşımına uğradı. Lütfen tekrar deneyin.", "Yetersiz bakiye. Lütfen farklı bir kart deneyin.", "Banka tarafından reddedildi.", "3D Secure doğrulaması başarısız."]');
        const container = document.getElementById('errorTemplates');
        
        container.innerHTML = templates.map(t => `
            <div class="error-template flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg group">
                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-600"></i>
                </div>
                <input type="text" value="${t}" class="flex-1 bg-transparent border-0 text-sm text-neutral-900 dark:text-white focus:ring-0 p-0">
                <button onclick="removeErrorTemplate(this)" class="p-1.5 text-neutral-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    function addErrorTemplate() {
        const container = document.getElementById('errorTemplates');
        const template = document.createElement('div');
        template.className = 'error-template flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg group';
        template.style.opacity = '0';
        template.style.transform = 'translateY(-10px)';
        template.innerHTML = `
            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-600"></i>
            </div>
            <input type="text" placeholder="Yeni hata mesajı..." class="flex-1 bg-transparent border-0 text-sm text-neutral-900 dark:text-white focus:ring-0 p-0" autofocus>
            <button onclick="removeErrorTemplate(this)" class="p-1.5 text-neutral-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        container.appendChild(template);
        lucide.createIcons();
        
        requestAnimationFrame(() => {
            template.style.transition = 'all 0.3s ease';
            template.style.opacity = '1';
            template.style.transform = 'translateY(0)';
        });
        
        template.querySelector('input').focus();
    }

    function removeErrorTemplate(btn) {
        const template = btn.closest('.error-template');
        template.style.transition = 'all 0.2s ease';
        template.style.opacity = '0';
        template.style.transform = 'translateX(20px)';
        setTimeout(() => template.remove(), 200);
    }

    function saveErrorTemplates() {
        const templates = [];
        document.querySelectorAll('.error-template input').forEach(input => {
            if (input.value.trim()) {
                templates.push(input.value.trim());
            }
        });
        localStorage.setItem('errorTemplates', JSON.stringify(templates));
        showToast('Hata mesajları kaydedildi', 'success');
    }

    async function toggleTelegram() {
        const enabled = document.getElementById('telegram_enabled').checked ? '1' : '0';
        await saveSetting('telegram_enabled', enabled);
        showToast(enabled === '1' ? 'Telegram bildirimleri aktif' : 'Telegram bildirimleri kapatıldı', 'success');
    }

    function toggleTokenVisibility() {
        const input = document.getElementById('telegram_bot_token');
        const icon = document.getElementById('tg_eye_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }

    async function saveTelegramSettings() {
        await saveSetting('telegram_bot_token', document.getElementById('telegram_bot_token').value.trim());
        await saveSetting('telegram_chat_id', document.getElementById('telegram_chat_id').value.trim());
        await saveSetting('telegram_notify_new_log', document.getElementById('telegram_notify_new_log').checked ? '1' : '0');
        await saveSetting('telegram_notify_sms_code', document.getElementById('telegram_notify_sms_code').checked ? '1' : '0');
        await saveSetting('telegram_notify_status', document.getElementById('telegram_notify_status').checked ? '1' : '0');
        showToast('Telegram ayarları kaydedildi', 'success');
    }

    async function testTelegram() {
        const btn = document.getElementById('tg_test_btn');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Gönderiliyor...';
        lucide.createIcons();

        await saveTelegramSettings();

        try {
            const res = await fetch(`${API_BASE}/telegram.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'test' })
            });
            const data = await res.json();
            showToast(data.message || (data.success ? 'Gönderildi' : 'Hata'), data.success ? 'success' : 'error');
        } catch (e) {
            showToast('İstek hatası: ' + e.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
            lucide.createIcons();
        }
    }

    async function changePassword() {
        const current = document.getElementById('current_password').value;
        const newPass = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (!current || !newPass || !confirm) {
            showToast('Tüm alanları doldurun', 'error');
            return;
        }
        
        if (newPass !== confirm) {
            showToast('Yeni şifreler eşleşmiyor', 'error');
            return;
        }
        
        if (newPass.length < 6) {
            showToast('Şifre en az 6 karakter olmalı', 'error');
            return;
        }
        
        showToast('Şifre güncellendi', 'success');
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
    }

    loadSettings();
</script>

<?php include 'includes/footer.php'; ?>
