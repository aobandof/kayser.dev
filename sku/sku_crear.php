<?php
require_once "../shared/clases/config.php";
require_once("../shared/clases/DBConnection.php");
// require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/HelpersDB.PHP";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$mysqli=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

if($_POST['option']=="cargar_selects_independientes"){
  $options=[];
  $nombre_name="";
  $nombre_id="";
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $arr_ops=[];
    if(!isset($array_tabla['dep']) && $tabla!="RelacionPrefijo"){
      $nombre_name=$array_tabla['campo'];
      if(!isset($array_tabla['id']))//TABLA SIN DEPENDENCIA SIN ID
        $query="select ".$array_tabla['campo']." as id,".$array_tabla['campo']." as name from $tabla";
      else {
        $nombre_id=$array_tabla['id'];
        $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla";
      }
      if($array_tabla['bd']=="mysql"){// SI LA TABLA ES MYSQL
        if(($arr_ops=$mysqli->select($query))===false){
          $data['errors'][]=$mysqli->getErrors();
          continue;
        }
      }else {
        if(($arr_ops=$sqlsrv->select($query))===false){
          $data['errors'][]=$sqlsrv->getErrors();
          continue;
        }
      }
      if($arr_ops==0)
          $arr_ops="SIN RESULTADOS";
      $options[]=array('tabla'=>$tabla, 'options'=>$arr_ops);
    }
  }// fin foreach
  $mysqli->closeConnection();
  $sqlsrv->closeConnection();
  $data['values']=$options;
  echo json_encode($data);
}
if($_GET['option']=="cargar_selects_dependientes") {
  array_splice($array_grand_child,0);//vaciamos el array nietos para buscar nuevos nietos
  //array $array_grand_child es global, declarado en un asset y contendrá los descendientes de las tablas qe se veran afectadas a peticion de la vista
  //es decir segun el nombre y valor del padre, se buscarán tablas dependientes y se cargarán valores relacionados al padre
  //y en  $array_grand_child se guardaran los nombres de descendientes de estas tablas y se enviaran a la vista para resetearse //este array con nombres se enviara en el index 0 del array data enviado a la vista
  $array_tabla_extraida=[];
  $options=[];
  $padre=$_GET['nom_tabla_padre'];
  if($tablas_sku[$padre]['dep']=='padre')//se buscará dependientes de DEPARTAMENTO (el padre supremo)
    $codigo_padre=getIdFromName($_GET['nom_tabla_padre'],$_GET['val_tabla_padre']); //en este caso, la vista envió el valor del nombre del departamente y no el id
  else
    $codigo_padre=$_GET['val_tabla_padre']; // para este caso, se pasó el id de la tabla obtenido del val del option padre
  // echo $codigo_padre."<br>";
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $ops="";//"<option value=''></option>";
    $arr_ops=[];
    if(isset($array_tabla['dep'])){//si la tabla recorrida tiene padre
      if($_GET['nom_tabla_padre']==$array_tabla['dep']){//si la tabla padre de la tabla recorrida es padre que vino de la vista
        addGrandChild($tabla); // buscamos descendientes dependientes de esta tabla y la agregamos al array $array_grand_child
        $nombre_id=$array_tabla['id'];
        $nombre_name=$array_tabla['campo'];
        if($array_tabla['bd']=="mysql") { // la tabla y la relacion estan en MOTOR MYSQL
          if(!isset($array_tabla['id'])) { //TABLA CON ID o CODIGO distinto al NOMBRE
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE SIN ID PERO CON ID PADRE ENTERO
              $query = "select ".$array_tabla['campo']." as id, ".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
            else
              $query = "select ".$array_tabla['campo']." as id, ".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          }else {// TABLA DEPENDIENTE CON ID
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE CON ID PROPIO E ID PADRE ENTERO
              $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
            else
              $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";
          }
          echo $query."<br>";
          if(($arr_ops=$mysqli->select($query))===false){
            $data['errors'][]=$sqlsrv->getErrors();
            continue;//pasamos al siguiente recorrido de foreach
          }else {
            if($arr_ops==0)
              $arr_ops="SIN RESULTADOS";
          }
        }//fin if if($array_tabla['bd']=="mysql")
        else {//cargamos en un array el id y name de la tabla en mencion
          $array_tabla_extraida=getArrayIdName($tabla);
          if($tablas_sku[$padre]['type_id']=="INT")
            $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
          else
            $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'";            
          echo $query_relacion."<br>";
          if (($arr_rel=$mysqli->select($query_relacion)) === false) {
            $data['errors'][]=$sqlsrv->getErrors();
            continue;//pasamos al siguiente recorrido de foreach
          }else{
            // var_dump($arr_tabla);
             foreach ($arr_tabla as $value) {# code...
              $nom_id_dep_rel=$value[1];
              $arr_ops[]=Array('id'=>$value[1], 'name'=>(string)$array_tabla_extraida["$nom_id_dep_rel"]);
            }
          }
        }
        if($tabla=='Talla'){
          // var_dump($arr_ops);
          $arr_tallas=[];
          foreach ($arr_ops as $value)
            $arr_tallas[]=array('familia'=>$value['id'], 'tallas'=>cargarTallasToFamilia($value['id']));
          $data['values'][]=array('tabla'=>$tabla, 'options'=>$arr_tallas);
        } else
          $data['values'][]=array('tabla'=>$tabla, 'options'=>$arr_ops);
      }//fin if($_GET['nom_tabla_padre']==$array_tabla['dep'])
    }
  }//fin foreach
  array_unshift($options, $array_grand_child);//agregmos los descendientes al inicio de la data a enviar por json
  // $conexion_mssql->desconectar();
  // $mysqli->close();
  echo json_encode($data);
}
?>
