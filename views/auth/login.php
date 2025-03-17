<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/Proyectos/simulacionTrafico/public/css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" action="/Proyectos/simulacionTrafico/login">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <br>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>