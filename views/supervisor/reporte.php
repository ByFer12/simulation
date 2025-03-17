<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/../../database/Database.php';
use database\Database;

// Obtener la conexión a la base de datos
$db = Database::getInstance()->getConnection();

// Establecer fechas por defecto (último mes) si no se proporcionan
$fechaFin = date('Y-m-d');
$fechaInicio = date('Y-m-d', strtotime("-30 days"));

// Actualizar fechas si se proporcionan mediante formulario
if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
}

// Ejecutar el procedimiento almacenado con las fechas
$sql = "CALL ReporteActividadCompleta(?, ?)";
$stmt = $db->prepare($sql);
$stmt->bindParam(1, $fechaInicio);
$stmt->bindParam(2, $fechaFin);
$stmt->execute();
$monitores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="dashboard-content">
  <section class="main-area">
    <h2>Reporte de Actividad Completa por Monitor</h2>
    <button class="btn-back" onclick="window.history.back()">Volver</button>
    
    <!-- Formulario para filtrar por fechas -->
    <div class="filter-form">
      <form method="POST" action="">
        <div class="form-group">
          <label for="fecha_inicio">Fecha Inicio:</label>
          <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fechaInicio; ?>">
        </div>
        <div class="form-group">
          <label for="fecha_fin">Fecha Fin:</label>
          <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaFin; ?>">
        </div>
        <button type="submit" class="btn-filter">Filtrar</button>
      </form>
    </div>

    <div class="report-summary">
      <h3>Período: <?php echo date('d/m/Y', strtotime($fechaInicio)); ?> - <?php echo date('d/m/Y', strtotime($fechaFin)); ?></h3>
      <p>Total de monitores activos: <?php echo count($monitores); ?></p>
    </div>

    <table class="report-table">
      <thead>
        <tr>
          <th>ID Monitor</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Usuario</th>
          <th>Total Sesiones</th>
          <th>Simulaciones</th>
          <th>Iteraciones</th>
          <th>Semáforos</th>
          <th>Vehículos</th>
          <th>Tiempo Rojo (Prom)</th>
          <th>Tiempo Amarillo (Prom)</th>
          <th>Tiempo Verde (Prom)</th>
          <th>Primera Actividad</th>
          <th>Última Actividad</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($monitores && count($monitores) > 0): ?>
          <?php foreach ($monitores as $monitor): ?>
            <tr>
              <td><?php echo htmlspecialchars($monitor['id_monitor']); ?></td>
              <td><?php echo htmlspecialchars($monitor['nombre']); ?></td>
              <td><?php echo htmlspecialchars($monitor['apellido']); ?></td>
              <td><?php echo htmlspecialchars($monitor['username']); ?></td>
              <td><?php echo htmlspecialchars($monitor['total_sesiones']); ?></td>

              <td><?php echo htmlspecialchars($monitor['total_simulaciones']); ?></td>
              <td><?php echo htmlspecialchars($monitor['total_iteraciones']); ?></td>
              <td><?php echo htmlspecialchars($monitor['total_semaforos_configurados']); ?></td>
              <td><?php echo htmlspecialchars($monitor['total_vehiculos_monitoreados']); ?></td>
              <td><?php echo htmlspecialchars(round($monitor['promedio_tiempo_rojo'])); ?></td>
              <td><?php echo htmlspecialchars(round($monitor['promedio_tiempo_amarillo'])); ?></td>
              <td><?php echo htmlspecialchars(round($monitor['promedio_tiempo_verde'])); ?></td>
              <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($monitor['primera_actividad']))); ?></td>
              <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($monitor['ultima_actividad']))); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="15">No se encontraron datos de actividad para monitores en el período seleccionado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    
    <!-- Sección para exportar datos -->
    <div class="export-options">
      <button class="btn-export" onclick="exportTableToCSV('reporte_actividad_monitores.csv')">Exportar a CSV</button>
      <button class="btn-export" onclick="window.print()">Imprimir Reporte</button>
    </div>
  </section>
</main>

<!-- Script para exportar tabla a CSV -->
<script>
function exportTableToCSV(filename) {
  var csv = [];
  var rows = document.querySelectorAll('.report-table tr');
  
  for (var i = 0; i < rows.length; i++) {
    var row = [], cols = rows[i].querySelectorAll('td, th');
    
    for (var j = 0; j < cols.length; j++) {
      row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
    }
    
    csv.push(row.join(','));
  }
  
  // Descargar CSV
  var csvFile = new Blob([csv.join('\n')], {type: "text/csv"});
  var downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}
</script>

<style>
.filter-form {
  margin: 20px 0;
  padding: 15px;
  background-color: #f5f5f5;
  border-radius: 5px;
}

.filter-form .form-group {
  display: inline-block;
  margin-right: 15px;
}

.report-summary {
  margin: 20px 0;
  padding: 10px;
  background-color: #e8f4ff;
  border-left: 4px solid #4a90e2;
}

.export-options {
  margin-top: 20px;
  text-align: right;
}

.btn-export {
  background-color: #4CAF50;
  color: white;
  padding: 8px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-left: 10px;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-size: 0.9em;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.report-table thead tr {
  background-color: #4a90e2;
  color: #ffffff;
  text-align: left;
}

.report-table th,
.report-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #dddddd;
}

.report-table tbody tr:hover {
  background-color: #f1f1f1;
}

@media print {
  .btn-back, .filter-form, .export-options, nav {
    display: none;
  }
}
</style>

<?php
include __DIR__ . '/includes/footer.php';
?>