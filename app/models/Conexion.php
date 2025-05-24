<?php

require_once "../config/Server.php";
require_once "../helpers/helper.php";

//Temporalmente los parámetros de conexión estarán en la clase
//hasta arreglar el problema que genera llamarlo desde REPORTES
  
class Conexion{
  
  protected static function getConexion(){
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
/*
  public function encryption($string){
    $output=FALSE;
    $key=hash('sha256', SECRET_KEY);
    $iv=substr(hash('sha256', SECRET_IV), 0, 16);
    $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
    $output=base64_encode($output);
    return $output;
  }

  protected static function decryption($string){
    $key=hash('sha256', SECRET_KEY);
    $iv=substr(hash('sha256', SECRET_IV), 0, 16);
    $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
    return $output;
  }

  protected static function generarCodigoAleatorio($letra, $longitud, $numero){
    for ($i = 1; $i <= $longitud; $i++){
      $aleatorio = rand(0,9);
      $letra .= $aleatorio;
    }
    return $letra . "-" . $numero;
  }
*/

  

}