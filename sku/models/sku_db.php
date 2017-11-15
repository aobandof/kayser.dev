<?php
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
$sqlsrv_13=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'KAYSER_SBO');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
