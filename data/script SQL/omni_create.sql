/*CREATE DATABASE OMNI_KAYSER
GO
USE OMNI_KAYSER
GO
CREATE TABLE Cliente (
	codigo VARCHAR(20) PRIMARY KEY,
	rut VARCHAR(15) NOT NULL UNIQUE,
	nombre VARCHAR(80) NOT NULL,
	direccion VARCHAR(100) NOT NULL,
	comuna VARCHAR(50),
	ciudad VARCHAR(50),
	email VARCHAR(50),
	telefono VARCHAR(15),
	celular VARCHAR(15),
	fecha_nacimiento DATETIME,
	fecha_registro DATETIME,
	tipo VARCHAR(20),
	detalle VARCHAR(100)	
)*/



-- VALIDACIONES ---
-- es factible que no tenga email o no tenga celular, pero hay que validar que 







