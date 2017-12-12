USE Stock

exec sp_columns Kayser_OCRD
select * from Kayser_OCRD

select CardName, U_GSP_TPVWHSCODE from Kayser_OCRD where GroupCode=6  and U_GSP_SENDTPV='Y' and U_GSP_TPVWHSCODE!='NULL'

select LicTradNum as Rut, CardName as Nombres,Cellular as Celular,Phone1 as Telefono,E_Mail as Email,Address as Direccion,County as Comuna,City as Ciudad, CONVERT(VARCHAR(24),U_GSP_BIRTHDATE) as Cumpleaños, CONVERT(DATE,U_GSP_UPDATEDATE) as Fecha_Ultima_Compra from Kayser_OCRD where GroupCode=6  and U_GSP_SENDTPV='Y'

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y')



select LicTradNum as Rut, CardName as Nombres,Cellular as Celular,Phone1 as Telefono,E_Mail as Email,Address as Direccion,County as Comuna,City as Ciudad, CONVERT(VARCHAR(24),U_GSP_BIRTHDATE) as Cumpleaños, CONVERT(DATE,U_GSP_UPDATEDATE) as Fecha_Ultima_Compra from Kayser_OCRD where GroupCode=6  and U_GSP_SENDTPV='Y'


select LicTradNum as Rut, CardName as Nombres,Cellular as Celular,Phone1 as Telefono,E_Mail as Email,Address as Direccion,County as Comuna,City as Ciudad, U_GSP_BIRTHDATE as Cumpleaños,U_GSP_UPDATEDATE as Fecha_Ultima_Compra from Kayser_OCRD where GroupCode=6  and U_GSP_SENDTPV='Y'

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,p.Phone1,p.Phone1 from Kayser_OCRD as p INNER JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' and t.WhsCode=105)

select WhsCode,WhsName from Kayser_OWHS where (U_GSP_SENDTPV='Y')

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,p.Phone1,p.Phone1 from Kayser_OCRD as p INNER JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' and t.WhsCode=105)

SELECT CONVERT(VARCHAR(10),CAST(LEFT(U_GSP_UPDATEDATE,10) AS DATE),103) FROM Kayser_OCRD
SELECT REVERSE(CONVERT(VARCHAR(10),U_GSP_UPDATEDATE,103)) FROM Kayser_OCRD

SELECT U_GSP_UPDATEDATE FROM Kayser_OCRD

SELECT CONVERT(DATE,LEFT(U_GSP_UPDATEDATE,10),113) FROM Kayser_OCRD

SELECT LEFT(U_GSP_UPDATEDATE,10) FROM Kayser_OCRD

SELECT ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p

SELECT CONVERT(VARCHAR(20),GETDATE(),106)

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p INNER JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' and p.City LIKE '%temuc%' )

select WhsCode,WhsName from Kayser_OWHS where (U_GSP_SENDTPV='Y') order by WhsName

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p INNER JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' and t.WhsCode='110')

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p INNER JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' AND ( p.City LIKE '%saa%' OR p.City LIKE '%sann%' OR p.City LIKE '%sant%' OR p.City LIKE '%stg%') ) 

execute sp_columns kayser_OWHS


--*************************************   DESCRIPCION DE TABLAS   *************************************************************************************
--*****************************************************************************************************************************************************
SELECT * FROM Kayser_OCRD -- COMPRADORAS CON ALGUNA CARACTERISTICAS (AQUI ESTAN LAS PROMOTORAS CON GroupCode=6)
	select C.CardCode,CardName,C.LicTradNum, C.U_GSP_TPVWHSCODE, G.GroupName from Kayser_OCRD as C JOIN Kayser_OCRG as G ON C.GroupCode=G.GroupCode
SELECT * FROM kayser_OWHS -- TIENDAS
SELECT * FROM kayser_OCRG -- GRUPO DE COMPRADORES (Siendo los grupos: CLIENTES, MAYORISTAS, TIENDAS, PROMOTORAS, MAYORISTAS, GRANDES TIENDAS, ETC)
SELECT * FROM Kayser_OITM	-- ARTICULOS Y SKUs
SELECT * FROM Kayser_OITB	-- GRUPOS de ARTICULOS
SELECT ItmsGrpCod,ItmSGrpNam FROM Kayser_OITB
SELECT * FROM Kayser_SEASON	-- SUB SUB GRUPO DE ARTICULOS

SELECT * FROM Kayser_OSLP	-- VENDEDORES Y OTROS
SELECT * FROM KAYSER_COMPRA -- TABLA QUE MUESTRA EL TOTAL DE COMPRAS Y PRECIO TOTAL POR ARTICULO
SELECT * FROM KAYSER_VENTAS	-- TABLA QUE MUESTRA LA CANTIDAD DE UNIDADES VENDIDAS POR MES
SELECT * FROM PFNC			-- PEDIDO FACTURA NOTA DE CREDITO 
SELECT * FROM PFNC WHERE P_Orden='9350420323'	
SELECT * FROM PFNC as P JOIN KAYSER_RDR1 AS R ON P.P_Num=R.DocEntry WHERE P.P_Num=26197
SELECT * FROM KAYSER_RDR1	-- ****** VENTAS A CLIENTES MAYORISTAS ***** (TIENE MUCHOS REGISTROS, DEMORA EN CARGAR)
SELECT V.DocEntry,V.TrgetEntry,V.ItemCode,V.Dscription,V.Quantity,V.ShipDate,V.Price,V.LineTotal,V.WhsCode,V.DocDate,V.BaseCard FROM KAYSER_RDR1 AS V
--NO CONSIDERAR:
----------------
SELECT * FROM Kayser_OPLN	-- Algunos registros relacionados con clientes (promotoras, grandes tiemdas, etc)
--**************************************************************************************************************************************************

--PEDIDO,FACTURAS, NOTAS DE CREDITO:
------------------------------------
--SELECT P.P_Num as MOVIMIENTO,C.LicTradNum AS RUT_CLIENTE,C.CardName AS CLIENTE,P.P_Orden AS ORDEN_COMPRA,'' AS NOTA_VENTA,'' AS N_FACTURA, '' AS NOTA_CREDITO,'' AS UNI_SOLICITADAS, '' AS UNI_FACTURAS, '' AS UNI_PENDIENTES, '' AS MONTO_FACTURADO, '' AS MONTO_NOTA_CREDITO
--FROM PFNC AS P JOIN KAYSER_RDR1 AS V ON P.P_Num=V.DocEntry JOIN Kayser_OCRD AS C ON V.BaseCard=c.CardCode WHERE P.P_Orden='9350420319'
SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND P_Orden LIKE '%7900420115%' ORDER BY P_Orden
SELECT * FROM PFNC WHERE P_Orden='7900420115'
SELECT P_Orden FROM PFNC GROUP BY P_Orden
SELECT * FROM PFNC
SELECT * FROM PFNC WHERE P_Orden='1234' AND P_Orden!='' ORDER BY P_Num

SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND Mes='5' AND P_Orden LIKE '%7900420%'  ORDER BY P_Orden

SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND Mes='5' AND Anio LIKE '%2017%'  ORDER BY P_Orden


--*************************************************************************************************************************************************
USE GSP
SELECT * FROM Gsp_SboKayserResumen

SELECT RUT, max(Fecha) as fech_ultima_compra FROM Gsp_SboKayserResumen GROUP BY RUT	-- Tabla que muestra compras con fechas de clientes para poder obtener la ultima compra de la promotora

--pero como necesitamos tb el total de la ultima compra, etonces la siguinte consulta lo contiene:
SELECT T2.Rut, T2.fecha_maxima, T1.Total FROM Gsp_SboKayserResumen as T1 RIGHT JOIN
(SELECT Rut,max(Fecha) as fecha_maxima from Gsp_SboKayserResumen GROUP BY Rut) T2
ON T1.Fecha=T2.fecha_maxima AND T1.RUT=T2.RUT where T2.RUT='17607874K'
-- como hay mismas promotoras con la misma fecha y muchas compras entonces hay qe sumar esas compras.
SELECT T2.Rut, T2.fecha_maxima, SUM(T1.Total) FROM Gsp_SboKayserResumen as T1 JOIN
(SELECT Rut,max(Fecha) as fecha_maxima from Gsp_SboKayserResumen GROUP BY Rut) T2
ON T1.Fecha=T2.fecha_maxima AND T1.RUT=T2.RUT
GROUP BY T2.Rut,T2.fecha_maxima
 -- de otra forma:
SELECT T1.Rut, T2.fecha_maxima, SUM(T1.Total) FROM Gsp_SboKayserResumen as T1 JOIN
(SELECT Rut,max(Fecha) as fecha_maxima from Gsp_SboKayserResumen GROUP BY Rut) T2
ON T1.Fecha=T2.fecha_maxima AND T1.RUT=T2.RUT
GROUP BY T1.Rut,T2.fecha_maxima

USE stock
SELECT * FROM Cte_Fec
exec sp_columns Cte_Fec

select ISNULL(t.WhsName,''),p.LicTradNum, p.CardName,ISNULL(p.Cellular,''),ISNULL(p.Phone1,''),ISNULL(LOWER(p.E_Mail),''),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), ISNULL(CONVERT(VARCHAR(10),UltimaCompra,103),''), ISNULL(Bruto,0)
from Kayser_OCRD as p
LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode
LEFT JOIN Cte_Fec ON P.LicTradNum=Rut
where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y')

select t.WhsName,p.LicTradNum, p.CardName,p.Cellular,p.Phone1,LOWER(p.E_Mail),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), CONVERT(VARCHAR(10),CAST(LEFT(p.U_GSP_UPDATEDATE,10) AS DATE),103) from Kayser_OCRD as p LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y')


--*************************************************************************************************************************************************

SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND P_Orden LIKE '%79004201%'  ORDER BY P_Orden

SELECT * FROM PFNC WHERE P_Orden='7900420115' ORDER BY ObjType

SELECT * FROM PFNC WHERE ObjType='17' AND P_Orden!='NULL' AND P_Orden!='' AND P_Num LIKE '%48608%'  ORDER BY P_Orden




select ISNULL(t.WhsName,''),p.LicTradNum, p.CardName,ISNULL(p.Cellular,''),ISNULL(p.Phone1,''),ISNULL(LOWER(p.E_Mail),''),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), ISNULL(CONVERT(VARCHAR(10),UltimaCompra,103),''), ISNULL(Bruto,0)
                from Kayser_OCRD as p
                LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode
                LEFT JOIN Cte_Fec ON P.LicTradNum=Rut
                where (p.GroupCode=6  and p.U_GSP_SENDTPV='Y' AND t.WhsName IS NULL )

select * from MM_PromotorasAyer