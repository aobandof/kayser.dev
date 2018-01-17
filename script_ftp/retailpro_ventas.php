<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/MssqlConexion.php";

$ayer = date('Y-m-d', strtotime('-1 day'));
$fourteen_days_ago = date('Y-m-d', strtotime('-15 day'));
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
$query_ventas.= "FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(date, GETDATE() - 15, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA";

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

$file = fopen($filename, 'w');
fwrite($file, $csv_ventas);

/////----- 'CONEXION A SFTP Y ENVIO DE ARCHIVO' -----/////
$host="45.56.75.110";
$port=22;
$user="kayser";
$password="ey%T;WWv=9ko*;R541ay";
$ruta="/kayser/upload";
/**** conexion *****/
$conn_id=@ftp_connect($host,$port);
if($conn_id)
	{
		if(@ftp_login($conn_id,$user,$password))
		{
			if(@ftp_chdir($conn_id,$ruta))
			{
        ftp_pasv($conn_id, true);//por defecto en Linux, la conexion se establece en modo activo ( servidor ->cliente), por esto la cambiamos modo pasivo ya que nosotros estableceremos la conexion
				if(@ftp_put($conn_id,$filename,$filename,FTP_ASCII/*FTP_BINARY*/))
					echo "Fichero subido correctamente";
				else
					echo "No ha sido posible subir el fichero";
			}else
			  echo "No existe el directorio especificado";
		}else
			echo "El usuario o la contraseña son incorrectos";
		# Cerramos la conexion ftp
		ftp_close($conn_id);
	}else
		echo "No ha sido posible conectar con el servidor";
//despues de enviar el archivo lo eliminamos
fclose($file); //cerramos el puntero al archivo
// unlink($filename);//eliminamos el archivo para que no llene nuestro directorio
?>