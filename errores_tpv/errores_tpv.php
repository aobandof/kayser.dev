<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");

ini_set('display_errors', '1');

$a=0;
$AccountCode;///////////////////////
$ItemCode;///////////////////////
$Currency;///////////////////////
$UnitPrice;///////////////////////
$Quantity;///////////////////////
$WarehouseCode;///////////////////////
$RecordKey;
$RecordKey_count=0;
$LineNum;
$RecordKey_;
$Comments_;
$DocDate_;
$DocEntry_;


$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');

$query_errors=  <<<EOD
SELECT t1_1.U_GSP_LIBOTI, t1_1.U_GSP_LIARTI, cast(t1_1.CANTIDAD as int), t2.ItemCode, t2.WhsCode, cast(t2.OnHand as int)
FROM	(	SELECT t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI, SUM(t1.U_GSP_LIQUAN) AS CANTIDAD
			FROM dbo.[@GSP_TPVLIN] AS t1 
			INNER JOIN dbo.[@GSP_TPVCAP] AS t2 ON t1.U_GSP_DOCCODE = t2.Code
			WHERE (t2.U_GSP_ERROR LIKE '%la cantidad recae%') AND (t1.U_GSP_LIARTI <> 'KDIF') AND (t2.U_GSP_CADOCU IN ('VTI_AG', 'VTIM_AG', 'VFA')) AND (t2.U_GSP_CAESTA = 'E')
			GROUP BY t1.U_GSP_LIBOTI, t1.U_GSP_LIARTI  
		)	AS t1_1 
INNER JOIN dbo.OITW AS t2 ON t1_1.U_GSP_LIBOTI = t2.WhsCode AND t1_1.U_GSP_LIARTI = t2.ItemCode AND t1_1.CANTIDAD > t2.OnHand
order by U_GSP_LIBOTI asc
EOD;

if(($arr_error=$sqlsrv->select($query_errors,'sqlsrv_n_p'))===false){
    $errors=$sqlsrv->getErrors();
    echo "<h2>Errores encontrados, POR FAVOR CONTACTE A INFORMATICA</h2>";
    var_dump($errors);
    echo "<a href='index.html'><button>REGRESAR</button></a>";
    // exit;
}else { //SI LA PRIMERA CONSULTA ES CORRECTA
  if($arr_error===0){
    echo "<h2>SIN RESULTADOS</h2>";
    // exit;
    echo "<a href='index.html'><button>REGRESAR</button></a>";
  }
  $data_analysis="";
  $costo=0;
  $costo_img;
  $suma_diferencia=0;
  $count_almacen=0;
  $almacen;
  $count_fila;
  $count_fila_=0;
  foreach ($arr_error as $row_error) {
    $count_fila++;
    $diferencia=$row_error[2]-$row_error[5];
    $data_analysis.=$count_fila.";".$row_error[0].";".$row_error[1].";".$row_error[2].";".$row_error[3].";".$row_error[4].";".$diferencia.";";
    $data_analysis.="\r\n";
    $query_stock="select cast(AvgPrice as int),U_APOLLO_SEG1,U_APOLLO_SEG2,U_APOLLO_SEG3 from OITM where ItemCode='".$row_error[1]."'";
    if(($arr_stock=$sqlsrv->select($query_stock,'sqlsrv_n_p'))===false){
      $errors=$sqlsrv->getErrors();
      echo "<h2>Errores encontrados, POR FAVOR CONTACTE A INFORMATICA</h2>";
      var_dump($errors);
      exit;
    }else { //SI LA SEGUNDA CONSULTA ES CORRECTA
      $row_stock=$arr_stock[0];
      if ($row_stock[0] == 0) {
        $segmentos_array = explode('-',$row_error[1]);//dividimos el codigo sku en en arry guardando el cod del articulo y el color en $seg_1 y $seg_2
        $seg_1=$segmentos_array[0];
        $seg_2=$segmentos_array[1];
        $query_prod3="select cast(avg(AvgPrice) as int)from OITM where U_APOLLO_SEG1='$seg_1' and U_APOLLO_SEG2='$seg_2'  and AvgPrice !=0"; //$row_stock[0]
        if(($arr_prod3=$sqlsrv->select($query_prod3,'sqlsrv_n_p'))===false){
          $errors=$sqlsrv->getErrors();
          echo "<h2>Errores encontrados, POR FAVOR CONTACTE A INFORMATICA</h2>";
          var_dump($errors);
        }else{ //SI LA TERCERA CONSULTA ES CORRECTA
          $row_prod3=$arr_prod3[0];
          if ($row_prod3[0] == 0) {
            $query_prod2 = "select cast(avg(AvgPrice) as int) from OITM where U_APOLLO_SEG1='$seg_1' and AvgPrice !=0";
            if(($arr_prod2=$sqlsrv->select($query_prod2,'sqlsrv_n_p'))===false){
              $errors=$sqlsrv->getErrors();
              echo "<h2>Errores encontrados, POR FAVOR CONTACTE A INFORMATICA</h2>";
              var_dump($errors);              
            }else { //SI LA CUARTA CONSULTA ES CORRECTA
              $row_prod2=$arr_prod2[0];
              $costo=$row_prod2[0];
              if (!$costo) {
                  $costo_img='falta_costo.png';
              } 
            }                 
          }else{
            $costo=$row_prod3[0];
            $costo_img='no_falta_costo.png';
          }          
        }
      }else {
        $costo=$row_stock[0];
        $costo_img='no_falta_costo.png';
      }

      if ($row_error[0] != $almacen and $costo > 0) {
        $almacen=$row_error[0];
        $count_almacen++;
        $RecordKey_count++;        
        $RecordKey_.=$RecordKey_count."<";
        $Comments_.=$row_error[0]."- AJUSTES <";
        $DocDate_=$mday."-".$mon."-".$year;
        $count_fila_=0;          
      }
      $suma_diferencia+=$diferencia;
      if ($costo > 0) {
          $RecordKey.=$RecordKey_count."<";
          $LineNum.=$count_fila_."<";
          $AccountCode='_SYS00000000225';
          $ItemCode.=$row_error[3]."<";
          $Currency='$';
          $UnitPrice.=$costo."<";
          $Quantity.=$diferencia."<";
          $WarehouseCode.=$row_error[0]."<";
      }    
      $count_fila_++;
    }// fin eslse si la segunda consulta es correcta
  }//fin foreach recorriendo las filas de la primera consulta

  header('Content-Type: application/x-octet-stream'); // Archivo de Excel
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

  if($_GET['option']=='analisis'){
    $filename_analysis="Analisis(".date('d-m-Y H:i:s').").csv";
    $columns_analysis = 'N. FILA;ALMACEN;ARTICULO;VENTA;ITEMCODE;STOCK;DIFERENCIA;';
    $columns_analysis .= "\r\n";
    $data_analysis=$columns_analysis.$data_analysis;
    header('Content-Disposition: attachment; filename="'.$filename_analysis.'"');
    echo $data_analysis;
  }else {      
    if($_GET['option']=='detalle'){
      $filename_detail="Detalle(".date('d-m-Y H:i:s').").csv";
      $data_detail="";
      $data_header="";
      $columns_detail="RecordKey;LineNum;AccountCode;ItemCode;Currency;UnitPrice;Quantity;WarehouseCode;";      
      $columns_detail.= "\r\n";      
      $ItemCode_array=explode('<', $ItemCode);
      $UnitPrice_array=explode("<", $UnitPrice);
      $Quantity_array=explode("<", $Quantity);
      $WarehouseCode_array=explode("<", $WarehouseCode);
      $RecordKey_array=explode("<", $RecordKey);
      $LineNum_array= explode("<", $LineNum);
      $tm=0;     
      while($ItemCode_array[$tm]){
        $data_detail.=$RecordKey_array[$tm].';'.$LineNum_array[$tm].';'.$AccountCode.';'.$ItemCode_array[$tm].';'.$Currency.';'.$UnitPrice_array[$tm].';'.$Quantity_array[$tm].';'.$WarehouseCode_array[$tm].";"; 
        $data_detail .= "\r\n";
        $tm++;
      }
      $data_detail=$columns_detail.$data_detail;
      header('Content-Disposition: attachment; filename="'.$filename_detail.'"');
      echo $data_detail;    
    } elseif($_GET['option']=='cabecera'){
      $columns_header="RecordKey;Comments;DocDate;";
      $columns_header.= "\r\n";
      $hoy=date('d-m-Y');
      $filename_header="Encabezado(".date('d-m-Y H:i:s').").csv";
        ####### TABLA ENCABEZADOS
      $RecordKey_array =explode("<", $RecordKey_);
      $Comments_array  =explode("<",  $Comments_);
      $te=0;      
      while($RecordKey_array[$te]){
        $data_header.=$RecordKey_array[$te].';'.$Comments_array[$te].';'.$hoy.';';
        $te++;
        $data_header.= "\r\n";
      }
      $data_header=$columns_header.$data_header;
      header('Content-Disposition: attachment; filename="'.$filename_header.'"');
      echo $data_header;
    }//fin else $_GET[option]=detail
  }////fin else $_GET[option]=analisis
}//fin else si no hay errores en la primera consulta

?>