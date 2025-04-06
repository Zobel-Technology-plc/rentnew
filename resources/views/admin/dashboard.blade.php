<x-app-layout>
    <!-- Main wrapper with Alpine.js state management -->
    <div x-data="{
            isSidebarOpen: localStorage.getItem('sidebarOpen') === 'true',
            toggleSidebar() {
                this.isSidebarOpen = !this.isSidebarOpen;
                localStorage.setItem('sidebarOpen', this.isSidebarOpen);
            }
        }" 
        class="min-h-screen bg-gray-50 dark:bg-gray-900">
        
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
            :class="{'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen}">
            
            <!-- Logo and Close Button Section -->
            <div class="flex items-center justify-between h-16 px-4 border-b dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">Admin Panel</span>
                </div>
                <!-- Mobile Close Button -->
                <button @click="toggleSidebar" 
                        class="p-2 rounded-lg text-gray-600 lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2">
                <x-sidebar-link icon="home" label="Dashboard" route="admin.dashboard" :active="request()->routeIs('admin.dashboard')"/>
                <x-sidebar-link icon="users" label="Users" route="admin.users" :active="request()->routeIs('admin.users')"/>
                <x-sidebar-link icon="chart-bar" label="Analytics" route="admin.analytics" :active="request()->routeIs('admin.analytics')"/>
                <x-sidebar-link icon="building-office" label="Company Settings" route="admin.settings.company" :active="request()->routeIs('admin.settings.company')"/>
                <x-sidebar-link icon="cog" label="Settings" route="admin.settings" :active="request()->routeIs('admin.settings')"/>
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

        <!-- Backdrop for mobile -->
        <div x-show="isSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="toggleSidebar"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden">
        </div>

        <!-- Main Content Area -->
        <main class="lg:ml-64 transition-all duration-300">
            <!-- Top Navigation Bar -->
            <nav class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b dark:border-gray-700 shadow-sm">
                <div class="px-4 sm:px-6">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left side - Toggle Button -->
                        <div class="flex items-center">
                            <button 
                                @click="toggleSidebar"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <span class="sr-only">Open sidebar</span>
                                <!-- Animated Hamburger Icon -->
                                <div class="relative w-6 h-6">
                                    <span class="absolute inset-0 transform transition duration-300 flex items-center justify-center"
                                          :class="{'rotate-180 opacity-0': isSidebarOpen}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </span>
                                    <span class="absolute inset-0 transform transition duration-300 flex items-center justify-center"
                                          :class="{'rotate-180 opacity-100': isSidebarOpen, 'opacity-0': !isSidebarOpen}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                </div>
                            </button>
                            <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white lg:hidden">
                                Dashboard
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
            <div class="p-4 sm:p-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Total Properties Card -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-white/30 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-white">Total Properties</h2>
                                <p class="text-3xl font-bold text-white">{{ $totalProperties }}</p>
                                <p class="text-white/70 text-sm mt-1">
                                    @if($propertyGrowth > 0)
                                        ↑ {{ $propertyGrowth }}% from last month
                                    @elseif($propertyGrowth < 0)
                                        ↓ {{ abs($propertyGrowth) }}% from last month
                                    @else
                                        No change from last month
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Rentals Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-white/30 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-white">Active Rentals</h2>
                                <p class="text-3xl font-bold text-white">{{ $activeRentals }}</p>
                                <p class="text-white/70 text-sm mt-1">
                                    @if($rentalGrowth > 0)
                                        ↑ {{ $rentalGrowth }}% from last month
                                    @elseif($rentalGrowth < 0)
                                        ↓ {{ abs($rentalGrowth) }}% from last month
                                    @else
                                        No change from last month
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue Card -->
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-white/30 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-white">Monthly Revenue</h2>
                                <p class="text-3xl font-bold text-white">${{ number_format($monthlyRevenue, 2) }}</p>
                                <p class="text-white/70 text-sm mt-1">
                                    @if($revenueGrowth > 0)
                                        ↑ {{ $revenueGrowth }}% from last month
                                    @elseif($revenueGrowth < 0)
                                        ↓ {{ abs($revenueGrowth) }}% from last month
                                    @else
                                        No change from last month
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests Card -->
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-white/30 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-white">Pending Requests</h2>
                                <p class="text-3xl font-bold text-white">{{ $pendingRequests }}</p>
                                <p class="text-white/70 text-sm mt-1">{{ $urgentRequests }} require attention</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Rental Status Distribution -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-2 sm:mb-0">Rental Status Distribution</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active: {{ $rentalStatusDistribution['active'] }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending: {{ $rentalStatusDistribution['pending'] }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Completed: {{ $rentalStatusDistribution['completed'] }}
                                </span>
                            </div>
                        </div>
                        <div class="relative h-48 sm:h-64">
                            <canvas id="rentalStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Monthly Rental Revenue -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Monthly Rental Revenue</h3>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Last 6 months
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Recent Rentals -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Rentals</h3>
                        <div class="space-y-4">
                            @forelse($recentRentals as $rental)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center mb-2 sm:mb-0">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $rental->property->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Tenant: {{ $rental->tenant->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($rental->status === 'active') bg-green-100 text-green-800
                                            @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $rental->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent rentals</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Transactions</h3>
                        <div class="space-y-4">
                            @forelse($recentTransactions as $transaction)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center mb-2 sm:mb-0">
                                        <div class="p-2 bg-green-100 rounded-full">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $transaction->rental->property->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ ucfirst($transaction->type) }} Payment
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                            +${{ number_format($transaction->amount, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent transactions</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
    <script>
        // Rental Status Distribution Chart
        const rentalStatusCtx = document.getElementById('rentalStatusChart');
        if (rentalStatusCtx) {
            new Chart(rentalStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Pending', 'Completed'],
                    datasets: [{
                        data: {{ json_encode([
                            $rentalStatusDistribution['active'],
                            $rentalStatusDistribution['pending'],
                            $rentalStatusDistribution['completed']
                        ]) }},
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(107, 114, 128, 0.8)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(107, 114, 128, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Monthly Revenue Chart
        const revenueData = {{ json_encode($monthlyRevenues) }};
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData.data,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>