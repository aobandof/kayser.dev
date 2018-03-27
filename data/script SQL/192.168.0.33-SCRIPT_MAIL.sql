-- SCRIPT PARA DETECTAR DIFERENCIAS ENTRE DOCUMENTOS Y CORRELATIVOS --
----------------------------------------------------------------------

SELECT     U_GSP_CADOCU AS TipoDOC, U_GSP_CABOTI AS Almacen, MAX( U_GSP_BOLETA) Ultimo
FROM         dbo.[@GSP_TPVCAP] AS t0
WHERE     (YEAR(U_GSP_CADATA) = YEAR(GETDATE())) AND (MONTH(U_GSP_CADATA) = MONTH(GETDATE())) AND (DAY(U_GSP_CADATA) = DAY(GETDATE())-1) AND
                      (U_GSP_CADOCU <> N'vrg') AND (U_GSP_CANUME <> U_GSP_BOLETA)
group by U_GSP_CADOCU, U_GSP_CABOTI                     
order by U_GSP_CABOTI asc


-- LA SIGUIENTE VISTA RESUME TODO ESO:
SELECT * FROM U_MMcorrelativo


-- SCRIPT PARA DETECTAR  --
----------------------------------------------------------------------
select top 5 code, U_GSP_CABOTI,U_GSP_CACLIE, U_GSP_CADOCU,U_GSP_CANUME, U_GSP_CADATA, U_GSP_ERROR  from [@GSP_TPVCAP] where U_GSP_ERROR like '%no se ha integrado%' and year(u_gsp_cadata)>=2017 AND month(u_gsp_cadata)=12

select code, U_GSP_CABOTI, U_GSP_CACLIE, U_GSP_CADOCU, U_GSP_CANUME, convert(varchar,convert(date,U_GSP_CADATA)), U_GSP_ERROR from [@GSP_TPVCAP] where U_GSP_ERROR like '%no se ha integrado%' and year(u_gsp_cadata)>=2018


--------------------------------------------------------------------------------

select top 100000 Anio, Mes, CodCte, Razon, GroupName, Sku, Articulo,Grupo, Origen, Destino, Costo, Precio, Entrada, Salida, Saldo, Neto, Guia from PDFENE



SELECT  A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct as total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC


-- SCRIPT PARA REPORTAR GUIS MAL RECEPCIONADAS -- BD SBO_KAYSER -- SERVER 192.168.0.33
-----------------------------------------------------------------------------------------
select Code Codigo, U_GSP_CABOTI CodTienda, U_GSP_CACLIE Tienda, convert(varchar,convert(date,U_GSP_CADATA)) Fecha, U_GSP_CAHORA Hora,  U_GSP_CANLIN LineasSKU from [@GSP_TPVCAP] where U_GSP_ERROR like '%no es necesario crear%' and convert(date,U_GSP_CADATA)= convert(date, GETDATE()-3)