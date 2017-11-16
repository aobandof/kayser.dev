<?php
require_once "config.php";
require_once "DBConnection.php";
require_once "HelpersDB.php";
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
echo "cantidad de registros";
echo $mysqli->quantityRecords('SELECT codigo FROM articulo WHERE lista_id=2');

