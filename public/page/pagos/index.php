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
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", async () => {
      async function obtenerCronograma(){
        const params = new URLSearchParams()
        params.append("operation","crearCronograma")
        params.append("fechaRecibida","2025-10-10")
        params.append("monto",3000)
        params.append("tasa",5)
        params.append("numeroCuotas",12)

      const response = await fetch (`../../../app/controllers/pago.controller.php?${params}`,{method:'GET'})
      return await response.text()
      }

      async function renderCronograma(){
        const tabla = document.querySelector("#tabla-pagos tbody")
        tabla.innerHTML = await obtenerCronograma();
      }

      await renderCronograma()
      
    })
  </script>

</body>
</html>