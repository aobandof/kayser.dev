192.168.0.13 PARA OBTENER LAS GUOIAS
SELECT * FROM [dbo].[GUIAS_E] where FolioNum='23177081'



--#############################################################################--
--###########  PARA DETENER ACTIVIDAD EN TIENDAS DURANTE INDENTARIO ###########--
--#############################################################################--


SELECT T1.U_GSP_NAME, T1.U_GSP_AUTOEXEC, T1.U_GSP_AUTOEXEC FROM [@GSP_TPVWCD] AS T1 INNER JOIN [@GSP_TPVSHOP] AS T2 ON T1.U_GSP_NAME=T2.U_GSP_DFLTCARD

SELECT * FROM [@GSP_TPVWCD]
SELECT U_GSP_DFLTCARD, U_GSP_TIPOINTEGR, U_GSP_TIPOINTEGRCP FROM [@GSP_TPVSHOP]
SELECT * FROM [@GSP_TPVSHOP]
/**********************update checkbox en el server 192.168.0.33********************************************/

--update [@GSP_TPVSHOP] set U_GSP_TIPOINTEGR='NO_INTEG', U_GSP_TIPOINTEGRCP='NO_INTEGRAR'   /*sin check ROJO*/

--update [@GSP_TPVSHOP] set U_GSP_TIPOINTEGR='FACT+COBRO', U_GSP_TIPOINTEGRCP='INTEGRAR'      /*con check VERDE*/

--update [@GSP_TPVWCD] set U_GSP_AUTOEXEC='N', U_GSP_FILDATEEND=getdate()-1                   /*sin check ROJO*/

--update [@GSP_TPVWCD] set U_GSP_AUTOEXEC='Y', U_GSP_FILDATEEND='2099-12-31'                        /*con check VERDE*/

SELECT T1.U_GSP_DFLTCARD, T1.U_GSP_TIPOINTEGR, T1.U_GSP_TIPOINTEGRCP, T2.U_GSP_AUTOEXEC, T2.U_GSP_AUTOEXEC FROM [@GSP_TPVSHOP] AS T1 INNER JOIN [@GSP_TPVWCD] AS T2   ON T1.U_GSP_DFLTCARD=T2.U_GSP_NAME