select ISNULL(t.WhsName,'') as Region,p.LicTradNum as Rut, p.CardName as Nombre,ISNULL(p.Cellular,'') as Celular,ISNULL(p.Phone1,'') as Telefono,ISNULL(LOWER(p.E_Mail),'') as Email, 
p.Address as Direccion,p.County as Comuna,p.City as Ciudad ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),'') as Cumpleaños, ISNULL(CONVERT(VARCHAR(10),UltimaCompra,103),'') as [Ultima Compra], 
ISNULL(CAST(ROUND(Bruto,0) AS INT),0) as [Valor Ultima Compra]
			from Kayser_OCRD as p
            LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode
            LEFT JOIN Cte_Fec ON P.LicTradNum=Rut
            where p.GroupCode=6  and p.U_GSP_SENDTPV='Y'

--- CONSULTA SOLO NOMBRE Y EMAIL DE TODA SLAS PROMOTORAS
select UPPER(cardName) as NOMBRE, REPLACE(LOWER(E_Mail),' ','') as EMAIL from Kayser_OCRD where E_Mail != '' AND E_Mail != 'NO TIENE' AND E_Mail != 'SIN CORREO'

--- consulta rut, email y telefono
SELECT p.LicTradNum, p.CardName,ISNULL(p.Cellular,''),ISNULL(p.Phone1,''),ISNULL(LOWER(p.E_Mail),'')
from Kayser_OCRD as p 
where p.GroupCode=6  and p.U_GSP_SENDTPV='Y'
