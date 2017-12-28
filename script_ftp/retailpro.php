<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/sku_db_sqlsrv_13.php";
require_once "../shared/clases/sku_db_sqlsrv_13_gsp.php";
require_once "../shared/clases/sku_db_sqlsrv_16.php";

/* OBTENEMOS EL ARRAY */
$query_ventas_1 = "SELECT TOP (100) PERCENT  ITE.code AS [Codigo Producto], ite.[description] as [Descripcion Producto], STO.code AS [Codigo Local], sto.[description] as [Descripcion Local],";
$query_ventas_1.= " CONVERT(VARCHAR(10), DOC.creation_date, 120) + ' ' + LEFT(DOC.document_time, 2) + ':' + RIGHT(DOC.document_time, 2) + ':00' AS [Fecha Inicio],";
$query_ventas_1.= " CONVERT(VARCHAR(10), DOC.creation_date, 120) + ' ' + LEFT(DOC.document_time, 2) + ':' + RIGHT(DOC.document_time, 2) + ':00' AS [Fecha Termino],";
$query_ventas_1.= " CAST(SUM(DET.quantity) AS int) AS [Venta Total Unidades], CAST(SUM(DET.final_net_total)*1.19 AS int) AS [Venta Total en Valor],";
$query_ventas_1.= " CAST(SUM(DET.discount) AS int) AS [Inventario en unidades], CAST(SUM(DET.discount) AS int) AS [Inventario en Valor], CAST(SUM(DET.discount) AS int) AS [Venta Total en Valor Costo]";
$query_ventas_1.= " FROM DOCUMENT AS DOC WITH (NOLOCK) INNER JOIN";
$query_ventas_1.= " DOCUMENTDET AS DET WITH (NOLOCK) ON DOC.ID = DET.id_document INNER JOIN";
$query_ventas_1.= " DOCUMENT_TYPE AS TYP ON DOC.id_document_type = TYP.ID INNER JOIN";
$query_ventas_1.= " ITEM AS ITE ON DET.id_item = ITE.ID INNER JOIN";
$query_ventas_1.= " STORE AS STO ON DOC.id_store = STO.ID";
$query_ventas_1.= " WHERE (TYP.code IN ('FAC', 'FAC_ELE', 'NCR', 'NCR_ELE', 'BOL', 'BOLFIS')) AND (TYP.flag_tributary = 1) AND DOC.creation_date >= '2016-01-01' and STO.code<>'013'";
$query_ventas_1.= " GROUP BY    ITE.code, ite.[description], STO.code, sto.[description], DOC.creation_date, DOC.document_time, DET.discount";
// $query_ventas_2 = "SELECT  top 100   SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], U_GSP_CADATA AS [Fecha Inicio], U_GSP_CADATA AS [Fecha termino],";
// $query_ventas_2.= "sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo]";
// $query_ventas_2.= "FROM Gsp_SboKayserDetalle where U_GSP_CADATA<CONVERT(DATETIME, '2017-12-14 00:00:00', 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA";


// $query_productos = "select t0.itemcode as [Código de producto], t0.itemname as [Descripción de producto], U_APOLLO_SEG1 [Articulo], U_APOLLO_SEG2 [Color], t0.U_Marca as [Marca], t1.ItmsGrpNam as [Grupo], U_SubGrupo1 as [Sub-Grupo], t0.U_APOLLO_SSEG3 as [Talla], t0.u_estilo as [Estilo], U_APOLLO_S_GROUP [Temporada], U_Fila [Presentación], t3.Name [Categoria Prenda], U_IDCopa [Sostén Copa], U_Material as Material";
// $query_productos.= "from  Kayser_OITM t0 inner join Kayser_OITB t1 on t0.ItmsGrpCod=t1.ItmsGrpCod inner join Kayser_DIV t2 on T0.U_APOLLO_DIV=T2.Code INNER JOIN Kayser_SEASON t3 on t0.U_APOLLO_SEASON=t3.Code";
// $query_almacen = "select WhsCode [Código de local] , WhsName [Nombre de local], City [Ciudad], ZipCode [eMail], U_GSP_LOGISTICO [Categoria], U_APOLLO_IDCU [Supervisor], U_SDG_SBO_REPCLIENTE [Jefa de Local] from Kayser_OWHS t0  where U_GSP_SENDTPV='Y'";
// header("Content-Type: text/html;charset=utf-8");
$arr_ventas1=$sqlsrv_16->select($query_ventas_1,"sqlsrv_n_p");
if($arr_ventas1!==false && $arr_ventas1!==0){
  $data['ventas1']=$arr_ventas1;
} else ($arr_ventas1===false) ? $data['errors']=$sqlsrv_16->getErrors() : $data['cant_ventas1']=$arr_ventas1;
// $arr_ventas2=$sqlsrv_13_gsp->select($query_ventas2,"sqlsrv_n_p");
// $arr_productos=$sqlsrv_13->select($query_productos,"sqlsrv_n_p");
// $arr_almacen=$sqlsrv_13->select($query_almacen,"sqlsrv_n_p");



// var_dump($arr_ventas2);
// var_dump($arr_productos);
// var_dump($arr_almacen);
echo json_encode($data);
?>