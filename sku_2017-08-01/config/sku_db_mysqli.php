<?php
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
if(($mysqli->getConnection())===false)  {
  $data['errors'][]=$mysqli->getErrors(); 
  echo json_encode($data);
  exit;
}
