<?php

//Clase->Metodo 
require_once "../models/Pago.php";
require_once "../helpers/helper.php";

if (isset($_GET['operation'])) {
  switch ($_GET['operation']) {
    case 'crearCronograma':
      $idcontrato = intval($_GET['idcontrato']);
      $contratoModel = new Contrato();
    $contrato = $contratoModel->getById($idcontrato);

    if (!$contrato) {
        die("Contrato no encontrado.");
    }
       // 3) Extraer variables
    $monto        = floatval($contrato['monto']);
    // tu tasa está guardada como porcentaje en DB, p.ej. 12.5 => 12.5%
    $tasa         = floatval($contrato['interes']) / 100;
    $numeroCuotas = intval($contrato['numcuotas']);
    // usamos la fecha de inicio del contrato
    $fechaInicio  = new DateTime($contrato['fechainicio']);

    // 4) (Opcional) si quieres registrar pagos: instancia PagoModel
    $pagoModel = new Pago();

    // 5) Cálculo del cronograma (igual que tenías)
    $cuota = round(Pago($tasa, $numeroCuotas, $monto), 2);

      echo "
      <tr>
      <td>0</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>{$monto}</td>
      </tr>
      ";

      //Operaciones Basicas
      $saldoCapital = $monto;
      $interesPeriodo = 0;
      $abonoCapital = 0;

      //Acumuladores
      $sumatoriaInteres = 0;

      for ($i = 1; $i <= $numeroCuotas; $i++) {
        $interesPeriodo = $saldoCapital * $tasa;
        $abonoCapital = $cuota - $interesPeriodo;
        $saldoCapitalTemporal = $saldoCapital - $abonoCapital;
        $sumatoriaInteres += $interesPeriodo;

        $interesPeriodoPrint = number_format($interesPeriodo, 2, '.', ',');
        $abonoCapitalPrint = number_format($abonoCapital, 2, '.', ',');
        $cuotaPrint = number_format($cuota, 2, ".", ",");
        $saldoCapitalTemporalPrint = number_format($saldoCapitalTemporal, 2, '.', ',');

        //Ultima iteracion
        if ($i  == $numeroCuotas) {

          $saldoCapitalTemporalPrint = 0.00;
        }
        echo "
      <tr>
      <td>{$i}</td>
      <td>{$fechaInicio->format('d-m-Y')}</td>
      <td>{$interesPeriodoPrint}</td>
      <td>{$abonoCapitalPrint}</td>
      <td>{$cuotaPrint}</td>
      <td>{$saldoCapitalTemporalPrint}</td>
      </tr>
      ";
        //Incrementar el mes
        $fechaInicio->modify('+1 month');
        $saldoCapital = $saldoCapitalTemporal;
      }
      $sumatoriaInteresPrint = number_format($sumatoriaInteres, 2, ".", ",");
      echo "
      <tr>
      <td></td>
      <td></td>
      <td>{$sumatoriaInteresPrint}</td>
      <td></td>
      <td></td>
      <td></td>
      </tr>
      ";
  }
}
