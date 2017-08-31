<?php
class DBConnection {
  private $_connection;
  private $_driver;
  public function __construct( $driver, $host, $user, $pass, $database ) {
    $this->_driver=$driver;
    if($driver=="sqlsrv"){
      $info = array('Database'=>$database,"Uid" => $user,"PWD" => $pass, "CharacterSet" => "UTF-8");
      $this->_connection =sqlsrv_connect($host, $info);
    }else if($driver=="mysql"){
      $this->_connection = mysqli_connect($host,$user,$pass,$database);
      
      $this->_connection->set_charset("utf8");//$this->conexion->character_set_name() //para comprobar
      $this->_connection->query("SET collation_connection = utf8_bin");
    }//cuando tengamos mas driver podemos seguir editando estas opciones
  }
  public function getConection(){
    return $_connection;
  }
  public function closeConection(){
    if($this->_driver=="sqlsrv")  sqlsrv_close($this->_connection);
    else if ($this->_driver=="mysql")  $this->_connection->close(); // si hay mas driver se agregan mas aca
  }
  public function getErrors(){


  }
}
?>
