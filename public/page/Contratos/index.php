<?php
require_once "../../partials/header.php";
?>
<h3>Listado de Contratos</h3>
<hr>
<div class="container">

  
  <div class="card">
  <div class="card-body">
    <table class="table table-striped" id="tabla-contratos">
      <thead>
        <tr>
          <th>ID Contrato</th>
          <th>ID Beneficiario</th>
          <th>Beneficiario</th>
          <th>Monto</th>
          <th>Interés (%)</th>
          <th>Fecha Inicio</th>
          <th>Día Pago</th>
          <th>Nº Cuotas</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se inyectarán las filas -->
      </tbody>
    </table>
  </div>
</div>
</div>



<script>
document.addEventListener("DOMContentLoaded", async () => {
  const tbody = document.querySelector("#tabla-contratos tbody");

  try {
    const res = await fetch("http://localhost/pagoweb/app/controllers/contrato.controller.php?operation=getAll");
    if (!res.ok) throw new Error("Error al cargar contratos");
    const html = await res.text();
    tbody.innerHTML = html;
  } catch (err) {
    console.error(err);
    tbody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center text-danger">
          No se pudieron cargar los contratos.
        </td>
      </tr>`;
  }
});
</script>
