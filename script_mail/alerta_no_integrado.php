<?php
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
ini_set('display_errors', '0');
date_default_timezone_set("America/Santiago");

$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$data=[]; $existe_error_conexion=0;
$table='';
if(($sqlsrv_33->getConnection())===false) { $data['errors'][]=$sqlsrv_33->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
// $query_select="select code, U_GSP_CANUME, U_GSP_CADATA, U_GSP_ERROR from [@GSP_TPVCAP] where U_GSP_ERROR like '%no se ha integrado%' and year(u_gsp_cadata)>=2018";
$query_select="select top 5 code, U_GSP_CABOTI,U_GSP_CACLIE, U_GSP_CADOCU,U_GSP_CANUME, U_GSP_CADATA, U_GSP_ERROR  from [@GSP_TPVCAP] where U_GSP_ERROR like '%no se ha integrado%' and year(u_gsp_cadata)>=2017 AND month(u_gsp_cadata)=12";
$arr_query=$sqlsrv_33->select($query_select,"sqlsrv_n_p");
// var_dump($arr_query);
if($arr_query!==false && $arr_query!==0) {  
  $quan_reg=count($arr_query);
  $table.="<table border='1px'><thead><th style='padding: .6rem;'>CODIGO</th><th style='padding: .6rem;'>BOTI</th><th style='padding: .6rem;'>DOCUMENTO</th><th style='padding: .6rem;'>CLIENTE</th><th style='padding: .6rem;'>NUMERO</th><th style='padding: .6rem;'>FECHA</th><th style='padding: .6rem;'>ERROR</th></thead><tbody>";
  for($i=0;$i<$quan_reg;$i++){
    $table.="<tr><td style='padding: .6rem;'>".$arr_query[$i][0]."</td><td style='padding: .6rem; text-align:center;'>".$arr_query[$i][1]."</td><td style='padding: .6rem; text-align:center;'>".$arr_query[$i][2]."</td><td style='padding: .6rem; text-align:center;'>".$arr_query[$i][3]."</td><td style='padding: .6rem; text-align:center;'>".$arr_query[$i][4]."</td><td style='padding: .6rem; text-align:center;'>".date_format($arr_query[$i][5], 'Y-m-d')."</td><td style='padding: .6rem; text-align:center;'>".$arr_query[$i][6]."</td></tr>";
  }
  // echo $quan_reg;
  $table.="</tbody></table>";
  $message="<h2>Errores Encontrados </h2>".$table;
  $subject="NO SE HA INTEGRADO";
}else{
  if($arr_query!==false){
    $message=$sqlsrv_33->getErrors();
    $subject="ERROR EN CONSULTA NO SE HA INTEGRADO";
  }
}

// echo "<br>".$table;
if($table==''){
  echo "SIN ERRORES...";
  exit();
}
///--- ############################### ---
///--- DATOS PARA ENVIO DE CSV AL MAIL ---
$destinatario ="aobando@kayser.cl";
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=UTF-8\r\n"; //PARA ENVIO EN FORMATO HTML
$headers .= "From: INFORMATICA KAYSER <informatica@kayser.cl>\r\n";
if(mail($destinatario, $subject, $message, $headers)){
  echo "e-mail enviado correctamente";
}
else{
  echo "error, NO SE PUDO ENVIAR EL EMAIL";
}   
?>