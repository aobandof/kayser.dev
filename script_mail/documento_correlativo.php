<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
ini_set('display_errors', '0');
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
$query="SELECT * FROM U_MMcorrelativo";

if(($str_consulta=$sqlsrv->selectCsv($query,";"))!==false) {  
  if($str_consulta!==0){        
    #### DATOS DE CABECERA ####
    $destinatario=/*"aobando@kayser.cl";//*/"informatica@kayser.cl";
    $titulo="Diferencias Documento/Correlativo";
    $ahora=date("Y-m-d H-m-s");
    $columnas=implode(';',$sqlsrv->getColumnsLastSelect()).";\r\n";
    $contenido=$columnas.$str_consulta;
    $sqlsrv->closeConnection();#cerramos la conexion
    $headers = "From: INFORMATICA KAYSER <informatica@kayser.cl>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: application/octet-stream; name=".$titulo.".csv\r\n"; //envio directo de datos
    $headers .= "Content-Disposition: attachment; filename=".$titulo."(".$ahora.").csv\r\n";
    $headers .= "Content-Transfer-Encoding: binary\r\n";
    $headers .= utf8_decode($contenido);
    $headers .= "\r\n"; //retorno de carro y salto de linea
    if(mail($destinatario, $titulo,"", $headers)){
        echo "<h2>mail enviado correctamente</h2>";
        // echo $contenido;
    }
    else{
      mail("informatica@kayser.cl", "Error en envio de Email DIFERENCIA DOCUMENTO/CORRELATIVO", "Error en envio de Email DIFERENCIA DOCUMENTO/CORRELATIVO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
      // mail("aobando@kayser.cl", "Error en envio de Email DIFERENCIA DOCUMENTO/CORRELATIVO", "Error en envio de Email DIFERENCIA DOCUMENTO/CORRELATIVO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
    }  
  }else {
    mail("informatica@kayser.cl", "SIN RESULTADOS EN CONSULTA DIFERENCIA DOCUMENTO/CORRELATIVO", "SIN RESULTADOS EN CONSULTA DIFERENCIA DOCUMENTO/CORRELATIVO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
    // mail("aobando@kayser.cl", "SIN RESULTADOS EN CONSULTA DIFERENCIA DOCUMENTO/CORRELATIVO", "SIN RESULTADOS EN CONSULTA DIFERENCIA DOCUMENTO/CORRELATIVO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
    
  }
}else {
  mail("informatica@kayser.cl", "Error enla consulta DIFERENCIA DOCUMENTO/CORRELATIVO", "Error ENVIO MAIL PROGRAMADO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
  // mail("aobando@kayser.cl", "Error enla consulta DIFERENCIA DOCUMENTO/CORRELATIVO", "Error ENVIO MAIL PROGRAMADO", "From: Informática Kayser <informatica@kayser.cl>\r\n");
  
}

?>