<?php

require_once "../models/Conexion.php";

class Cotizacion extends Conexion {

  private $pdo;
  public function __construct(){
    $this->pdo = parent::getConexion();
  }
}