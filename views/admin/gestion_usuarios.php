<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

require_once __DIR__ . '/../../database/Database.php';
use database\Database;
// Obtener la conexión
$db = Database::getInstance()->getConnection();

// Consulta para obtener los usuarios
$sql = "SELECT id, nombre,apellido, rol FROM usuarios";
$stmt = $db->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Gestión de Usuarios</h2>
    <button class="btn-add" onclick="window.location.href='/Proyectos/simulacionTrafico/?url=register'">Agregar Usuario</button>

    <table class="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($usuarios): ?>
          <?php foreach ($usuarios as $usuario): ?>
            <tr>
              <td><?php echo htmlspecialchars($usuario['id']); ?></td>
              <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
              <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
              <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
              <td>
                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn-edit">Editar</a>
                <a href="eliminar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="5">No se encontraron usuarios.</td>
            </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>
<?php
include __DIR__ . '/includes/footer.php';
?>
