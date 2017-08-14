<?php
class MssqlConexion{
    private $con;
    public function __construct($servidor,$user,$pass,$bd){
        $info = array('Database'=>$bd,"Uid" => $user,"PWD" => $pass, "CharacterSet" => "UTF-8");
        $this->con =sqlsrv_connect($servidor, $info);
    }
    public function obtener_conector(){
        return $this->con;
    }

    public function desconectar() {
        sqlsrv_close($this->con);
    }
 }
?>
