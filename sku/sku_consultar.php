<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/inflector.php");
$sqlsrv_13_stock=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv_17_wmstek=new DBConnection('sqlsrv', $MSSQL['17']['host'], $MSSQL['17']['user'], $MSSQL['17']['pass'],'WMSTEK_KAYSER');
$mysqli_dev_articulos=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[];
$existe_error_conexion=0;
if($sqlsrv_13_stock->getConnection()===false) { $data['errors'][]=$sqlsrv_13_stock->getErrors(); $existe_error_conexion=1; }
if($sqlsrv_17_wmstek->getConnection()===false)  { $data['errors'][]=$sqlsrv_17_wmstek->getErrors(); $existe_error_conexion=1; }
if($mysqli_dev_articulos->getConnection()===false)  {$data['errors'][]=$mysqli_dev_articulos->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
$query="select * from Marca";
if($arr_marcas=$mysqli_dev_articulos->select($query)===false)
  $data['errors'][]=$mysqli_dev_articulos->getErrors();
else {
  $data[]=array('tabla'=>Marcas, 'filas'=>$arr_marcas);
}
echo json_encode($data);
?>
