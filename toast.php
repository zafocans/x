<div id="toastContainer" class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2"></div>

<script>
let toastCount = 0;

function showToast(message, type = 'info') {
    const config = {
        success: { icon: 'check', bg: 'bg-emerald-500', iconBg: 'bg-emerald-600' },
        error: { icon: 'x', bg: 'bg-red-500', iconBg: 'bg-red-600' },
        warning: { icon: 'alert-triangle', bg: 'bg-amber-500', iconBg: 'bg-amber-600' },
        info: { icon: 'info', bg: 'bg-blue-500', iconBg: 'bg-blue-600' }
    };
    
    const { icon, bg, iconBg } = config[type] || config.info;
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
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
            <div class="toast-progress h-full bg-white/40 rounded-full" style="width: 100%"></div>
        </div>
    `;
    toast.style.position = 'relative';
    
    container.appendChild(toast);
    lucide.createIcons();
    
    requestAnimationFrame(() => {
        toast.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
        toast.style.transform = 'translateX(0)';
    });
    
    const progressBar = toast.querySelector('.toast-progress');
    setTimeout(() => {
        progressBar.style.transition = 'width 4s linear';
        progressBar.style.width = '0%';
    }, 100);
    
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
</script>
