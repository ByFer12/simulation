<?php
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/nav.php';

?>
<main class="dashboard-content">
    <section class="main-area">
        <h2>Panel de Administración</h2>
        <p>Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Administrador'; ?>. Aquí puedes gestionar el sistema.</p>

        <div class="admin-options">
            <div class="option-card">
                <h3><a href="#">Gestión de Usuarios</a></h3>
                <p>Crear, editar y eliminar usuarios del sistema (monitores, supervisores).</p>
            </div>

            <div class="option-card">
                <h3><a href="#">Configuración General</a></h3>
                <p>Modificar parámetros generales del sistema, como límites de velocidad, etc.</p>
            </div>

            <div class="option-card">
                <h3><a href="#">Análisis de Datos</a></h3>
                <p>Visualizar reportes y análisis del tráfico simulado y/o real.</p>
            </div>

            <div class="option-card">
                <h3><a href="#">Mantenimiento de Semáforos</a></h3>
                <p>Gestionar la información de los semáforos (ubicación, configuración predeterminada).</p>
            </div>
        </div>
    </section>
</main>
<?php
include_once __DIR__ . '/includes/footer.php';
?>