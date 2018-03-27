***CREACION DE USUARIO CON ACCESOS SYSADMIN, DESDE CONSOLA***

- ABRIMOS EL EDITOR BASH (CMD, o POWERSHEL) con privilegios administrativos
- Nos conectamos con autenticacion de windows (sin credenciales)
```
SQLCMD -S [instancia]
```
Ejemplo: 	```SQLCMD -S PURETA-TI\SQLEXPRESS ```

- Con lo anterior ya entramos a editar comandos SQL.
- Ahora creamos el usuario con privilegios administrativos
```
CREATE LOGIN [nombre_usuario] WITH PASSWORD='[password]'
GO
SP_ADDSRVROLEMEMBER '[nombre_usuario]','SYSADMIN'
GO
```
Ejemplo:
```
CREATE LOGIN omni WITH PASSWORD='12345'
GO
SP_ADDSRVROLEMEMBER 'omni','SYSADMIN'
GO
```

- control + C para salir del editor SQL y despues realizamos una conexion, pero ahora con autenticacion SQL para comprobar que el usuario se creo de forma correcta
```
SQLCMD -S [instancia] -U [nonbre_usuario] -P [password]
```
Ejemplo: ```SQLCMD -S PURETA-TI\SQLEXPRESS -U omni -P 12345```