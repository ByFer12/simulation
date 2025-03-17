<?php
include '/includes/header.php';
include '/includes/nav.php';
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Configuración General</h2>
    <form class="config-form" method="post" action="guardar_configuracion.php">
      <div class="form-group">
        <label for="limite-velocidad">Límite de Velocidad (km/h):</label>
        <input type="number" id="limite-velocidad" name="limite-velocidad" value="60">
      </div>
      <div class="form-group">
        <label for="tiempo-semaforo">Tiempo de Semáforo (segundos):</label>
        <input type="number" id="tiempo-semaforo" name="tiempo-semaforo" value="30">
      </div>
      <button type="submit" class="btn-save">Guardar Configuración</button>
    </form>
  </section>
</main>
<?php
include '/includes/footer.php';
?>
