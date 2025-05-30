<?php
require_once "../../partials/header.php";
?>
<h3>Beneficiarios | Registro</h3>
<hr>
<div class="container">

<div class="card border">
  <div class="card-body">
    <form id="formBeneficiario" autocomplete="off">
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="apellidos" name="apellidos" minlength="3" maxlength="100"
              placeholder="apellidos" autofocus required>
            <label for="numdoc"><strong>Apellidos</strong></label>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="nombres" name="nombres" minlength="3" maxlength="100"
              placeholder="apellidos" required>
            <label for="numdoc"><strong>Nombres</strong></label>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="dni" name="dni" minlength="8" maxlength="8"
              pattern="[0-9A-Za-z]+" placeholder="dni" required>
            <label for="numdoc"><strong>DNI</strong></label>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="telefono" name="telefono" minlength="9" maxlength="9"
              placeholder="telefono" required>
            <label for="numdoc"><strong>Telefono</strong></label>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="direccion" name="direccion" minlength="3" maxlength="100"
              placeholder="direccion" required>
            <label for="numdoc"><strong>Direccion</strong></label>
          </div>
        </div>
      </div>

  </div>

  <div class="card-footer text-end">
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
    <button id="btnregistrar" type="submit" class="btn btn-success">Registrar</button>
  </div>
  </form>
</div>
</div>

<script src="http://localhost/PagoWeb/public/assets/js/swalcustom.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formBeneficiario");

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      const apellidos = document.getElementById("apellidos").value;
      const nombres = document.getElementById("nombres").value;
      const dni = document.getElementById("dni").value;
      const telefono = document.getElementById("telefono").value;
      const direccion = document.getElementById("direccion").value;

      const datos = new FormData();
      datos.append("apellidos", apellidos);
      datos.append("nombres", nombres);
      datos.append("dni", dni);
      datos.append("telefono", telefono);
      datos.append("direccion", direccion);

      try {
        const response = await fetch("../../../app/controllers/beneficiario.controller.php?operation=add", {
          method: "POST",
          body: datos
        });

        if (!response.ok) {
          throw new Error("Error en la respuesta del servidor");
        }
        const afectadas = parseInt(text, 10);
        

        // Verificar la respuesta y mostrar un mensaje al usuario
        if (afectadas > 0) {
          alert('Beneficiario registrado exitosamente');
          window.location.href = `http://localhost/PagoWeb/public/page/beneficiarios/`;
          form.reset();
        } else {
          showToast("Ocurrió un error al registrar el beneficiario", "ERROR");
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("Ocurrio un error al registrar el campo", "ERROR")
      }

    });

  });
</script>
