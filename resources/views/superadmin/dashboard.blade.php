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

            // Initialize dark mode
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
                   'translate-x-0': $store.sidebar.open,
                   '-translate-x-full': !$store.sidebar.open
               }">
            
            <!-- Logo Section with visible close button -->
            <div class="flex items-center justify-between h-16 px-6 border-b dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">SuperAdmin Panel</span>
                </div>
                <!-- Desktop Close Button -->
                <button @click="$store.sidebar.toggle()" 
                        class="block p-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2">
                <x-sidebar-link icon="home" label="Dashboard" route="superadmin.dashboard" :active="request()->routeIs('superadmin.dashboard')"/>
                <x-sidebar-link icon="users" label="Users" route="superadmin.users" :active="request()->routeIs('superadmin.users')"/>
                <x-sidebar-link icon="chart-bar" label="Analytics" route="superadmin.analytics" :active="request()->routeIs('superadmin.analytics')"/>
                <x-sidebar-link icon="cog" label="Settings" route="superadmin.settings" :active="request()->routeIs('superadmin.settings')"/>
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
        </aside>

        <!-- Add this mini sidebar for collapsed state -->
        <div x-show="!$store.sidebar.open" 
             class="fixed inset-y-0 left-0 z-50 w-20 bg-white dark:bg-gray-800 shadow-lg hidden lg:block">
            <div class="flex flex-col items-center py-4">
                <button @click="$store.sidebar.toggle()" 
                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="$store.sidebar.open" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="$store.sidebar.toggle()"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden">
        </div>

        <!-- Main Content Area -->
        <main class="transition-all duration-300"
              :class="{
                  'lg:ml-64': $store.sidebar.open,
                  'lg:ml-20': !$store.sidebar.open
              }">
            <!-- Top Navigation Bar -->
            <nav class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b dark:border-gray-700 shadow-sm">
                <div class="px-4 sm:px-6">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left side - Toggle Button (visible only on mobile) -->
                        <div class="flex items-center lg:hidden">
                            <button @click="$store.sidebar.toggle()"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <span class="sr-only">Toggle sidebar</span>
                                <svg class="w-6 h-6" 
                                     :class="{'hidden': $store.sidebar.open}"
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white">
                                SuperAdmin
                            </span>
                        </div>

                        <!-- Right side - User Info -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <img class="w-8 h-8 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" 
                                 alt="User avatar">
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-6 animate-fade-in">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <x-stat-card 
                        title="Total Users" 
                        :value="$totalUsers"
                        icon="users"
                        color="primary"/>
                    
                    <x-stat-card 
                        title="Active Users" 
                        :value="$activeUsers"
                        icon="user-check"
                        color="success"/>
                    
                    <x-stat-card 
                        title="Pending Users" 
                        :value="$pendingUsers"
                        icon="user-clock"
                        color="warning"/>
                    
                    <x-stat-card 
                        title="Blocked Users" 
                        :value="$blockedUsers"
                        icon="user-x"
                        color="danger"/>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- User Growth Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">User Growth</h3>
                        <div class="h-80">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>

                    <!-- User Distribution Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">User Distribution</h3>
                        <div class="h-80">
                            <canvas id="userDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Activity</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentActivity as $activity)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $activity->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $activity->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $activity->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                                   ($activity->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Properly format PHP variables for JavaScript
            const chartData = {
                userGrowth: {
                    labels: JSON.parse("{{ json_encode($userGrowth['labels']) }}".replace(/&quot;/g, '"')),
                    data: JSON.parse("{{ json_encode($userGrowth['data']) }}".replace(/&quot;/g, '"'))
                },
                users: {
                    active: "{{ $activeUsers }}",
                    pending: "{{ $pendingUsers }}",
                    blocked: "{{ $blockedUsers }}"
                }
            };

            const chartConfig = {
                userGrowth: {
                    type: 'line',
                    data: {
                        labels: chartData.userGrowth.labels,
                        datasets: [{
                            label: 'New Users',
                            data: chartData.userGrowth.data,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false,
                                    color: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                },
                userDistribution: {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Pending', 'Blocked'],
                        datasets: [{
                            data: [
                                parseInt(chartData.users.active),
                                parseInt(chartData.users.pending),
                                parseInt(chartData.users.blocked)
                            ],
                            backgroundColor: [
                                '#10B981',
                                '#F59E0B',
                                '#EF4444'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '70%'
                    }
                }
            };

            // Initialize charts
            const userGrowthChart = new Chart(
                document.getElementById('userGrowthChart').getContext('2d'),
                chartConfig.userGrowth
            );

            const userDistributionChart = new Chart(
                document.getElementById('userDistributionChart').getContext('2d'),
                chartConfig.userDistribution
            );
        });

        // Initialize sidebar state
        if (localStorage.getItem('sidebarOpen') === null) {
            localStorage.setItem('sidebarOpen', 'true');
        }
    </script>
    @endpush
</x-app-layout> 