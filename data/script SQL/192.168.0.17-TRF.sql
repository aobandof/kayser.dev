SELECT *  FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida]  where IdDocSalida = '105TRF457' 

update [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida] set EstadoInterfaz='C' where IdDocSalida = '105TRF457'

select idAlmacen, idDocSalida, idCliente, EstadoInterfaz FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida] where IdDocSalida = '105TRF457'