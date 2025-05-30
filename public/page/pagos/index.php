  <?php
  require_once "../../partials/header.php";
  ?>
  <h3>Cronograma de Pagos</h3>
  <hr>

  <div class="container">
    <table class="table table-striped table-bordered" id="tabla-pagos">
      <thead>
        <tr>
          <th>Item</th>
          <th>Fecha Pago</th>
          <th>Interés</th>
          <th>Abono Capital</th>
          <th>Valor cuota</th>
          <th>Saldo Capital</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>

    <div class="text-right">
  <a href="../beneficiarios/" class="btn btn-sm btn-secondary">Volver</a>
    </div>
  </div>

<script>
  document.addEventListener("DOMContentLoaded", async () => {
    
    const urlParams = new URLSearchParams(window.location.search);
    const idcontrato = urlParams.get('idcontrato') || '0';  // por defecto 1

    // 2) Función para pedir el cronograma
    async function obtenerCronograma(id) {
      const params = new URLSearchParams({
        operation: 'crearCronograma',
        idcontrato
      });
      const resp = await fetch(`../../../app/controllers/pago.controller.php?${params}`);
      return await resp.text();
    }

    // 3) Renderizar en la tabla
    const tbody = document.querySelector("#tabla-pagos tbody");
    tbody.innerHTML = await obtenerCronograma(idcontrato);
  });
</script>


</body>
</html>