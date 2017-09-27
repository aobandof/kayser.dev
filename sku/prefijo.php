<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$mysqli=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

// **************************   CARGA EN DATA EL Código del Articulo o SKU buscado ***************************
  $dpto=getIdFromName('Kayser_OITB',$_POST['padre']);
  $marca=$_POST['Marca'];
  $subdpto=$_POST['Subdpto'];
  $prenda=$_POST['Kayser_SEASON'];
  $categoria=$_POST['Kayser_DIV'];
  $presentacion=$_POST['Presentacion'];
  // echo "Marca: ".$marca."<br>";
  // echo "Dpto: ".$dpto."<br>";
  // echo "Subdpto: ".$subdpto."<br>";
  // echo "Prenda: ".$prenda."<br>";
  // echo "Categoria: ".$categoria."<br>";
  // echo "Presentacion: ".$presentacion."<br>";
  $solo2="";
  $prefijo="";
  $data=[];
  $query="SELECT Dpto_codigo,SubDpto_id,Prenda_codigo,Categoria_codigo,Presentacion_id, prefijo from RelacionPrefijo ";
  $arr_prefijos=$mysqli->select($query,"mysqli_a_o");
  // var_dump($arr_prefijos);
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
  //AHORA OBTENEMOS EL CORRELATIVO:
  $query_ultimo="SELECT U_APOLLO_SEG1 FROM Kayser_OITM WHERE ItemCode like '$prefijo.%' AND U_APOLLO_SEG1 IS NOT NULL GROUP BY U_APOLLO_SEG1";
  echo json_encode($data);

?>
