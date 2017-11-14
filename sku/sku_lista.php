<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/inflector.php";
require_once "sku_funciones.php";

error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
///$sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
///if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

if($_POST['option']=='save_and_send_skus'){
  // $send=sendMail($_POST);
  // if($send===true)
  //   $data['resp']='READY';
  // else
  //   $data['resp']='NO SE PUDO ENVIAR EL ARCHIVO...';
  $data=sendMail($_POST);
  echo json_encode($data);
}

///--- GUARDAREMOS EL ARTICULO CON SKUS O SOLAMENTE SKUS DEPENDIENDO DE LA OPCION
if($_POST['option']=="save_article_list"){
  ///$first_barcode=getFirstBarcode()  
  $hoy=date('Y-m-d h:i:s');
  $data=[];
  $skus=[];
  $sku_refused=[];
  $barcodes=[];
  $code_article=$_POST['articulo'];
  $itemname=$_POST['itemname'];
  
  $query_insert_article="INSERT INTO articulo VALUES ('$code_article','".$_POST['itemname']."', ".$_POST['marca_code'].",'".$_POST['marca_name']."'";
  $colores_name=$_POST['colores_name'];
  $colores_code=$_POST['colores_code'];
  $tallas_name=$_POST['tallas_name'];
  $tallas_orden=$_POST['tallas_orden'];
  $colores_length=count($colores_name);
  $tallas_length=count($tallas_name);
  if(isset($_POST['copa'])){
    $copa=$_POST['copa'];
    $fcopa=$_POST['fcopa'];
  }else {
    $copa='';
    $fcopa='';
  }
  $filas='';
  $values=[];
  // $data['colores']=$colores_name;
  // $data['colores_length']=$colores_length;
  // $data['tallas_length']=$tallas_length;
  
  for ($i = 0; $i < $colores_length; $i++){
    for ($j = 0; $j < $tallas_length; $j++){
      $sku=$code_article.'-'.substr($colores_name[$i],0,3).'-'.$tallas_name[$j];
      // $query_sku="INSERT INTO sku VALUES('$sku','$code_article',".$colores_code[$i].",'".$colores_name[$i]."','".$tallas_name[$j]."','".$tallas_orden[$j]."','$copa','$fcopa','$hoy')";
      $values['sku_code']=$sku;
      $values['articulo_codigo']=$code_article;
      $values['color_code']=$colores_code[$i];
      $values['color_name']=$colores_name[$i];
      $values['talla_name']=$tallas_name[$j];
      $values['talla_orden']=$tallas_orden[$j];
      $values['copa']=$copa;
      $values['fcopa']=$fcopa;
      $values['fecha_creacion']=$hoy;
      /// if($mysqli->insert('sku',$values)==1){
        $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$sku.'">';        
        $filas.="<div><div>".($i*$j+1)."</div><div>$sku</div><div>barcode</div><div>$colores_name[$i]</div><div>$tallas_name[$i]</div><div>$img_delete</div></div>";        
      /// }
      
      ///---BUSCAR EN 192.168.0.13 y en MYSQL list
      ///$query_search_13="SELECT itemCode from Kayser_OITM WHERE itemCode='$sku'";
      ///$arr_exist_13=$sqlsrv->select($query_search_13,'sqlsrv_n_p');
      ///$query_search_list="SELECT sku_code from sku WHERE sku_code='$sku'";
      ///$arr_exist_list=$mysqli->select($query_search_list,'mysqli_a_o');
      ///if($arr_exist_13!=0 AND $arr_exist_13!=false){
        ///$sku_refused['sku']=$sku;
        ///$sku_refused['origin']='192.138.0.13';
      ///}elseif($arr_exist_list!=0 AND $arr_exist_list!=false){
        ///$sku_refused['sku']=$sku;
        ///$sku_refused['origin']='LISTA';
      ///}else{
        //ACA REGISTRAMOS EL SKU EN LA LISTA Y DIBUJAMOS LA FILA DE DIVS
        
      ///}        
      ///---Buscar en Mysql Lista_Articulo      
    }
  }

  //ESTA OPCION ES CUANDO SE DISPARA EL EVENTO DE GENERAR UN ARTICULO NUEVO, POR LO QUE NO HAY QUE PREGUNTAR POR NADA,
  //CREAMOS LOS SKUS CON SUS BARCODES CORRESPONDIENTES PARA DESPUES
  //DEDICARNOSS A INSERTAR EN LAS TABLAS: ARTICULO, SKU Y LISTA

  //DEVOLVEREMOS UN ARRAY CON LOS SKUS (sku_code, barcode, color y talla) para poder llenar el componente a crear
  $data['articulo']=$code_article;
  $data['itemname']=$itemname;
  $data['skus']=$values;
  $data['filas']=$filas;

  echo json_encode($data);

}

function sendMail($arr_cont){
  $content_csv="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalesUnit;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgStdPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";
  $content_csv.="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalUnitMsr;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";
  $cant_colores=count($arr_cont['colores_name']);
  $cant_tallas=count($arr_cont['tallas_name']);
  if(isset($arr_cont['copa_name']))
    $copa=$arr_cont['copa_name'];
  $c=0;
  $t=0;
  for($i=0; $i<count($arr_cont['skus']); $i++){
    if($t<$cant_tallas){
      $colorito=$arr_cont['colores_name'][$c];
      $tallita=$arr_cont['tallas_name'][$t];
      $ordencito=$arr_cont['tallas_orden'][$t];
      $t++;
    }else {      
      $colorito=$arr_cont['colores_name'][$c+1];
      $tallita=$arr_cont['tallas_name'][0];
      $ordencito=$arr_cont['tallas_orden'][0];
      $t=1;
      $c++;
    }      
    $fila_csv="";
    $fila_csv.= ($i+1).";";
    $fila_csv.= $arr_cont['skus'][$i].";";
    $fila_csv.= strval($arr_cont['barcodes'][$i]).";tNO;";                  ///---con columna default
    $fila_csv.= $arr_cont['caracteristica'].";C;tYES;tNO;M;1;";             ///---con columnaS default
    $fila_csv.= $arr_cont['itemname'].";"; 
    $fila_csv.= $arr_cont['dpto_code'].";tYES;M;";                          ///---con columnaS default                   
    $fila_csv.= $arr_cont['prenda_name'].";";
    $fila_csv.= $arr_cont['articulo'].";"; 
    $fila_csv.= $colorito.";";
    $fila_csv.= $tallita.";";
    $fila_csv.= $arr_cont['talla_familia'].";";
    $fila_csv.= $arr_cont['prenda_code'].";1;";                             ///---con columnaS default
    $fila_csv.= $ordencito.";Y;";                                           ///---con columnaS default
    $fila_csv.= $arr_cont['marca_name'].";";
    $fila_csv.= $arr_cont['tprenda_name'].";";
    $fila_csv.= $arr_cont['material_name'].";";
    $fila_csv.= $arr_cont['grupo_uso_name'].";";
    $fila_csv.= $arr_cont['subdpto_name'].";";
    $fila_csv.= $arr_cont['composicion_name'].";Y;;";                       ///---con columnaS default
    $fila_csv.= $arr_cont['categoria_code'].";;";
    $fila_csv.= $arr_cont['copa_name'].";";
    $fila_csv.= $arr_cont['presentacion_name'].";";
    $fila_csv.= $arr_cont['tcatalogo_name'].";";
    $fila_csv.= $arr_cont['copa_name']."\r\n"; 
    $content_csv.=$fila_csv;       
  }
  ///--- ############################### ---
  ///--- DATOS PARA ENVIO DE CSV AL MAIL ---
  ///--- ############################### ---
  $hoy=date("Y-m-d H.i.s");
  $destinatario ="aobando@kayser.cl,mmora@kayser.cl";
  $titulo = "PLANTILLA_CARGA_SKUS_(".$hoy.")";
  $headers = "From: DISENO <diseno@kayser.cl>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: application/octet-stream; name=".$titulo.".csv\r\n"; //envio directo de datos
  $headers .= "Content-Disposition: attachment; filename=".$titulo.".csv\r\n";
  $headers .= "Content-Transfer-Encoding: binary\r\n";
  $headers .= utf8_decode($content_csv);
  $headers .= "\r\n";

  // if(mail($destinatario, $titulo,"", $headers)){
  //   return true;
  // }
  // else{
  //   return false;
  // }
  return($content_csv);   
}
?>
