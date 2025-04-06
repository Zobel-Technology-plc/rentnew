import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Dark mode initialization
document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        dark: localStorage.getItem('darkMode') === 'true',
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('darkMode', this.dark);
        }
    }));
});

// Chart.js default configuration
if (window.Chart) {
    Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563';
    Chart.defaults.borderColor = document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb';
}

Alpine.start();

// Add any custom JavaScript here
