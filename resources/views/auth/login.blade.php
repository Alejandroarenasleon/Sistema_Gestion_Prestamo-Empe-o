<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div style="width: 80px; height: 80px; background: rgba(212, 160, 23, 0.15); border: 2px solid #d4a017; margin: 20px auto;"></div>
    
    <h2>Iniciar Sesión</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="login">Usuario:</label>
            <input type="text" id="login" name="login" required autofocus>
        </div>
        <br>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html> 