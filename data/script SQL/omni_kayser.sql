/*CREATE DATABASE OMNI_KAYSER
GO
USE OMNI_KAYSER
GO*/
/*
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
/*
CREATE TABLE Venta (
		

)*/

/*
CREATE TABLE DocumentoSalida (
	IdAlmacen	varchar(10) NOT NULL,
	IdOwner	varchar(5) NOT NULL,
	IdDocSalida	varchar(20) NOT NULL,
	NroReferencia	varchar(20),
	NroOrdenCliente	varchar(50),
	Tipo	varchar(15),
	FechaEmision	datetime NOT NULL,
	FechaCompromiso	datetime,
	FechaExpiracion	datetime,
	AlmacenDestino	varchar(10),
	IdCliente	varchar(20),
	IdSucursal	varchar(15),
	EnviarPor	char(1),
	IdKit	varchar(20),
	Cantidad	numeric(9),
	Prioridad	int,
	Observaciones	varchar(200),
	FechaCreacion	datetime NOT NULL,
	Factura	varchar(20),
	FechaAnulacion	datetime,
	AnuladoPor	varchar(10),
	XD	char(1),
	BO	char(1),
	EstadoInterfaz	char(1) NOT NULL,
	FechaCreacionERP	datetime NOT NULL,
	FechaModificacionERP	datetime,
	FechaLecturaWMS	datetime,
	primary key (IdAlmacen, IdOwner, IdDocSalida)
)*/
/*
CREATE TABLE DetalleSalida (
	IdAlmacen	varchar(10)	NOT NULL,
	IdOwner	varchar(5) NOT NULL,
	IdDocSalida	varchar(20) NOT NULL,
	IdArticulo	varchar(20) NOT  NULL,
	NroLinea	varchar(5) NOT NULL,
	Cantidad	numeric,
	primary key (IdAlmacen, IdOwner, IdDocSalida, IdArticulo),
	foreign key (IdAlmacen, IdOwner, IdDocSalida) references DocumentoSalida (IdAlmacen, IdOwner, IdDocSalida)	
)*/




-- VALIDACIONES ---
-- es factible que no tenga email o no tenga celular, pero hay que validar que 







