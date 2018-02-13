<?php
require_once "../config/require.php";
require_once "../config/sku_db_mysqli.php";
require_once "../config/sku_db_sqlsrv_33.php";

#########################################  READ DUN #############################################################
if($_POST['option']=="read"){
  $filas=[];
  $filter = $_POST['filter'];
  // $query="SELECT U_DUN14_ITEMCODE AS sku, Name as barcode, Code as dun, U_DUN14_ALT1 as height, U_DUN14_ANC1 as width, U_DUN14_LAR1 as long, U_DUN14_CANT1, U_DUN14_MEDIDA as container from [@DUN_14] where  U_DUN14_ITEMCODE like '$filter%'";
  $query="select t0.ItemCode as sku, t0.CodeBars as barcode, t1.Code as dun, t1.U_DUN14_ALT1 as height, t1.U_DUN14_ANC1 as width, t1.U_DUN14_LAR1 as long, t1.U_DUN14_CANT1 as cant, t1.U_DUN14_MEDIDA as medida  from OITM t0 full outer join [@DUN_14] t1 on t0.ItemCode=t1.U_DUN14_ITEMCODE  where t0.U_APOLLO_SEG1 = '$filter'";
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
  $data['barcodes'] = $_POST['barcodes'];

  //LA API ME DEVOLVERA AQUELOS BARCODES QUE NO SE PUDIERON GUARDAR
  $data['inserted']= $_POST['barcodes'];
  echo json_encode($data);
}
#########################################  UPDATE DUN #############################################################

?>
