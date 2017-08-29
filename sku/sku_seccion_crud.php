<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/MssqlConexion.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
require_once("../shared/clases/campos.php");
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
///$conexion_mssql=new MssqlConexion($MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
///$conector_mssql=$conexion_mssql->obtener_conector();
$mysqli=new mysqli($MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$mysqli->set_charset("utf8");
$mysqli->query("SET collation_connection = utf8_bin");
$data=[];
///if(!$conector_mssql){
///   if(sqlsrv_errors()!=null) {
///       cargarErrores();
///       exit;
///   }
///}
if($_POST['opcion']=="cargar_seccion"){
  $filas=[];
  $cabecera=[];
  $ntabla=$_POST['nom_tabla'];
  if(isset($tablas_sku[$ntabla]['id']))
    if($ntabla!='Marca')
      $query="select ".$tablas_sku[$ntabla]['id']." as Codigo,".$tablas_sku[$ntabla]['campo']." as Nombre from  $ntabla";
    else
      $query="select ".$tablas_sku[$ntabla]['id']." as Codigo, ".$tablas_sku[$ntabla]['campo']." as Nombre, Prefijo from  $ntabla";
  else {
    $query="select ".$tablas_sku[$ntabla]['campo']." as Nombre from  $ntabla";
  }
  if($tablas_sku[$ntabla]['bd']=='mysql'){ // si el motor es MYSQL
    if(!$registros=$mysqli->query($query)){
      $options[]=array('error'=> "error en consulta $query");
    }else {
      $arr_cabecera=$registros->fetch_fields();//cuando $mysqli es orientado a objetos, con este metodo se carga todo el array y no es necesario recorrerlo
      foreach ($arr_cabecera as $value){
        foreach ($value as $key => $value) {
          if($key=='name')
            $cabecera[]=$value;
        }
      }
      while($reg=$registros->fetch_assoc())
        $filas[]=$reg;
    }
  }else { // si el motor es MSSQL
    if(!$registros=sqlsrv_query($conector_mssql, $query, array(), array("Scrollable"=>'static'))) {
      $options[]=array('error'=> "error en consulta $query");
    }else {
      foreach( sqlsrv_field_metadata( $registros) as $fieldMetadata )
        $cabecera[]=$fieldMetadata['Name'];
      while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC))
        $filas[]=$reg;
    }
  }
  $data['cabeceras']=$cabecera;
  $data['filas']=$filas;
  echo json_encode($data);
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  $data['errores']=$errores;
  echo json_encode($data);
}
?>
