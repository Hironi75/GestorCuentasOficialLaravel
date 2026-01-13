<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - Gestor de Cuentas</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="login-wrap">
    <h2>Iniciar Sesión</h2>
    
    @if(session('error'))
    <div class="alert alert-error" style="background:#fee; color:#c33; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center;">
      {{ session('error') }}
    </div>
    @endif
    
    @if(session('success'))
    <div class="alert alert-success" style="background:#efe; color:#3c3; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center;">
      {{ session('success') }}
    </div>
    @endif
    
    <div class="form">
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <input type="text" placeholder="Usuario" name="usuario" required autocomplete="username" />
        <input type="password" placeholder="Contraseña" name="password" required autocomplete="current-password" />
        <button type="submit">Iniciar sesión</button>
      </form>
    </div>
    
    <!--
    <p style="margin-top: 20px; text-align: center;">
      <a href="{{ route('register') }}" style="color: #3498db; text-decoration: none;">¿No tienes cuenta? Crear una nueva</a>
    </p>-->
    
    <p style="margin-top: 10px; text-align: center; color: #7f8c8d; font-size: 0.9rem;">
      Sistema de Gestión de Cuentas
    </p>
  </div>
</body>
</html>
