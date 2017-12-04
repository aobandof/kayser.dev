<?php 
require_once "../config/config.php";
require_once "../config/DBConnection.php";
require_once "../config/HelpersDB.php";
require_once "../config/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
if($_POST['option']=="load_article_list"){
  $tables=$_POST['tables'];
  $tables_leng = count($tables);
  for($i=0; $i<$tables_leng; $i++){
    $table=$tables[$i];
    $query="SELECT ".$tablas_sku[$table]['id']." as id, ".$tablas_sku[$table]['campo']." as name FROM ".$table;
    if($tablas_sku[$table]['bd']=="mysql"){
      $arr_tabla=$mysqli->select($query,'mysqli_a_o');
    }else {
      $arr_tabla=$sqlsrv->select($query,'sqlsrv_a_p');
    }
    $data['tables'][$table]=$arr_tabla;
  }
  ///--- AHORA OBTENEMOS EL ARTICULO A MODIFICAR, para seleccionar y elegirlos en los option
  


  echo json_encode($data);
}