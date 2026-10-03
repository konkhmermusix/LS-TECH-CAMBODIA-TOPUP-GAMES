<div
    x-data="{
        visible: false,
        lastScrollY: 0,
        handleScroll() {
            const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScrollY > 250 && currentScrollY < this.lastScrollY - 6) {
                // Scrolling up and not at the very top -> Show button
                this.visible = true;
            } else if (currentScrollY > this.lastScrollY + 6 || currentScrollY <= 150) {
                // Scrolling down or near top -> Hide button
                this.visible = false;
            }
            this.lastScrollY = Math.max(0, currentScrollY);
        }
    }"
    x-init="
        lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
        window.addEventListener('scroll', () => handleScroll(), { passive: true });
    "
    x-show="visible"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-6 scale-90"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-6 scale-90"
    style="display: none;"
    class="fixed bottom-6 right-6 z-40"
>
    <button
        type="button"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' }); visible = false;"
        aria-label="Scroll to top"
        class="flex items-center justify-center w-12 h-12 rounded-full glass-panel border border-white/60 dark:border-white/10 text-gray-700 dark:text-gray-200 hover:text-blue-500 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 select-none cursor-pointer"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</div>
