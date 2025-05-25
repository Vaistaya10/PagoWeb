<?php

require_once "../config/Server.php";
require_once "../helpers/helper.php";

//Temporalmente los parámetros de conexión estarán en la clase
//hasta arreglar el problema que genera llamarlo desde REPORTES
  
class Conexion{
  
  public static function getConexion(){
    try{
      $pdo = new PDO(SGBD, USER, PASS);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public static function ejecutarConsulaSimple($consulta){
    $sql = self::getConexion()->prepare($consulta);
    $sql->execute();
    return $sql;
  }

}