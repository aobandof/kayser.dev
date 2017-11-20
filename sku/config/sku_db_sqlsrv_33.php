<?php
$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'KAYSER_SBO');
if(($sqlsrv_33->getConnection())===false) { 
  $data['errors'][]=$sqlsrv_33->getErrors();
  echo json_encode($data);
  exit;
}
