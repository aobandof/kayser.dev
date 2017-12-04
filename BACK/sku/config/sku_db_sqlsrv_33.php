<?php
$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
if(($sqlsrv_33->getConnection())===false) { 
  $data['errors'][]=$sqlsrv_33->getErrors();
  $data['paver']="no se por que entra aca";
  echo json_encode($data);
  // $sqlsrv_33->closeConnection();
  exit;

}
