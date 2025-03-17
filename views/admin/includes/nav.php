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
      <li><a href="/Proyectos/simulacionTrafico/?url=admin/dashboard">Inicio</a></li>
      <li><a href="/Proyectos/simulacionTrafico/?url=admin/dashboard/gestion_usuarios">Gestión de Usuarios</a></li>

      <li><a href="/Proyectos/simulacionTrafico/?url=admin/dashboard/gestion_semaforo">Gestión de Semaforos</a></li>
      <li><a href="/Proyectos/simulacionTrafico/?url=admin/dashboard/gestion_calle">Gestión de Calles</a></li>
      
    </ul>
    <div class="logout-button">
      <a href="/Proyectos/simulacionTrafico/logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
  </nav>
