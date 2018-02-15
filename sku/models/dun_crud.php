<?php
require_once "../config/require.php";
require_once "../config/sku_db_mysqli.php";
require_once "../config/sku_db_sqlsrv_33.php";

#########################################  READ DUN #############################################################
if($_POST['option']=="read"){
  $filas=[];
  $filter = $_POST['filter'];
  // $query="SELECT U_DUN14_ITEMCODE AS sku, Name as barcode, Code as dun, U_DUN14_ALT1 as height, U_DUN14_ANC1 as width, U_DUN14_LAR1 as long, U_DUN14_CANT1, U_DUN14_MEDIDA as container from [@DUN_14] where  U_DUN14_ITEMCODE like '$filter%'";
  $query="select t0.ItemCode as sku, t0.CodeBars as barcode, t1.Code as dun, t1.U_DUN14_ALT1 as height, t1.U_DUN14_ANC1 as width, t1.U_DUN14_LAR1 as long, t1.U_DUN14_CANT1 as cant, t1.U_DUN14_MEDIDA as medida  from OITM t0 full outer join [@DUN_14] t1 on t0.ItemCode=t1.U_DUN14_ITEMCODE  where t0.U_APOLLO_SEG1 = '$filter' ORDER BY t0.itemCode";
  // echo $query."<br>";
  $arr_dun=$sqlsrv_33->selectDtable($query, 1, ['select'], 'div');
  if($arr_dun!==0 && $arr_dun!==false){  
    $data['rows']=$arr_dun;
  }else { $arr_dun===false ? $data['errors']=$sqlsrv_33->getErrors() : $data['cant_dun']=$arr_dun; }
  // header("Content-Type: text/html;charset=utf-8"); 
  echo json_encode($data);
}

#########################################  CREATE DUN #############################################################
if($_POST['option']=="create"){
  $query="INSERT INTO [@DUN_14] VALUES ( ?,?,?,?,?,?,?,? )";
  $length = count($_POST['barcodes']);  
  for($i=0; $i<$length; $i++){
    $parameters = array($_POST['duns'][$i],$_POST['barcodes'][$i],$_POST['skus'][$i],$_POST['altura'],$_POST['anchura'],$_POST['largo'],$_POST['cantidad'],$_POST['medida'] );
    $reg_inserted = $sqlsrv_33->insertUpdateDeleteSqlsrv($query,$parameters);
    if($reg_inserted !== false && $reg_inserted!==0){
      $arr_inserted[]=$_POST['barcodes'][$i];
    }else{
       if($reg_inserted === false) $data['errors']=$sqlsrv_33->getErrors();
       $arr_refused[]=$_POST['barcodes'][$i];
    }
  }
  if(isset($arr_refused)) $data['refused']=$arr_refused;
  $data['inserted']= $arr_inserted;
  echo json_encode($data);
}
#########################################  UPDATE DUN #############################################################
if($_POST['option']=="update"){
  $query="UPDATE [@DUN_14] SET U_DUN14_ALT1 = ?, U_DUN14_ANC1 = ?, U_DUN14_LAR1 = ?, U_DUN14_CANT1 = ?, U_DUN14_MEDIDA = ? WHERE Name = ?";
  $length = count($_POST['barcodes']);  
  for($i=0; $i<$length; $i++){
    $parameters = array( $_POST['altura'], $_POST['anchura'], $_POST['largo'], $_POST['cantidad'], $_POST['medida'], $_POST['barcodes'][$i] );
    $reg_updated = $sqlsrv_33->insertUpdateDeleteSqlsrv($query,$parameters);
    $data['resp_updated'][]=$reg_updated;
    if($reg_updated !== false && $reg_updated!==0){
      $arr_updated[]=$_POST['barcodes'][$i];
    }else{
       if($reg_updated === false) $data['errors']=$sqlsrv_33->getErrors();
       $arr_refused[]=$_POST['barcodes'][$i];
    }
  }
  if(isset($arr_refused)) $data['refused']=$arr_refused;
  $data['updated']= $arr_updated;
  echo json_encode($data);
}

#########################################  UPDATE DUN #############################################################
if($_POST['option']=="delete"){
  $query="DELETE FROM [@DUN_14] WHERE Name = ?";
  $length = count($_POST['barcodes']);  
  for($i=0; $i<$length; $i++){
    $parameters = array( $_POST['barcodes'][$i] );
    $reg_deleted = $sqlsrv_33->insertUpdateDeleteSqlsrv($query,$parameters);
    if($reg_deleted !== false && $reg_deleted!==0){
      $arr_deleted[]=$_POST['barcodes'][$i];
    }else{
       if($reg_deleted === false) $data['errors']=$sqlsrv_33->getErrors();
       $arr_refused[]=$_POST['barcodes'][$i];
    }
  }
  if(isset($arr_refused)) $data['refused']=$arr_refused;
  $data['deleted']= $arr_deleted;
  echo json_encode($data);

}
?>


