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

select * from DocumentoSalida
/*
CREATE TABLE Venta (
	id INT IDENTITY(1,1) PRIMARY KEY,
	codigo_cliente VARCHAR(20) NOT NULL,
	numero_documento VARCHAR(20) NOT NULL,
	codigo_pedido VARCHAR(20) NOT NULL UNIQUE,
	fecha_documento DATETIME,
	codigo_tienda VARCHAR(10) NOT NULL,
	instance_database VARCHAR(20) NOT NULL
	FOREIGN KEY (codigo_cliente) REFERENCES Cliente(codigo) ON DELETE CASCADE ON UPDATE CASCADE
)*/

/*
CREATE TABLE VentaDetalle (
	id_venta INT NOT NULL,
	sku_codigo VARCHAR(20) NOT NULL,
	sku_cantidad INT NOT NULL,
	sku_precio_total FLOAT NOT NULL,
	PRIMARY KEY (id_venta,sku_codigo),
	FOREIGN KEY (id_venta) REFERENCES Venta(id) ON DELETE CASCADE ON UPDATE CASCADE	
)
*/

SELECT * FROM VentaDetalle


CREATE TABLE Prueba (
	id INT IDENTITY(1,1) PRIMARY KEY,
	nombre VARCHAR(20) NOT NULL,
	detalle VARCHAR(50)
)

INSERT INTO Prueba VALUES ('abelin', 'dato ingresado a modo de prueba')

INSERT INTO Prueba VALUES ('joelin', 'dato ingresado a modo de prueba'),('joancito', 'dato ingresado a modo de prueba'),('abelin', 'dato ingresado a modo de prueba')

SELECT * FROM Prueba

SELECT @@IDENTITY AS 'primer_valor'; 

-- VALIDACIONES ---
-- es factible que no tenga email o no tenga celular, pero hay que validar que 


SELECT * FROM Cliente
DELETE FROM Cliente where codigo != ''
exec sp_help cliente
truncate table cliente

INSERT INTO [OMNI_KAYSER].dbo.Cliente VALUES ('26082384-1C','26082384-1','MONTERREY FLORES, JOAN
MANUEL','avenida presidente balmaceda 2720','santiago',
'santiago','quinta normal','joanmonterrey@gmail.com','','931289778','1993-03-16',
'2018-05-10','16','')

SELECT * FROM Venta
TRUNCATE TABLE Venta
exec sp_help Venta

INSERT INTO [OMNI_KAYSER].dbo.Venta VALUES ('26082384-1C','26082384-1','MONTERREY FLORES, JOAN MANUEL','avenida presidente balmaceda 2720','santiago',
'santiago','quinta normal','joanmonterrey@gmail.com','','931289778','1993-03-16',
'2018-05-10','16','')

TRUNCATE TABLE VentaDetalle
SELECT * FROM VentaDetalle


