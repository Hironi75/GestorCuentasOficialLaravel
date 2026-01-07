<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- ================== CSS ================== -->


    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <!-- =============== FIN CSS =============== -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <!-- =============== HTML =============== -->
  <div class="login-wrap">
    <h2>Login</h2>
    <div class="form">
      <form method="GET" action="/gestor">
        <input type="text" placeholder="Usuario" name="un" required />
        <input type="password" placeholder="Contraseña" name="pw" required />
        <button type="submit">Iniciar sesión</button>
      </form>
    </div>
  </div>
  <!-- =============== FIN HTML =============== -->

  <!-- =============== JS =============== -->
  <script src="script.js"></script>
  <!-- =============== FIN JS =============== -->
</body>
</html>
