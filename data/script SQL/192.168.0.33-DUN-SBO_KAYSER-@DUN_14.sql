select * from [@DUN_14]


SELECT U_DUN14_ITEMCODE AS sku, Name as barcode, Code as dun, U_DUN14_ALT1 as height, U_DUN14_ANC1 as width, U_DUN14_LAR1 as long, U_DUN14_CANT1, U_DUN14_MEDIDA as container from [@DUN_14]

SELECT top 100 U_DUN14_ITEMCODE AS sku, Name as barcode, Code as dun, U_DUN14_ALT1 as height, U_DUN14_ANC1 as width, U_DUN14_LAR1 as long, U_DUN14_CANT1 as cant, U_DUN14_MEDIDA as container from [@DUN_14]

SELECT U_DUN14_ITEMCODE AS sku, Name as barcode, Code as dun, U_DUN14_ALT1 as height, U_DUN14_ANC1 as width, U_DUN14_LAR1 as long, U_DUN14_CANT1, U_DUN14_MEDIDA as container from [@DUN_14] where  U_DUN14_ITEMCODE like '10.07%'

select t0.ItemCode as sku, t0.CodeBars as barcode, t1.Code as dun, t1.U_DUN14_ALT1 as height, t1.U_DUN14_ANC1 as width, t1.U_DUN14_LAR1 as long, t1.U_DUN14_CANT1 as cant, t1.U_DUN14_MEDIDA as medida  from OITM t0 full outer join [@DUN_14] t1 on t0.ItemCode=t1.U_DUN14_ITEMCODE  where t0.U_APOLLO_SEG1 = '10.07'