<?php
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
ini_set('display_errors', '0');
date_default_timezone_set("America/Santiago");
set_time_limit(2000);

$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'pdf');
$data=[]; $existe_error_conexion=0;
$table='';
if(($sqlsrv_33->getConnection())===false) { $data['errors'][]=$sqlsrv_33->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

$from=$_POST['from'];
$to=$_POST['to'];
$table="<table><thead><tr>";
$query="select * from PDFENE where Fecha>='$from' and Fecha<='$to'";
// $query="select top 10 * from PDFENE";

$data['querys']=$query;
$table_reg=$sqlsrv_33->selectTable($query);
// var_dump($table_reg);
if($table_reg!==false && $table_reg!==0){
  $arr_fields=$sqlsrv_33->getColumnsLastSelect();
  foreach($arr_fields as $field)
    $table.="<th>$field</th>";  
  $table.="</tr></tr></thead><tbody>$table_reg</tbody></html>";
}else ($table_reg===false) ? $data['errors']=$sqlsrv_33->getErrors() : $data['cant_reg']=$table_reg;
$data['table']=$table;
header("Content-Type: text/html;charset=utf-8");
// header('Content-type: application/json'); 
echo json_encode($data);
// echo $table;