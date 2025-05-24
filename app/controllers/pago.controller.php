<?php

//Clase->Metodo 
require_once "../helpers/helper.php";

if(isset($_GET['operation'])){
  switch ($_GET['operation']) {
    case 'crearCronograma':
      $fechaRecibida = $_GET['fechaRecibida'];
      $fechaInicio = new DateTime($fechaRecibida);

      $monto        = floatval($_GET['monto']);
      $tasa         = floatval($_GET['tasa'])/100; // 
      $numeroCuotas = floatval($_GET['numeroCuotas']);

      // $tasaMensual = pow((1 + $tasa), (1 / 12)) - 1;
      $cuota = round(Pago($tasa,$numeroCuotas,$monto),2);

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

      for($i = 1; $i <= $numeroCuotas; $i++){
        $interesPeriodo = $saldoCapital * $tasa;
        $abonoCapital = $cuota - $interesPeriodo;
        $saldoCapitalTemporal = $saldoCapital - $abonoCapital;
        $sumatoriaInteres += $interesPeriodo;

        $interesPeriodoPrint = number_format($interesPeriodo,2,'.',',');
        $abonoCapitalPrint = number_format($abonoCapital,2,'.',',');
        $cuotaPrint = number_format($cuota,2,".",",");
        $saldoCapitalTemporalPrint =number_format($saldoCapitalTemporal,2,'.',',');

        //Ultima iteracion
        if ($i  == $numeroCuotas){

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
      $sumatoriaInteresPrint = number_format($sumatoriaInteres,2,".",",");
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

     


/*
      echo json_encode([
      "cuota"       => $cuota,
      "numeroCuotas"       => $numeroCuotas,
      "monto"       => $monto,
      "tasaMensual" => $tasa
    ]);
      break;
  */  
  }
}

?>