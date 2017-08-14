<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
$conexion=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
$conector=$conexion->obtener_conector();
if(!$conector){
  echo "NO SE ESTABLECIO LA CONEXION CON LA BASE DE DATOS<br>";
  if( (sqlsrv_errors() ) != null) {
    ///mail("aobando@kayser.cl", "Error en la Conexion a la Base de Datos para envio de Email", "Error en envio de mensaje", "From: Informática Kayser <aobando@kayser.cl>\r\n");
    exit("Error en la conexion");
  }
}
 $time=time();
 //$fila="";
 //$fila=array();
 //$filas=array();
 $mensaje="";
 /*** CABECERA PARA CORREO ***/

 //$destinatario ="mmora@kayser.cl";
    $titulo = "Promotoras Ingresadas el ".date("d-m-Y", $time); 
    $uid = md5(uniqid(time())); //frabrico un ID único que usaré para el "boundary"
/*********************************************************************/
/**** CABECERA PARA ARCHIVO CSV ******/
// header('Expires: 0');
// header('Cache-control: private');
// header('Content-Type: application/x-octet-stream'); // Archivo de Excel
// header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
// header('Content-Description: File Transfer');
// header('Last-Modified: '.date('D, d M Y H:i:s'));
// header('Content-Disposition: attachment; filename="'.$titulo.'.csv"');
// header("Content-Transfer-Encoding: binary");
/************************************************************************/

$query="select * from MM_PromotorasAyer";
$registros=sqlsrv_query($conector, $query);
if( $registros === false ) {
    if( (sqlsrv_errors() ) != null) {
        ///mail("aobando@kayser.cl", "Error en conexion base de datos para envio de Email", "Error en envio de mensaje", $headers);
        exit("Error en la 1ra consulta a la base de datos (También se enviará este aviso al correo)");
    }
}
else {
    while ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) {
        $fila="";
        foreach($reg as $indice=>$valor ){
            if($indice=='CreateDate' || $indice=='FechaNac'){
                if($valor!=null) {
                    $date=date_format($valor, 'd/m/Y');
                    $fila.=$date.";";
                    //$fila[]=$date;
                }else $fila.=";";
            } else {
                $fila.=$valor.";";
                //$fila[]=$valor;
            }
            //$fila=trim($fila,';');//quitamos el ultimo asterisco
            //$filas[]=$fila;//trim($fila,'*');//quitamos el ultimo asterisco
            //unset($fila);
        }
        $mensaje.=$fila."\r\n";
        //$mensaje=chunk_split(base64_encode($mensaje));
        // $cuerpo="Content-type: text/plain;charset=iso-8859-1\n";
        // $cuerpo.="Content-transfer-encoding: 7BIT\n";
        // //$cuerpo.="Envio Archivo con listado de Promotora ingresadas el dia: ".date("d-m-Y", $time);
        // $adjunto='Content-type:csv ;name="'.$titulo.'.csv"';
        // $adjunto.="Content-Transfer-Encoding: BASE64\n";
        // $adjunto.='Content-disposition: attachment;filename="'.$titulo.'.csv"\n\n';
        // $adjunto.=chunk_split(base64_encode($mensaje));
        // $cuerpo.=$adjunto;

    }
    /**** preparamos el wrapper de salida */
    $conexion->desconectar();
    //$adjunto=chunk_split(base64_encode($mensaje));
    #################    CABECERA #######################333
    $headers = "From: INFORMATICA KAYSER <informatica@kayser.cl>\r\n";
    $headers .= "Reply-To: informatica@kayser.cl\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary='" . $uid . "'\r\n\r\n";
    #################    MENSAJE  #########################33
    $cuerpo = '--'.$uid.'\r\n';
    $cuerpo .= "Content-type: text/html; charset=UTF-8\r\n";
    $cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $cuerpo .= "Lista de Promotoras enviadas el: ".date("d-m-Y", $time). "\r\n\r\n";
    $cuerpo .= "--" . $uid . "\r\n";
    //$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
    //$headers .= "Content-Type: multipart/mixed; "; 
    //$headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "Content-Type: application/octet-stream; name=".$titulo.".csv\r\n"; //envio directo de datos
    //$headers .= "Content-Transfer-Encoding: binary\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $headers .= "Content-Disposition: attachment; filename=".$titulo.".csv\r\n\r\n";
    $headers .= $titulo.".csv\r\n\r\n";
    $headers .= "--" . $uid . "--";


    //$headers .= base64_encode($mensaje);
    //$headers .= "\r\n"; //retorno de carro y salto de linea
    $destinatario ="aobando@kayser.cl";
    
    mail($destinatario, $titulo, $cuerpo, $headers);
    // if(mail($destinatario, $titulo, $cuerpo, $headers)){
        ///mail("aobando@kayser.cl", "Mensaje enviadado a usuario de forma correcta", $mensaje, $headers);
        //echo "mail eviado correctamente<br><br>";
        //echo utf8_decode($mensaje);
    // }
    // else{
    //   echo "error en envio de mail";
    //   mail("aobando@kayser.cl", "Error en envio de Email", "Error en envio de mensaje", $headers);
    // }
}
