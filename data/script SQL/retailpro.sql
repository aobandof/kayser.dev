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

-- ventas los ultimos 7 dias
-----------------------------
SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=CONVERT(date, GETDATE() - 7, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA

-- ventas mensuales
--------------------
SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino],
                  sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] 
FROM GSP.dbo.Gsp_SboKayserDetalle where U_GSP_CADATA>=DATEADD(mm,-1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) AND U_GSP_CADATA<DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA


SELECT DATEADD(mm,-1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))
SELECT DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)
SELECT TOP 10 U_GSP_CADATA FROM GSP.dbo.Gsp_SboKayserDetalle
SELECT DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) 'Primer d�a del mes actual'

-- 192.168.0.13: MAESTRO DE LOCALES
-- BD:	Stock
------------------------------------
select WhsCode [C�digo de local] , WhsName [Nombre de local], City [Ciudad], ZipCode [eMail], U_GSP_LOGISTICO [Categoria], U_APOLLO_IDCU [Supervisor], U_SDG_SBO_REPCLIENTE [Jefa de Local] from Kayser_OWHS t0  where U_GSP_SENDTPV='Y'


-- 192.168.0.13: MAESTRO DE PRODUCTOS
-- BD:	Stock
-------------------------------------
select t0.itemcode as [C�digo de producto], t0.itemname as [Descripci�n de producto], U_APOLLO_SEG1 [Articulo], U_APOLLO_SEG2 [Color], t0.U_Marca as [Marca], t1.ItmsGrpNam as [Grupo], U_SubGrupo1 as [Sub-Grupo], t0.U_APOLLO_SSEG3 as [Talla], t0.u_estilo as [Estilo], U_APOLLO_S_GROUP [Temporada], U_Fila [Presentaci�n], t3.Name [Categoria Prenda], U_IDCopa [Sost�n Copa], U_Material as Material
from  Kayser_OITM t0 inner join Kayser_OITB t1 on t0.ItmsGrpCod=t1.ItmsGrpCod inner join Kayser_DIV t2 on T0.U_APOLLO_DIV=T2.Code INNER JOIN Kayser_SEASON t3 on t0.U_APOLLO_SEASON=t3.Code



-- 192.168.0.17: INVENTARIO
-- BD: WMSTEK_KAYSER
---------------------------
select t0.IdArticulo, CAST(SUM(t0.Cantidad) AS int) as Cant 
from   Existencia as t0 inner join
             Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY IdArticulo ORDER BY IdArticulo



--------------------------------------------------
-- NUEVAS CONSULTAS
--------------------------------------------------

--INVENTARIO CASA MATRIZ
-- 192.168.0.17 -- BD [WMSTEK_KAYSER
-- 192.168.0.13 -- BD Stock

select            t0.IdArticulo AS [Codigo Producto],
                  t0.IdArticulo AS [Descripcion Producto],
                  t0.idalmacen AS [Codigo Local],
                  t3.WhsName AS [Descripcion Local],
                  convert(date, GETDATE()) AS [Fecha Inicio],
                  convert(date, GETDATE()) AS [Fecha termino],
                  CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta total unidades],
                  CAST(SUM(t0.Cantidad) as int) * 0  AS [Venta Total en Valor],
                  CAST(SUM(t0.Cantidad) AS int) AS [Inventario en unidades],
                  CAST(SUM(t0.Cantidad)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor],
                  CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta Total en Valor Costo]
                 
                 
from   [192.168.0.17].[WMSTEK_KAYSER].[dbo].[Existencia]   as t0 inner join
       [192.168.0.17].[WMSTEK_KAYSER].[dbo].[ubicacion]          as t1 on t0.IdUbicacion=t1.IdUbicacion inner join
       Stock.dbo.Kayser_OWHS                                           as t3 on t3.WhsCode=t0.idalmacen COLLATE Modern_Spanish_CS_AS inner join
       Stock.dbo.Kayser_OITM                                           as t2 on t0.idarticulo=t2.ItemCode  COLLATE Modern_Spanish_CS_AS
             
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY t0.IdArticulo, t0.idalmacen, t3.WhsName  --ORDER BY IdArticulo


select t0.IdArticulo AS [Codigo Producto], t0.IdArticulo AS [Descripcion Producto], t0.idalmacen AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,convert(date, GETDATE())) AS [Fecha Inicio],
convert(varchar,convert(date, GETDATE())) AS [Fecha termino], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.Cantidad) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.Cantidad) AS int) AS [Inventario en unidades],
CAST(SUM(t0.Cantidad)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta Total en Valor Costo]
from [192.168.0.17].[WMSTEK_KAYSER].[dbo].[Existencia]   as t0 inner join [192.168.0.17].[WMSTEK_KAYSER].[dbo].[ubicacion] as t1 on t0.IdUbicacion=t1.IdUbicacion
inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.idalmacen COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.idarticulo=t2.ItemCode COLLATE Modern_Spanish_CS_AS             
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY t0.IdArticulo, t0.idalmacen, t3.WhsName  --ORDER BY IdArticulo


--INVENTARIO TIENDAS
-- 192.168.0.13 -- BD Stock
select            t0.itemcode AS [Codigo Producto],
                  t0.itemcode AS [Descripcion Producto],
                  t0.whscode AS [Codigo Local],
                  t3.WhsName AS [Descripcion Local],
                  convert(date, GETDATE()) AS [Fecha Inicio],
                  convert(date, GETDATE()) AS [Fecha termino],
                  CAST(SUM(t0.onhand) as int) * 0 AS [Venta total unidades],
                  CAST(SUM(t0.onhand) as int) * 0  AS [Venta Total en Valor],
                  CAST(SUM(t0.onhand) AS int) AS [Inventario en unidades],
                  CAST(SUM(t0.onhand)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor],
                  CAST(SUM(t0.onhand) as int) * 0 AS [Venta Total en Valor Costo]
                 
                 
from   Stock.dbo.Kayser_OITW                                           as t0 inner join
       Stock.dbo.Kayser_OWHS                                           as t3 on t3.WhsCode=t0.WhsCode COLLATE Modern_Spanish_CS_AS inner join
       Stock.dbo.Kayser_OITM                                           as t2 on t0.ItemCode=t2.ItemCode  COLLATE Modern_Spanish_CS_AS
      
       
where t3.U_GSP_SENDTPV='Y'  and t2.ItemCode='10.07-bco-s'
GROUP BY t0.ItemCode, t0.WhsCode, t3.WhsName  ORDER BY t0.ItemCode

select t0.itemcode AS [Codigo Producto], t0.itemcode AS [Descripcion Producto], t0.whscode AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(date, GETDATE()) AS [Fecha Inicio], convert(date, GETDATE()) AS [Fecha termino],
CAST(SUM(t0.onhand) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.onhand) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.onhand) AS int) AS [Inventario en unidades],
CAST(SUM(t0.onhand)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.onhand) as int) * 0 AS [Venta Total en Valor Costo]                                  
from Stock.dbo.Kayser_OITW as t0 inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.WhsCode COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.ItemCode=t2.ItemCode  COLLATE Modern_Spanish_CS_AS      
where t3.U_GSP_SENDTPV='Y' and t0.onhand>0 GROUP BY t0.ItemCode, t0.WhsCode, t3.WhsName  ORDER BY t0.ItemCode
 

 -------------------  consultas modificafas
 ----- ventas ultimos 7 dias ------ BD GSP -  SERVER 192.168.0.13
SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino], 
sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] , t1.AVGPRICE AS Costo 
FROM GSP.dbo.Gsp_SboKayserDetalle t0 INNER JOIN gsp.dbo.OITM t1 on t1.ITEMCODE=t0.SKU 
where U_GSP_CADATA>=CONVERT(date, GETDATE() - 7, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA, t1.AVGPRICE

------ ventas mensuales ----------- BD GSP -  SERVER 192.168.0.13
SELECT SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha Inicio], CONVERT(VARCHAR(10),U_GSP_CADATA,120) AS [Fecha termino], 
sum(VentaU) AS [Venta total unidades], sum(NetoU) AS [Venta Total en Valor], sum(StockCM * 0) AS [Inventario en unidades], sum(StockCM * 0) AS [Inventario en Valor], sum(StockCM * 0) AS [Venta Total en Valor Costo] , t1.AVGPRICE AS Costo 
FROM GSP.dbo.Gsp_SboKayserDetalle t0 INNER JOIN gsp.dbo.OITM t1 on t1.ITEMCODE=t0.SKU 
where U_GSP_CADATA>=DATEADD(mm,-1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) AND U_GSP_CADATA<DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA, t1.AVGPRICE



------ CONSULTAS A 13 PARA OMNI ------


select P.ItemCode, P.PriceList, P.Price as precio
from [Stock].[dbo].[Kayser_ITM1] as P 
where P.Price IS not NULL AND  P.Price!=0 --and P.PriceList=12
order by PriceList



SELECT top 100 S.ItemCode as sku, ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 
from [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S


select ItemCode, precio_detalle, precio_promotora


------------################################################################################################################################### -------------------------------
------------##############################################       C O N S U L T A S   F I N A L E S      ####################################### -------------------------------
------------################################################################################################################################### -------------------------------

--- Ventas �ltimos 7 d�as:
SELECT top 10 SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,105) AS [Fecha Inicio], 
CONVERT(VARCHAR(10),U_GSP_CADATA,105) AS [Fecha termino], CAST(sum(VentaU) AS INT) AS [Venta total unidades], CAST(sum(NetoU) AS INT) AS [Venta Total en Valor], 
CAST(sum(StockCM * 0) AS INT) AS [Inventario en unidades], CAST(sum(StockCM * 0) AS INT) AS [Inventario en Valor], CAST(sum(VentaU * t1.AVGPRICE) AS INT) AS [Venta Total en Valor Costo] , 
CAST(ROUND(t1.AVGPRICE,0) AS INT) AS Costo 
FROM GSP.dbo.Gsp_SboKayserDetalle t0 INNER JOIN gsp.dbo.OITM t1 on t1.ITEMCODE=t0.SKU 
where U_GSP_CADATA>=CONVERT(date, GETDATE() - 7, 102) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA, t1.AVGPRICE

--- Ventas Mensuales:
SELECT top 10 SKU AS [Codigo Producto], Art AS [Descripcion Producto], Almacen AS [Codigo Local], WhsName AS [Descripcion Local], CONVERT(VARCHAR(10),U_GSP_CADATA,105) AS [Fecha Inicio], 
CONVERT(VARCHAR(10),U_GSP_CADATA,105) AS [Fecha termino], CAST(sum(VentaU) AS INT) AS [Venta total unidades], CAST(sum(NetoU) AS INT) AS [Venta Total en Valor], CAST(sum(StockCM * 0) AS INT) AS [Inventario en unidades], 
CAST(sum(StockCM * 0) AS INT) AS [Inventario en Valor], CAST(sum(VentaU * t1.AVGPRICE) AS INT) AS [Venta Total en Valor Costo] , CAST(ROUND(t1.AVGPRICE,0) AS INT) AS Costo 
FROM GSP.dbo.Gsp_SboKayserDetalle t0 INNER JOIN gsp.dbo.OITM t1 on t1.ITEMCODE=t0.SKU 
where U_GSP_CADATA>=DATEADD(mm,-1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) AND U_GSP_CADATA<DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) group by SKU,Art,Almacen,WhsName,U_GSP_CADATA, t1.AVGPRICE

--- STOCK Casa Matriz:
select t0.IdArticulo AS [Codigo Producto], t0.IdArticulo AS [Descripcion Producto], t0.idalmacen AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,GETDATE()-1,105) AS [Fecha Inicio], 
convert(varchar,GETDATE()-1,105) AS [Fecha termino], CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta total unidades], 
CAST(SUM(t0.Cantidad) as int) * 0  AS [Venta Total en Valor], CAST(SUM(t0.Cantidad) AS int) AS [Inventario en unidades], CAST(SUM(t0.Cantidad)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], 
CAST(SUM(t0.Cantidad) as int) * 0 AS [Venta Total en Valor Costo] 
from [192.168.0.17].[WMSTEK_KAYSER].[dbo].[Existencia]   as t0 inner join [192.168.0.17].[WMSTEK_KAYSER].[dbo].[ubicacion] as t1 on t0.IdUbicacion=t1.IdUbicacion 
inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.idalmacen COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.idarticulo=t2.ItemCode COLLATE Modern_Spanish_CS_AS 
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY t0.IdArticulo, t0.idalmacen, t3.WhsName

--- STOCK TIENDAS:
select t0.itemcode AS [Codigo Producto], t0.itemcode AS [Descripcion Producto], t0.whscode AS [Codigo Local], t3.WhsName AS [Descripcion Local], convert(varchar,GETDATE()-1,105) AS [Fecha Inicio], 
convert(varchar,GETDATE()-1,105) AS [Fecha termino], CAST(SUM(t0.onhand) as int) * 0 AS [Venta total unidades], CAST(SUM(t0.onhand) as int) * 0  AS [Venta Total en Valor], 
CAST(SUM(t0.onhand) AS int) AS [Inventario en unidades], CAST(SUM(t0.onhand)  *  max(t2.AvgPrice) as int) AS [Inventario en Valor], CAST(SUM(t0.onhand) as int) * 0 AS [Venta Total en Valor Costo] 
from Stock.dbo.Kayser_OITW as t0 inner join Stock.dbo.Kayser_OWHS as t3 on t3.WhsCode=t0.WhsCode COLLATE Modern_Spanish_CS_AS inner join Stock.dbo.Kayser_OITM as t2 on t0.ItemCode=t2.ItemCode  COLLATE Modern_Spanish_CS_AS 
where t3.U_GSP_SENDTPV='Y' GROUP BY t0.ItemCode, t0.WhsCode, t3.WhsName  ORDER BY t0.ItemCode