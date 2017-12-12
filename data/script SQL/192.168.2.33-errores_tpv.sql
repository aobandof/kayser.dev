
SELECT t1_1.U_GSP_LIBOTI, t1_1.U_GSP_LIARTI, t1_1.CANTIDAD, t2.ItemCode, t2.WhsCode, t2.OnHand
FROM	(	SELECT t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI, SUM(t1.U_GSP_LIQUAN) AS CANTIDAD
			FROM dbo.[@GSP_TPVLIN] AS t1 
			INNER JOIN dbo.[@GSP_TPVCAP] AS t2 ON t1.U_GSP_DOCCODE = t2.Code
			WHERE (t2.U_GSP_ERROR LIKE '%la cantidad recae%') AND (t1.U_GSP_LIARTI <> 'KDIF') AND (t2.U_GSP_CADOCU IN ('VTI_AG', 'VTIM_AG', 'VFA')) AND (t2.U_GSP_CAESTA = 'E')
			GROUP BY t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI  
		)	AS t1_1 
INNER JOIN dbo.OITW AS t2 ON t1_1.U_GSP_LIBOTI = t2.WhsCode AND t1_1.U_GSP_LIARTI = t2.ItemCode AND t1_1.CANTIDAD > t2.OnHand
order by U_GSP_LIBOTI asc


SELECT t1_1.U_GSP_LIBOTI, t1_1.U_GSP_LIARTI, cast(t1_1.CANTIDAD as int), t2.ItemCode, t2.WhsCode, cast(t2.OnHand as int)
FROM	(	SELECT t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI, SUM(t1.U_GSP_LIQUAN) AS CANTIDAD
            FROM [@GSP_TPVLIN] AS t1 
            INNER JOIN [@GSP_TPVCAP] AS t2 ON t1.U_GSP_DOCCODE = t2.Code
            WHERE (t2.U_GSP_ERROR LIKE '%la cantidad recae%') AND (t1.U_GSP_LIARTI <> 'KDIF') AND (t2.U_GSP_CADOCU IN ('VTI_AG', 'VTIM_AG', 'VFA')) AND (t2.U_GSP_CAESTA = 'E')
            GROUP BY t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI
		) AS t1_1
INNER JOIN  dbo.OITW AS t2 ON t1_1.U_GSP_LIBOTI = t2.WhsCode AND t1_1.U_GSP_LIARTI = t2.ItemCode AND t1_1.CANTIDAD > t2.OnHand
where U_GSP_LIARTI not like 'BOL%'  and U_GSP_LIARTI not like 'PROM%'
order by U_GSP_LIBOTI asc

select cast(AvgPrice as int),U_APOLLO_SEG1,U_APOLLO_SEG2,U_APOLLO_SEG3 from OITM where ItemCode='10K017-SUR-L'

select cast(avg(AvgPrice) as int) from OITM where U_APOLLO_SEG1='10K017' and U_APOLLO_SEG2='SUR'  and AvgPrice !=0

