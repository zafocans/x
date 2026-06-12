        <aside class="fixed left-0 top-0 h-screen w-64 bg-white dark:bg-neutral-900 border-r border-neutral-200 dark:border-neutral-800 flex flex-col z-40">
            <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <img src="https://iili.io/qdxx9z7.jpg" alt="FLOR1CK" class="w-9 h-9 rounded-lg object-cover">
                    <div>
                        <h1 class="font-semibold text-neutral-900 dark:text-white text-sm">FLOR1CK</h1>
                        <p class="text-xs text-neutral-500">Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                <a href="index.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>
                <a href="orders.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors <?= ($currentPage ?? '') === 'orders' ? 'active' : '' ?>">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    Log Tablosu
                </a>
                <a href="visitors.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors <?= ($currentPage ?? '') === 'visitors' ? 'active' : '' ?>">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Ziyaretçiler
                </a>
                <a href="bans.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors <?= ($currentPage ?? '') === 'bans' ? 'active' : '' ?>">
                    <i data-lucide="ban" class="w-4 h-4"></i>
                    Yasaklamalar
                </a>

                <div class="pt-4 mt-4 border-t border-neutral-200 dark:border-neutral-800">
                    <a href="settings.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors <?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Ayarlar
                    </a>
                </div>
            </nav>

            <div class="p-3 border-t border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center justify-between px-3 py-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-neutral-200 dark:bg-neutral-700 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-neutral-600 dark:text-neutral-400"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-neutral-900 dark:text-white">Admin</p>
                            <p class="text-[10px] text-neutral-500">Çevrimiçi</p>
                        </div>
                    </div>
                    <a href="?logout=1" class="p-2 text-neutral-400 hover:text-red-500 transition-colors" title="Çıkış">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </aside>

        <main class="flex-1 ml-64">
            <header class="sticky top-0 z-30 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-sm border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?= $pageTitle ?? 'Dashboard' ?></h2>
                        <p class="text-sm text-neutral-500"><?= $pageDescription ?? '' ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="toggleTheme()" class="p-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors">
                            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
                            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
                        </button>
                        <button class="p-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors relative">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-6">
