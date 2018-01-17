<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/MssqlConexion.php";

//CONEXIONES
$conexion_stock=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');

//CONECTORES
$conec_stock=$conexion_stock->obtener_conector();

$ayer = date('Y-m-d', strtotime('-1 day'));
$ayer_string=str_replace('-','',$ayer);
$csv_stock='';
$filename="766977901_".$ayer_string."_".$ayer_string."_".$ayer_string."_KAYSER_B2B_DIA_INV.csv";

//QUERYS
$query_stock_cm = "select t0.IdArticulo AS [Codigo Producto], t0.IdArticulo AS [Descripcion Producto], t0.idalmacen AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,convert(date, GETDATE(),102)) AS [Fecha Inicio], ";
$query_stock_cm.= "convert(varchar,convert(date, GETDATE(),102)) AS [Fecha termino], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.Cantidad) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.Cantidad) AS int) AS [Inventario en unidades], ";
$query_stock_cm.= "CAST(SUM(t0.Cantidad)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta Total en Valor Costo] ";
$query_stock_cm.= "from [192.168.0.17].[WMSTEK_KAYSER].[dbo].[Existencia]   as t0 inner join [192.168.0.17].[WMSTEK_KAYSER].[dbo].[ubicacion] as t1 on t0.IdUbicacion=t1.IdUbicacion ";
$query_stock_cm.= "inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.idalmacen COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.idarticulo=t2.ItemCode COLLATE Modern_Spanish_CS_AS ";
$query_stock_cm.= "where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY t0.IdArticulo, t0.idalmacen, t3.WhsName";

$query_stock_tiendas = "select t0.itemcode AS [Codigo Producto], t0.itemcode AS [Descripcion Producto], t0.whscode AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,convert(date, GETDATE())) AS [Fecha Inicio], convert(varchar,convert(date, GETDATE())) AS [Fecha termino], ";
$query_stock_tiendas.= "CAST(SUM(t0.onhand) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.onhand) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.onhand) AS int) AS [Inventario en unidades], ";
$query_stock_tiendas.= "CAST(SUM(t0.onhand)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.onhand) as int) * 0 AS [Venta Total en Valor Costo] ";
$query_stock_tiendas.= "from Stock.dbo.Kayser_OITW as t0 inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.WhsCode COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.ItemCode=t2.ItemCode  COLLATE Modern_Spanish_CS_AS ";
$query_stock_tiendas.= "where t3.U_GSP_SENDTPV='Y' and t0.onhand>0 GROUP BY t0.ItemCode, t0.WhsCode, t3.WhsName  ORDER BY t0.ItemCode";

// $registros_stock_cm=sqlsrv_query($conec_stock, $query_stock_cm);
// $registros_stock_tiendas=sqlsrv_query($conec_stock, $query_stock_tiendas);
// //CANTIDAD DE CAMPOS
// $quant_fields_stock=sqlsrv_num_fields($registros_stock_cm);
// //OBTENEMOS LA CABECERA STOCK
// foreach( sqlsrv_field_metadata($registros_stock_cm) as $fieldMetadata )
//   $csv_stock.=$fieldMetadata['Name'].";";
// $csv_stock=substr($csv_stock,0,strlen($csv_stock)-1)."\r\n";
// //OBTENEMOS TODAS LAS FILAS DEL STOK DE CASA MATRIZ
// while ($reg = sqlsrv_fetch_array($registros_stock_cm, SQLSRV_FETCH_NUMERIC)) {
//   $fila='';
//   for($j=0;$j<$quant_fields_stock;$j++){
//     $fila.=$reg[$j].";";
//   }
//   $fila=substr($fila,0,strlen($fila)-1)."\r\n";
//   $csv_stock.=$fila;
// }
// //OBTENEMOS TODAS LAS FILAS DEL STOCK DE LAS TIENDAS Y LAS CARGAMOS EN LA MISMA VARIABLE CSV
// while ($reg = sqlsrv_fetch_array($registros_stock_tiendas, SQLSRV_FETCH_NUMERIC)) {
//   $fila='';
//   for($j=0;$j<$quant_fields_stock;$j++){
//     $fila.=$reg[$j].";";
//   }
//   $fila=substr($fila,0,strlen($fila)-1)."\r\n";
//   $csv_stock.=$fila;
// }
// $file = fopen($filename, 'w');
// fwrite($file, $csv_stock);
// fclose($file);

/////----- 'CONEXION A SFTP Y ENVIO DE ARCHIVO' -----/////
$host="45.56.75.110";
$port=22;
$user="kayser";
$password="ey%T;WWv=9ko*;R541ay";
$ruta="/kayser/upload";
/**** conexion *****/
$conn_id=ftp_ssl_connect($host,$port);
// if($conn_id!==false)
	// {
		if(ftp_login($conn_id,$user,$password))
		{
			// if(@ftp_chdir($conn_id,$ruta))
			// {
   //      ftp_pasv($conn_id, true);//por defecto en Linux, la conexion se establece en modo activo ( servidor ->cliente), por esto la cambiamos modo pasivo ya que nosotros estableceremos la conexion
   //      // if(@ftp_put($conn_id,$filename,$filename,FTP_ASCII/*FTP_BINARY*/))
   //      if(@ftp_put($conn_id,'prueba.txt','prueba.txt',FTP_ASCII/*FTP_BINARY*/))
			// 		echo "Fichero subido correctamente";
			// 	else
			// 		echo "No ha sido posible subir el fichero";
			// }else
			//   echo "No existe el directorio especificado";
			echo ftp_pwd($conn_id);
		}else
			echo "El usuario o la contraseña son incorrectos";
		# Cerramos la conexion ftp
		ftp_close($conn_id);
	// }else
		// echo "No ha sido posible conectar con el servidor";

//despues de enviar el archivo lo eliminamos
// unlink($filename);
?>