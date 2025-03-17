<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Supervisor</title>
    <link rel="stylesheet" href="/Proyectos/simulacionTrafico/public/css/supervisor_dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <h3>Tiempo de Sesión<br /> <span id="session-time">00:00:00</span></h3>
            <br />
            <div class="user-info">
                <span class="user-icon">&#128100;</span> <span class="user-name"><?php echo $_SESSION['nombre'] ?? 'Supervisor'; ?></span>
            </div>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Monitoreo de Simulación</a></li>
                <li><a href="#">Gestión de Semáforos</a></li>
                <li><a href="#">Reportes de Monitores</a></li>
            </ul>
            <div class="logout-button">
                <a href="/Proyectos/simulacionTrafico/logout" class="btn-logout">Cerrar Sesión</a>
            </div>
        </nav>

        <main class="dashboard-content">
            <section class="main-area">
                <h2>Panel de Supervisor</h2>
                <p>Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Supervisor'; ?>. Aquí puedes supervisar el sistema.</p>

                <div class="supervisor-options">
                    <div class="option-card">
                        <h3><a href="#">Monitoreo de Simulación</a></h3>
                        <p>Ver el estado actual de las simulaciones en curso.</p>
                        </div>

                    <div class="option-card">
                        <h3><a href="#">Gestión de Semáforos</a></h3>
                        <p>Visualizar y gestionar el estado y la configuración de los semáforos.</p>
                        </div>

                    <div class="option-card">
                        <h3><a href="#">Reportes de Monitores</a></h3>
                        <p>Acceder a reportes generados por los monitores.</p>
                        </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let startTime = localStorage.getItem('sessionStartTime');
            const sessionTimeDisplay = document.getElementById('session-time');

            if (!startTime) {
                startTime = new Date().getTime();
                localStorage.setItem('sessionStartTime', startTime);
            } else {
                startTime = parseInt(startTime, 10);
            }

            function updateSessionTime() {
                const currentTime = new Date().getTime();
                const elapsedTime = Math.floor((currentTime - startTime) / 1000); // Segundos transcurridos

                const hours = Math.floor(elapsedTime / 3600);
                const minutes = Math.floor((elapsedTime % 3600) / 60);
                const seconds = elapsedTime % 60;

                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                sessionTimeDisplay.textContent = formattedTime;
            }

            setInterval(updateSessionTime, 1000); // Actualizar cada segundo

            window.addEventListener('beforeunload', function() {
                localStorage.removeItem('sessionStartTime');
            });
        });
    </script>

    <footer class="dashboard-footer">
        hecho por byron torres
    </footer>
</body>

</html>