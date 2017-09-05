<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
$sqlsrv_13_stock=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv_17_wmstek=new DBConnection('sqlsrv', $MSSQL['17']['host'], $MSSQL['17']['user'], $MSSQL['17']['pass'],'WMSTEK_KAYSER');
$mysqli_dev_articulos=new DBConnection('mysqli', $MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if($sqlsrv_13_stock->getConnection()===false) { $data['errors'][]=$sqlsrv_13_stock->getErrors(); $existe_error_conexion=1; }
if($sqlsrv_17_wmstek->getConnection()===false)  { $data['errors'][]=$sqlsrv_17_wmstek->getErrors(); $existe_error_conexion=1; }
if($mysqli_dev_articulos->getConnection()===false)  {$data['errors'][]=$mysqli_dev_articulos->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}
$sku=$_POST['sku'];

// **************************   CARGA EN DATA EL Precio del Articulo o SKU buscado ***************************
if(isset($_POST['precio'])){ // SI LA VISTA SOLICITO consulta_precios
  if($_POST['precio']==15)// PRECIOS POR DETALLE
    $query_precios="SELECT  TOP 1 /*ItemCode,*/ CAST(ROUND((Price*1.19),0) AS int) as Precio FROM Kayser_ITM1  where PriceList=15 AND ROUND((Price*1.19),0) IS NOT NULL and ItemCode LIKE '$sku%' ORDER BY ItemCode ASC";
  else  // PRECIOS PROMOTORAS
    $query_precios="SELECT	TOP 1 /*ItemCode,*/ CAST(ROUND((Price*1.19),0) AS int) AS Precio FROM Kayser_ITM1  where PriceList=17 AND ROUND((Price*1.19),0) IS NOT NULL and ItemCode LIKE '$sku%' ORDER BY ItemCode ASC";
  if(($arr_precios=$sqlsrv_13_stock->select($query_precios))===false)
    $data['errors'][]=$sqlsrv_13_stock->getErrors();
  else{
    if($arr_precios==0)
      $data['precio']="SIN RESULTADOS";
    else
      $data['precio']=$arr_precios[0]['Precio'];
  }
}
// **************************  CARGA EN DATA EL ARRAY CON el STOCK del Articulo o SKU buscado **************
if(isset($_POST['stock'])){
  if($_POST['stock']=='disponible') // STOCK DISPONIBLE
    $query_stock="SELECT IdArticulo, CAST(SUM(Cantidad) AS int) as Cant from Existencia where idAlmacen = '001' AND IdUbicacion LIKE '01%' AND IdArticulo LIKE '$sku%' GROUP BY IdArticulo ORDER BY IdArticulo";
  else// STOCK TOTAL MENOS FALLADOS
    $query_stock="SELECT IdArticulo, CAST(SUM(Cantidad) AS int) as Cant from Existencia where idAlmacen = '001' AND IdUbicacion NOT LIKE 'FALLA%' AND IdArticulo LIKE '$sku%' GROUP BY IdArticulo ORDER BY IdArticulo";
  if(($arr_stock=$sqlsrv_17_wmstek->select($query_stock))===false)
    $data['errors'][]=$sqlsrv_17_wmstek->getErrors();
  else{
    if($arr_stock==0)
      $data['stock']="SIN RESULTADOS";
    else
      $data['stock']=$arr_stock;
  }
}

if($_POST['opcion']='cargar_select_tiendas'){
  $query_tiendas = "SELECT WhsCode as Codigo,WhsName as Nombre from Kayser_OWHS where (U_GSP_SENDTPV='Y') order by WhsName" ;
  if(!$arr_tiendas=$sqlsrv_13_stock->select($query_tiendas))
    $data['errors'][]=$sqlsrv_13_stock->getErrors();
  else
    $data['tiendas']=$arr_tiendas;
}

if(isset($_POST['cargar_busqueda_skau'])){


}

echo json_encode($data);
// echo "</br></br>";
// print_r($data['errors']);echo "</br></br>";
// print_r($data['stock']);echo "</br></br>";
// print_r($data['precios']);echo "</br></br>";
// print_r($data['tiendas']);echo "</br></br>";
?>
