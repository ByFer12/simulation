<?php
// includes/nav.php
?>
<div class="dashboard-container">
        <nav class="dashboard-nav">
            <h3>Tiempo de Sesión<br /> <span id="session-time">00:00:00</span></h3>
            <br />
            <div class="user-info">
                <span class="user-icon">&#128100;</span> <span class="user-name"><?php echo $_SESSION['nombre'] ?? 'Supervisor'; ?></span>
            </div>
            <ul>
            <li><a href="/Proyectos/simulacionTrafico/?url=supervisor/dashboard">Inicio</a></li>
                <li><a href="/Proyectos/simulacionTrafico/?url=supervisor/dashboard/simulaciones">Monitoreo de Simulación</a></li>
                <li><a href="/Proyectos/simulacionTrafico/?url=supervisor/dashboard/sesiones"> Sesiones</a></li>
                <li><a href="/Proyectos/simulacionTrafico/?url=supervisor/dashboard/reporte">Reportes de Monitores</a></li>
            </ul>
            <div class="logout-button">
                <a href="/Proyectos/simulacionTrafico/logout" class="btn-logout">Cerrar Sesión</a>
            </div>
        </nav>