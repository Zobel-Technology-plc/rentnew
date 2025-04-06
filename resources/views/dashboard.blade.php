<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" 
         x-data="{ 
            dropdownOpen: false,
            activeMenu: localStorage.getItem('activeMenu') || 'dashboard'
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
         "
         @toggle-sidebar.window="$store.sidebar.toggle()">
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out bg-white dark:bg-gray-800 shadow-lg"
             :class="{
                'translate-x-0': $store.sidebar.isOpen,
                '-translate-x-full': !$store.sidebar.isOpen
             }">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between h-16 px-6 border-b dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</span>
                </div>
                <!-- Sidebar Close Button -->
                <button @click="$store.sidebar.toggle()" 
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden
                               focus:outline-none transition-colors duration-200
                               text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2">
                <x-sidebar-link icon="home" label="Dashboard" route="dashboard" :active="request()->routeIs('dashboard')"/>
                <!-- Reports Dropdown -->
                <div class="space-y-1">
                    <button @click="dropdownOpen = !dropdownOpen" 
                            class="flex items-center justify-between w-full px-4 py-2 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Reports</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="{'rotate-180': dropdownOpen}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Content -->
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-10 space-y-1">
                        <a href="{{ route('analytics.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Analytics</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Performance</a>
                    </div>
                </div>

                <!-- Other Menu Items -->
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Users</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Theme Toggle -->
            <div class="absolute bottom-0 w-full p-4 border-t dark:border-gray-700">
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                    <span>Theme</span>
                    <div class="relative">
                        <div class="w-10 h-5 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transform transition-transform duration-200"
                             :class="{'translate-x-5': darkMode}"></div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="$store.sidebar.isOpen && window.innerWidth < 1024" 
             class="fixed inset-0 z-20 bg-gray-900/50"
             @click="$store.sidebar.toggle()"
             x-transition:enter="transition-opacity ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Main Content -->
        <div class="transition-all duration-300"
             :class="{
                'ml-0': !$store.sidebar.isOpen,
                'ml-64': $store.sidebar.isOpen
             }">
            <!-- Content Area -->
            <main class="p-6">
                @isset($header)
                    <header class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ $header }}
                        </h2>
                    </header>
                @endisset

                <!-- Page Content -->
                <div class="py-4">
                    {{ $slot ?? '' }}
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        const chartData = {
            months: ($userGrowth['labels'] ?? []),
            growth: ($userGrowth['data'] ?? []),
            users: {
                active: {$activeUsers},
                pending: { $pendingUsers},
                blocked: {$blockedUsers}
            }
        };

        const chartConfig = {
            userGrowth: {
                type: 'line',
                data: {
                    labels: chartData.months,
                    datasets: [{
                        label: 'New Users',
                        data: chartData.growth,
                        borderColor: '#3B82F6',
                        tension: 0.3,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            },
            userDistribution: {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [chartData.users.active, chartData.users.pending, chartData.users.blocked],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%'
                }
            }
        };

        // Initialize charts when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            new Chart(
                document.getElementById('userGrowthChart').getContext('2d'),
                chartConfig.userGrowth
            );

            new Chart(
                document.getElementById('userDistributionChart').getContext('2d'),
                chartConfig.userDistribution
            );
        });
    </script>
    @endpush
</x-app-layout>
