<?php
$sqlsrv_13=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
if(($sqlsrv_13->getConnection())===false) { 
  $data['errors'][]=$sqlsrv_13->getErrors(); 
  echo json_encode($data);
  exit;
}
