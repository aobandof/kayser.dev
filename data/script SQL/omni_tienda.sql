select * from dbo.BOTIGUES
SELECT BOCODI, 

select * from dbo.DOCUMENTS

SELECT * FROM dbo.DOCUMENTS_LINES

SELECT * FROM dbo.CLIENTS
SELECT * FROM dbo.CLIENTS WHERE CLDNI='76697790-1'
SELECT * FROM dbo.CLIENTS WHERE CLOBS='TIENDA' AND 

-- consulta para obtener la boleta y datos del cliente
SELECT D.BOCODI as codigo_tienda, D.TIPDOC as tipo_doc, D.TICODI as correlativo_doc, D.TIBOLETA as numero_doc, CONVERT(VARCHAR(10),D.TIDATA,120) as fecha_doc, D.TIHORA as hora_doc, 
C.CLCODI as codigo, C.CLDNI as rut, C.CLNOM as nombre, C.CLADRE as direccion, C.CLPROV as comuna, C.CLPOBL as ciudad, C.CLTARF as tipo, 
C.CLTEF as telefono, C.CLMOVIL as celular, C.CLEMAIL as email, CONVERT(VARCHAR(10),C.CLFECHANAC,120) as fecha_nacimiento, CONVERT(VARCHAR(10),C.DULM,120) as fecha_registro
FROM DOCUMENTS AS D 
INNER JOIN CLIENTS AS C ON D.CLCODI=C.CLCODI
WHERE D.TIBOLETA = '5808'

-- consulta para obtener los SKU de la boleta
SELECT DL.ARCODI as codigo, DL.ARDEST as itemname, DL.TLCODI as cantidad, DL.TLTOT as precio_total 
FROM DOCUMENTS_LINES AS DL INNER JOIN DOCUMENTS AS D ON DL.TICODI=D.TICODI AND DL.TLDATA=D.TIDATA 
WHERE D.TIBOLETA='5808' 
ORDER BY DL.ARCODI

-- CONSULTA PARA OBTENER LOS DATOS DE LA TIENDA
SELECT CLCODI as codigo, CLNOM as nombre, CLADRE as direccion, CLPOBL as region FROM dbo.CLIENTS WHERE CLOBS='TIENDA' AND CLCODI LIKE '177-%'


