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
                    $contrato = $b['contrato_reciente'] ?? '';
                    $inicio    = $b['fechainicio']       ?? '';
                    echo "
                    <tr>
                      <td>{$b['idbeneficiario']}</td>
                      <td>{$b['apellidos']}</td>
                      <td>{$b['nombres']}</td>
                      <td>{$b['dni']}</td>
                      <td>{$b['telefono']}</td>
                      <td>{$b['direccion']}</td>
                      <td>{$contrato}</td>
                      <td>{$inicio}</td>
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
            // Recibimos los datos por GET (podrías usar POST si prefieres)
            $params = [
                'apellidos' => $_GET['apellidos']  ?? '',
                'nombres'   => $_GET['nombres']    ?? '',
                'dni'       => $_GET['dni']        ?? '',
                'telefono'  => $_GET['telefono']   ?? '',
                'direccion' => $_GET['direccion']  ?? ''
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
