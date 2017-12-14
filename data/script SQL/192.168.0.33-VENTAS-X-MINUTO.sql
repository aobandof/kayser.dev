-- TABLAS Y VISTA DATABASE 192.168.0.33 PARA OBTENER LAS VENTAS AL MINUTO AL DETALLE Y PROMOTORAS
----------------------------------------------------------------------------------------------------
use SBO_KAYSER
select * from OWHS -- TABLA GENERAL DE DETALLE DE VENTAS
SELECT * FROM MM_KAYSER_VentaMinuto -- VISTA PARA VENTAS POR MINUTO AL DETALLE
select * from MM_KAYSER_VentaMinutoPromotoras -- VISTA PARA VENTAS POR MINUTO DE PROMOTORAS

-- VENTAS AL DETALLE POR MINUTO
SELECT A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct as total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC

-- VENTAS PROMOTORAS AL MINUTO PROMOTORAS
SELECT A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct AS total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras as A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC


----- OTRAS CONSULTAS ------
select * from MM_ClienteKAYSER -- cliente kayser que compra a precios de promotora pero no es promotora. esta consulta muestra las compras de este cliente en los ultimos 3 dias




---------------------------------------------------------
---- NO LA USAMOS YA QUE ESTAN EN OTRAS BASE DE DATOS ----
-- ventas mensuales promotoras 192.168.0.13
USE GSP
SELECT * FROM MM_VentaPROMOTORAMes ORDER BY Bruto -- 63 filas
Select sum(Bruto) from MM_VentaPROMOTORAMes -- 196.569.033

----------------------------------------------



-- TABLAS Y VISTA DATABASE 192.168.0.13 QUE ALMACENAN INFORMACION PARA OBTENER LAS VENTAS HASTA EL DIA ANTERIOR (SIRVE PARA VENTAS ACUMULADAS)
------------------------------------------------------------------------------------------------------------------------------------------------
select top 200 * from [GSP].[dbo].[Gsp_SboKayserResumen]  -- TABLA QUE SE ALIMENTA TODAS LAS MAÑANAS DE SAP


-- CONSULTA QUE OBTIENE LA VENTA DE UN DÍA ESPECIFICO HASTA LA MISMA HORA DE LA CONSULTA (es decir, si hoy son las 15:25:59 horas, se consultara la venta de un DIA X hasta esta misma hora)
SELECT bodega as tienda,CAST(SUM(Total) AS INT),Fecha AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '2016' AND MONTH(Fecha) = '10' AND DAY(Fecha) = '24' group by Almacen, Bodega, Fecha
-- CONSULTA MAS OPTIMIZADA
SELECT bodega as tienda,CAST(SUM(Total) AS INT) AS total, Fecha  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-24',20) group by Almacen, Bodega, Fecha
-- misma consulta para PHP
SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-24', 20)  group by Almacen, Bodega

SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-24', 20) AND fecha<=CONVERT(datetime, '2016-10-24 13:22:00', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-25', 20) AND fecha<=CONVERT(datetime, '2016-10-25 14:11:47', 20) group by Almacen, Bodega

SELECT bodega as cod_tienda, almacen, CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) /*AND fecha<=CONVERT(datetime, '2016-10-26 12:24:41', 20)*/ group by Almacen, Bodega
SELECT Fecha, bodega as cod_tienda, almacen, CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha>=CONVERT(datetime, '2016-10-26 00:00:00', 20) AND fecha<=CONVERT(datetime, '2016-10-26 12:24:41', 20) group by Almacen, Bodega, Fecha
SELECT Fecha,Horas,bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '12:50:00', 20) group by Almacen, Bodega,Fecha,Horas
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha>=CONVERT(datetime, '2017-10-24 00:00:00', 20) AND fecha<=CONVERT(datetime, '2017-10-24 12:54:41', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha>=CONVERT(datetime, '2017-10-25 00:00:00', 20) AND fecha<=CONVERT(datetime, '2017-10-25 17:50:56', 20) group by Almacen, Bodega

SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2017-10-17', 20) AND Horas<=CONVERT(datetime, '14:42:40', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2017-10-17', 20) AND Horas<=CONVERT(datetime, '14:45:38', 20) group by Almacen, Bodega

-- CONSULTA QUE OBTIENE LAS VENTAS ACUMULADAS DEL MES SOLO DE LAS PROMOTORAS
SELECT bodega as tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '2017' AND MONTH(Fecha) = '10'  AND [Lista de Precios]='PROMOTORA CKL' group by bodega

-- CONSULTA QUE OBTIENE LA VENTA TOTAL ACUMULADA DEL MES HASTA EL DIA ANTERIOR
SELECT bodega as tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '2016' AND MONTH(Fecha) = '10' group by bodega





SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '16:58:24', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2017-10-26', 20) AND Horas<=CONVERT(datetime, '16:58:33', 20) group by Almacen, Bodega ORDER BY total DESC

SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '17:02:05', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-6', 20) AND Horas<=CONVERT(datetime, '16:58:33', 20) group by Almacen, Bodega ORDER BY total DESC

SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '17:02:05', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '17:02:05', 20) group by Almacen, Bodega ORDER BY total DESC

SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '17:45:21', 20) group by Almacen, Bodega
SELECT bodega as cod_tienda, Almacen as tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2016-10-26', 20) AND Horas<=CONVERT(datetime, '17:45:34', 20) group by Almacen, Bodega ORDER BY total DESC


SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '2017-10-30', 20) AND Horas<=CONVERT(datetime, '11:26:12', 20) group by Almacen, Bodega ORDER BY total DESC


SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '2016' AND MONTH(Fecha) = '11'  AND [Lista de Precios]='PROMOTORA CKL' group by bodega

select top 200 * from [GSP].[dbo].[Gsp_SboKayserResumen] WHERE Almacen='RENCA CASA MATRIZ' AND Dia=13-- AND Horas>'16:00:00'

SELECT name, collation_name FROM sys.databases;