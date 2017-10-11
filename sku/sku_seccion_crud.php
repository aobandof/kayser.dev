<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
ini_set('display_errors', '0');
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$mysqli=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
if($_POST['option']=="cargar_seccion"){
  $filas=[];
  $cabecera=[];
  $ntabla=$_POST['nom_tabla'];
  if(isset($tablas_sku[$ntabla]['id'])) //SI LA TABLA TIENE ID
    if($ntabla!='Marca')//TABLA MARCA TIENE OTRO CAMPO "prefijo"
      $query="select ".$tablas_sku[$ntabla]['id']." as Codigo,".$tablas_sku[$ntabla]['campo']." as Nombre from  $ntabla";
    else
      $query="select ".$tablas_sku[$ntabla]['id']." as Codigo, ".$tablas_sku[$ntabla]['campo']." as Nombre, Prefijo from  $ntabla";
  else {
    $query="select ".$tablas_sku[$ntabla]['campo']." as Nombre from  $ntabla";
  }
  if($tablas_sku[$ntabla]['bd']=='mysql'){ // si el motor es MYSQL
    if(($arr_tabla=$mysqli->select($query,'mysqli_a_o'))===false){
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
    if(($arr_tabla=$sqlsrv->select($query,'sqlsrv_a_p'))===false){
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
#########################################  INSERT ITEM #############################################################
if($_GET['option']=="create_item") {
  echo "entro<br>";
  $table=$_GET['table'];
  foreach ($_GET as $key => $value)
    if($key!='table' && $key!='option')
      $values[$key]=$value;
  //PRIMERO COMPROBAMOS QUE NO EXISTA EL NOMBRE A INGRESAR, NI EL   
  
  // if($tablas_sku[$table]['bd']=='mysql'){// inicialmente solo se podran editar BDx de motor MYSQL
    $result=$mysqli->insert($table,$values);
  // }else
    // $result=$sqlsrv->insert($table,$values);
  /// if($result===false)
  ///   $data['errors'][]=$mysqli->getErrors();
  /// else
    $data['result']=$result;

  // $data['table']=$table;
  // $data['values']=$values;
  // echo "nada por ahora";
  echo json_encode($data);
}
#########################################  UPDATE ITEM #############################################################
if($_POST['option']=="update_item") {
  echo "nada por ahora";
}
#########################################  DELETE ITEM #############################################################
if ($_POST['option'] == "delete_item" ){
  echo "nada por ahora";
}
?>
