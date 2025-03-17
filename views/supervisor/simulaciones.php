<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/../../database/Database.php';
use database\Database;

// Obtener la conexión a la base de datos
$db = Database::getInstance()->getConnection();

// Ejecutar el procedimiento almacenado
$sql = "CALL getReporteMonitoresSupervisor()";
$stmt = $db->prepare($sql);
$stmt->execute();
$monitores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Reporte de Simulaciones por Monitor</h2>

    <table class="simulation-report-table">
      <thead>
        <tr>
          <th>ID Monitor</th>
          <th>Nombre del Monitor</th>
          <th>Total de Simulaciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($monitores): ?>
          <?php foreach ($monitores as $monitor): ?>
            <tr>
              <td><?php echo htmlspecialchars($monitor['monitor_id']); ?></td>
              <td><?php echo htmlspecialchars($monitor['monitor_nombre']); ?></td>
              <td style="text-align: center;"><?php echo htmlspecialchars($monitor['total_simulaciones']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="3">No hay simulaciones registradas para monitores.</td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>
<?php
include __DIR__ . '/includes/footer.php';
?>
