
/*******************************************************************************************************************/
-- 192.168.0.33 / database: SBO_KAYSER 
 select * from MM_VRWEB -- DESCONTINUADO -- VISTA para obtener el STOCK de casa Matriz, Retorna SI o NO tiene stock
 -- por ahora no consultaremos al SAP, es decir las consultas a continuacion no se usará, sen su lugar consultaremos a KOGNOS
 select * from OPLN -- MUESTRA LA  LISTA CON LOS PRECIOS A NUESTROS DISTINTOS CLIENTES
-- LISTNUM = 15 // PRECIOS PARA CLIENTES CONVENCIONALES QUE COMPRAN EN TIENDAS O EN WEB
-- LISTNUM = 17 // PRECIOS PARA PROMOTORAS
-- PRice * 1.19 redonedado
select * from OPLN WHERE ListNum=15
select * from OPLN WHERE ListNum=17
select * from ITM1 where PriceList=15
select ItemCode,(PRice*1.19),ROUND((Price*1.19),0) from ITM1 where PriceList=15
select ItemCode,ROUND((Price*1.19),0) as precio from ITM1 where PriceList=15  GROUP BY ROUND((Price*1.19),0)-- AND ItemCode='S9425-AZU-14'
select ItemCode,ROUND((Price*1.19),0) as precio from ITM1 where PriceList=15 and ROUND((Price*1.19),0)<500 ORDER BY ROUND((Price*1.19),0) DESC
select ItemCode,ROUND((Price*1.19),0) as precio from ITM1 where PriceList=15 AND ItemCode='S7434-NAR-6' AND ROUND((Price*1.19),0)>300


/******************************************************************************************************************/
-- 192.168.0.17 BDX WMSTEK_KAYSER_INTERFAZ
select * from MM_StockVrWeb -- DESCONTINUADO -- vista para obtener el STOCK de casa matriz en cantidad

select * from Existencia -- tabla que muestra la cantidades de SKUS

select IdArticulo, CAST(SUM(Cantidad)-30 AS int) as Cant from Existencia
where idAlmacen = '001' AND IdUbicacion LIKE '01%'
GROUP BY IdArticulo HAVING SUM(Cantidad)>30
ORDER BY IdArticulo
-- tener presente que el cambio a renca idAlmacen = '01'
-- vALORES DE COLUMNA idUbicacion:
	-- * DISPONIBLES: Todos aquellos que comienzan con '01'
	-- * ANDEN1: Aquellos que estan en Caja pero el camion, recien llegados o listos para irse.
	-- * PTL, DISTRIBUCION COLGADOS, AQUELLOS QUE ESTAN EN BODEGA PENDIENTES A UN ENVÍO O ALMACENAMIENTO
	--

/****************************************************************************************************************************************/
/***** consulta a 192.168.0.13 BD: Stock ****/
select ItemCode, U_tipoarticulo from Kayser_OITM ORDER BY U_tipoarticulo desc
select ItemCode, U_tipoarticulo from Kayser_OITM where U_tipoarticulo='L' ORDER BY ItemCode -- PARA OBTENER LOS ARTICULOS A REPORTAR A WEB

/*select ItemCode,
CASE WHEN PRICELIST =15 THEN ROUND((Price*1.19),0) END as DETALLE,
CASE WHEN PRICELIST =17 THEN ROUND((Price*1.19),0) END as PROMOTORA
	from Kayser_ITM1  where PriceList IN ('15','17')*/ -- Aca intentamos hacer que seleccione los 24mil y tantos registros con las columnas itemCode, precio DETALLE y precio PROOMOTORAS

-- CONSULTAS PARA OBTENER LOS PRECIOS
SELECT  ItemCode, CAST(ROUND((Price*1.19),0) AS int) as DETALLE FROM Kayser_ITM1  where PriceList=15 AND ROUND((Price*1.19),0) IS NOT NULL and ROUND((Price*1.19),0)>100 ORDER BY ROUND((Price*1.19),0) ASC
SELECT	ItemCode, CAST(ROUND((Price*1.19),0) AS int) AS PROMOTORAS FROM Kayser_ITM1  where PriceList=17 AND ROUND((Price*1.19),0) IS NOT NULL and ROUND((Price*1.19),0)>100 ORDER BY ROUND((Price*1.19),0) ASC



select IdArticulo, CAST(SUM(Cantidad)-30 AS int) as Cant from Existencia where idAlmacen = '01' AND IdUbicacion LIKE '01%' GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo

select ItemCode from Kayser_OITM where U_tipoarticulo='L' ORDER BY ItemCode

-----------------------------------------------------------------------------------------------------------------
-------------------------     PRUEBAS CON CONSULTAS EN EL 17    -------------------------------------------------
-----------------------------------------------------------------------------------------------------------------
select IdArticulo, CAST(SUM(Cantidad)-30 AS int) as Cant from Existencia where idAlmacen = '01' AND IdUbicacion LIKE '01%' GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo

SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'Existencia'

--- CONSULTA ABEL ---
select IdArticulo, CAST(SUM(Cantidad)-30 AS int) as Cant
from Existencia
where idAlmacen = '01' AND IdUbicacion LIKE '01%' AND (cast(SUBSTRING(IdUbicacion,3,3) as int)<135  OR cast(SUBSTRING(IdUbicacion,3,3) as int)>135) AND cast(SUBSTRING(IdUbicacion,3,3) as int)!=580
GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo

--- CONSULTA MARIO ---
select t0.IdArticulo, CAST(SUM(t0.Cantidad)-30 AS int) as Cant
from   Existencia as t0 inner join
             Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo



----------------------------------
----------------------------------
select t0.IdArticulo, CAST(SUM(t0.Cantidad) AS int) as Cant 
from   Existencia as t0 inner join
             Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion
where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
GROUP BY IdArticulo ORDER BY IdArticulo



