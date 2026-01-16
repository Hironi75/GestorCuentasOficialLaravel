<header class="gestor-header">
    <h1>Gestor de Cuentas</h1>
    <nav class="nav-tabs">
        <a href="/dashboard" class="nav-tab">Inicio</a>
        <a href="/cuenta" class="nav-tab">Cuenta</a>
        <a href="/gestor" class="nav-tab">Gestor</a>
    </nav>
    <div class="user-info">
        <span class="in-user-valid">{{ session('usuario_nombre') }}</span>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>
</header>
