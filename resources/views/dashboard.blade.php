<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900"
         x-data="{ 
            dropdownOpen: false,
            activeMenu: localStorage.getItem('activeMenu') || 'dashboard',
            darkMode: localStorage.getItem('darkMode') === 'true'
         }"
         x-init="
            Alpine.store('sidebar', {
                isOpen: localStorage.getItem('sidebarOpen') === 'true',
                toggle() {
                    this.isOpen = !this.isOpen;
                    localStorage.setItem('sidebarOpen', this.isOpen);
                }
            });
            
            if (localStorage.getItem('sidebarOpen') === null) {
                Alpine.store('sidebar').isOpen = window.innerWidth >= 1024;
                localStorage.setItem('sidebarOpen', Alpine.store('sidebar').isOpen);
            }

            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
         "
         @toggle-sidebar.window="$store.sidebar.toggle()">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out"
               :class="{
                   'translate-x-0': $store.sidebar.isOpen,
                   '-translate-x-full': !$store.sidebar.isOpen
               }">
            <!-- Sidebar content -->
            <div class="flex items-center justify-between h-16 px-6 border-b dark:border-gray-700">
                <!-- Logo and title -->
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</span>
                </div>
                <!-- Toggle button -->
                <button @click="$store.sidebar.toggle()" 
                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Backdrop -->
        <div x-show="$store.sidebar.isOpen && window.innerWidth < 1024" 
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75"
             @click="$store.sidebar.toggle()">
        </div>

        <!-- Main content -->
        <main class="transition-all duration-300"
              :class="{
                  'lg:ml-64': $store.sidebar.isOpen,
                  'lg:ml-0': !$store.sidebar.isOpen
              }">
            <!-- Your page content here -->
        </main>
    </div>
</x-app-layout> 