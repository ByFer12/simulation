<?php
// includes/nav.php
?>
<div class="dashboard-container">
  <nav class="dashboard-nav">
    <h3>Tiempo de Sesión<br /><span id="session-time">00:00:00</span></h3>
    <br />
    <div class="user-info">
      <span class="user-icon">&#128100;</span> <span class="user-name"><?php echo $_SESSION['nombre'] ?? 'Administrador'; ?></span>
    </div>
    <ul>
      <li><a href="/Proyectos/simulacionTrafico/public/views/dashboard">Inicio</a></li>
      <li><a href="/Proyectos/simulacionTrafico/?url=admin/dashboard/gestion_usuarios">Gestión de Usuarios</a></li>

      <li><a href="/Proyectos/simulacionTrafico/public/views/configuracion_general.php">Configuración General</a></li>
      <li><a href="/Proyectos/simulacionTrafico/public/views/analisis_datos.php">Análisis de Datos</a></li>
      <li><a href="/Proyectos/simulacionTrafico/public/views/mantenimiento_semaforos.php">Mantenimiento de Semáforos</a></li>
    </ul>
    <div class="logout-button">
      <a href="/Proyectos/simulacionTrafico/logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
  </nav>
