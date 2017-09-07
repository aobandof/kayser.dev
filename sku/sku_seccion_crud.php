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
    if(($arr_tabla=$mysqli->select($query))===false){
      $data['errors'][]=$mysqli->getErrors();
    }else {
      //obtenemos los nombres de campos de la consulta de la primera fila del resultado del query
      if($arr_tabla==0)
        $arr_tabla="SIN RESULTADOS";
      else
        foreach ($arr_tabla[0] as $key => $value)
          $arr_cabeceras[]=$key;
    }
  }
  else { //si el motor es MSSQL
    if(($arr_tabla=$sqlsrv->select($query))===false){
        $data['errors'][]=$sqlsrv->getErrors();
    }else {
      if($arr_tabla==0)
        $arr_tabla="SIN RESULTADOS";
      else
        foreach ($arr_tabla[0] as $key => $value)
          $arr_cabeceras[]=$key;
    }
  }
  $data['cabeceras']=$arr_cabeceras;
  $data['filas']=$arr_tabla;
  echo json_encode($data);
}
?>
