<?php
require_once("../shared/clases/config.php");
require_once("sku_db.php");
require_once("../shared/clases/inflector.php");
ini_set('display_errors', '0');
$data=[]; 
#########################################  SELECT ITEM #############################################################
if($_POST['option']=="read_relations"){
  $filas=[];
  $cabecera=[];
  $ntabla=$_POST['nom_tabla'];
  if(isset($tablas_sku[$ntabla]['id'])) //SI LA TABLA TIENE ID
    if($ntabla!='marca')//TABLA MARCA TIENE OTRO CAMPO "prefijo"
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo,".$tablas_sku[$ntabla]['campo']." AS Nombre from  $ntabla";
    else
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo, ".$tablas_sku[$ntabla]['campo']." AS Nombre, simbolo, posicion, tipo from  $ntabla";
  else {
    $query="select ".$tablas_sku[$ntabla]['campo']." AS Nombre from  $ntabla";
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
if($_POST['option']=="create_relation") {
  $repeated=0;
  $table=$_POST['table'];
  // var_dump($_POST);
  foreach ($_POST as $key => $value)
    if($key!='table' && $key!='option')
      $values[$key]=$value;
  //EXCEPCIONES DE CIERTAS TABLAS:
  ///ESTA EXCEPCION QUEDA NULA DADO QUE MARCA PUEDE TENER MISMO SIMBOLO
  // if($table=="Marca"){//dado que marca tiene el campo prefijo que es unico excepto cuando es vacio
  //   $prefijo=$_POST['prefijo'];
  //   $query_marca="select nombre from Marca where prefijo='$prefijo'";
  //   if(($arr_marca=$mysqli->select($query_marca,'mysqli_a_o'))!==false){
  //     if($arr_marca!==0)//quiere decir que se encontró el valor
  //       $repeated=1;
  //   }else 
  //     $data['errors'][]=$sqlsrv->getErrors();
  // }
  ///////// FIN EXCEPCIONES ////////
  if($repeated==0){
    if($tablas_sku[$table]['bd']=='mysql'){// inicialmente solo se podran editar BDx de motor MYSQL
      $result=$mysqli->insert($table,$values);
      if($result===false) $data['errors'][]=$mysqli->getErrors();
      else $data['result']=$result;
    }elseif($tablas_sku[$table]['bd']=='mssql') {     
      $result=$sqlsrv->insert($table,$values);
      if($result===false) $data['errors'][]=$sqlsrv->getErrors();
      else $data['result']=$result;
    }else
      $data['errors'][]="Nombre de Tabla o campos desconocidos";    
  }else { //si el valor es repetido
    $data['result']=-1;
  }
  echo json_encode($data);
}
#########################################  UPDATE ITEM #############################################################
if($_POST['option']=="update_relation") {
  $repeated=0;
  $table=$_POST['table'];
  $id_name=$tablas_sku[$table]['id'];
  $id_value=$_POST['id'];
  // var_dump($_POST);
  foreach ($_POST as $key => $value)
    if($key!='table' && $key!='option' && $key!="id")
      $values[$key]=$value;
  //EXCEPCIONES DE CIERTAS TABLAS:
  if($table=="marca"){//dado que marca tiene el campo prefijo que es unico excepto cuando es vacio
    $prefijo=$_POST['prefijo'];
    $query_marca="select nombre from marca where prefijo='$prefijo' AND prefijo!='' AND $id_name!=$id_value";
    if(($arr_marca=$mysqli->select($query_marca,'mysqli_a_o'))!==false){
      if($arr_marca!==0)//quiere decir que se encontró el valor
        $repeated=1;
    }else
      $data['errors'][]=$sqlsrv->getErrors();
  }
  ///////// FIN EXCEPCIONES ////////
  // echo $table."<br>";

  if($repeated==0){
    if($tablas_sku[$table]['bd']=='mysql'){// inicialmente solo se podran editar BDx de motor MYSQL
      $result=$mysqli->update($table,$id_name,$id_value,$values);
      if($result===false)
        $data['errors'][]=$mysqli->getErrors();
      else 
        $data['result']=$result;
    }elseif($tablas_sku[$table]['bd']=='mssql') {     
      $result=$sqlsrv->update($table,$id_name,$id_value,$values);
      if($result===false) $data['errors'][]=$sqlsrv->getErrors();
      else $data['result']=$result;
    }else
      $data['errors'][]="Nombre de Tabla o campos desconocidos";    
  }else { //si el valor es repetido
    $data['result']=-1;
    $data['resp']="No se pueden Actualizar Valores Existentes";
  }
   
  echo json_encode($data);
}
#########################################  DELETE ITEM #############################################################
if ($_POST['option'] == "delete_relation" ){
  $table=$_POST['table'];
  $id=$_POST['id'];
  if($tablas_sku[$table]['type_id']=='INT')
    $query="DELETE from $table WHERE ".$tablas_sku[$table]['id']."=$id";
  else 
    $query="DELETE from $table WHERE ".$tablas_sku[$table]['id']."='$id'";
  // echo $query."<br>";
  if($tablas_sku[$table]['bd']=='mysql'){
    if(($result=$mysqli->delete($query))===false)
      $data['errors'][]=$mysqli->getErrors();
  }elseif($tablas_sku[$table]['bd']=='mssql'){
    if(($result=$sqlsrv->delete($query))===false)#Esta pendiente esto
      $data['errors'][]=$sqlsrv->getErrors();
  }else
    $data['errors'][]="Nombre de Tabla o campos desconocidos";    
  if($result!==null)
    $data['result']=$result;
  else{
    // echo "el valor de result es: ".$result;
    $data['errors'][]="ERROR, NO SE PUEDE ELIMINAR";  
  }
  echo json_encode($data);
}
?>
