<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/MssqlConexion.php";

$ayer = date('Y-m-d', strtotime('-1 day'));
$fourteen_days_ago = date('Y-m-d', strtotime('-14 day'));
$ayer_string=str_replace('-','',$ayer);
$fourteen_days_ago_string=str_replace('-','',$fourteen_days_ago);
$filename="766977901_".$fourteen_days_ago_string."_".$ayer_string."_KAYSER_B2B_DIA.csv";
$csv_ventas='';

//CONEXIONES
$conexion_ventas=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','GSP');

//CONECTORES
$conec_ventas=$conexion_ventas->obtener_conector();

//QUERYS
$query_ventas = "SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino], ";
$query_ventas.= "sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] ";
$query_ventas.= "FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(date, GETDATE() - 14, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA";

$registros_ventas=sqlsrv_query($conec_ventas, $query_ventas);

//CANTIDAD DE CAMPOS
$quant_fields_ventas=sqlsrv_num_fields($registros_ventas);

//OBTENEMOS LA CABECERA VENTAS
foreach( sqlsrv_field_metadata($registros_ventas) as $fieldMetadata )
  $csv_ventas.=$fieldMetadata['Name'].";";
$csv_ventas=substr($csv_ventas,0,strlen($csv_ventas)-1)."\r\n";
//OBTENEMOS TODAS LAS FILAS DEL STOK DE CASA MATRIZ
while ($reg = sqlsrv_fetch_array($registros_ventas, SQLSRV_FETCH_NUMERIC)) {
  $fila='';
  for($j=0;$j<$quant_fields_ventas;$j++){
    $fila.=$reg[$j].";";
  }
  $fila=substr($fila,0,strlen($fila)-1)."\r\n";
  $csv_ventas.=$fila;
}

/////----- 'CONEXION A SFTP Y ENVIO DE ARCHIVO' -----////
$host="45.56.75.110";
$port=22;
$user="kayser";
$password="ey%T;WWv=9ko*;R541ay";
$ruta="/kayser/upload/";
$connection = ssh2_connect($host, $port);
if (ssh2_auth_password($connection, $user, $password)) {
  $sftp = ssh2_sftp($connection);
  echo "Conexion Exitosa, subiendo archivo..."."\n";
	file_put_contents("ssh2.sftp://".intval($sftp).$ruta.$filename, $csv_ventas);
} 
else {
    echo "Datos de autenticacion incorrectos";
}
?>