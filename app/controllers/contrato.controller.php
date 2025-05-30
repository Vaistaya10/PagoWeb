<?php
// app/controllers/contrato.controller.php

require_once __DIR__ . "/../models/Contrato.php";

if (!isset($_GET['operation'])) {
    http_response_code(400);
    echo "No se recibió ninguna operación.";
    exit;
}

switch ($_GET['operation']) {

    case 'getAll':
        try {
            $contrato = new Contrato();
            $rows  = $contrato->getAll();
            foreach ($rows as $c) {
                echo "
                <tr>
                  <td>{$c['idcontrato']}</td>
                  <td>{$c['idbeneficiario']}</td>
                  <td>{$c['beneficiario']}</td>
                  <td>{$c['monto']}</td>
                  <td>{$c['interes']}</td>
                  <td>{$c['fechainicio']}</td>
                  <td>{$c['diapago']}</td>
                  <td>{$c['numcuotas']}</td>
                </tr>";
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error al listar contratos: " . $e->getMessage();
        }
        break;



    case 'getById':
        if (empty($_GET['idcontrato'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta idcontrato']);
            exit;
        }
        $id = intval($_GET['idcontrato']);
        try {
            $contrato  = new Contrato();
            $result = $contrato->getById($id);
            if (empty($result)) {
                http_response_code(404);
                echo json_encode(['error' => 'Contrato no encontrado']);
            } else {
                header('Content-Type: application/json');

                echo json_encode($result[0]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;



    case 'add':

        $params = [
            'idbeneficiario' => $_POST['idbeneficiario'] ?? '',
            'monto'          => $_POST['monto']          ?? '',
            'interes'        => $_POST['interes']        ?? '',
            'fechainicio'    => $_POST['fechainicio']    ?? '',
            'diapago'        => $_POST['diapago']        ?? '',
            'numcuotas'      => $_POST['numcuotas']      ?? '',
        ];


        if (in_array('', $params, true)) {
            http_response_code(400);
            echo "Faltan datos para crear contrato.";
            exit;
        }

        try {
            $contrato    = new Contrato();
            $affected = $contrato->add($params);
            
            echo $affected;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error al crear contrato: " . $e->getMessage();
        }
        break;


    default:
        http_response_code(400);
        echo "Operación no soportada.";
}
