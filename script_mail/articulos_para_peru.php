    <?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
$conexion=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
$conector=$conexion->obtener_conector();
if(!$conector){
  echo "NO SE ESTABLECIO LA CONEXION CON LA BASE DE DATOS<br>";
  if( (sqlsrv_errors() ) != null) {
    mail("informatica@kayser.cl", "Error en la Conexion a la BDx / REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
  }
}
$destinatario ="aobando@kayser.cl";//"percy.luna@kayser.pe";
$titulo = "Listado de Articulos";
$hoy=date('d-m-Y');
$ayer=strtotime ( '-1 day', strtotime ($hoy));
$ayer=date('d-m-Y',$ayer);
$contenido="";
$query="select * from MM_Peru";
$registros=sqlsrv_query($conector, $query);
if( $registros === false ) {
    if( (sqlsrv_errors() ) != null) {
        mail("informatica@kayser.cl", "Error enla consulta a la BDx / REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
        //exit("Error en la 1ra consulta a la base de datos (También se enviará este aviso al correo)");
    }
}
else {
    //OBTENEMOS LOS NOMBRES DE CAMPOS
    $columnas="";
    foreach( sqlsrv_field_metadata( $registros) as $fieldMetadata ) {
           $columnas.=$fieldMetadata['Name'].";";
    }
    $contenido.=$columnas."\r\n";
    while ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) {
        $fila="";
        foreach($reg as $indice=>$valor ){
            if($valor!=null)
                $fila.=$valor.";";
            else
            	$fila.=";";
        }
        $contenido.=$fila."\r\n";
    }
    $conexion->desconectar();
    $headers = "From: INFORMATICA KAYSER <informatica@kayser.cl>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: application/octet-stream; name=".$titulo.".csv\r\n"; //envio directo de datos
    $headers .= "Content-Disposition: attachment; filename=".$titulo.".csv\r\n";
    $headers .= "Content-Transfer-Encoding: binary\r\n";
    $headers .= utf8_decode($contenido);
    $headers .= "\r\n"; //retorno de carro y salto de linea
    //$cuerpo="Envio Archivo con listado de Promotora ingresadas el: ".$ayer;
if(mail($destinatario, $titulo,"", $headers)){
        //echo $contenido;
        echo "mail eviado correctamente<br><br>";
    }
    else{
      mail("informatica@kayser.cl", "Error en envio de Email REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
    }
}
