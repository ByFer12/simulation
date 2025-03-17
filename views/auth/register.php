<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Vinculamos nuestro CSS -->
    <link rel="stylesheet" href="/Proyectos/simulacionTrafico/public/css/register.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        
        <!-- Si hay algún error en $data['error'], lo mostramos -->
        <?php if (isset($data['error'])): ?>
            <div class="alert">
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario de registro -->
        <form action="/Proyectos/simulacionTrafico/register" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required>
            </div>

            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="">-- Selecciona un rol --</option>
                    <option value="administrador">Administrador</option>
                    <option value="monitor">Monitor</option>
                    <option value="supervisor">Supervisor</option>
                </select>
            </div>

            <button type="submit">Registrarse</button>
        </form>
        
        <p>¿Ya tienes una cuenta? <a href="/Proyectos/simulacionTrafico/views/auth/login.php">Inicia sesión</a></p>
    </div>
</body>
</html>
