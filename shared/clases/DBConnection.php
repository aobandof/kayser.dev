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
  public function select($query){
    $arr_export=[];
    if($this->_driver=="sqlsrv"){
      if(!$registros=sqlsrv_query($this->_connection, $query, array(), array("Scrollable"=>'static'))) {
        return false;
      }else {
        while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC))
          $arr_export[]=$reg;
      }
    }else if ($this->_driver=="mysql"){
      //$registros=$this->_connection->query($query);
      if(!$registros=$this->_connection->query($query))
        return false;
      else
        while($reg=$registros->fetch_assoc())
          $arr_export[]=$reg;
    }
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
    }else if ($this->_driver=="mysqli"){
      if ($this->_connection->connect_error){
        $arr_errors[]=array('code'=>$this->_connection->connect_errno, 'message'=>$this->_connection->connect_error);
      }else
        $arr_errors[]=array('code'=>$this->_connection->errno, 'message'=> $this->_connection->error);
    }
    return $arr_errors;
  }
}

?>
