SQLCMD -S $env:computername\SQLEXPRESS -E -Q "SELECT name  FROM sys.databases"

$database = Read-Host -Prompt 'Ingrese el nombre de la base de datos del punto de venta'
$user = Read-Host -Prompt 'Ingrese el usuario a crear'
$pass = Read-Host -Prompt 'Ingrese el password'

Write-Host "Los parametros ingresados son: database: '$database' -- usuario: $user -- contraseña: $pass" 


#SQLCMD -S $env:computername\SQLEXPRESS -E -Q "CREATE LOGIN ofaber WITH PASSWORD='ResyaK2357'"
#SQLCMD -S $env:computername\SQLEXPRESS -E -Q "SP_ADDSRVROLEMEMBER 'ofaber','SYSADMIN'"

SQLCMD -S $env:computername\SQLEXPRESS -E -Q "CREATE LOGIN $user WITH PASSWORD='$pass'"
SQLCMD -S $env:computername\SQLEXPRESS -E -Q "SP_ADDSRVROLEMEMBER '$user','SYSADMIN'"

echo "A CONTINUACION GUARDAREMOS LAS CREDENCIALES EN UN ARCHIVO DE TEXTO y crearemos el usuario admin"
pause

