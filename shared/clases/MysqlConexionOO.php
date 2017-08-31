<?php
class MysqlConexion{
    private $con;
    public function __construct($servidor,$user,$pass,$bd){
        $this->con = new mysqli($servidor,$user,$pass,$bd);
        $this->con->query($this->con,"SET NAMES 'utf8'");
    }
    public function obtener_conector(){
        return $this->con;
    }
    public function getErrors() {

    }
 }
?>
