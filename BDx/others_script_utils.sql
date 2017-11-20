use kayser_articulos;

show tables; 

select * from relacionprefijo;

truncate table presentacion;
truncate table color;
truncate table marca;
truncate table usuario;
drop table usuario;
truncate table lista_has_usuario;


select * from usuario;


describe usuario;

select * from lista_has_usuario;
SELECT user, perfil FROM usuario WHERE user='admin' AND password='admin'