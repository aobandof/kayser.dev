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
select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad)-30 AS int) as Cantidad from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion where  t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY IdArticulo,Cantidad HAVING SUM(Cantidad)>30 ORDER BY IdArticulo



-- TABLAS EN WMS CON EL PEDIDO EXPRESS DE LA VENTA OMNI
SELECT * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPacking] where IdDocSalida='0000000011'
SELECT * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPackingDetalle] where IdDocSalida='0000000011'
 



--- CONSULTAS A OTROS SERVDORES ----
-----------------------------------

-- Consulta de Información de SKU
SELECT top 100 S.ItemCode as sku, ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 
from [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S

-- Consultas de Precios Detalle y Promotoras de SKUs
select P.ItemCode as sku, P.Price as precio 
from [192.168.0.13].[Stock].[dbo].[Kayser_ITM1] as P 
where P.Price IS not NULL AND  P.Price!=0  AND P.PriceList in(10,12)
