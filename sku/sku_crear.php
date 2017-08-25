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
$mysqli=new mysqli($MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$mysqli->set_charset("utf8");
$mysqli->query("SET collation_connection = utf8_bin");
if(!$conector_mssql){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
// echo "el id es: ".getIdFromName("Kayser_OITB", "lola")."<br>";
// var_dump(getArrayIdName("Kayser_OITB"));
// addGrandChild('Kayser_OITB');//OJO CON LAS MAYUSCULAS
// var_dump($array_grand_child);
if($_POST['opcion']=="cargar_selects_independientes"){
  // echo json_encode("enviando json");
  $options=[];
  $nombre_name="";
  $nombre_id="";
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $arr_ops=[];
    if(!isset($array_tabla['dep'])){
      $nombre_name=$array_tabla['campo'];
      if(!isset($array_tabla['id']))//TABLA SIN DEPENDENCIA SIN ID
        $query="select * from $tabla";
      else {
        $nombre_id=$array_tabla['id'];
        $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla";
      }
      //**** DEPENDIENDO DEL TIPO DE MOTOR DE BASE DE DATOS *****/
      if($array_tabla['bd']=="mysql"){ // SI LA TABLA ES MYSQL
        if(!$registros=$mysqli->query($query)){
          $error="Error: La ejecución de la consulta falló debido a: \nQuery: " . $query . "\nErrno: " . $mysqli->errno . "\nError: " . $mysqli->error . "\n";
          $options[]=array('error'=> $error);
          continue;//pasamos al siguiente recorrido de foreach
        }else {
          while ($reg = $registros->fetch_assoc()) {
            if($nombre_id=="")
              $arr_ops[]=array('id'=>$reg[$nombre_name],'name'=>(string)$reg[$nombre_name]);
            else
              $arr_ops[]=array('id'=>$reg[$nombre_id], 'name'=>(string)$reg[$nombre_name]);
          }
        }
      }
      else { // si la tabla es MSSQL
        if(!$registros=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ))){
          $options[]=array('error'=> "tarea (detector y enviar por aca)");
          continue;//pasamos al siguiente recorrido de foreach
        }else {
          while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC))
            $arr_ops[]=array('id'=>$reg["$nombre_id"],'name'=>$reg["$nombre_name"]);
        }
      }
      $options[]=array('tabla'=>$tabla, 'options'=>$arr_ops);
    }
  }// fin foreach
  $conexion_mssql->desconectar();
  $mysqli->close();
  echo json_encode($options);
}
if($_POST['opcion']=="cargar_selects_dependientes") {
  array_splice($array_grand_child,0);//vaciamos el array nietos para buscar nuevos nietos
  //array $array_grand_child es global, declarado en un asset y contendrá los descendientes de las tablas qe se veran afectadas a peticion de la vista
  //es decir segun el nombre y valor del padre, se buscarán tablas dependientes y se cargarán valores relacionados al padre
  //y en  $array_grand_child se guardaran los nombres de descendientes de estas tablas y se enviaran a la vista para resetearse //este array con nombres se enviara en el index 0 del array data enviado a la vista
  $array_tabla_extraida=[];
  $options=[];
  $padre=$_POST['nom_tabla_padre'];
  if($tablas_sku[$padre]['dep']=='padre')//se buscará dependientes de DEPARTAMENTO (el padre supremo)
    $codigo_padre=getIdFromName($_POST['nom_tabla_padre'],$_POST['val_tabla_padre']); //en este caso, la vista envió el valor del nombre del departamente y no el id
  else
    $codigo_padre=$_POST['val_tabla_padre']; // para este caso, se pasó el id de la tabla obtenido del val del option padre
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $ops="";//"<option value=''></option>";
    $arr_ops=[];
    if(isset($array_tabla['dep'])){//si la tabla recorrida tiene padre
      if($_POST['nom_tabla_padre']==$array_tabla['dep']){//si la tabla padre es padre que vino de la vista
        addGrandChild($tabla); // buscamos descendientes dependientes de esta tabla y la agregamos al array $array_grand_child
        $nombre_id=$array_tabla['id'];
        $nombre_name=$array_tabla['campo'];
        if($array_tabla['bd']=="mysql") { // la tabla y la relacion estan en MOTOR MYSQL
          if(!isset($array_tabla['id'])) { //TABLA CON ID o CODIGO distinto al NOMBRE
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE SIN ID PERO CON ID PADRE ENTERO
              $query = "select ".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
            else
              $query = "select ".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          }else {// TABLA DEPENDIENTE CON ID
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE CON ID PROPIO E ID PADRE ENTERO
              $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
            else
              $query="select ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          }
          if(!$registros=$mysqli->query($query)){
            $error="Error: La ejecucion de la consulta fallo debido a: \nQuery: " . $query . "\nErrno: " . $mysqli->errno . "\nError: " . $mysqli->error . "\n";
            $options[]=array('error'=> $error);
            continue;//pasamos al siguiente recorrido de foreach
          }else {
            while ($reg = $registros->fetch_assoc()) {
             if($array_tabla['id']==""){
                $arr_ops[]=Array('id'=>$reg["$nombre_name"], 'name'=>$reg["$nombre_name"]);
              } else {
                $arr_ops[]=Array('id'=>$reg["$nombre_id"], 'name'=>$reg["$nombre_name"]);
              }
            }
          }
        }//fin if if($array_tabla['bd']=="mysql")
        else {//cargamos en un array el id y name de la tabla en mencion
          $array_tabla_extraida=getArrayIdName($tabla);
          if($tablas_sku[$padre]['type_id']=="INT")
            $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
          else
            $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          if(!$registros=$mysqli->query($query_relacion)){
            $error="Error: La ejecución de la consulta falló debido a: \nQuery: " . $query_relacion . "\nErrno: " . $mysqli->errno . "\nError: " . $mysqli->error . "\n";
            $options[]=array('error'=> $error);
            continue;//pasamos al siguiente recorrido de foreach
          }else{
            while($reg=$registros->fetch_array()){
              $nom_id_dep_rel=$reg[1];
              $arr_ops[]=Array('id'=>$reg[1], 'name'=>(string)$array_tabla_extraida["$nom_id_dep_rel"]);
            }
          }
        }
        if($tabla=='Talla'){
          $arr_tallas=[];
          foreach ($arr_ops as $value)
            $arr_tallas[]=array('familia'=>$value['id'], 'tallas'=>cargarTallasToFamilia($value['id']));
          $options[]=array('tabla'=>$tabla, 'options'=>$arr_tallas);
        } else
          $options[]=array('tabla'=>$tabla, 'options'=>$arr_ops);
      }//fin if($_GET['nom_tabla_padre']==$array_tabla['dep'])
    }
  }//fin foreach
  array_unshift($options, $array_grand_child);//agregmos los descendientes al inicio de la data a enviar por json
  $conexion_mssql->desconectar();
  $mysqli->close();
  echo json_encode($options);
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  var_dump(json_encode($errores));
}
?>
