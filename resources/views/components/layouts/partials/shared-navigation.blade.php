<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="{{ auth()->user()->role === 'medecin' ? route('medecin.dashboard') : route('patient.dashboard') }}" class="text-xl font-bold text-blue-600 dark:text-blue-500">Lara-Consult</a>
        </div>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center space-x-4">
            <a href="{{ auth()->user()->role === 'medecin' ? route('medecin.dashboard') : route('patient.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
            
            @if(auth()->user()->role === 'medecin')
                <a href="/consultations" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Historique</a>
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium">
                    Déconnexion
                </button>
            </form>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="p-2 rounded-md text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden pb-4">
        <a href="{{ auth()->user()->role === 'medecin' ? route('medecin.dashboard') : route('patient.dashboard') }}" class="block text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 py-2 px-4 rounded">Dashboard</a>
        
        @if(auth()->user()->role === 'medecin')
            <a href="/consultations" class="block text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 py-2 px-4 rounded mt-1">Historique</a>
        @endif

        <div class="mt-2 px-4">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    // Ensure this script doesn't run into conflicts if loaded multiple times.
    if (!window.navigationScriptLoaded) {
        document.addEventListener('click', function(event) {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (menuButton && mobileMenu && event.target.closest('#mobile-menu-button')) {
                mobileMenu.classList.toggle('hidden');
            }
        });
        window.navigationScriptLoaded = true;
    }
</script>