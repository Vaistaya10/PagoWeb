<?php
// app/controllers/beneficiario.controller.php

require_once __DIR__ . "/../models/Beneficiario.php";

if (isset($_GET['operation'])) {
    switch ($_GET['operation']) {

        // ------------------------------------------------
        // 1) Listar todos los beneficiarios + su contrato
        // ------------------------------------------------
        case 'getAll':
            try {
                $rows = Beneficiario::getAll();
                foreach ($rows as $b) {
                    // contrato_reciente y fechainicio pueden ser null
                    $contrato = $b['contrato_reciente'] ?? 'Sin contrato';
                    $inicio    = $b['fechainicio']       ?? 'No iniciado';
                    echo "
                    <tr>
                      <td>{$b['idbeneficiario']}</td>
                      <td>{$b['apellidos']}</td>
                      <td>{$b['nombres']}</td>
                      <td>{$b['dni']}</td>
                      <td>{$b['telefono']}</td>
                      <td>{$b['direccion']}</td>
                      <td>{$contrato}   </td>
                      <td>{$inicio} </td>
                      <td>
                        <a href='../contratos/registrar-contrato.php?id={$b['idbeneficiario']}' class='btn btn-primary btn-sm'>+ Contrato</a>
                        <a href='../pagos/index.php?idcontrato={$contrato}' class='btn btn-warning btn-sm'>Cronograma</a>
                      </td>
                    </tr>";
                }
            } catch (Exception $e) {
                // En caso de error, envía un mensaje
                http_response_code(500);
                echo "Error al listar beneficiarios: " . $e->getMessage();
            }
            break;

        // --------------------------------
        // 2) Crear un nuevo beneficiario
        // --------------------------------
        case 'add':
            error_log("POST en add: " . json_encode($_POST));
            // Recibimos los datos por GET (podrías usar POST si prefieres)
            $params = [
                'apellidos' => $_POST['apellidos']  ?? '',
                'nombres'   => $_POST['nombres']    ?? '',
                'dni'       => $_POST['dni']        ?? '',
                'telefono'  => $_POST['telefono']   ?? '',
                'direccion' => $_POST['direccion']  ?? ''
            ];

            // Validación mínima
            if (in_array('', $params, true)) {
                http_response_code(400);
                echo "Faltan datos para crear beneficiario.";
                exit;
            }

            try {
                $model   = new Beneficiario();
                $affected = $model->add($params);
                // Devuelve 1 si se insertó, 0 si falló (dni duplicado, etc.)
                echo $affected;
            } catch (Exception $e) {
                http_response_code(500);
                echo "Error al crear beneficiario: " . $e->getMessage();
            }
            break;

        default:
            http_response_code(400);
            echo "Operación no soportada.";
    }
} else {
    http_response_code(400);
    echo "No se recibió ninguna operación.";
}
