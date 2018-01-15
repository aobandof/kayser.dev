<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/MssqlConexion.php";

//CONEXIONES
$conexion_ventas=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','GSP');
$conexion_stock=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');

//CONECTORES
$conec_ventas=$conexion_ventas->obtener_conector();
$conec_stock=$conexion_stock->obtener_conector();

$csv_stock='';
$csv_ventas='';
//QUERYS
$query_ventas = "SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], ";
$query_ventas.= "CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino], sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], ";
$query_ventas.= "sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] ";
$query_ventas.= "FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(DATETIME, GETDATE() - 15, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA";

$query_stock_cm = "select t0.IdArticulo AS [Codigo Producto], t0.IdArticulo AS [Descripcion Producto], t0.idalmacen AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,convert(date, GETDATE(),102)) AS [Fecha Inicio], ";
$query_stock_cm.= "convert(varchar,convert(date, GETDATE(),102)) AS [Fecha termino], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.Cantidad) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.Cantidad) AS int) AS [Inventario en unidades], ";
$query_stock_cm.= "CAST(SUM(t0.Cantidad)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta Total en Valor Costo] ";
$query_stock_cm.= "from [192.168.0.17].[WMSTEK_KAYSER].[dbo].[Existencia]   as t0 inner join [192.168.0.17].[WMSTEK_KAYSER].[dbo].[ubicacion] as t1 on t0.IdUbicacion=t1.IdUbicacion ";
$query_stock_cm.= "inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.idalmacen COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.idarticulo=t2.ItemCode COLLATE Modern_Spanish_CS_AS ";
$query_stock_cm.= "where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY t0.IdArticulo, t0.idalmacen, t3.WhsName";

$query_stock_tiendas = "select t0.itemcode AS [Codigo Producto], t0.itemcode AS [Descripcion Producto], t0.whscode AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(date, GETDATE()) AS [Fecha Inicio], convert(date, GETDATE()) AS [Fecha termino], ";
$query_stock_tiendas.= "CAST(SUM(t0.onhand) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.onhand) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.onhand) AS int) AS [Inventario en unidades], ";
$query_stock_tiendas.= "CAST(SUM(t0.onhand)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.onhand) as int) * 0 AS [Venta Total en Valor Costo] ";
$query_stock_tiendas.= "from Stock.dbo.Kayser_OITW as t0 inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.WhsCode COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.ItemCode=t2.ItemCode  COLLATE Modern_Spanish_CS_AS ";
$query_stock_tiendas.= "where t3.U_GSP_SENDTPV='Y' and t0.onhand>0 GROUP BY t0.ItemCode, t0.WhsCode, t3.WhsName  ORDER BY t0.ItemCode";

$registros_stock_cm=sqlsrv_query($conec_stock, $query_stock_cm);
//CANTIDAD DE CAMPOS
$quant_fields=sqlsrv_num_fields($registros_stock_cm);
//OBTENEMOS LA CABECERA STOCK
foreach( sqlsrv_field_metadata($registros_stock_cm) as $fieldMetadata )
  $csv_stock.=$fieldMetadata['Name'].";";
$csv_stock=substr($csv_stock,0,strlen($csv_stock)-1)."\r\n";
while ($reg = sqlsrv_fetch_array($registros_stock_cm, SQLSRV_FETCH_NUMERIC)) {
  $fila='';
  for($j=0;$j<$quant_fields;$j++){
    $fila.=$reg[$j].";";
  }
  $fila=substr($fila,0,strlen($fila)-1)."\r\n";
  $csv_stock.=$fila;
}
// header('Content-type: application/json'); 
header("Content-Type: text/html;charset=utf-8");
// echo json_encode($csv_stock);
echo $csv_stock;
?>