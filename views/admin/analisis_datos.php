<?php
include '/includes/header.php';
include '/includes/nav.php';
?>
<main class="dashboard-content">
  <section class="main-area">
    <h2>Análisis de Datos</h2>
    <p>Aquí se mostrarán los reportes de pruebas, número de archivos cargados, tiempo de sesión, etc.</p>
    <!-- Ejemplo de contenedor para gráficos o reportes -->
    <div class="report-container">
      <h3>Reporte de Pruebas</h3>
      <table class="report-table">
        <thead>
          <tr>
            <th>ID Prueba</th>
            <th>Archivo Cargado</th>
            <th>Tiempo de Sesión</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>101</td>
            <td>prueba1.xml</td>
            <td>00:45:12</td>
            <td>2025-03-17</td>
          </tr>
          <!-- Más filas según la data -->
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php
include '/includes/footer.php';
?>
