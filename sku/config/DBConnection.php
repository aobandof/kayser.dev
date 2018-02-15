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

  ######################   FUNCION SELECT CSV ########################  
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

  ######################   FUNCION SELECT TABLE ########################  
  /////----- 'SOLO RETORNA LOS TRs del TBODY' -----/////
  ///### $pos_id contendra el entero que indica la posicion de campo que sera el id de la fila
  ///### $array_buttons, sera un arreglo con cadenas que indiquen que botones agregaremos a las filas (ejemplo ['update', 'delete','select'])
  ///### $tag sera "div" o "td"
  public function selectDtable($query, $pos_id, $array_buttons, $tag){//DEVUELVE UNA CADENA FORMATO CSV, SIN NOMBRES DE COLUMNAS    
    $filas='';
    $buttons=[];
    $buttons =  array(  'show' => "<a href='#'><img class='icon_table' src='./src/img/lupa.png'></a>",
                        'update' => "<a href='#'><img class='icon_table' src='./src/img/edit.png'></a>",
                        'delete' => "<a href='#'><img class='icon_table' src='./src/img/delete.png'></a>",
                        'select' => '<input class="chb_table" type="checkbox" id="">' );    
    if($this->_driver=="sqlsrv"){       
      $this->_registros_select=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));      
      if($this->_registros_select===false){
        return false;
      }else {        
        if(sqlsrv_num_rows($this->_registros_select)>0){                      
          while($reg=sqlsrv_fetch_array($this->_registros_select,SQLSRV_FETCH_NUMERIC)) {
            $tag==='div' ? $filas.="<div class='dtable_row' id='".$reg[$pos_id]."'>" : $filas.="<tr class='table_row' id='".$reg[$pos_id]."'>";
            foreach ($reg as $value)
              $filas.="<$tag>$value</$tag>";
            foreach ($array_buttons as $button)
              $filas.="<$tag>".$buttons[$button]."</$tag>";
            $tag==='div' ? $filas.="</div>" : $filas.="</tr>"; 
         
          }
        }else
          return 0;
      }     
    }else if ($this->_driver=="mysqli") {
      $this->_registros_select=$this->_connection->query($query);
      if($this->_registros_select===false)
        return false;
      else{
        while($reg=$this->_registros_select->fetch_assoc()){
          $filas.="<div dtable_row id='".$reg[$pos_id]."'>";
          foreach ($reg as $value) $filas.="<div>$value</div>";
          foreach ($array_buttons as $value) $filas.="<div>".$buttons[$value]."</div>";
          $filas.="</div>";
        }
      }       
    }

    // echo "el contenido para CSV es: <br>".$content;
    if($filas!="") // consulta vacia
      return $filas;
    else
      return 0;
  }

  ######################   FUNCION PARA LAS INSERCIONES   ########################
  public function insert_easy($query){
    if(!($this->_connection->query($query))){
        return false;
    }else {
      return $this->_connection->affected_rows;
    }
  }   
  //$table:  nombre de la tabla
  //$values: array asociativo donde las keys son los nombres de los campos de la tabla
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
  ##################### FUNCION PARA INSERTAR SQLSRV EVITANDO INYECCION **********************/
  public function insertUpdateDeleteSqlsrv($query,$arr_params/*='delete'*/){
    /*if($arr_params==='delete')
      $stmt = sqlsrv_query($this->_connection);
    else*/
      $stmt = sqlsrv_query($this->_connection, $query, $arr_params);
    return sqlsrv_rows_affected($stmt);
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
    echo "<br>".$query."<br>";
    if(is_numeric($id_val)) { $types.='i'; $id_val=intval($id_val);  }
    else $types.='s';
    $arr_values[]=$id_val;
    if($this->_driver=='mysqli'){//por ahora solo actualizaremos tablas Mysql
      $stmt=$this->_connection->prepare($query);
      $vals = array_merge(array($types),$arr_values);
      var_dump($vals);
      call_user_func_array(array($stmt,"bind_param"),$vals);
      $stmt->execute();
      if($this->_connection->connect_errno)
        return false;
      else
        return $this->_connection->affected_rows;
    }
  }
  public function update_easy($query){
    if(!($this->_connection->query($query))){
      return false;      
    }else {
      return $this->_connection->affected_rows;
    }
  }
  ######################   FUNCION DELETE   ########################
  public function delete($query){
    if ($this->_driver=="mysqli"){//POR AHORA SOLO MYSQLI
      if(!$this->_connection->query($query))
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
        $arr_errors=array('code'=>$this->_connection->connect_errno, 'message'=>$this->_connection->connect_error);
      }else
        $arr_errors=array('code'=>$this->_connection->errno, 'message'=> $this->_connection->error);
    }
    return $arr_errors;
  }

//FUNCION QUE DETERMINA SI HAY REGISTROS SEGUN UNA $QUERY, de existir, devuelve la cantidad
  public function quantityRecords($query){
    $arr_registry=[];
    $cant_registros=0;
    if($this->_driver=="mysqli"){  
      if(!($arr_registry=$this->_connection->query($query))){
        return false;
      }else        
        while($arr_registry->fetch_array())
          $cant_registros++;
    }
    // echo "cantidad de registros con esta consulta: ".$cant_registros;
    return $cant_registros;
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
        while($reg=$registros->fetch_array()) 
          $arr_export[intval($reg[0])]=$reg[1];
      }
    }
    if(count($arr_export)==0) // consulta vacia
      return 0;
    else
      return $arr_export;       
  }

  public function getColumnFromColumn($nom_tabla,$nom_column_search,$nom_column,$type_column,$val_column){
    //sirve para valores unicos y para una sola coincidencia, SI SON MUCHOS RETORNA UN ARRAY, SI ES SOLO, RETORNA EL VALOR
    //type_column sera el tipo del campo a comparar con WHERE, por eso el requerimiento para ver si va entre comillas o no
    //type_column = NUMBER or STRING
    $arr_export=[];
    ($type_column=='NUMBER') ? $query="SELECT $nom_column_search from $nom_tabla where $nom_column=$val_column" : $query=$query="SELECT $nom_column_search from $nom_tabla where $nom_column='$val_column'";
    if($this->_driver=="sqlsrv"){       
      //
    }elseif($this->_driver=="mysqli"){
      $registros=$this->_connection->query($query);
      if($registros===false) {
        return false;
      }else {
        while($reg=$registros->fetch_array()) {
          $arr_export[]=$reg[0];
        }
      }
    }
    if(count($arr_export)==0) // consulta vacia
      return 0;
    elseif(count($arr_export)>1)
      return $arr_export;
    else
       return $arr_export[0];
  }

  public function selectOptions($query){    
    $opt="";
    if($this->_driver=="sqlsrv"){       
      $registros=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
      if($registros===false) { return false;
      }else {
        while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_NUMERIC)) 
          $opt.="<option value=".$reg[0].">".$reg[1]."</option>";
      }
    }elseif($this->_driver=="mysqli"){
      $registros=$this->_connection->query($query);
      if($registros===false) return false;
      else {
        while($reg=$registros->fetch_array()) 
          $opt.="<option value=".$reg[0].">".$reg[1]."</option>";
      }
    }
    return $opt;
  }
}



?>
