<?php
// app/models/PagoModel.php

require_once __DIR__ . "/Conexion.php";

class Pago extends Conexion {

     private $pdo;

  public function __CONSTRUCT() {
    $this->pdo = parent::getConexion();
  }

    public function getPagosByContrato($idcontrato): array{

        $result = [];
        try {
            $sql = "CALL spGetPagosByContrato(?)";
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

  public function getContratoById($idcontrato): array {

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

    public function add($params = []): int {
        $numRows = 0;
        try {
            $sql = "CALL spRegisterPago(?,?,?,?,?,?)";
           $stmt = $this->pdo->prepare($sql);
      $stmt->execute(array(
        $params["idcontrato"],
        $params["numcuota"],
        $params["fechapago"],
        $params["monto"],
        $params["penalidad"],
        $params["medio"]
      ));

      $numRows = $stmt->rowCount();

    } catch (PDOException $e) {
      error_log("Error DB: " . $e->getMessage());
      return $numRows;
    } 
    return $numRows;
  }

}
