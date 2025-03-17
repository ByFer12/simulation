<?php
include '/includes/header.php';
include '/includes/nav.php';
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Mantenimiento de Semáforos</h2>
    <button class="btn-add">Agregar Semáforo</button>
    <table class="traffic-light-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Ubicación</th>
          <th>Configuración</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Calle 1 y Av. Principal</td>
          <td>30 seg</td>
          <td>
            <a href="#" class="btn-edit">Editar</a>
            <a href="#" class="btn-delete">Eliminar</a>
          </td>
        </tr>
        <!-- Más filas según la data -->
      </tbody>
    </table>
  </section>
</main>
<?php
include '/includes/footer.php';
?>
