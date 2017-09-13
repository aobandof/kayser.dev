<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
$mysqli_dev_articulos=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if($mysqli_dev_articulos->getConnection()===false)  {$data['errors'][]=$mysqli_dev_articulos->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

// **************************   CARGA EN DATA EL Código del Articulo o SKU buscado ***************************
  $marca=$_GET['Marca'];
  // $dpto=getIdFromName('Kayser_OITB',$_GET['padre']);
  $query_cod_dpto="SELECT ".$tablas_sku["Kayser_OITB"]["id"]." FROM Kayser_OITB WHERE ".$tablas_sku["Kayser_OITB"]["campo"]."='".$_GET['padre']."';";
  $arr_cod_dpto=$mysqli_dev_articulos->select($query_cod_dpto);
  $dpto=$arr_cod_dpto[0];
  echo $query_cod_dpto;
  var_dump($arr_cod_dpto);
  $subdpto=$_GET['Subdpto'];
  $prenda=$_GET['Kayser_SEASON'];
  $categoria=$_GET['Kayser_DIV'];
  $presentacion=$_GET['Presentacion'];
  echo $marca;
  echo $dpto;
  echo $subdpto;
  echo $prenda;
  echo $categoria;
  echo $presentacion;
  $solo2="";
  $prefijo="";
  $data=[];
  $query="SELECT Dpto_codigo,SubDpto_id,Prenda_codigo,Categoria_codigo,Presentacion_id, prefijo from RelacionPrefijo ";
  $arr_prefijos=$mysqli_dev_articulos->select($query);
  foreach ($arr_prefijos as $value) {
    if($value['Dpto_codigo']==$dpto AND $value['Prenda_codigo']==$prenda){
      if($solo2==""){
        $solo2="cualquier_valor";
        $prefijo=$value['prefijo'];
      }
      if($value['SubDpto_id']==$subdpto){
        $prefijo=$value['prefijo'];
        break;
      }
      if($value['Categoria_codigo']==$categoria){
        $prefijo=$value['prefijo'];
        break;
      }
    }
  }
  $data['prefijo']=$prefijo;
  echo json_encode($data);

?>
