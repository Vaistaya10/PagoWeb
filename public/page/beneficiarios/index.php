  <?php
  require_once "../../partials/header.php";
  ?>
  <h3>Beneficiarios</h3>
  <hr>

  <!-- Vista de Beneficiarios -->
<div class="container">


  <div class="text-end mb-3">
    <a href="registrar-beneficiario.php" class="btn btn-success">Registrar</a>
  </div>
  <table id="tabla-beneficiarios" class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Apellidos</th>
        <th>Nombres</th>
        <th>DNI</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Contrato Reciente</th>
        <th>Fecha Inicio</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- Aquí se inyectan las filas vía JS -->
    </tbody>
  </table>
</div>

<script>
  // Al cargar la página, trae y renderiza los beneficiarios
  document.addEventListener("DOMContentLoaded", async () => {
    const params = new URLSearchParams({ operation: 'getAll' });
    const res = await fetch(`http://localhost/pagoweb/app/controllers/beneficiario.controller.php?${params}`);
    const htmlRows = await res.text();
    document.querySelector('#tabla-beneficiarios tbody').innerHTML = htmlRows;
  });
</script>
