<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/../../database/Database.php';
use database\Database;

// Obtener la conexión a la base de datos
$db = Database::getInstance()->getConnection();

// Consultar los semáforos en la base de datos
$sql = "SELECT id, simulation_id, json_id, red, green, yellow FROM semaphores";
$stmt = $db->prepare($sql);
$stmt->execute();
$semaforos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Gestión de Semáforos</h2>
    <button class="btn-add" onclick="window.location.href='/Proyectos/simulacionTrafico/?url=agregar_semaforo'">Agregar Semáforo</button>

    <table class="traffic-light-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Id Simulacion</th>
          <th>Id del JSON</th>
          <th>Tiempo en Rojo (Ms)</th>
          <th>Tiempo en Verde (ms)</th>
          <th>Tiempo en Verde (Ms)</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($semaforos): ?>
          <?php foreach ($semaforos as $semaforo): ?>
            <tr>
              <td><?php echo htmlspecialchars($semaforo['id']); ?></td>
              <td style="text-align: center;"><?php echo htmlspecialchars($semaforo['simulation_id']); ?></td>
              <td><?php echo htmlspecialchars($semaforo['json_id']); ?></td>
              <td><?php echo htmlspecialchars($semaforo['red']); ?></td>
              <td><?php echo htmlspecialchars($semaforo['green']); ?></td>
              <td><?php echo htmlspecialchars($semaforo['yellow']); ?></td>
              <td>
                <a href="/Proyectos/simulacionTrafico/?url=editar_semaforo&id=<?php echo $semaforo['id']; ?>" class="btn-edit">Editar</a>
                <a href="eliminar_semaforo.php?id=<?php echo $semaforo['id']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar este semáforo?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="6">No hay semáforos registrados.</td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>
<?php
include __DIR__ . '/includes/footer.php';
?>
