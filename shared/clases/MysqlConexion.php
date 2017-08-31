<?php
class MysqlConexion{
    private $con;
    public function __construct($servidor,$user,$pass,$bd){
        $this->con = mysqli_connect($servidor,$user,$pass,$bd);
        mysqli_query($this->con,"SET NAMES 'utf8'");
    }
    public function obtener_conector(){
        return $this->con;
    }
    public function desconectar() {
        mysqli_close($this->con);
    }
 }
?>
