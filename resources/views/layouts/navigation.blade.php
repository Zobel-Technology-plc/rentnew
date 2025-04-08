<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side -->
            <div class="flex items-center">
                <!-- Sidebar Toggle Button -->
                <button @click="$store.sidebar.toggle()" 
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 
                               focus:outline-none transition-colors duration-200
                               text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500">
                    <!-- Hamburger Icon -->
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         x-show="!$store.sidebar.isOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <!-- Close Icon -->
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         x-show="$store.sidebar.isOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <!-- Page Title -->
                <span class="text-lg font-semibold text-gray-800 dark:text-white ml-4">
                    {{ $title ?? 'Dashboard' }}
                </span>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- Equipment Management -->
                    <x-nav-link :href="route('equipment.categories.index')" :active="request()->routeIs('equipment.categories.*')">
                        {{ __('Equipment Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('equipment.items.index')" :active="request()->routeIs('equipment.items.*')">
                        {{ __('Equipment Items') }}
                    </x-nav-link>

                    <!-- Rental Management -->
                    <x-nav-link :href="route('rentals.index')" :active="request()->routeIs('rentals.*')">
                        {{ __('Rentals') }}
                    </x-nav-link>
                    <x-nav-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                        {{ __('Maintenance') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center">
                <!-- Theme Toggle -->
                <div class="mr-8">
                    <x-theme-toggle />
                </div>
                
                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 
                                     dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
                            <img class="w-8 h-8 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" 
                                 alt="User avatar">
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Equipment Management (Mobile) -->
            <x-responsive-nav-link :href="route('equipment.categories.index')" :active="request()->routeIs('equipment.categories.*')">
                {{ __('Equipment Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('equipment.items.index')" :active="request()->routeIs('equipment.items.*')">
                {{ __('Equipment Items') }}
            </x-responsive-nav-link>

            <!-- Rental Management (Mobile) -->
            <x-responsive-nav-link :href="route('rentals.index')" :active="request()->routeIs('rentals.*')">
                {{ __('Rentals') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                {{ __('Maintenance') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
