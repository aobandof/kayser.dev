<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER

/****** INSTANCIAMOS LOS PARAMATROS PARA LA CONEXION EN ESTE SCRIPT **************/
$conexion=new MssqlConexion('PURETA-TI\SQLEXPRESS','abelin','12345','master');
                            
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
$query = "SELECT name, database_id, create_date FROM sys.databases";
  $registros = sqlsrv_query($conector, $query);
  if( $registros === false ){
      die( print_r( "Error de consulta".sqlsrv_errors(), true));
  } else {
    While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC))
        var_dump($reg);
  }
  $conexion->desconectar();     
?>
