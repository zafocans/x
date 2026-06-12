<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$lockoutRemaining = 0;
$ip = getClientIP();

if (isLockedOut($ip)) {
    $lockoutRemaining = getRemainingLockoutTime($ip);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $lockoutRemaining === 0) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Geçersiz istek. Lütfen sayfayı yenileyin.';
        logSecurityEvent('csrf_failed', ['username' => $username]);
    } else {
        $result = login($username, $password);
        
        if ($result['success']) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['error'];
            
            if (isLockedOut($ip)) {
                $lockoutRemaining = getRemainingLockoutTime($ip);
            }
        }
    }
}

$csrfToken = getCSRFToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>Giriş - FLOR1CK Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/motion@11.15.0/dist/motion.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'brand': '#0056A4',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #0a0a0a 0%, #171717 100%);
        }
        .login-container { opacity: 0; }
    </style>
</head>
<body class="login-bg min-h-screen font-sans flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="login-container w-full max-w-sm relative z-10">
        <div class="text-center mb-8">
            <img src="https://iili.io/qdxx9z7.jpg" alt="FLOR1CK" class="w-16 h-16 rounded-xl mx-auto mb-4 object-cover">
            <h1 class="text-xl font-semibold text-white">FLOR1CK Panel</h1>
            <p class="text-sm text-neutral-500 mt-1">Yönetim paneline giriş yapın</p>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
            <?php if ($lockoutRemaining > 0): ?>
            <div class="bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3 mb-4">
                <div class="flex items-center gap-2 text-red-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <p class="text-sm">Çok fazla başarısız deneme.</p>
                </div>
                <p class="text-xs text-red-400/70 mt-1">Lütfen <span id="lockoutTimer"><?= ceil($lockoutRemaining / 60) ?></span> dakika bekleyin.</p>
            </div>
            <?php elseif ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3 mb-4">
                <p class="text-sm text-red-400"><?= htmlspecialchars($error) ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4" id="loginForm" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                
                <div>
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Kullanıcı Adı</label>
                    <input type="text" name="username" required placeholder="admin" maxlength="50" autocomplete="username" <?= $lockoutRemaining > 0 ? 'disabled' : '' ?> class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-400 mb-2">Şifre</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder="••••••••" maxlength="100" autocomplete="current-password" <?= $lockoutRemaining > 0 ? 'disabled' : '' ?> class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-neutral-300" tabindex="-1">
                            <i data-lucide="eye" class="w-4 h-4" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" <?= $lockoutRemaining > 0 ? 'disabled' : '' ?> class="w-full bg-brand hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-brand">
                    <span id="btnText">Giriş Yap</span>
                    <i data-lucide="arrow-right" class="w-4 h-4" id="btnIcon"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-neutral-600 mt-6">
        kıskanmak, imrenmek, haset etmek, gıpta etmek, çekememek, iç geçirmek, özenmek, fesatlanmak, gözü kalmak, göz dikmek, imreni duymak, hasetlenmek, çekememezlik yapmak, gözü olmak, gözü takılmak, gözü kaymak, içi gitmek, içi burkulmak, içlenmek, heves etmek, meyletmek, talep duymak, arzu etmek, hırslanmak, hırs yapmak, rekabet etmek, kıskançlık duymak, haset duymak, gıptalanmak, imreniş duymak, özeniş göstermek, gözü ısırmak, gözüne kestirmek, gönlü kaymak, gönlü kalmak, içi kıpırdamak, içi sızlamak, kıskançlanmak
        </p>
    </div>

    <script>
        lucide.createIcons();
        const { animate } = Motion;

        document.addEventListener('DOMContentLoaded', () => {
            animate('.login-container', 
                { opacity: [0, 1], y: [30, 0] }, 
                { duration: 0.6, easing: 'ease-out' }
            );
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            
            if (btn.disabled) {
                e.preventDefault();
                return;
            }
            
            btnText.textContent = 'Giriş yapılıyor...';
            btnIcon.setAttribute('data-lucide', 'loader-2');
            btnIcon.classList.add('animate-spin');
            lucide.createIcons();
            
            btn.disabled = true;
        });

        <?php if ($lockoutRemaining > 0): ?>
        let remaining = <?= $lockoutRemaining ?>;
        const timerEl = document.getElementById('lockoutTimer');
        const form = document.getElementById('loginForm');
        
        const interval = setInterval(() => {
            remaining--;
            timerEl.textContent = Math.ceil(remaining / 60);
            
            if (remaining <= 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
        <?php endif; ?>

        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
