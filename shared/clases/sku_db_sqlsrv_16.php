<?php
$sqlsrv_16=new DBConnection('sqlsrv', $MSSQL['16']['host'], $MSSQL['16']['user'], $MSSQL['16']['pass'],'POSOne_CC_Kayser_PROD2');
if(($sqlsrv_16->getConnection())===false) { 
  $data['errors'][]=$sqlsrv_16->getErrors();
  $data['paver']="no se por que entra aca";
  echo json_encode($data);
  exit;

}
