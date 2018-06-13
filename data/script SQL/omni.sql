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


----------------------------------------------------------------
----- TABLAS EN WMS CON EL PEDIDO EXPRESS DE LA VENTA OMNI -----
----------------------------------------------------------------

SELECT  * FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[ConfirmacionPacking] WHERE TIPO = 'TRF' ORDER BY IDDOCSALIDA
SELECT * FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[ConfirmacionPackingDetalle] where IdDocSalida='0000000011'

SELECT  * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPacking] WHERE TIPO = 'TRF' ORDER BY IDDOCSALIDA -- where IdDocSalida='0000000011' 
SELECT * FROM [WMSTEK_KAYSER].[dbo].[ConfirmacionPackingDetalle] where IdDocSalida='0000000011'
 
exec sp_columns ConfirmacionPacking
exec sp_help ConfirmacionPacking

SELECT DATA_TYPE, 
FROM INFORMATION_SCHEMA.COLUMNS
WHERE 
     TABLE_NAME = 'ConfirmacionPacking' AND 
     COLUMN_NAME = 'idDocSalida'


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


--------------------------------------------------------
-------- PROCEDIMIENTO ALMACENADO PARA SELECT SKU ------
--------------------------------------------------------
--ALTER PROC SP_OMNI_select_skus
--CREATE PROC SP_OMNI_select_skus
@input as VARCHAR(30)
AS
SELECT	S.ItemCode as sku, S.ItemName as descripcion, S.CodeBars as barcode, S.U_APOLLO_SEG2 as color, S.U_APOLLO_SSEG3 as talla, CAST(ROUND((PD.Price*1.19),0) AS INT) as precio_detalle, 
		CAST(ROUND((PP.Price*1.19),0) AS INT) as precio_promotora,
		CASE 
			WHEN C.Cantidad>30 THEN C.Cantidad-30 ELSE 0
		END AS cantidad	
FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] as S 
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=12) AS PD ON PD.ItemCode = S.ItemCode
INNER JOIN (SELECT ItemCode, Price FROM [192.168.0.13].[Stock].[dbo].[Kayser_ITM1]   WHERE PriceList=16) AS PP ON PP.ItemCode = S.ItemCode
INNER JOIN (
			select t0.IdArticulo as sku, CAST(SUM(t0.Cantidad) AS int) as Cantidad 
			from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion 
			where  t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2')
			GROUP BY IdArticulo
			) AS C ON S.ItemCode = c.sku COLLATE SQL_Latin1_General_CP1_CI_AS
WHERE S.U_APOLLO_SEG1 IN (SELECT U_APOLLO_SEG1 FROM [192.168.0.13].[Stock].[dbo].[Kayser_OITM] WHERE CodeBars = @input OR U_APOLLO_SEG1 = @input)
ORDER BY C.Cantidad DESC
--------------------------------------------------------
EXEC SP_OMNI_select_skus '7800000179859'


-----------------------------------------------------------------------------
-- CONSULTA AL KOGNOS POR LOS NOMBRES DE LAS BASE DE DATOS EN CADA TIENDA ---
------------------------------------------------------------------------------

SELECT * FROM	[192.168.0.13].[Stock].[dbo].[kayser_key] where nom_sql LIKE '%\SQLEXPRESS' order by n_local
 

----------------------------------------------------------------
----- TABLAS EN WMS CON EL PEDIDO EXPRESS DE LA VENTA OMNI -----
----------------------------------------------------------------
SELECT  * FROM [WMSTEK_KAYSER_INTERFAZ].dbo.DocumentoSalida where YEAR(FechaEmision)=2018 AND MONTH(FechaEmision)=6
SELECT * FROM [WMSTEK_KAYSER_INTERFAZ].dbo.DetalleSalida WHERE idDocSalida='32805'
SP_COLUMNS DocumentoSalida
SP_HELP DocumentoSalida
----------------------------------------------------------------------------------------------
------------ STORE PROCEDURE PARA INSERTAR TABLAS RELACIONADAS CON EL PEDIDO OMNI ------------
----------------------------------------------------------------------------------------------
CREATE PROCEDURE SP_OMNI_guardar_pedido (
	@codigo_almacen VARCHAR(10), --será 001, pero es recomendable pasarlo como parámetro por si algun momento cambia
	@codigo_pedido VARCHAR(20), --será el correlativo del codigo pedido_omni, con la sintaxis: 'OMNI000000000000001'
	@cli_rut VARCHAR(50),
	@doc_fecha DATETIME,
	@fecha_limite DATETIME,	--@doc_fecha + x dias (dependiendo del plazo maximo de entrega establecido por la empresa)
	@tie_codigo VARCHAR(20),
	@tie_nombre VARCHAR(50),
	@tie_region VARCHAR(15),
	@tie_direccion VARCHAR(100) )
AS
BEGIN
	--INSERT INTO [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida] VALUES ( 
	INSERT INTO [OMNI_KAYSER].[dbo].[DocumentoSalida] VALUES ( 
		@codigo_almacen,'KAYS',@codigo_pedido,'PEDIDO_OMNI',@cli_rut,'TRF',@doc_fecha,'',@fecha_limite,@tie_codigo,
		@tie_codigo,@tie_nombre,@tie_region,@tie_direccion,'','','C','',''
	)
END

INSERT INTO [OMNI_KAYSER].[dbo].[DocumentoSalida]  (
	IdAlmacen,IdOwner,IdDocSalida,NroReferencia,NroOrdenCliente,Tipo,FechaEmision,FechaCompromiso,FechaExpiracion,AlmacenDestino,IdCliente,IdSucursal,EnviarPor,IdKit,Cantidad,Prioridad,
	Observaciones,FechaCreacion,Factura,FechaAnulacion,AnuladoPor,XD,BO,EstadoInterfaz,FechaCreacionERP,FechaModificacionERP,FechaLecturaWMS )
VALUES (
      '01','KAYS','OMNI0000000000000001','PEDIDO_OMNI','26082384-1','TRF','2018-05-10 17:22:19','','2018-05-12 05:22:19','177-RENCA',
      '177-RENCA','santiago','','',0,0,'','2018-05-10 17:22:19','7223',
      '','','','','C','','','')

INSERT INTO [OMNI_KAYSER].dbo.DocumentoSalida VALUES (
      '01','KAYS','OMNI000001','PEDIDO_OMNI','24215707-9','TRF','','','','177',
      '177-RENCA','santiago','miraflores 8770','','',0,0,'','','7222',
      '','','','','','C','','','')

select * from [OMNI_KAYSER].[dbo].[DocumentoSalida]
select * from [OMNI_KAYSER].[dbo].[DetalleSalida]
EXEC SP_HELP DocumentoSalida
EXEC SP_HELP DocumentoSalida
SELECT TOP 5 * FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida]


----------------------------------------------------------------
------ STORE PROCEDURE PARA OBTENER EL ULTIMO COD_PEDIDO -------
----------------------------------------------------------------
-- recordar que el formato es : 'OMNI000000000000001' ----------
----------------------------------------------------------------
ALTER PROCEDURE SP_OMNI_obtener_correlativo_pedido 
--CREATE PROCEDURE SP_OMNI_obtener_correlativo_pedido 
AS
BEGIN
	IF (SELECT COUNT(*) FROM [OMNI_KAYSER].dbo.DocumentoSalida WHERE idDocSalida LIKE 'OMNI%') = 0  
		SELECT 'OMNI0000000000000001' as codigo_pedido 
	ELSE 
		SELECT CONCAT('OMNI',RIGHT(CONCAT('000000000000000' , CONVERT(INT,SUBSTRING(MAX(idDocSalida),5,16)) + 1), 16)) as codigo_pedido 
		FROM [OMNI_KAYSER].dbo.DocumentoSalida WHERE idDocSalida LIKE 'OMNI%'
END

EXEC SP_OMNI_obtener_correlativo_pedido 

-- OMNI0000000000000001