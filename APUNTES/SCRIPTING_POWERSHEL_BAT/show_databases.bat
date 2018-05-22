SQLCMD -S %computername%\SQLEXPRESS -E -Q "SELECT name  FROM sys.databases"

SQLCMD -S %computername%\SQLEXPRESS -E -Q "CREATE LOGIN ofaberito WITH PASSWORD='ResyaK2357'"
SQLCMD -S %computername%\SQLEXPRESS -E -Q "SP_ADDSRVROLEMEMBER 'ofaberito','SYSADMIN'"

pause

