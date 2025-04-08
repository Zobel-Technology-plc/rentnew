import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Dark mode initialization
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        value: localStorage.getItem('darkMode') === 'true',
        toggle() {
            this.value = !this.value;
            localStorage.setItem('darkMode', this.value);
            document.documentElement.classList.toggle('dark', this.value);
        }
    });

    // Sidebar store initialization
    Alpine.store('sidebar', {
        open: localStorage.getItem('sidebarOpen') === 'true',
        toggle() {
            this.open = !this.open;
            localStorage.setItem('sidebarOpen', this.open);
        }
    });
    
    // Initialize dark mode on page load
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }

    // Initialize sidebar state if not exists
    if (localStorage.getItem('sidebarOpen') === null) {
        localStorage.setItem('sidebarOpen', 'true');
    }
});

// Chart.js default configuration
if (window.Chart) {
    Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563';
    Chart.defaults.borderColor = document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb';
}

Alpine.start();

// Add any custom JavaScript here
