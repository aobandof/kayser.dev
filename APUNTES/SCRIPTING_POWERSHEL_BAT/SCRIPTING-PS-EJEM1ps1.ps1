clear
#Sql Browser en funcionamiento
#to discover SQL Server instances
Start-Service "SQLBrowser"

$instanceName = "LIAM\SQLEXPRESS"
$server = New-Object -TypeName Microsoft.SqlServer.Management.Smo.Server -ArgumentList $instanceName

$server.Databases | select name
