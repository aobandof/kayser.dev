<?php
class DBConnection {
  private $_connection;
  private $_driver;
  private $_registros_select;
  public function __construct( $driver, $host, $user, $pass, $database ) { // returna la conexion
    $this->_driver=$driver;
    if($this->_driver=="sqlsrv"){
      $info = array('Database'=>$database,"Uid" => $user,"PWD" => $pass, "CharacterSet" => "UTF-8");
      $this->_connection =sqlsrv_connect($host, $info);
    }else if($this->_driver=="mysqli"){
        error_reporting(E_ERROR);// SOLO ERRORES, NO WARNINGS NI NOTICES
        $this->_connection = new mysqli($host,$user,$pass,$database); // Con mysqli_connect huviera usado @mysqli_connect para que no me muestre los warnings
        error_reporting(E_ALL ^ E_NOTICE);
        if (!$this->_connection->connect_error){
          $this->_connection->set_charset("utf8");//$this->conexion->character_set_name() //para comprobar
          $this->_connection->query("SET collation_connection = utf8_bin");
        }
    }//cuando tengamos mas driver podemos seguir editando estas opciones
  }
  public function getConnection(){
      if ($this->_connection->connect_error)
        return false;
      return $this->_connection;
  }
  public function query($query){

  }
  //FUNCION QUE DETERMINA SI HAY REGISTROS SEGUN UNA $QUERY, de existir, devuelve la cantidad
  function quantityRecords($query){
    $arr_registry=[];
    $cant_registros=0;
    if($this->_driver=="mysqli"){  
      if(($arr_registry=$this->_connection->query($query))!=false){
        while($arr_registry->fetch_array())
          $cant_registros++;
      }else
        return false;
    }
    // echo "cantidad de registros con esta consulta: ".$cant_registros;
    return $cant_registros;
  }
######################   FUNCION SELECT  ########################
//$query:  cadena de insersion
//$tipo_array: cadena ('mysqli_a_o', 'mysqli_b_o','sqlsrv_a_p','sqlsrv_n_p' que significan: asociativo_orientado a objetos, boot_orientado a objetos, asociativo_procedurar, numeric_procedurarl respectivamente )
  public function select($query,$tipo_array){
    $arr_export=[];
    if($this->_driver=="sqlsrv"){       
      $this->_registros_select=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));      
      if($this->_registros_select===false){
        return false;
      }else {
        if(sqlsrv_num_rows($this->_registros_select)>0){
          if($tipo_array=='sqlsrv_a_p')
            while($reg=sqlsrv_fetch_array($this->_registros_select,SQLSRV_FETCH_ASSOC)) 
              $arr_export[]=$reg;
          elseif($tipo_array=='sqlsrv_n_p') 
            while($reg=sqlsrv_fetch_array($this->_registros_select,SQLSRV_FETCH_NUMERIC))
              $arr_export[]=$reg;
          else 
            while($reg=sqlsrv_fetch_array($this->_registros_select,SQLSRV_FETCH_BOOT))
              $arr_export[]=$reg;            
        }else
          return 0;
      }
    }else if ($this->_driver=="mysqli") {
      $this->_registros_select=$this->_connection->query($query);
      if($this->_registros_select===false)
        return false;
      else
          if($tipo_array=='mysqli_a_o')
            while($reg=$this->_registros_select->fetch_assoc()) 
              $arr_export[]=$reg;
          elseif($tipo_array=='mysqli_b_o') // recordar que cuando es Orientado a Objetos solo existe ->fecth_assoc() y ->fetch_array(), el ultimo es asociativo y numerico
            while($reg=$this->_registros_select->fetch_array())
              $arr_export[]=$reg;        
    }
    // var_dump($arr_export);
    if(count($arr_export)==0) // consulta vacia
      return 0;
    else
      return $arr_export;
  }

  public function selectCsv($query,$del){//DEVUELVE UNA CADENA FORMATO CSV, SIN NOMBRES DE COLUMNAS    
    if($this->_driver=="sqlsrv"){       
      $this->_registros_select=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));      
      if($this->_registros_select===false){
        return false;
      }else {
        if(sqlsrv_num_rows($this->_registros_select)>0){
            $content="";
            while($reg=sqlsrv_fetch_array($this->_registros_select,SQLSRV_FETCH_NUMERIC)) {
              $content.=implode(';',$reg);
              $content.=";\r\n";
            }
        }else
          return 0;
      }
    }else if ($this->_driver=="mysqli") {
      $this->_registros_select=$this->_connection->query($query);
      if($this->_registros_select===false)
        return false;
      else{
        $content="";
        while($reg=$this->_registros_select->fetch_assoc()){
          $content.=implode(';',$reg);
          $content.=";\r\n";
        }
      }  
    }
    // echo "el contenido para CSV es: <br>".$content;
    if($content!="") // consulta vacia
      return $content;
    else
      return 0;
  }
######################   FUNCION PARA LAS INSERCIONES   ########################
//$table:  nombre de la tabla
//$values: array asociativo donde las keys son los nombres de los campos de la tabla
public function insert_easy($query){
  if(($this->_connection->query($query))===true){
    return $this->_connection->affected_rows;
  }else {
    return false;
  }
}   
public function insert($table,$values){ 
    $types="";
    $questions="";
    $string_keys="";
    foreach ( $values as $key => $valor ){
      $string_keys.=$key.",";
      if(is_numeric($valor))//SI VALOR PASADO DESDE LA VISTA ES NUMERO
        is_int($valor) ? $types.='i' : $types.='d';//SI ES ENTERO O DOUBLE
      else //SI VALOR PASADO DESDE LA VISTA ES UNA CADENA
        $types.='s';
      $questions.="?,";
      $arr_values[]=$valor;
    }
    $questions=substr($questions,0,strlen($questions)-1);//SACAMOS LA COMA DEL FINAL DE:  ?,?,...?,
    $string_keys=substr($string_keys,0,strlen($string_keys)-1);//SACAMOS LA COMA DEL FINAL DE LOS NOMBRES DE CAMPOS
    $query="INSERT INTO $table ($string_keys) values ($questions)";
    if ($this->_driver=="mysqli"){//POR AHORA SOLO MYSQLI y SERA EVITANDO INYECCIONES DE CODIGO
      $stmt=$this->_connection->prepare($query);
      $vals = array_merge(array($types), $arr_values);//PARA PODER UNIRLOS, $types SE CONVIERTE EN UN ARRAY con: array($types)
      call_user_func_array(array($stmt, 'bind_param'), $vals); 
      $stmt->execute();
      if ($this->_connection->connect_errno) {
        // echo "errores existentes<br>";
        return false; //SI HUBIERON ERRORES, RETORNA FALSO
      }else{
        // echo "esta arrojando null<br>";
        return $this->_connection->affected_rows; //SI return -1 NO SE PUDO REALIZAR LA INSERSION, 1 QUE SE REALIZÓ CORRECTAMENTE
      }  
    }
  }
  ####################   FUNCION PARA ACTUALIZAR   ############################
  public function update($table,$id_nam,$id_val,$values){
    $types="";
    $query= "UPDATE $table SET ";
    $arr_values=[];
    // var_dump($values);
    foreach($values as $key => $value){
      $query.=$key."=?,";
      if(is_numeric($value))//SI VALOR PASADO DESDE LA VISTA ES NUMERO
        is_int($value) ? $types.='i' : $types.='d';//SI ES ENTERO O DOUBLE
      else //SI VALOR PASADO DESDE LA VISTA ES UNA CADENA
        $types.='s';      
      $arr_values[]=$value;
    }
    $query=substr($query,0,strlen($query1)-1);//sacamos la ultima coma del final de la cadena
    $query=$query." WHERE $id_nam=?";
    if(is_numeric($id_val))
      is_int($id_val) ? $types.='i' : $types.='d';
    else
      $types.='s';
    $arr_values[]=$id_val;
    if($this->_driver=='mysqli'){//por ahora solo actualizaremos tablas Mysql
      $stmt=$this->_connection->prepare($query);
      $vals = array_merge(array($types),$arr_values);
      // var_dump($vals);
      call_user_func_array(array($stmt,"bind_param"),$vals);
      $stmt->execute();
      if($this->_connection->connect_errno)
        return false;
      else
        return $this->_connection->affected_rows;
    }
  }
  ######################   FUNCION DELETE   ########################
  public function delete($query){
    if ($this->_driver=="mysqli"){//POR AHORA SOLO MYSQLI
      $deleteds=$this->_connection->query($query);
      if($deleteds===false)
        return false;
      else
        return $this->_connection->affected_rows;
    }
  }
  ########################   FUNCION CERRAR CONEXION  ########################
  public function closeConnection(){
    if($this->_driver=="sqlsrv")  sqlsrv_close($this->_connection);
    else if ($this->_driver=="mysql")  $this->_connection->close(); // si hay mas driver se agregan mas aca
  }
  ########################################   METODO PARA OBTENER LOS ERRORES  #########################################
  public function getErrors(){ // funcion que retorna un array con el/los errores de la conexion o la transaccion a la BDx
    $arr_errors=[];
    if($this->_driver=="sqlsrv") {
      if( ($errors = sqlsrv_errors() ) != null)
        foreach( $errors as $error )
            $arr_errors[]=array('sqlstate'=>$error[ 'SQLSTATE'], 'code'=>$error[ 'code'], 'message'=>$error[ 'message']);
    }elseif ($this->_driver=="mysqli"){
      // if ($this->_connection->connect_error){
      if ($this->_connection->connect_errno){
        $arr_errors[]=array('code'=>$this->_connection->connect_errno, 'message'=>$this->_connection->connect_error);
      }else
        $arr_errors[]=array('code'=>$this->_connection->errno, 'message'=> $this->_connection->error);
    }
    return $arr_errors;
  }

  ######################################   METODO PARA OBTENER LA CABECERA DEL ULTIMO SELECT ###########################
  function getColumnsLastSelect(){
    $arr_columns=[];
    if($this->_driver=="sqlsrv"){ 
      foreach( sqlsrv_field_metadata( $this->_registros_select) as $fieldMetadata )
        $arr_columns[]=$fieldMetadata['Name'];
    }elseif($this->_driver=="mysqli"){
      $info_columns=$this->_registros_select->fetch_fields();
      foreach($info_columns as $valor)
        $arr_columns[]=$valor->name;
    }
    return $arr_columns;
  }
  ######################################################################################################################
  ###############################################  FUNCIONES PERSONALIZADAS ############################################
  ######################################################################################################################
  public function selectArrayUniAssocIdName($query){ 
    //funcion que  retorna un array unidimencional asociativo
    //ejemplo: $query="select id,nombre from tabla //tiene que se un par de campos y no necesariamente el id, sino otros campos que se relacionen
    //se retornará el array: { 'id1'=>'nombre1', 'id2'=>'nombre2' .... }
    $arr_export=[];
    if($this->_driver=="sqlsrv"){       
      $registros=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
      if($registros===false){
        return false;
      }else {
        if(sqlsrv_num_rows($registros)>0){
          while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_NUMERIC)) 
            $arr_export[$reg[0]]=$reg[1];
        }else
          return 0;
      }
    }elseif($this->_driver=="mysqli"){
      $registros=$this->_connection->query($query);
      if($registros===false)
        return false;
      else {
        while($reg=$registros->fetch_arrow()) 
          $arr_export[intval($reg[0])]=$reg[1];
      }
    }
    if(count($arr_export)==0) // consulta vacia
      return 0;
    else
      return $arr_export;       
  }



  // public function selectParameterized($name_table,$arr_select,$name_key, $name_field,$val_field){
  //   echo "hola";
  // }

}

?>
