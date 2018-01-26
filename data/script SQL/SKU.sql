--- PARA SKU
SELECT TOP 10 * FROM Kayser_OITM	-- SKUs
SELECT TOP 10 * FROM Kayser_OITB	-- GRUPOS de ARTICULOS
SELECT U_SubGrupo1 FROM Kayser_OITM WHERE U_SubGrupo1!='null' GROUP BY U_SubGrupo1
SELECT U_SubGrupo1 FROM Kayser_OITM GROUP BY U_SubGrupo1
SELECT * FROM Kayser_SEASON	-- SUB SUB GRUPO DE ARTICULOS

SELECT TOP 10 * FROM Kayser_SEASON	-- PRENDA
SELECT TOP 10 * FROM Kayser_DIV	-- CATEGORIA

--- TABLAS EN EL 33
SELECT TOP 10 * FROM OITM			-- SKU

select U_APOLLO_SEASON FROM OITM where ItemCode='13.5014-ROS-M'

select * from OITM where CodeBars = '7800010002604'

SELECT U_APOLLO_SEG1 FROM OITM -- ARTICULOS
SELECT * FROM OITB						-- DPTO
SELECT ItmsGrpCod as code_dpto, ItmsGrpNam as name_dpto FROM OITB	-- DPTO	
SELECT ItmsGrpCod as code_dpto, ItmsGrpNam as name_dpto FROM OITB WHERE ItmsGrpCod != 106 AND ItmsGrpCod != 108 AND ItmsGrpCod != 127 AND ItmsGrpCod != 128 AND ItmsGrpCod != 129 AND ItmsGrpCod != 130

 SELECT ItmsGrpCod as code_dpto, UPPER(ItmsGrpNam) as name_dpto FROM OITB WHERE ItmsGrpCod != 106 AND ItmsGrpCod != 108 AND ItmsGrpCod != 127 AND ItmsGrpCod != 128 AND ItmsGrpCod != 129 AND ItmsGrpCod != 130 AND ItmsGrpCod != 140 AND ItmsGrpCod != 121 AND ItmsGrpCod != 135 AND ItmsGrpNam NOT LIKE '01-INS%' AND ItmsGrpNam NOT LIKE 'INSUMOS%'

SELECT * FROM [dbo].[@APOLLO_SEASON]	-- PRENDA
SELECT * FROM [dbo].[@APOLLO_DIV]		-- CATEGORIA

SELECT Code,Name from [@APOLLO_SEASON] ORDER BY Name
SELECT Code,Name from [@APOLLO_SEASON] ORDER BY Name

-- PARA ARTICULOS
select U_APOLLO_SEG1, ItemCode,ItemName,FrgnName, CodeBars, U_APOLLO_SEG2,U_APOLLO_SSEG3, U_APOLLO_SEASON, U_Material, U_SubGrupo1 from Kayser_OITM order by U_APOLLO_SEG1
select S.U_APOLLO_SEG1, COUNT(distinct S.ItemCode ) as cantidad_SKU from Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) GROUP BY S.ItemCode ORDER BY S.U_APOLLO_SEG1 -- Para ver la cantidad de SKU por articulo
select S.U_APOLLO_SEG1, COUNT(*) as cantidad_SKU, S.ItemName from Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL)  GROUP BY S.U_APOLLO_SEG1, S.ItemName ORDER BY U_APOLLO_SEG1-- Para ver la cantidad de SKU por articulo
select COUNT(*) as cantidad_SKU from Kayser_OITM GROUP BY U_APOLLO_SEG1

select S.U_APOLLO_SEG1 from Kayser_OITM as S  -- 23265 rows	
select distinct S.U_APOLLO_SEG1 from Kayser_OITM as S -- 3561 rows incluido el valor NULL
select distinct S.U_APOLLO_SEG1 from Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) -- 3560 rows
select S.U_APOLLO_SEG1 , COUNT(S.U_APOLLO_SEG1) as cantidad_sku from Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) GROUP BY S.U_APOLLO_SEG1 ORDER BY S.U_APOLLO_SEG1  -- 3560 ROWS
select distinct S.U_APOLLO_SEG1 , COUNT(S.U_APOLLO_SEG1) as cantidad_sku from Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL)  GROUP BY S.U_APOLLO_SEG1 ORDER BY S.U_APOLLO_SEG1  -- 3560 ROWS /lo mismo que el anteior: DISTINCT esta de mas cuando se usa GROUP BY
-- SI QUEREMOS EVITAR EL GROUP BY:
select DISTINCT S.U_APOLLO_SEG1, COUNT(S.U_APOLLO_SEG1) OVER(PARTITION BY S.U_APOLLO_SEG1) as cantidad  FROM Kayser_OITM as S  WHERE (S.U_APOLLO_SEG1 IS NOT NULL) ORDER BY S.U_APOLLO_SEG1 -- 3560 rows / sin GROUP BY

select DISTINCT S.U_APOLLO_SEG1 as codigo_art, S.ItemName as nombre FROM Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL)

select DISTINCT t.codigo_art, COUNT(t.nombre) OVER(PARTITION BY t.nombre) as can_nom_por_art
from (select DISTINCT S.U_APOLLO_SEG1 as codigo_art, S.ItemName as nombre FROM Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL)) t
order by t.codigo_art


-- los 2 sgtes select demuestran que hay codigos de articulos repetidos para denominaciones parecidas pero distintas
select DISTINCT  S.U_APOLLO_SEG1,S.ItemName FROM Kayser_OITM as S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) ORDER BY S.U_APOLLO_SEG1 -- 3889 ROWS
select DISTINCT S.U_APOLLO_SEG1, S.ItemName, COUNT(S.U_APOLLO_SEG1) OVER(PARTITION BY S.U_APOLLO_SEG1) as cantidad_sku  FROM Kayser_OITM as S  WHERE (S.U_APOLLO_SEG1 IS NOT NULL) ORDER BY  S.ItemName /*S.U_APOLLO_SEG1*/ -- 3889 ROWS / sin GROPUP BY
-- el siguiente select muestra los itemName que tienen el mismo codigo de articulo
select DISTINCT S.U_APOLLO_SEG1, S.ItemName, COUNT(S.U_APOLLO_SEG1) OVER(PARTITION BY S.ItemName) as cantidad_sku 
FROM Kayser_OITM as S
WHERE (S.U_APOLLO_SEG1 IS NOT NULL)
ORDER BY  S.ItemName /*S.U_APOLLO_SEG1*/ -- 3889 ROWS / sin GROPUP BY








--************* SELECT PARA ARTICULOS ******************
select S.U_APOLLO_SEG1 as codigo_art, S.ItemName as nombre/*, G.ItmsGrpNam as grupo, S.U_SubGrupo1  as sub_grupo, SSG.Name as sub_sub_grupo ,S.FrgnName as marca, S.U_Material*/, COUNT(*) as cantidad_sku
from Kayser_OITM AS S
/*JOIN Kayser_OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod
JOIN Kayser_SEASON AS SSG ON S.U_APOLLO_SEASON=SSG.Code*/
WHERE (S.U_APOLLO_SEG1 IS NOT NULL) /*AND (S.U_SubGrupo1 IS NOT NULL)*/
GROUP BY  S.U_APOLLO_SEG1, S.ItemName/*, G.ItmsGrpNam, S.U_SubGrupo1, SSG.Name,S.FrgnName, S.U_Material*/
ORDER BY S.U_APOLLO_SEG1 /*nombre*/
--*******************************************************



select top 50 * from Kayser_ITM1 where ItemCode like '%10.07-b%' -- PRECIOS RELACIONADA A Kayser_OITM
	-- priceList 15 = detalle sin iva
	-- priceList 17 = promotora sin iva

select s.U_APOLLO_SEG1, g.ItmsGrpNam from Kayser_OITM as s JOIN Kayser_OITB as g on s.ItmsGrpCod=g.ItmsGrpCod group by s.U_APOLLO_SEG1,g.ItmsGrpNam


--------------------------------
---- consultas para scripts ----
--------------------------------

SELECT ItmsGrpCod,ItmSGrpNam FROM Kayser_OITB											-- consulta de codigo y nombre de GRUPO
SELECT U_SubGrupo1 as VALUE, U_SubGrupo1 FROM Kayser_OITM
WHERE U_SubGrupo1!='null' GROUP BY U_SubGrupo1 ORDER BY U_SubGrupo1						-- consulta nombre de Sub grupo / NO TIENE TABLA / valor obtenido de tabla de sku/articulos
SELECT Code, Name FROM Kayser_SEASON ORDER BY Code										-- consulta de codigo y nombre de SUB SUB GRUPO
SELECT SWW FROM Kayser_OITM  WHERE SWW!='null' AND SWW!='0' GROUP BY SWW ORDER BY SWW	-- consulta la prenda que tambien sería una categorización
select * from Kayser_DIV  -- CONSULTA PARA CATEGORIA PRENDA


SELECT S.ItemCode, S.ItemName, S.U_APOLLO_SEG1, S.U_MATERIAL, S.U_APOLLO_SEG2, S.QryGroup1, S.U_Marca, S.U_EVD, S.QryGroup44, S.U_SUBGRUPO1, S.SWW, S.U_ESTILO, S.QryGroup2, S.U_APOLLO_COO, S.BWeight1
FROM Kayser_OITM AS S
-- S.ForeignName, S.BarCode
EXEC sp_columns KAYSER_OITM

--###########  OTRAS CONSULTAS ############
SELECT S.ItemCode as codigo_sku, S.U_APOLLO_SEG1 as codigo_articulo,S.ItemName as nombre, S.ItmsGrpCod as codigo_grupo, S.U_SubGrupo1  as sub_grupo, S.U_APOLLO_SEASON as codigo_sub_sub_grupo, S.FrgnName AS marca, S.U_Material as material, S.U_APOLLO_SEG2 as color,S.U_APOLLO_SSEG3 as talla, S.CodeBars as codebar
FROM  Kayser_OITM AS S
WHERE (S.U_APOLLO_SEG1 IS NULL) -- esta consulta muestra todos los SKU que no tienen codigo de articulo / 561 registros no hay que cosiderarlos en el reporte de SKU

-- OCURRENCIAS DE SKU PARA VER SI HAY CODIGOS REPETIDOS
SELECT S.ItemCode, COUNT(distinct S.ItemCode) as ocurrencia FROM Kayser_OITM AS S GROUP BY S.ItemCode ORDER BY ocurrencia
-- O
SELECT S.ItemCode, COUNT(*) as ocurrencia FROM Kayser_OITM AS S GROUP BY S.ItemCode ORDER BY ocurrencia -- TODOS TIENEN OCURRENCIA 0 // TOTAL 23329 SKU


--************* SELECT PARA SKUs *************************
SELECT S.ItemCode as codigo_sku, S.U_APOLLO_SEG1 as codigo_articulo,S.ItemName as nombre, G.ItmsGrpNam as grupo, S.U_SubGrupo1  as sub_grupo, SSG.Name as sub_sub_grupo, C.Name as cat_prenda,  S.FrgnName AS marca, S.U_Material as material, S.CodeBars as codebar, S.U_IDCopa as copa, S.U_GSP_SECTION as forma_copa
FROM Kayser_OITM AS S
JOIN Kayser_OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod
LEFT JOIN Kayser_SEASON AS SSG ON S.U_APOLLO_SEASON=SSG.Code
LEFT JOIN Kayser_DIV AS C ON S.U_APOLLO_DIV=C.Code
WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '10.%'
ORDER BY nombre
--*******************************************************
SELECT ItemCode FROM Kayser_OITM WHERE ItemCode like '10.%' ORDER BY ItemCode
SELECT ItmsGrpCod FROM Kayser_OITB WHERE ItmSGrpNam='DAMA'
SELECT U_APOLLO_COO FROM Kayser_OITM WHERE U_APOLLO_COO IS NOT NULL GROUP BY U_APOLLO_COO ORDER BY U_APOLLO_COO DESC

--**********************  ALGUNAS ESTADISTICAS ***********************************************************************************
SELECT S.ItemCode, S.ItmsGrpCod 
FROM Kayser_OITM AS S INNER JOIN Kayser_OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod -- TODOS LOS SKU TIENEN UN CODIGO SUBGRUPO ASIGNADO
	
SELECT S.ItemCode, S.U_APOLLO_SEASON
from Kayser_OITM AS S INNER JOIN Kayser_SEASON AS SSG ON S.U_APOLLO_SEASON=SSG.Code -- SOLO 21921 SKU TIENEN ASOCIADO UN SUB SUB GRUPO EXISTENTE
WHERE S.U_APOLLO_SEASON=0

select * from [dbo].[Kayser_DUN_14]


/***********  PARA EL SCRIPT ENVIO DE ARTIUCULOS A PERU *******/
--percy.luna@kayser.pe
select * from [dbo].[MM_Peru]
-- todos los dias 9:00
-- Relación de Artículos

/***********  ALGUNOS SELECT PARA VER EL COMPORTAMIENTO DE LAS TALLAS ********/
SELECT S.ItemCode as codigo_sku, S.ItemName as nombre, G.ItmsGrpNam as dpto, S.U_SubGrupo1  as sub_dpto, SSG.Code as cod_prenda, SSG.Name as prenda, S.SWW as otra_prenda, S.U_APOLLO_SEG3 AS cod_talla, U_APOLLO_SSEG3 AS talla
FROM Kayser_OITM AS S
JOIN Kayser_OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod
LEFT JOIN Kayser_SEASON AS SSG ON S.U_APOLLO_SEASON=SSG.Code
WHERE (S.U_APOLLO_SEG1 IS NOT NULL) 
ORDER BY nombre

--SELECT S.U_APOLLO_SEASON AS cod_prenda, S.U_APOLLO_SEG3 AS cod_talla
--FROM Kayser_OITM AS S
--WHERE (S.U_APOLLO_SEG1 IS NOT NULL AND S.U_APOLLO_SEASON IS NOT NULL)
--GROUP BY S.U_APOLLO_SEASON, S.U_APOLLO_SEG3
--ORDER BY S.U_APOLLO_SEASON


SELECT ItmsGrpCod FROM Kayser_OITB WHERE ItmSGrpNam='lola';

SELECT * FROM Kayser_OITB;

SELECT ItmsGrpCod FROM Kayser_OITB WHERE ItmSGrpNam='dama'

SELECT ItmsGrpCod FROM Kayser_OITB WHERE ItmSGrpNam='dama';

-- SELECT QUE ARROJA EL MAYOR CORRELATIVO DE CODIGOS DE ARTICULOS
SELECT TOP 1 CONVERT(INT,SUBSTRING( U_APOLLO_SEG1 ,CHARINDEX('.',U_APOLLO_SEG1)+1 , LEN(U_APOLLO_SEG1 ))) AS CORRELATIVO FROM Kayser_OITM 
WHERE ItemCode like '160.%' AND U_APOLLO_SEG1 IS NOT NULL
GROUP BY U_APOLLO_SEG1
ORDER BY CONVERT(INT,SUBSTRING( U_APOLLO_SEG1 ,CHARINDEX('.',U_APOLLO_SEG1)+1 , LEN(U_APOLLO_SEG1 ))) DESC

SELECT top 10 CodeBars from Kayser_OITM WHERE CodeBars like '78000%' order by  CodeBars DESC

SELECT TOP 100 SUBSTRING( itemCode ,0,CHARINDEX('-',itemCode)-1) FROM Kayser_OITM
WHERE ItemCode like '11.%' AND U_APOLLO_SEG1 IS NOT NULL
GROUP BY itemCode
ORDER BY SUBSTRING(itemCode,0,CHARINDEX('-',ItemCode)-1) DESC

SELECT TOP 1 SUBSTRING( itemCode ,0,CHARINDEX('-',itemCode)-1) FROM Kayser_OITM WHERE ItemCode like '14.%' AND U_APOLLO_SEG1 IS NOT NULL GROUP BY itemCode ORDER BY SUBSTRING(itemCode,0,CHARINDEX('-',itemCode)-1) DESC


SELECT SUBSTRING( itemCode ,LEN('14.')+1,CHARINDEX('-',itemCode)-LEN('14.')-1) FROM Kayser_OITM
WHERE ItemCode like '14.%' AND U_APOLLO_SEG1 IS NOT NULL
GROUP BY SUBSTRING( itemCode ,LEN('14.')+1,CHARINDEX('-',itemCode)-LEN('14.')-1) ORDER BY SUBSTRING( itemCode ,LEN('14.')+1,CHARINDEX('-',itemCode)-LEN('14.')-1) DESC


SELECT SUBSTRING( itemCode ,LEN('67.')+1,PATINDEX('%-%',itemCode)) FROM Kayser_OITM WHERE ItemCode like '67.%' AND U_APOLLO_SEG1 IS NOT NULL GROUP BY SUBSTRING( itemCode ,LEN('67.')+1,PATINDEX('%-%',itemCode))

SELECT top 20 itemCode, CHARINDEX('-',itemCode) from Kayser_OITM WHERE ItemCode like '67.%' AND U_APOLLO_SEG1 IS NOT NULL ORDER BY itemCode DESC

SELECT SUBSTRING( itemCode ,LEN('67.')+1,CHARINDEX('-',itemCode)-LEN('67.')-1) FROM OITM WHERE ItemCode LIKE '67.%' GROUP BY SUBSTRING( itemCode ,LEN('67.')+1,CHARINDEX('-',itemCode)-LEN('67.')-1);

SELECT SUBSTRING( itemCode ,LEN('10.')+1,CHARINDEX('-',itemCode)-LEN('10.')-1) FROM Kayser_OITM WHERE ItemCode LIKE '10.%' GROUP BY SUBSTRING( itemCode ,LEN('10.')+1,CHARINDEX('-',itemCode)-LEN('10.')-1);

SELECT SUBSTRING( itemCode ,LEN('11.')+1,CHARINDEX('-',itemCode)-LEN('11.')-1) FROM Kayser_OITM WHERE ItemCode LIKE '11.%' GROUP BY SUBSTRING( itemCode ,LEN('11.')+1,CHARINDEX('-',itemCode)-LEN('11.')-1);

SELECT SUBSTRING( itemCode ,LEN('10.')+1,CHARINDEX('-',itemCode)-LEN('10.')-1) FROM Kayser_OITM WHERE ItemCode LIKE '10.%' GROUP BY SUBSTRING( itemCode ,LEN('10.')+1,CHARINDEX('-',itemCode)-LEN('10.')-1)

SELECT SUBSTRING( itemCode ,LEN('99M')+1,CHARINDEX('-',itemCode)-LEN('99M')-1) FROM Kayser_OITM WHERE ItemCode LIKE '99M%' GROUP BY SUBSTRING( itemCode ,LEN('99M')+1,CHARINDEX('-',itemCode)-LEN('99M')-1);

SELECT TOP 1 U_APOLLO_SEG1 FROM OITM WHERE U_APOLLO_SEG1='99M1000'SELECT TOP 1 U_APOLLO_SEG1 FROM OITM WHERE U_APOLLO_SEG1='99M1000'

SELECT SUBSTRING( itemCode ,LEN('P299M')+1,CHARINDEX('-',itemCode)-LEN('P299M')-1) FROM OITM WHERE ItemCode LIKE 'P299M%' GROUP BY SUBSTRING( itemCode ,LEN('P299M')+1,CHARINDEX('-',itemCode)-LEN('P299M')-1)

SELECT top 1 CodeBars from OITM WHERE CodeBars like '780001%' order by  CodeBars DESC


select top 10 * FROM OITM

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE '63.1170%'

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE '65.1170%'

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE '50.851%'

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE '99MP283%'

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE '63.1165%'

SELECT top 1 SUBSTRING(CodeBars,0,LEN(CodeBars)) from OITM WHERE CodeBars like '780001%' order by  SUBSTRING(CodeBars,0,LEN(CodeBars)) DESC

SELECT top 1 SUBSTRING(CodeBars,0,LEN(CodeBars)) from OITM WHERE CodeBars like '780001%' order by  SUBSTRING(CodeBars,0,LEN(CodeBars)) DESC

SELECT top 1 SUBSTRING(CodeBars,0,LEN(CodeBars)) as barcode from OITM WHERE CodeBars like '780001%' order by  SUBSTRING(CodeBars,0,LEN(CodeBars)) DESC

SELECT itemCode, U_APOLLO_SEG1 from OITM where ItemCode LIKE 'S6331%'

SELECT
	S.ItemCode as sku_codigo, S.U_APOLLO_SEG1 as articulo_codigo,S.ItemName as itemname, S.ItmsGrpCod as dpto_codigo, S.U_SubGrupo1  as subdpto_name,
	S.U_APOLLO_SEASON as prenda_codigo, S.U_APOLLO_DIV as categoria_codigo,
	S.FrgnName AS marca_name, S.U_Material as material_name, S.U_APOLLO_SEG2 as color, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_S_GROUP as tcatalogo_name,
	S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name
FROM OITM AS S
/*JOIN OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod
LEFT JOIN [@APOLLO_SEASON] AS SSG ON S.U_APOLLO_SEASON=SSG.Code
LEFT JOIN [@APOLLO_DIV] C ON S.U_APOLLO_DIV=C.Code*/
WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '50.514-%'
ORDER BY nombre

SELECT TOP 100 ItemCoFROM OITM

CONSULTAS A MYSQL
----------------
SELECT S.codigo as cod_sku, S.barcode, S.color_name, S.talla_name, A.codigo as cod_articulo, A.itemname, A.dpto_name, A.subdpto_name, A.prenda_name, A.categoria_name,  A.tprenda_name,  A.tcatalogo_name, A.grupouso_name, A.caracteristica_name, A.composicion_name
FROM sku as S INNER JOIN articulo as A ON S.articulo_codigo=A.codigo
WHERE A.lista_id=3 ORDER BY S.barcode ASC



select CONVERT(U_Material, SERVERPROPERTY('Latin1_General_CS_AI')) from OITM WHERE U_Material like 'ALGODÓN'

SELECT CONVERT (varchar, SERVERPROPERTY('collation'));

SELECT U_Material from OITM where U_Material collate SQL_Latin1_General_CP1_CI_AS = 'ALGODÓN'

SELECT U_Material FROM OITM WHERE U_Material Like '%ALGODÓN%' COLLATE SQL_Latin1_General_CP850_CI_Ai

SELECT name, collation_name FROM sys.databases;  -- SQL_Latin1_General_CP850_CI_AS

SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, D.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, S.U_APOLLO_SEASON as prenda_code, P.Name , S.U_APOLLO_DIV as categoria_code,
C.Name, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name,
S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name FROM OITM AS S 
JOIN OITB AS D ON S.ItmsGrpCod=D.ItmsGrpCod
LEFT JOIN [@APOLLO_SEASON] AS P ON S.U_APOLLO_SEASON=P.Code
LEFT JOIN [@APOLLO_DIV] AS C ON S.U_APOLLO_DIV=C.Code
WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '67.1041-%'

SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, D.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, S.U_APOLLO_SEASON as prenda_code, P.Name , S.U_APOLLO_DIV as categoria_code,C.Name, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name,S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name FROM OITM AS S JOIN OITB AS D ON S.ItmsGrpCod=D.ItmsGrpCod LEFT JOIN [@APOLLO_SEASON] AS P ON S.U_APOLLO_SEASON=P.Code LEFT JOIN [@APOLLO_DIV] AS C ON S.U_APOLLO_DIV=C.Code WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '65.1170-%'


65.1170
63.1170

SELECT S.ItemCode as SKU, S.ItemName as DESCRIPCION, S.CodeBars as 'CODIGO DE BARRAS', S.U_APOLLO_SEG2 as COLOR  FROM OITM AS S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '99.M6%'

SELECT S.ItemCode as SKU, S.ItemName as DESCRIPCION, S.CodeBars as 'CODIGO DE BARRAS', S.U_APOLLO_SEG2 as COLOR  FROM OITM AS S WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.CodeBars='780000216318'



SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, D.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, S.U_APOLLO_SEASON as prenda_code, P.Name as prenda_name , S.U_APOLLO_DIV as categoria_code,C.Name as categoria_name, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name,S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name, S.U_APOLLO_SEG3 as talla_familia FROM OITM AS S JOIN OITB AS D ON S.ItmsGrpCod=D.ItmsGrpCod LEFT JOIN [@APOLLO_SEASON] AS P ON S.U_APOLLO_SEASON=P.Code LEFT JOIN [@APOLLO_DIV] AS C ON S.U_APOLLO_DIV=C.Code WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '50.514-%'