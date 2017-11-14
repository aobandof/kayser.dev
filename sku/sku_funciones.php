<?php
function getFirstBarcode() {
  global $sqlsrv;
  $query_barcode="SELECT top 1 CodeBars from Kayser_OITM WHERE CodeBars like '780001%' order by  CodeBars DESC";
  $arr_last_barcode=$sqlsrv->select($query_barcode,'sqlsrv_a_p');
  if($arr_last_barcode!==false){
    ($arr_last_barcode!=0) ? $first_barcode=((int)$arr_first_barcode[0]['CodeBars'])+1 : $first_barcode=780001000000;
  }
  return $first_barcode;
}



?>