<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
$conexion=new MssqlConexion('192.168.0.33','sa','sa','SBO_KAYSER');
$conector=$conexion->obtener_conector();
if(!$conector){
  echo "NO SE ESTABLECIO LA CONEXION CON LA BASE DE DATOS<br>";
  if( (sqlsrv_errors() ) != null) {
    mail("aobando@kayser.cl", "Error en la Conexion a la BDx / REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
    //exit("Error en la conexion");
  }
}
$hoy=date('d-m-Y');
$ayer=strtotime ( '-1 day', strtotime ($hoy));
$ayer=date('d-m-Y',$ayer);
$contenido="";
$query="select * from MM_ClienteKAYSER";
$registros=sqlsrv_query($conector, $query);
if( $registros === false ) {
    if( (sqlsrv_errors() ) != null) {
        mail("aobando@kayser.cl", "Error enla consulta a la BDx / REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
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
            if($valor!=null) {
                if($indice=='BrutoL')
                	$fila.=number_format( floatval(abs($valor)), 0 , ',', '.').";";
                else if($indice=='Div')
                	$fila.=round(floatval($valor), 2).";";
                else
                	$fila.=$valor.";";
            }
            else 
            	$fila.=";";
        }
        $contenido.=$fila."\r\n";
    }
    $conexion->desconectar();
    $destinatario ="ronny@kayser.cl,cmarino@kayser.cl,jshahwan@kayser.cl,informatica@kayser.cl";
    $titulo = "Ventas Cliente Kayser (Ultimos 3 Dias)";
    $headers = "From: INFORMATICA KAYSER <informatica@kayser.cl>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: application/octet-stream; name=".$titulo.".csv\r\n"; //envio directo de datos
    $headers .= "Content-Disposition: attachment; filename=".$titulo.".csv\r\n";
    $headers .= "Content-Transfer-Encoding: binary\r\n";
    $headers .= utf8_decode($contenido);
    $headers .= "\r\n"; //retorno de carro y salto de linea
    //$cuerpo="Envio Archivo con listado de Promotora ingresadas el: ".$ayer;
if(mail($destinatario, $titulo,"", $headers)){
        //mail("aobando@kayser.cl", "Mensaje enviadado a usuario de forma correcta", $contenido, $headers);
        //echo $contenido;
        echo "mail eviado correctamente<br><br>";
    }
    else{
      mail("aobando@kayser.cl", "Error en envio de Email REPORTE CLIENTE KAYSER", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
    }
}
