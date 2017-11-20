USE kayser_articulos;
select * from articulo;
select * from sku;
select * from lista;
select * from lista_has_usuario

describe presentacion;

select* from presentacion;


SELECT nombre as name FROM subdpto WHERE id=3;
SELECT abreviatura from presentacion where id=2


SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE lista;
truncate table lista_has_usuario;
truncate table articulo;
truncate table sku;
SET FOREIGN_KEY_CHECKS=1;


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

INSERT INTO articulo VALUES ('D501001',2,'D501000-SOSTEN PETO CATALOGOI', 4,'DISNEY',106,'dama',5,'CORSETERIA','25','SOSTEN','63','PETO',1,'UNITARIO',5,'CATALOGO',2,'VERANO',21,'VER18',5,'ADULTO','full print',1,'100% ACRILICO','T03',100,'')
INSERT INTO lista values (NULL,'');
INSERT INTO sku VALUES('10.100s2-ACE-XS','10.1002',-1683047872,1,'ACERO','XS','7','','','2017-11-16 11:43:09')

truncate table marca;
truncate table composicion;
select * from marca;
select * from composicion;