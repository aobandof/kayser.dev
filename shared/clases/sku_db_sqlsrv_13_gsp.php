<?php
$sqlsrv_13_gsp=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'GSP');
if(($sqlsrv_13_gsp->getConnection())===false) { 
  $data['errors'][]=$sqlsrv_13_gsp->getErrors(); 
  echo json_encode($data);
  exit;
}
