<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
$conexion=new MssqlConexion('192.168.0.17','wms','pjc3l1','WMSTEK_KAYSER');
$conector=$conexion->obtener_conector();
if(!$conector){
  echo "NO SE ESTABLECIO LA CONEXION CON LA BASE DE DATOS<br>";
  if( (sqlsrv_errors() ) != null) {
    mail("aobando@kayser.cl", "Error en la Conexion a la Base de Datos para envio de Email", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
    exit;
  }
}
  $time=time();
  $mensaje="";
  $headers = "From: INFORMATICA KAYSER <aobando@kayser.cl>\r\n";
  $destinatario ="ofaber22@hotmail.com";
  $titulo = "Informe: Ultimos Despachos (".date("d/m/Y - H:i:s", $time).")";
  $query1="select a.NomCliente,a.CodCliente from dbo.Cliente as a where a.GiroCliente='TIENDA' and a.NomCliente != 'LOPEZ DE BELLO' and a.CodCliente != '100-SDLIQ' and CodCliente !='76204531-1C'";
  $registros1=sqlsrv_query($conector, $query1);
  if( $registros1 === false ) {
    if( (sqlsrv_errors() ) != null) {
      echo "Error en la 1ra consulta a la base de datos (También se enviará este aviso al correo)";
      mail("aobando@kayser.cl", "Error en conexion base de datos para envio de Email", "Error en envio de mensaje", $headers);
      exit;
    }
  }
  else {
    $i=1;
    while ($reg1 = sqlsrv_fetch_array($registros1, SQLSRV_FETCH_NUMERIC)) {
      $query2="select top 1 CONVERT(VARCHAR(16),FechaDespacho,21),DATEDIFF( d , cast(FechaDespacho as datetime) , cast(GETDATE() as datetime)) as dias from DocumentoSalida where Tipo='TRF' and IdCliente='".$reg1[1]."' and IdDocSalida like '%TRF%' order by FechaDespacho desc";
      $registros2=sqlsrv_query($conector,$query2);
      if($registros2===false){
        echo "Error en consulta a tabla (esto tb se enviará al correo.. eso creo)";
        mail("aobando@kayser.cl", "Error en consulta a base de datos para envio de Email", "Error en envio de mensaje", $headers);
      } else {
        $reg2=sqlsrv_fetch_array($registros2,SQLSRV_FETCH_NUMERIC);
        if($reg2[1]>=7){
          $mensaje.=$i.".- ".$reg1[0]." ".$reg2[0]." ( ".$reg2[1]." )\n";
          $i++;
        }
      }
    }
    $conexion->desconectar();
    if(mail($destinatario, $titulo, $mensaje, $headers)){
      mail("aobando@kayser.cl", "Mensaje enviadado a usuario de forma correcta", $mensaje, $headers);
      echo "mail eviado correctamente";
    }
    else{
      echo "error en envio de mail";
      mail("aobando@kayser.cl", "Error en envio de Email", "Error en envio de mensaje", $headers);
    }
  }
