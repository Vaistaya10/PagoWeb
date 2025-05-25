<?php

require_once "Conexion.php";

class Beneficiario extends Conexion {
    private $pdo;

    public function __construct(){
        $this->pdo = parent::getConexion();
    }

    public static function getAll(): array
    {
        try {
            $pdo = Conexion::getConexion();
            $stmt = $pdo->prepare("CALL spGetBeneficiariosContrato()");
            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $filas;
        } catch (PDOException $e) {
            throw new Exception("Error en Beneficiario::getAll -> " . $e->getMessage());
        }
    }


    public function add($params = []): int{
        $numRows = 0;
        try {
            $query = "CALL spCreateBeneficiario(?,?,?,?,?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                $params['apellidos'],
                $params['nombres'],
                $params['dni'],
                $params['telefono'],
                $params['direccion']
            ));
               $numRows = $stmt->rowCount(); 

            } catch (PDOException $e) {
      error_log("Error DB: " . $e->getMessage());
      return $numRows;
    } 
    return $numRows;
  }
    }



