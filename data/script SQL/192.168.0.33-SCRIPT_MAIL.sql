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