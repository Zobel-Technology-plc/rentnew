@props(['class' => ''])

<button
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="
        if (!localStorage.getItem('darkMode')) {
            localStorage.setItem('darkMode', false);
        }
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', value);
            if (value === true) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })
    "
    @click="darkMode = !darkMode"
    type="button"
    class="relative inline-flex items-center justify-center p-1 {{ $class }} 
           hover:opacity-80
           focus:outline-none
           transition-all duration-200 ease-in-out"
    aria-label="Toggle dark mode"
>
    <svg x-show="!darkMode" 
         x-transition:enter="transition-transform duration-200 ease-out"
         x-transition:enter-start="rotate-180 opacity-0"
         x-transition:enter-end="rotate-0 opacity-100"
         class="w-5 h-5 text-amber-500 transform" 
         fill="currentColor" 
         viewBox="0 0 20 20" 
         xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
    </svg>
    <svg x-show="darkMode" 
         x-transition:enter="transition-transform duration-200 ease-out"
         x-transition:enter-start="-rotate-180 opacity-0"
         x-transition:enter-end="rotate-0 opacity-100"
         class="w-5 h-5 text-blue-300 transform" 
         fill="currentColor" 
         viewBox="0 0 20 20" 
         xmlns="http://www.w3.org/2000/svg">
        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
    </svg>
</button> 