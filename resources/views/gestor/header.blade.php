<header class="gestor-header !relative !p-0">
    <!-- Barra superior con usuario -->
    <div class="bg-slate-800 px-4 py-2 flex justify-end items-center gap-3">
        <div class="flex items-center gap-2 bg-slate-700 px-3 py-1.5 rounded-full">
            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            <span class="text-white text-sm font-medium">{{ session('usuario_nombre') }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-1.5 rounded-full transition-all duration-200 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="hidden sm:inline">Cerrar Sesión</span>
            </button>
        </form>
    </div>

    <!-- Título y navegación -->
    <div class="py-4 px-4 text-center">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-wide m-0 mb-3">Gestor de Cuentas</h1>
        <nav class="flex flex-wrap justify-center gap-2">
            <a href="/dashboard" class="nav-tab text-sm sm:text-base px-4 sm:px-6 py-2 rounded-lg hover:bg-white/20 transition-all">Inicio</a>
            <a href="/cuenta" class="nav-tab text-sm sm:text-base px-4 sm:px-6 py-2 rounded-lg hover:bg-white/20 transition-all">Cuenta</a>
            <a href="/gestor" class="nav-tab text-sm sm:text-base px-4 sm:px-6 py-2 rounded-lg hover:bg-white/20 transition-all">Gestor</a>
        </nav>
    </div>
</header>
