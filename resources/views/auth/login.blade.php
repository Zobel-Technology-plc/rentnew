<x-guest-layout>
    <!-- <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-gray-900 via-gray-900/90 to-blue-900/80"> -->
        <div class="max-w-md w-full" 
             x-data="{ loading: false, passwordVisible: false }" 
             x-show="true"
             x-transition:enter="animate__animated animate__fadeIn animate__faster"
             x-transition:leave="animate__animated animate__fadeOut animate__faster">
            
            <!-- Login Card -->
            <div class="relative">
                <!-- Card Glow Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl opacity-75 blur-xl animate-pulse"></div>
                
                <!-- Card Content -->
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl backdrop-blur-xl 
                            border border-white/10 dark:border-gray-700/50">
                    
                    <!-- Card Header -->
                    <div class="p-8 text-center relative overflow-hidden border-b border-gray-200 dark:border-gray-700">
                        <!-- Logo Container -->
                        <div class="relative mb-6 group">
                            <div class="absolute inset-0 bg-blue-500 rounded-full blur-xl opacity-75 
                                      group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative w-20 h-20 mx-auto bg-white dark:bg-gray-800 rounded-full shadow-xl 
                                      flex items-center justify-center transform group-hover:scale-110 
                                      group-hover:rotate-6 transition-all duration-300">
                                <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 mb-2">
                            Welcome Back
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">Equipment Rental Management System</p>
                    </div>

                    <!-- Login Form -->
                    <div class="p-8">
                        <form method="POST" 
                              action="{{ route('login') }}" 
                              class="space-y-6" 
                              @submit.prevent="loading = true; $el.submit();">
                            @csrf
                            
                            <!-- Email Field -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <div class="relative">
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-4 py-2.5 rounded-lg bg-gray-50 dark:bg-gray-900
                                                  border border-gray-300 dark:border-gray-600 
                                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                  dark:text-gray-300 transition-all duration-300
                                                  group-hover:border-blue-500"
                                           :class="{ 'opacity-50': loading }">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                <div class="relative">
                                    <input :type="passwordVisible ? 'text' : 'password'"
                                           name="password" 
                                           required 
                                           class="w-full px-4 py-2.5 rounded-lg bg-gray-50 dark:bg-gray-900
                                                  border border-gray-300 dark:border-gray-600 
                                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                  dark:text-gray-300 transition-all duration-300
                                                  group-hover:border-blue-500"
                                           :class="{ 'opacity-50': loading }">
                                    <button type="button" 
                                            @click="passwordVisible = !passwordVisible"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!passwordVisible" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="passwordVisible" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="remember" 
                                           class="w-4 h-4 rounded border-gray-300 text-blue-600 
                                                  focus:ring-blue-500 focus:ring-offset-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 
                                              dark:hover:text-blue-300 transition-colors duration-200">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <button type="submit" 
                                    class="w-full py-3 px-4 flex items-center justify-center
                                           bg-gradient-to-r from-blue-600 to-purple-600 
                                           hover:from-blue-700 hover:to-purple-700
                                           text-white font-medium rounded-lg
                                           transform hover:scale-[1.02] transition-all duration-300
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 
                                           focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed
                                           disabled:hover:scale-100"
                                    :disabled="loading">
                                <template x-if="!loading">
                                    <span>Sign in</span>
                                </template>
                                <template x-if="loading">
                                    <div class="flex items-center space-x-2">
                                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                        </svg>
                                        <span>Processing...</span>
                                    </div>
                                </template>
                            </button>
                        </form>

                        <!-- Status Messages -->
                        <div class="mt-6 space-y-4">
                            @if (session('status'))
                                <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300 
                                            border border-green-200 dark:border-green-800/50"
                                     x-data="{ show: true }"
                                     x-show="show"
                                     x-transition.duration.300ms>
                                    <div class="flex items-center justify-between">
                                        <p>{{ session('status') }}</p>
                                        <button @click="show = false" class="text-green-600 hover:text-green-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Animate.css CDN in the layout or here -->
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    @endpush
</x-guest-layout>
