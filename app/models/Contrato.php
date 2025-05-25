<?php
// app/models/ContratoModel.php

require_once __DIR__ . "/Conexion.php";

class Contrato extends Conexion {

    private $pdo;

    public function __CONSTRUCT() {
        $this->pdo = parent::getConexion();
    }
    public  function getAll(): array {
        $result = [];

        try {
            $sql = "CALL spGetContratosActivos()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
      throw new Exception($e->getMessage());
    }
    return $result;
  }


    public function getById($idcontrato): array {

        $result = [];

        try {
            $sql = "CALL spGetContratoById(?)";
          $cmd = $this->pdo->prepare($sql);
      $cmd->execute(
        array($idcontrato)
      );
      $result = $cmd->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }

    return $result;
  }

    /**
     * Ejemplo de cómo llamar a sp_crear_contrato con parámetros de salida.
     */
    public function add($params = []): int {
        $numRows = 0;
        try {
            $query = "CALL spCreateContrato(?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($query);
      $stmt->execute(array(
        $params["idbeneficiario"],
        $params["monto"],
        $params["interes"],
        $params["fechainicio"],
        $params["diapago"],
        $params["numcuotas"]
      ));

      $numRows = $stmt->rowCount();

    } catch (PDOException $e) {
      error_log("Error DB: " . $e->getMessage());
      return $numRows;
    } 
    return $numRows;
  }
}
