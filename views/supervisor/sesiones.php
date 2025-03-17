<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/../../database/Database.php';
use database\Database;

// Obtener la conexión a la base de datos
$db = Database::getInstance()->getConnection();

// Ejecutar el procedimiento almacenado
$sql = "CALL ReporteSesionesPorMonitor()";
$stmt = $db->prepare($sql);
$stmt->execute();
$monitores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="dashboard-content">
  <section class="main-area">
    <h2>Reporte de Sesiones por Monitor</h2>
    <button class="btn-back" onclick="window.history.back()">Volver</button>

    <table class="report-table">
      <thead>
        <tr>
          <th>ID Monitor</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Usuario</th>
          <th>Total Sesiones</th>
          <th>Tiempo Total (segundos)</th>
          <th>Tiempo Formateado</th>
          <th>Primera Sesión</th>
          <th>Última Sesión</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($monitores): ?>
          <?php foreach ($monitores as $monitor): ?>
            <tr>
              <td><?php echo htmlspecialchars($monitor['id_monitor']); ?></td>
              <td><?php echo htmlspecialchars($monitor['nombre']); ?></td>
              <td><?php echo htmlspecialchars($monitor['apellido']); ?></td>
              <td><?php echo htmlspecialchars($monitor['username']); ?></td>
              <td><?php echo htmlspecialchars($monitor['total_sesiones']); ?></td>
              <td><?php echo htmlspecialchars($monitor['tiempo_total_segundos']); ?></td>
              <td><?php echo htmlspecialchars($monitor['tiempo_total_formateado']); ?></td>
              <td><?php echo htmlspecialchars($monitor['primera_sesion']); ?></td>
              <td><?php echo htmlspecialchars($monitor['ultima_sesion']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="9">No se encontraron sesiones registradas.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>

<?php
include __DIR__ . '/includes/footer.php';
?>
