-- 192.168.0.16:	VENTAS DETALLE ENERO 2016
-- BD:	POSOne_CC_Kayser_PROD2
-------------------------------------------------------------------------
SELECT      TOP (100) PERCENT  ITE.code AS [Codigo Producto], ite.[description] as [Descripcion Producto], STO.code AS [Codigo Local], sto.[description] as [Descripcion Local],
            CONVERT(VARCHAR(10), DOC.creation_date, 120) /* + ' ' + LEFT(DOC.document_time, 2) + ':' + RIGHT(DOC.document_time, 2) + ':00'*/ AS [Fecha Inicio],
            CONVERT(VARCHAR(10), DOC.creation_date, 120) /* + ' ' + LEFT(DOC.document_time, 2) + ':' + RIGHT(DOC.document_time, 2) + ':00'*/ AS [Fecha Termino],
            CAST(SUM(DET.quantity) AS int) AS [Venta Total Unidades], CAST(SUM(DET.final_net_total)*1.19 AS int) AS [Venta Total en Valor],
            CAST(SUM(DET.discount) AS int) AS [Inventario en unidades], CAST(SUM(DET.discount) AS int) AS [Inventario en Valor], CAST(SUM(DET.discount) AS int) AS [Venta Total en Valor Costo]

FROM DOCUMENT AS DOC WITH (NOLOCK) INNER JOIN
     DOCUMENTDET AS DET WITH (NOLOCK) ON DOC.ID = DET.id_document INNER JOIN
     DOCUMENT_TYPE AS TYP ON DOC.id_document_type = TYP.ID INNER JOIN
     ITEM AS ITE ON DET.id_item = ITE.ID INNER JOIN
     STORE AS STO ON DOC.id_store = STO.ID          
WHERE (TYP.code IN ('FAC', 'FAC_ELE', 'NCR', 'NCR_ELE', 'BOL', 'BOLFIS')) AND (TYP.flag_tributary = 1) AND DOC.creation_date >= '2016-01-01' and STO.code<>'013'
GROUP BY    ITE.code, ite.[description], STO.code, sto.[description], DOC.creation_date, DOC.document_time, DET.discount





-- 192.168.0.13:	VENTAS DETALLE DESDE FEBRERO DEL 2016 HASTA 14/12/17
-- BD:	GSP
-------------------------------------------------------------------------
SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA<CONVERT(DATETIME, '2016-07-01 00:00:00', 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA


SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(DATETIME, '2016-07-01 00:00:00', 102) AND  U_GSP_CADATA<CONVERT(DATETIME, '2017-01-01 00:00:00', 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA

SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(DATETIME, '2017-01-01 00:00:00', 102) AND  U_GSP_CADATA<CONVERT(DATETIME, '2017-07-01 00:00:00', 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA

SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(DATETIME, '2017-07-01 00:00:00', 102) AND  U_GSP_CADATA<CONVERT(DATETIME, '2017-12-15 00:00:00', 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA


-- 192.168.0.13: MAESTRO DE LOCALES
-- BD:	Stock
------------------------------------
select WhsCode [Código de local] , WhsName [Nombre de local], City [Ciudad], ZipCode [eMail], U_GSP_LOGISTICO [Categoria], U_APOLLO_IDCU [Supervisor], U_SDG_SBO_REPCLIENTE [Jefa de Local] from Kayser_OWHS t0  where U_GSP_SENDTPV='Y'


-- 192.168.0.13: MAESTRO DE PRODUCTOS
-- BD:	Stock
-------------------------------------
select t0.itemcode as [Código de producto], t0.itemname as [Descripción de producto], U_APOLLO_SEG1 [Articulo], U_APOLLO_SEG2 [Color], t0.U_Marca as [Marca], t1.ItmsGrpNam as [Grupo], U_SubGrupo1 as [Sub-Grupo], t0.U_APOLLO_SSEG3 as [Talla], t0.u_estilo as [Estilo], U_APOLLO_S_GROUP [Temporada], U_Fila [Presentación], t3.Name [Categoria Prenda], U_IDCopa [Sostén Copa], U_Material as Material
from  Kayser_OITM t0 inner join Kayser_OITB t1 on t0.ItmsGrpCod=t1.ItmsGrpCod inner join Kayser_DIV t2 on T0.U_APOLLO_DIV=T2.Code INNER JOIN Kayser_SEASON t3 on t0.U_APOLLO_SEASON=t3.Code



-- 192.168.0.17: INVENTARIO
---------------------------
select t0.IdArticulo, CAST(SUM(t0.Cantidad) AS int) as Cant 
from   Existencia as t0 inner join
             Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY IdArticulo ORDER BY IdArticulo