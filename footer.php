            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
        const { animate, stagger } = Motion;

        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            animate('main', { opacity: [1, 0.8, 1] }, { duration: 0.3 });
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            lucide.createIcons();
        }

        function initAnimations() {
            const cards = document.querySelectorAll('[data-animate="card"]');
            if (cards.length) {
                animate(cards, 
                    { opacity: [0, 1], y: [20, 0] }, 
                    { duration: 0.5, delay: stagger(0.1) }
                );
            }

            const rows = document.querySelectorAll('[data-animate="row"]');
            if (rows.length) {
                animate(rows, 
                    { opacity: [0, 1], x: [-10, 0] }, 
                    { duration: 0.4, delay: stagger(0.05) }
                );
            }

            const fadeIns = document.querySelectorAll('[data-animate="fade"]');
            if (fadeIns.length) {
                animate(fadeIns, 
                    { opacity: [0, 1] }, 
                    { duration: 0.5, delay: stagger(0.1) }
                );
            }

            const slideUps = document.querySelectorAll('[data-animate="slide-up"]');
            if (slideUps.length) {
                animate(slideUps, 
                    { opacity: [0, 1], y: [30, 0] }, 
                    { duration: 0.6, delay: stagger(0.15) }
                );
            }
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            modal.classList.remove('hidden');
            const backdrop = modal.querySelector('.modal-backdrop');
            const content = modal.querySelector('.modal-content');
            
            if (backdrop) animate(backdrop, { opacity: [0, 1] }, { duration: 0.2 });
            if (content) animate(content, { opacity: [0, 1], scale: [0.95, 1], y: [10, 0] }, { duration: 0.3 });
            
            lucide.createIcons();
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            const backdrop = modal.querySelector('.modal-backdrop');
            const content = modal.querySelector('.modal-content');
            
            if (content) animate(content, { opacity: [1, 0], scale: [1, 0.95], y: [0, 10] }, { duration: 0.2 });
            if (backdrop) animate(backdrop, { opacity: [1, 0] }, { duration: 0.2 });
            
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function animateButton(btn) {
            animate(btn, { scale: [1, 0.95, 1] }, { duration: 0.2 });
        }

        function animateRemove(element, callback) {
            animate(element, { opacity: [1, 0], x: [0, -20] }, { duration: 0.3 })
                .then(() => { if (callback) callback(); });
        }

        document.addEventListener('DOMContentLoaded', initAnimations);
    </script>
</body>
</html>
