use kayser_articulos;

select * from lista;
select * from lista_has_usuario;
select * from articulo;
select * from sku;
select * from tprenda;

show tables;


delete from lista;
delete from sku;


SELECT L.id, COUNT(S.codigo) as cant_skus, estado from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo INNER JOIN lista_has_usuario as LU on L.id=LU.lista_id WHERE LU.usuario_user='editor' AND L.estado='INICIADA' GROUP BY L.id

SELECT L.id, COUNT(S.codigo) as cant_skus, estado from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id