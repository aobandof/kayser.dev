<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
$conexion=new MssqlConexion('192.168.0.17','wms','pjc3l1','WMSTEK_KAYSER');
$conector=$conexion->obtener_conector();
if(!$conector){
  echo "NO SE ESTABLECIO LA CONEXION CON LA BASE DE DATOS<br>";
  if( (sqlsrv_errors() ) != null) {
    $datos=cargarErrores();
    header('Content-type: application/json');
    echo json_encode($datos);
    exit;
  }
}
//if(isset($_POST['solicitud'])){
  if($_POST['solicitud']="cargar_tabla"){
    $query1="select a.NomCliente,a.CodCliente from dbo.Cliente as a where a.GiroCliente='TIENDA' and a.NomCliente != 'LOPEZ DE BELLO' and a.CodCliente != '100-SDLIQ' and CodCliente !='76204531-1C'";
    $registros1=sqlsrv_query($conector, $query1);
    if( $registros1 === false ) {
      if( (sqlsrv_errors() ) != null) {
        $datos=cargarErrores();
        $conexion->desconectar();
        header('Content-type: application/json');
        echo json_encode($datos);
        exit;
      }
    }
    else {
      while ($reg1 = sqlsrv_fetch_array($registros1, SQLSRV_FETCH_NUMERIC)) {
        $query2="select top 1 CONVERT(VARCHAR(16),FechaDespacho,21),DATEDIFF( d , cast(FechaDespacho as datetime) , cast(GETDATE() as datetime)) as dias from DocumentoSalida where Tipo='TRF' and IdCliente='".$reg1[1]."' and IdDocSalida like '%TRF%' order by FechaDespacho desc";
        $registros2=sqlsrv_query($conector,$query2);
        if($registros2===false)
          die(print_r(sqlsrv_errors(),true));
        else {
          $reg2=sqlsrv_fetch_array($registros2,SQLSRV_FETCH_NUMERIC);
          //echo "<tr><td>".$reg1[0]."</td><td>".$reg1[1]."</td><td>".strval($reg2[0])."</td><td>".strval($reg2[1])."</td></tr>";
          $datos[]=array('nombre_tienda'=> $reg1[0],'codigo_tienda'=>$reg1[1],'ultimo_despacho'=>$reg2[0],'dias_transcurridos'=>$reg2[1]);
        }
      }
      $conexion->desconectar();
      header('Content-type: application/json');
      echo json_encode($datos);
    }
  }

function cargarErrores() {
  $errores[]=Array( "ERRORES"=>"SI");
  foreach( sqlsrv_errors() as $error )
    $errores[]=Array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  return $errores;
}
?>
