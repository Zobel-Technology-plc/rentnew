<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Company Settings</h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: {{ $settings->updated_at->diffForHumans() }}</span>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.company.update') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf

                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Company Name
                            </label>
                            <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 
                                          dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Company Logo
                            </label>
                            @if ($settings->logo_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($settings->logo_path) }}" 
                                         alt="Current Logo"
                                         class="h-20 w-auto">
                                </div>
                            @endif
                            <input type="file" name="logo"
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-primary-50 file:text-primary-700
                                          hover:file:bg-primary-100
                                          dark:file:bg-primary-900 dark:file:text-primary-400">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Currency Code (e.g., ETB, USD)
                            </label>
                            <input type="text" name="currency_code" 
                                   value="{{ old('currency_code', $settings->currency_code) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 
                                          dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   maxlength="3">
                            @error('currency_code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency Symbol -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Currency Symbol
                            </label>
                            <input type="text" name="currency_symbol" 
                                   value="{{ old('currency_symbol', $settings->currency_symbol) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 
                                          dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   maxlength="10">
                            @error('currency_symbol')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 