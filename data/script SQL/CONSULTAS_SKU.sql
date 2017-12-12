/***********************************************************************************************************************
*****************************************************   192.168.0.13   *************************************************
************************************************************************************************************************/

-- BDX: Stock:
-- CONSULTAS PARA OBTENER LOS PRECIOS de DETALLE (PriceList=15) y PROMOTORA (PriceList=17)
SELECT  ItemCode, CAST(ROUND((Price*1.19),0) AS int) as Precio FROM Kayser_ITM1  where PriceList=15 AND ROUND((Price*1.19),0) IS NOT NULL ORDER BY ItemCode ASC
SELECT	ItemCode, CAST(ROUND((Price*1.19),0) AS int) AS Precio FROM Kayser_ITM1  where PriceList=17 AND ROUND((Price*1.19),0) IS NOT NULL ORDER BY ItemCode ASC

-- CONSULTA PARA OBTENER CODIGO Y NOMBRE DE TIENDAS HABILITADAS 
SELECT WhsCode,WhsName from Kayser_OWHS where (U_GSP_SENDTPV='Y') order by WhsName








/***********************************************************************************************************************
*****************************************************   192.168.0.17   *************************************************
************************************************************************************************************************/

-- BD: 


-- Consulta para los SKUs Disponibles:
select IdArticulo, CAST(SUM(Cantidad) AS int) as Cant from Existencia
where idAlmacen = '001' AND IdUbicacion LIKE '01%' AND IdArticulo LIKE '10.09%'
GROUP BY IdArticulo
ORDER BY IdArticulo

-- Consulta para los SKUs Totales, menos los Fallados
select IdArticulo, CAST(SUM(Cantidad) AS int) as Cant from Existencia
where idAlmacen = '001' AND IdUbicacion NOT LIKE 'FALLA%' AND IdArticulo LIKE '10.09%'
GROUP BY IdArticulo
ORDER BY IdArticulo









