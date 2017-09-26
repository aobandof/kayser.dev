<?php
class DBConnection {
  private $_connection;
  private $_driver;
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

  public function select($query,$tipo_array){
    $arr_export=[];
    if($this->_driver=="sqlsrv"){       
      $registros=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
      if($registros===false){
        return false;
      }else {
        if(sqlsrv_num_rows($registros)>0){
          if($tipo_array=='sqlsrv_a_p')
            while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC)) 
              $arr_export[]=$reg;
          elseif($tipo_array=='sqlsrv_n_p') 
            while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_NUMERIC))
              $arr_export[]=$reg;
          else 
            while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_BOOT))
              $arr_export[]=$reg;            
        }else
          return 0;
      }
    }else if ($this->_driver=="mysqli") {
      $registros=$this->_connection->query($query);
      if($registros===false)
        return false;
      else
          if($tipo_array=='mysqli_a_o')
            while($reg=$registros->fetch_assoc()) 
              $arr_export[]=$reg;
          elseif($tipo_array=='mysqli_b_o') // recordar que cuando es Orientado a Objetos solo existe ->fecth_assoc() y ->fetch_array(), el ultimo es asociativo y numerico
            while($reg=$registros->fetch_array())
              $arr_export[]=$reg;        
    }
    // var_dump($arr_export);
    if(count($arr_export)==0) // consulta vacia
      return 0;
    else
      return $arr_export;
  }

  public function insert($query){

  }
  public function update($query){

  }
  public function delete($query){

  }
  public function closeConnection(){
    if($this->_driver=="sqlsrv")  sqlsrv_close($this->_connection);
    else if ($this->_driver=="mysql")  $this->_connection->close(); // si hay mas driver se agregan mas aca
  }
  public function getErrors(){ // funcion que retorna un array con el/los errores de la conexion o la transaccion a la BDx
    $arr_errors=[];
    if($this->_driver=="sqlsrv") {
      if( ($errors = sqlsrv_errors() ) != null)
        foreach( $errors as $error )
            $arr_errors[]=array('sqlstate'=>$error[ 'SQLSTATE'], 'code'=>$error[ 'code'], 'message'=>$error[ 'message']);
    }elseif ($this->_driver=="mysqli"){
      if ($this->_connection->connect_error){
        $arr_errors[]=array('code'=>$this->_connection->connect_errno, 'message'=>$this->_connection->connect_error);
      }else
        $arr_errors[]=array('code'=>$this->_connection->errno, 'message'=> $this->_connection->error);
    }
    return $arr_errors;
  }
  ######################################################################################################################
  ###############################################  FUNCIONES PERSONALIZADAS ############################################
  ######################################################################################################################
  public function selectArrayUniAssocIdName($query){ //funcion que  retorna un array unidimencional asociativo, donde el id es el ID de la tabla
    //$query debe tener la forma de: select id,name from nombre_tabla
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
          $arr_export[$reg[0]]=$reg[1];
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
