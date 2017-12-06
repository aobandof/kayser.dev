<?php
// require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
// require_once("../shared/clases/inflector.php");
ini_set('display_errors', '0');
date_default_timezone_set("America/Santiago");

$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$sqlsrv_13=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv_promes=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv_33->getConnection())===false) { $data['errors'][]=$sqlsrv_33->getErrors(); $existe_error_conexion=1; }
if(($sqlsrv_13->getConnection())===false) { $data['errors'][]=$sqlsrv_13->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
$table='';

if($_GET['time']=='morning'){
  $hour='16:00:00';
  $hoy=date('Y-m-d H:i:s')."\n"; //OBTENEMOS LA FECHA
  $ayer = date('Y-m-d', strtotime('-1 day'));
  $ayer_full=$ayer." ".$hour;
  $query_store="SELECT  WhsCode as cod_tienda, WhsName as tienda FROM OWHS where U_GSP_SENDTPV = 'Y'";
  $arr_store=$sqlsrv_33->select($query_store,"sqlsrv_a_p");
  ///DEBIDO A QUE COGNOS, SOLO SE MUESTRAN LAS TIENDAS QUE VENDIERON, solo bastaria con preguntar los codigos de tienda, si estas no existen en el array de tiendas, entonces reportar como tienda sin venta
  //$query_morning="SELECT bodega as cod_tienda,Almacen,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '$ayer', 20) AND Horas>=CONVERT(datetime, '$hour', 20) group by Almacen, Bodega ORDER BY Almacen";        
  $query_morning="SELECT Bodega as  cod_tienda, Almacen FROM [GSP].[dbo].[Gsp_SboKayserResumen]  where fecha=CONVERT(datetime, '2017-12-05', 20) AND Horas>=CONVERT(datetime, '16:00:00', 20) group by Bodega, Almacen";
  $arr_yesterday=$sqlsrv_13->selectArrayUniAssocIdName($query_morning);
  $quan_y=count($arr_yesterday);
  $quan_s=count($arr_store);
  $table.="<table border='1px'><thead><th style='padding: .6rem;'>TIENDA</th><th style='padding: .6rem;'>VENTAS</th></thead><tbody>";
  for($i=0;$i<$quan_s;$i++){
    $cod_store=$arr_store[$i]['cod_tienda'];
    if(!isset($arr_yesterday[$cod_store]))
      $table.="<tr><td style='padding: .6rem;'>".$arr_store[$i]['tienda']."</td><td style='padding: .6rem; text-align:center; '>0</td></tr>";
  }
  $table.="</tbody></table>";
  $message="<h2>Tiendas sin ventas<br>Ayer desde las 4:00 pm </h2>".$table;
  $subject="Tiendas con Venta = 0, Ayer desde las 4:00 pm";
}
if($_GET['time']=='afternoon'){
  $current_time=date("g:i a");
  $query_afternoon="SELECT  A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct as total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC";
  $arr_today=$sqlsrv_33->select($query_afternoon,"sqlsrv_a_p");
  $quan_t=count($arr_today);
  $table.="<table border='1px'><thead><th style='padding: .6rem;'>TIENDA</th><th style='padding: .6rem;'>VENTAS</th></thead><tbody>";
  for($i=0;$i<$quan_t;$i++){
    if($arr_today[$i]['total']==0)
      $table.="<tr><td style='padding: .6rem;'>".$arr_today[$i]['tienda']."</td><td style='padding: .6rem; text-align:center; '>0</td></tr>";
  }
  $table.="</tbody></table>";
  $message="<h2>Tiendas sin ventas<br>Hoy hasta las $current_time </h2>".$table;
  $subject="Tiendas con Venta = 0, Hoy hasta las $current_time";
}
///--- ############################### ---
///--- DATOS PARA ENVIO DE CSV AL MAIL ---
$link="http://192.168.0.19/sku/crear.php?list=$lista&status=CREADA&option=show";
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