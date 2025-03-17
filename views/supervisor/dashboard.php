<?php
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/nav.php';

?>

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

<?php
include_once __DIR__ . '/includes/footer.php';
?>