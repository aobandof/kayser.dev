<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
// require_once "../shared/clases/MysqlConexion.php";
require_once "../shared/clases/HelpersDB.PHP";
require_once "../shared/clases/inflector.php";
require_once "../shared/clases/campos.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
$conexion_mssql=new MssqlConexion($MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$conector_mssql=$conexion_mssql->obtener_conector();
$mysqli=new mysqli($MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_sku');
$mysqli->set_charset("utf8");
$mysqli->query("SET collation_connection = utf8_bin");
if(!$conector_mssql){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
// echo "el id es: ".getIdFromName("Kayser_OITB", "lola")."<br>";
if(isset($_POST['nom_tabla_padre'])) {
  echo json_encode("enviando json");
  $array_rel=[];
  $options=[];
  $padre=$_POST['nom_tabla_padre'];
  $codigo_padre=getIdFromName($_POST['nom_tabla_padre'],$_POST['val_tabla_padre']);
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $ops="";
    if($_POST['nom_tabla_padre']==$array_tabla['dep']){
      $nombre_id=$array_tabla['id'];
      $nombre_name=$array_tabla['campo'];
      if($_POST['nom_tabla_padre']==""){////////////////// TABLA SIN DEPENDENCIA
        if($array_tabla['id']=="")//TABLA SIN DEPENDENCIA SIN ID
          $query="select * from $tabla";
        else
          $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla";
      } else {//////////////////////////////////  TABLA DEPENDIENTE
        if($array_tabla['id']=="") {//TABLA DEPENDIENTE SIN ID
          if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE SIN ID PERO CON ID PADRE ENTERO
            $query = "select * from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
          else
            $query = "select * from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
        }else {// TABLA DEPENDIENTE CON ID
          if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE CON ID PROPIO E ID PADRE ENTERO
            $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
          else
            $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
        }
      }
      if($array_tabla['bd']=="mysql"){ // SI LA TABLA ES MYSQL
        if(!$registros=$mysqli->query($query)){
          $error="Error: La ejecución de la consulta falló debido a: \nQuery: " . $query . "\nErrno: " . $mysqli->errno . "\nError: " . $mysqli->error . "\n";
          $options[]=array('error'=> $error);
          continue;//pasamos al siguiente recorrido de foreach
        }else {
          $ops="";
          while ($reg = $registros->fetch_assoc()) {
            if($array_tabla['id']=="")
              $ops.="<option value='".$reg["$nombre_name"]."'>".$reg["$nombre_name"]."</option>";
            else
              $ops.="<option value='".$reg["$nombre_id"]."'>".$reg["$nombre_name"]."</option>";
          }
        }
      } else { // si la tabla es MSSQL
        if($array_tabla['dep']=""){   // tabla mssql sin DEPENDENCIA
          if(!$registros=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ))){
            $options[]=array('error'=> "tarea (detector y enviar por aca)");
            continue;//pasamos al siguiente recorrido de foreach
          }
          else {
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_ASSOC)){
              $ops.="<option value='".$reg["$nombre_id"]."'>".$reg["$nombre_name"]."</option>";
            }
          }
        } else {
          // NO PODEMOS HACER JOIN, dado que SON BASES DE DATOS DISTINTAS
          // realizamos una consulta a la Taba dependencia Mysql y lo guardamos en un Array (considerando solo los que tienen el codigo del padre)
          if($tablas_sku[$padre]['type_id']=="INT")
            $query_rel="SELECT * from ".$array_tabla['tabla_rel']." where ".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
          else
            $query_rel="SELECT * from ".$array_tabla['tabla_rel']." where ".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          if(!$registros=$mysqli->query($query)){
            $error="Error: La ejecución de la consulta falló debido a: \nQuery: " . $query_rel . "\nErrno: " . $mysqli->errno . "\nError: " . $mysqli->error . "\n";
            $options[]=array('error'=> $error);
            continue;//pasamos al siguiente recorrido de foreach
          }else {
            $ops="";
            while ($reg = $registros->fetch_numeric()) {
              $array_rel[]=$reg[1];
            }
          }
        }
      }
      $options[]=array('tabla'=>$tabla, 'options'=>$ops);
    }
  }
  $conexion_mssql->desconectar();
  $mysqli->close();
  echo json_encode($options);
}
else {
    echo json_encode('no se reconoce $_POST[opcion]'); //
}

function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  var_dump(json_encode($errores));
}
?>
