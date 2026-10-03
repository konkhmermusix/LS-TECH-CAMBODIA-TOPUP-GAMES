import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme Store (Light / Dark / System)
Alpine.store('theme', {
    mode: localStorage.getItem('theme_mode') || 'system',

    init() {
        window.addEventListener('theme-changed', (e) => {
            this.mode = e.detail.mode;
        });
    },

    setMode(mode) {
        if (window.setTheme) {
            window.setTheme(mode);
        }
        this.mode = mode;
    }
});

// Toast Store
Alpine.store('toast', {
    items: [],
    counter: 0,

    show(message, type = 'info', duration = 4000) {
        const id = ++this.counter;
        this.items.push({ id, message, type });

        if (duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }
    },

    success(message, duration) {
        this.show(message, 'success', duration);
    },

    error(message, duration) {
        this.show(message, 'error', duration);
    },

    warning(message, duration) {
        this.show(message, 'warning', duration);
    },

    info(message, duration) {
        this.show(message, 'info', duration);
    },

    remove(id) {
        this.items = this.items.filter(item => item.id !== id);
    }
});

Alpine.start();
