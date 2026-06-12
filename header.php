<!DOCTYPE html>
<html lang="tr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - FLOR1CK Panel</title>
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

        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11'; }
        .sidebar-link.active { background: rgba(0, 86, 164, 0.1); color: #0056A4; }
        .dark .sidebar-link.active { background: rgba(0, 86, 164, 0.2); }
        
        [data-animate] { opacity: 0; }
        .modal-backdrop { opacity: 0; }
        .modal-content { opacity: 0; transform: scale(0.95) translateY(10px); }
    </style>
</head>
<body class="bg-neutral-50 dark:bg-neutral-950 font-sans min-h-screen">
    <div class="flex">
