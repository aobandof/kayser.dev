USE kayser_articulos;
select * from articulo;
select * from sku;
select * from lista;
select * from lista_has_usuario;
select * from usuario;

select codigo,barcode from sku order by barcode DESC LIMIT 1;

SELECT SUBSTRING(barcode,1,LENGTH(barcode)-1) from sku order by barcode DESC LIMIT 1
SELECT SUBSTRING(barcode,1,2) from sku

SELECT codigo,SUBSTRING(codigo,2), LENGTH(barcode) from sku;

SELECT S.codigo as cod_sku, S.barcode, S.color_name, S.talla_name, S.talla_orden, A.codigo as cod_articulo, A.itemname FROM sku as S
INNER JOIN articulo as A ON S.articulo_codigo=A.codigo
WHERE A.lista_id=3; 

SELECT L.id, COUNT(S.codigo) from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id
SELECT L.id, COUNT(S.codigo) as cant_skus from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id
SELECT L.id,COUNT(S.codigo) from lista as L
INNER JOIN articulo as A on A.lista_id=L.id
INNER JOIN sku as S on A.codigo=S.articulo_codigo
GROUP BY L.id

SELECT A.codigo, COUNT(S.codigo) FROM articulo as A
INNER JOIN sku AS S ON A.codigo=S.articulo_codigo
GROUP BY A.codigo;

SELECT * FROM lista_has_usuario where lista_id=10;
update lista_has_usuario SET operacion='CREACION';

describe lista;
describe articulo;
describe sku;


SELECT nombre as name FROM subdpto WHERE id=3;
SELECT abreviatura from presentacion where id=2;


SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE lista;
truncate table lista_has_usuario;
truncate table articulo;
truncate table sku;
truncate table usuario;
SET FOREIGN_KEY_CHECKS=1;



DELETE from lista WHERE id=7
insert into usuario values ('reviser','reviser','reviser');
INSERT INTO usuario VALUES ('mmora','mmora','admin'),('aobando','aobando','admin'),('emonsalves','emonsalves','admin'),('mgiraldo','mgiraldo','admin'),('mvera','mvera','admin'),('cmarino','cmarino','reviser'),('fmunoz','fmunoz','reviser'),('gpassi','gpassi','reviser'),('ssalas','ssalas','reviser'),('comex','comex','editor'),('ldelteil','ldelteil','editor'),('mbustos             ','mbustos             ','editor'),('mpasten','mpasten','editor'),('smolina','smolina','editor'),('jbisquertt','jbisquertt','editor'),('rriquelme','rriquelme','editor'),('janais','janais','editor'),('admin','12345','admin'),('editor','12345','editor'),('reviser','12345','reviser'),('informatica','12345','admin');

SELECT L.id, COUNT(S.codigo) as cant_skus from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id;



describe skucreated;

truncate table marca;
truncate table presentacion;
truncate table relacionprefijo;

SET FOREIGN_KEY_CHECKS=0;
drop table usuario;
SET FOREIGN_KEY_CHECKS=1;

delete from lista where id=5;
SELECT barcode from sku order by barcode DESC -- LIMIT 1
SELECT @@identity AS id
SELECT codigo FROM articulo WHERE lista_codigo=2

describe articulo;
SELECT codigo FROM articulo;
SELECT RIGHT(codigo, LENGTH(codigo)-LENGTH('P2AC')) as corre from articulo where codigo LIKE 'P2AC%'
SELECT codigo, RIGHT(codigo, LENGTH(codigo)-LENGTH('P2150')) from articulo where codigo LIKE 'P2150%D'
INSERT INTO articulo VALUES ('P2141000',25,'P2141000-PANTALETA ALGODON', 1,'KAYSER',106,'dama',5,'CORSETERIA','09','CALZON','62','PANTALETA',2,'BIPACK',2,'ALGODON',1,'INVIERNO',23,'INV18',9,'ESCOLAR',,'',2,'100% ALGODON','T03')
INSERT INTO articulo VALUES ('P2AC1000',28,'P2AC1000-AJUSTADOR TIRANTES ACRILICO', 1,'KAYSER',106,'dama',1,'ACCESORIOS','01','AJUSTADOR','74','TIRANTES',2,'BIPACK',1,'ACRILICO',1,'INVIERNO',1,'DESCONTINUADO',5,'ADULTO',1,'FULL PRINT',1,'100% ACRILICO','T07')


INSERT INTO articulo VALUES ('D501001',2,'D501000-SOSTEN PETO CATALOGOI', 4,'DISNEY',106,'dama',5,'CORSETERIA','25','SOSTEN','63','PETO',1,'UNITARIO',5,'CATALOGO',2,'VERANO',21,'VER18',5,'ADULTO','full print',1,'100% ACRILICO','T03',100,'')
INSERT INTO lista values (NULL,'');
INSERT INTO sku VALUES('10.100s2-ACE-XS','10.1002',-1683047872,1,'ACERO','XS','7','','','2017-11-16 11:43:09')

truncate table marca;
truncate table composicion;
select * from marca;
select * from composicion;