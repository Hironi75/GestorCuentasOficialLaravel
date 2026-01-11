<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Registro - Gestor de Cuentas</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="login-wrap">
    <h2>Crear Cuenta</h2>
    
    @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
    @endif
    
    @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
    @endif
    
    <div class="form">
      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="text" placeholder="Usuario" name="usuario" required value="{{ old('usuario') }}" autocomplete="username" />
        <input type="password" placeholder="Contraseña" name="password" required autocomplete="new-password" />
        <input type="password" placeholder="Confirmar Contraseña" name="password_confirmation" required autocomplete="new-password" />
        <button type="submit">Crear Usuario</button>
      </form>
    </div>
    
    <p style="margin-top: 20px; text-align: center;">
      <a href="{{ route('login') }}" style="color: #3498db; text-decoration: none;">← Volver a Iniciar Sesión</a>
    </p>
  </div>
</body>
</html>
