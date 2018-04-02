-- consulta a la tabla existencia, para ver campos
select * from   Existencia as t0

-- CONSULTA GENERAL STOCK EN CASA MATRIZ CONSIDERANDO LOCALIZACIÓN CORRECTA, Y MOSTRANDO AQUELLOS STOCKS MAYORES A 30
select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad)-30 AS int) as cantidad  from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion where  t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo

-- CONSULTA POR FILTRO DE CANTIDAD PARA CADA ARTICULO : 
select t0.IdArticulo, CAST(SUM(t0.Cantidad)-30 AS int) as Cant from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion where t0.IdArticulo LIKE '10.034-%' AND t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo

-- CONSULTA GENERAL SIN FILTRO DE CANTIDAD
select t0.IdArticulo,  CAST(SUM(t0.Cantidad) AS int) as cant from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY IdArticulo ORDER BY IdArticulo

-- CONSULTA POR SKU O SUB CADENA
select t0.IdArticulo,  CAST(SUM(t0.Cantidad) AS int) as Cant 
from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion 
where t0.IdArticulo LIKE '10.034-%' and t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') 
GROUP BY IdArticulo ORDER BY IdArticulo

-- CONSULTA PARA BUSQUEDA DE SKU COMPLETO:
select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad)-30 AS int) as Cantidad 
from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion 
where  t0.IdArticulo LIKE '10.034%' t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY IdArticulo,Cantidad HAVING SUM(Cantidad)>30 ORDER BY IdArticulo



-- TABLAS EN WMS CON EL PEDIDO EXPRESS DE LA VENTA OMNI
SELECT * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPacking] where IdDocSalida='0000000011'
SELECT * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPackingDetalle] where IdDocSalida='0000000011'
 



--- CONSULTAS A OTROS SERVDORES ----
-----------------------------------

-- Consulta de Información de SKU
SELECT top 100 S.ItemCode as sku, ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 as talla 
from [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S

SELECT TOP 10 ItemCode,U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE CodeBars = ''

-- Consultas de Precios Detalle y Promotoras de SKUs
select ItemCode as sku, Price, PriceList 
from [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]  
where Price IS not NULL AND  Price!=0  /*AND P.PriceList = '12'*/
ORDER BY P.ItemCode

-------------------------------------------------------------------------------------------------------   


-- STORE PROCEDURE FOR SELECT SKU (details with precio_detalle and precio_promotoras) ---
CREATE PROC SP_OMNI_PRUEBA_select_skus
@input as VARCHAR(30)
AS
SELECT S.ItemCode as sku, S.ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 
FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S
WHERE S.ItemCode LIKE @input+'%'

EXEC SP_OMNI_PRUEBA_select_skus '10.034'


ALTER PROC SP_OMNI_select_skus
@input as VARCHAR(30)
AS
SELECT S.ItemCode as sku, S.ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 
FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S
WHERE S.ItemCode LIKE @input+'%'

DROP PROC SP_OMNI_select_skus

EXEC SP_OMNI_select_skus '10.034'
	--CASE WHEN P.PriceList=10 Then P.Price, 
	--CASE WHEN P.PriceList=12  AND P.ItemCode = S.ItemCode Then P.Price 

---- CONSULTA PARA SELECT SKU ----
SELECT	S.ItemCode as sku, S.ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 as talla, PD.Price as precio_detalle, 
		PP.Price as precio_promotora, C.Cantidad
FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S 
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=12) AS PD ON PD.ItemCode = S.ItemCode
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=16) AS PP ON PP.ItemCode = S.ItemCode
INNER JOIN (
			select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad)-30 AS int) as Cantidad 
			from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion 
			where  t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
			GROUP BY IdArticulo HAVING SUM(Cantidad)>30
			) AS C ON S.ItemCode = c.sku COLLATE SQL_Latin1_General_CP1_CI_AS
WHERE S.ItemCode LIKE '7808717908540%' OR S.U_APOLLO_SEG1 = (SELECT U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE CodeBars = '7808717908540')
ORDER BY S.ItemCode

--------------------------------------------------------
SELECT ItemCode, U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE U_APOLLO_SEG1=(SELECT U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE CodeBars = '7808717908540');
----------------------------------------------------------

---- PROCEDIMIENTO ALMACENADO PARA SELECT SKU ----
ALTER PROC SP_OMNI_select_skus
--CREATE PROC SP_OMNI_select_skus
@input as VARCHAR(30)
AS
SELECT	S.ItemCode as sku, S.ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 as talla, PD.Price as precio_detalle, 
		PP.Price as precio_promotora, C.Cantidad
FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S 
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=12) AS PD ON PD.ItemCode = S.ItemCode
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=16) AS PP ON PP.ItemCode = S.ItemCode
INNER JOIN (
			select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad)-30 AS int) as Cantidad 
			from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion 
			where  t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
			GROUP BY IdArticulo HAVING SUM(Cantidad)>30
			) AS C ON S.ItemCode = c.sku COLLATE SQL_Latin1_General_CP1_CI_AS
WHERE S.ItemCode LIKE @input+'%' OR S.CodeBars=@input
--WHERE S.ItemCode LIKE @input+'%' OR S.U_APOLLO_SEG1 = (SELECT U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE CodeBars = @input)
ORDER BY S.ItemCode
--------------------------------------------------------

EXEC SP_OMNI_select_skus '10.03'


