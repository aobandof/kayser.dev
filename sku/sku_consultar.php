<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/DBConection.php");
require_once("../shared/clases/inflector.php");
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
$conexion_mssql_13_stock=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$conexion_mssql_17_wmstek=new DBConnection('sqlsrv', $MSSQL['17']['host'], $MSSQL['17']['user'], $MSSQL['17']['pass'],'WMSTEK_KAYSER');
$conexion_mysql_dev=new DBConnection('mysql', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$mssql_13_stock=$conexion_mssql_13_stock->getConnection();
$mssql_17_wmstek=$conexion_mssql_17_wmstek->getConnection();
$mysql_dev=$conexion_mysql_dev->getConnection();
$data=[];
if(!$conector_mssql){
   if(sqlsrv_errors()!=null) {
       cargarErrores();
       exit;
   }
}

  echo json_encode($data);

function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  $data['errores']=$errores;
  echo json_encode($data);
}
?>
