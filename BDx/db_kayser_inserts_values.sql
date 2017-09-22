use kayser;
show tables;
/*describe Area;*/
insert into Area values (NULL,'INFORMATICA',''),(NULL,'DISEÑO',''),(NULL,'RECURSOS HUMANOS','');
insert into Empleado values 
(NULL,'24215707-9','OBANDO FLORIAN','ABEL RAFAEL','aobando@kayser.cl','949607845','DESARROLLADOR'),
(NULL,'XXXXXXXX-X','MORA','MARIO','mmora@kayser.cl','xxxxxxxxxx','JEFE');
select * from Area;
select * from Empleado;
insert into Ejecutivo values (1,1),(2,1);
insert into Aplicacion values (NULL,'intranet_web');
insert into User values
(NULL,'aobando','123456','administrador',1),
(NULL,'mmora','123456','administrador',2);
select * from User;
insert into Aplicacion_User values (1,1),(1,2);
						
