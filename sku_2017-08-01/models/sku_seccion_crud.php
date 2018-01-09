<?php
require_once "../config/require.php";
require_once "../config/sku_db_mysqli.php";
require_once "../config/sku_db_sqlsrv_33.php";

#########################################  SELECT ITEM #############################################################
if($_POST['option']=="cargar_seccion"){
  $filas=[];
  $cabecera=[];
  $ntabla=$_POST['nom_tabla'];
  if(isset($tablas_sku[$ntabla]['id'])) //SI LA TABLA TIENE ID
    if($ntabla=='marca')//TABLA MARCA TIENE OTRO CAMPO "prefijo"
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo, ".$tablas_sku[$ntabla]['campo']." AS Nombre, simbolo, posicion, tipo from  $ntabla";
    elseif($ntabla=='color')
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo, ".$tablas_sku[$ntabla]['campo']." AS Nombre, abreviatura from  $ntabla";
    elseif($ntabla=='presentacion')
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo, ".$tablas_sku[$ntabla]['campo']." AS Nombre, abreviatura from  $ntabla";
    else
      $query="select ".$tablas_sku[$ntabla]['id']." AS Codigo,".$tablas_sku[$ntabla]['campo']." AS Nombre from  $ntabla";
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
    if(($arr_tabla=$sqlsrv_33->select($query,'sqlsrv_a_p'))===false){
        $data['errors'][]=$sqlsrv_33->getErrors();
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
if($_POST['option']=="create_item") {
  $repeated=0;
  $table=$_POST['table'];
  $query_insert="INSERT into $table (";
  $fields_names="";
  $fields_values="";

  foreach ($_POST as $key => $value){
    if($key!='table' && $key!='option'){
      $fields_names.=$key.",";
      is_numeric($value) ? $fields_names.=$key."," : $fields_values.="'$value',";
    }    
  }
  $fields_names=substr($fields_names,0,strlen($fields_names)-1);
  $fields_values=substr($fields_values,0,strlen($fields_values)-1);
  $query_insert.=$fields_names.") VALUES (".$fields_values.")";
  $data['query']=$query_insert;
  
  //EXCEPCIONES DE CIERTAS TABLAS:
  ///ESTA EXCEPCION QUEDA NULA DADO QUE MARCA PUEDE TENER MISMO SIMBOLO
  // if($table=="Marca"){//dado que marca tiene el campo prefijo que es unico excepto cuando es vacio
  //   $prefijo=$_POST['prefijo'];
  //   $query_marca="select nombre from Marca where prefijo='$prefijo'";
  //   if(($arr_marca=$mysqli->select($query_marca,'mysqli_a_o'))!==false){
  //     if($arr_marca!==0)//quiere decir que se encontró el valor
  //       $repeated=1;
  //   }else 
  //     $data['errors'][]=$sqlsrv_33->getErrors();
  // }
  ///////// FIN EXCEPCIONES ////////
  if($repeated==0){
    if($tablas_sku[$table]['bd']=='mysql'){// inicialmente solo se podran editar BDx de motor MYSQL
      $reg_inserted=$mysqli->insert_easy($query_insert);
      if($reg_inserted!==-1 && $reg_inserted!=0 && $reg_inserted!==false) 
        $data['insert']=true;  
      else $reg_inserted===false ? $data['errors']=$mysqli->getErrors() : $data['reg_inserted']=$reg_inserted;

    }else
      $data['errors'][]="Nombre de Tabla o campos desconocidos";    
  }else { //si el valor es repetido
    $data['repeated']=true;
  }
  echo json_encode($data);
}
#########################################  UPDATE ITEM #############################################################
if($_POST['option']=="update_item") {
  $repeated=0;
  $table=$_POST['table'];
  $id_name=$tablas_sku[$table]['id'];
  $id_value=$_POST['id'];
  $query_update="UPDATE $table SET ";
  foreach ($_POST as $key => $value){
    if($key!='table' && $key!='option' && $key!="id")
      is_numeric($value) ? $query_update.="$key=$value, " : $query_update.="$key='$value', ";
  }
  $query_update=substr($query_update,0,strlen($query_update)-2); //sacamos el ", " del final de la query
  $tablas_sku[$table]['type_id'] == 'INT' ? $query_update.=" WHERE $id_name=$id_value" : $query_update.=" WHERE $id_name='$id_value'";
  $data['query']=$query_update;
  /**** EXCEPCIONES ESTATICAS: cuando marca, color, presentacion contienene columnas que pueden ser vacias pero si no tienen qe ser unicas */
  if($table=="marca"){
    $posicion=$_POST['posicion'];
    $simbolo=$_POST['simbolo'];
    $query_marca="select nombre from marca where simbolo='$simbolo' AND posicion='$posicion' AND $id_name!=$id_value";
    $arr_marca=$mysqli->select($query_marca,'mysqli_a_o');
    if($arr_marca!==false && $arr_marca!==0){//quiere decir que ya existe un registro con la combinacion simbolo posicion
        $repeated=1;
    }else 
      if($arr_marca===false) $data['erros']=$mysqli->getErrors();      
  }
  if($repeated==0){
    if($tablas_sku[$table]['bd']=='mysql'){// inicialmente solo se podran editar BDx de motor MYSQL
      $reg_updated=$mysqli->update_easy($query_update);
      if($reg_updated!==-1 && $reg_updated!=0 && $reg_updated!==false) 
        $data['update']=true;  
      else $reg_updated===false ? $data['errors']=$mysqli->getErrors() : $data['reg_updated']=$reg_updated;
    }else
      $data['errors'][]="Nombre de Tabla o campos desconocidos";    
  }else { //si el valor es repetido
    $data['resp']="No se pueden Actualizar Valores Existentes";
  }
  echo json_encode($data);
}
#########################################  DELETE ITEM #############################################################
if ($_POST['option'] == "delete_item" ){
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
    if(($result=$sqlsrv_33->delete($query))===false)#Esta pendiente esto
      $data['errors'][]=$sqlsrv_33->getErrors();
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
