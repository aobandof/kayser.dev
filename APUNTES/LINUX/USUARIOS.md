**CREACION DE USUARIOS EN LINUX**

- Para crear usuarios usamos el comando ```useradd ``` o ```adduser```
- Para modificar un usuario ```usermod```
- Para eliminasr un usuasrio ```userdel```
- Para agregar o modificar la contraseña de un usuario ```passwd [name_user]```
- Para crear un grupo ```groupadd```
- Para modificar grupo ```groupmod```
- Para eliminar un grupo ```groupdeldel```
- Para añadir usuario a un grupo ```adduser usuario grupo```
- Para eliminar un usuario de un grupo ```deluser usuario grupo```
- Cambiar o crear uusario propuetario de un directorio ```chown [name_user] [ruta_fichero]```
- Cambiar o crear grupo propietario de un directorio ```chgrp [name_group] [ruta_fichero]```
- Para ver los usuarios existentes: ```cat /etc/passwd```
- Para ver los grupos existentes: ```cat /etc/group```
- Para crear usuario de forma rapida:
```
add [name_user]
```
Con lo cual, al revisar los usuarios, veremos algo como esto:
> pureta:x:1000:1000::/home/pureta:/bin/bash
> mailnull:x:47:47::/var/spool/mqueue:/sbin/nologin
> smmsp:x:51:51::/var/spool/mqueue:/sbin/nologin
> archivo:x:1001:1001::/home/archivo:/bin/bash
> geoclue:x:990:987:User for geoclue:/var/lib/geoclue:/sbin/nologin

 por ejemplo, para el usuario pureta vemos que se le asigno una contraseña x, que su id es un correlativo 1000 para user y grupo, que se asignó su carpeta de usuario /home/pureta

- ***Crear un usuario de forma personalizada/avanzada:***
```
useradd kayser -c "Kayser Developers" -d /home/kayser -g 1002 -u 1002
```
 - -c	:	Comentario
 - -d	:	Directorio
 - -g	:	id de grupo (si no se especifica, sera el mismo del id de usuario. si si se especifica, tiene que ser un grupo existente)
 - -u	:	id de usuario (si no se especificam será el correlativo )
 - -G	:	id o nombre de otro grupo (segundo grupo)
 - -e	:	fecha de expiracion usuario (AAAAMMDD)
 - -s	:	Especificamos el shell (si no se especifica, Bash es el shell por defecto)
 - -r	:	Para crear una cuenta especial

- ***Asignar permisos a usuarios para carpetas y archivos:*** algunos ejemplos para cambiar permisos
```
chmod 777 [name_archivo]
chmod 755 [ruta_carpeta]
chmod -R 775 [ruta_carpeta] // -R forma recursiva (a todos los archivos y directorios contenidos)
chmod -R u+w [ruta_carpeta]
chmod -R u+w,g+x [ruta_carpeta] //agrupar asignacion de permisos con coma
```
donde u: usuario, g: grupo, o: otro, + agregar permiso, - quitar permisos, w: escritura, r: lectura, w: ejecucion

- ***Agregar un usuario al grupo Wheel: *** Este grupo especial, permite que todos sus miembros ejecuten todos los comandos
 
 Como usuario root, ejecutamos
```
usermod -aG wheel [name_user]```
Ahora abrimos el archivo ```/etc/sudoers``` y dentro verificamos que no esté comentada la siguiente linea: ```%wheel  ALL=(ALL)       ALL```. Despues cambiamos al usuario con ```sudo - [name_user]``` y para ejecutar un comando administrativo usamos ```sudo + comando```, previamente ingresando la contraseña del usuario en mención.

- ***Otorgamos permisos root a usuario: *** en el mismo archivo de sudoers (al que también podemos ingresar con el comando ```/usr/sbin/visudo```), bajo la linea ```root            ALL=(ALL:ALL) ALL```, agregamos el usuario con los mismo permisos: ```[name_user]            ALL=(ALL:ALL) ALL``` (los espacios son tabulaciones)




