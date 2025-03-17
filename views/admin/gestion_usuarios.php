<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';

?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Gestión de Usuarios</h2>
    <button class="btn-add">Agregar Usuario</button>
    <table class="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Ejemplo de fila, en producción los datos vendrán de la base de datos -->
        <tr>
          <td>1</td>
          <td>Juan Pérez</td>
          <td>juan@example.com</td>
          <td>Monitor</td>
          <td>
            <a href="#" class="btn-edit">Editar</a>
            <a href="#" class="btn-delete">Eliminar</a>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>María López</td>
          <td>maria@example.com</td>
          <td>Supervisor</td>
          <td>
            <a href="#" class="btn-edit">Editar</a>
            <a href="#" class="btn-delete">Eliminar</a>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</main>
<?php
include __DIR__ . '/includes/footer.php';
?>
