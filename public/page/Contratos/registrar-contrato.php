<?php
require_once "../../partials/header.php";
?>
<h3>Contratos | Registro</h3>
<hr>

<div class="container">
  <div class="card border">
    <div class="card-body">
      <form id="formContrato">
        <div class="row">

          <div class="col-md-4 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="monto" name="monto" minlength="3" maxlength="20" autofocus
              pattern="[0-9A-Za-z]+" placeholder="dni" required>
            <label for="numdoc"><strong>Monto</strong></label>
          </div>
        </div>

        <div class="col-md-4 mb-3">
  <div class="form-floating" style="padding-bottom:10px;">
    <input type="number" class="form-control" id="interes" name="interes" min="0.01" max="100.00" step="0.01" placeholder="Interés (%)" required >
    <label for="interes"><strong>Interés (%)</strong></label>
  </div>
</div>


         <div class="col-md-4">
            <div class="form-floating">
              <input type="date" class="form-control input" name="fechainicio" id="fechainicio" value="<?= date('Y-m-d') ?>" required >
              <label for="fechainicio"><strong>Fecha Inicio</strong></label>
            </div>
          </div>

          <div class="col-md-6 mb-3">
          <div class="form-floating">
            <input type="number" class="form-control" id="diapago" name="diapago" minlength="1" maxlength="2" min="1" max="31" step="1"
              pattern="[0-9A-Za-z]+" placeholder="dni" required>
            <label for="numdoc"><strong>Dia de pago</strong></label>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="form-floating">
            <input type="number" class="form-control" id="numcuotas" name="numcuotas" step="1" min="1" max="365"
              pattern="[0-9A-Za-z]+" placeholder="N° de cuotas" required>
            <label for="numdoc"><strong>N° de cuotas</strong></label>
          </div>
        </div>

        </div>
        <div class="card-footer text-end">
          <a href="../beneficiarios" class="btn btn-secondary">Cancelar</a>
          <button id="btnregistrar" type="submit" class="btn btn-success">Registrar</button>

        </div>
      </form>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formContrato');
  
  const urlParams     = new URLSearchParams(window.location.search);
  const idbeneficiario = urlParams.get('idbeneficiario');

  form.addEventListener('submit', async e => {
    e.preventDefault();

    
    if (!idbeneficiario) {
      alert('Falta id de beneficiario en la URL');
      return;
    }

    const formData = new FormData(form);
    formData.append('idbeneficiario', idbeneficiario);

    try {
      const response = await fetch(
        '../../../app/controllers/contrato.controller.php?operation=add',
        { method: 'POST', body: formData }
      );
      if (!response.ok) throw new Error('Error en la petición');

      
      const text     = await response.text();
      const affected = parseInt(text, 10);

      if (affected > 0) {
        alert('Contrato registrado exitosamente');
        
        window.location.href = `http://localhost/PagoWeb/public/page/beneficiarios/`;
      } else {
        alert('No se pudo crear el contrato.');
      }
    } catch (err) {
      console.error(err);
      alert('Ocurrió un error al registrar el contrato');
    }
  });
});
</script>
