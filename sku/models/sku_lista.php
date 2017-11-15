<?php
require_once "../config/config.php";
require_once "../config/DBConnection.php";
require_once "../config/HelpersDB.php";
require_once "../config/inflector.php";
require_once "../config/sku_funciones.php";

error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
// $sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

if($_POST['option']=="save_article"){
  $first_barcode=getFirstBarcode();
  $lista=$_POST['list'];
  $hoy=date('Y-m-d h:i:s');
  $data=[];
  $skus=[];
  // $barcodes=[];
  $code_article=$_POST['articulo'];
  $itemname=$_POST['itemname'];
  $estado='listado';
  $id_list_inserted=0;
  $sku_inserteds=0;
  //COMPROBAMOS QUE EL ARTICULO NO ESTE PENDIENTE DE REVISION O CARGA
  $query_exist_aritcle="SELECT codigo FROM articulo WHERE codigo='$code_article' AND estado!='cargado'";  
  $arr_exist_article=$mysqli->select($query_exist_aritcle,'mysqli_a_o');
  if($arr_exist_article!=0 AND $arr_exist_article!=false){
    $data['EXISTE']='ARTICULO PENDIENTE DE REVISION O CARGAR\nREVISAR o consultar a INFORMARTICA por favor.';
    echo json_encode($data);
    exit();
  }

  ///--- INICIALMENTE ANALIZAMOS LOS SKUS, POR QUE PUEDE QUE YA EXISTA EL ARTICULO Y LA LISTA, ADEMAS DEPENDERÁ

    ///--- YA SEA PARA UNA NUEVA LISTA O UNA LISTA EXISTENTE, ES NECESARIO QUE EL NUEVO ARTICULO QUE SE INTENTA AGREGAR A LA LISTA, NO SE ENCUENTRE EN LA MISMA LISTA NI EN OTRA LISTA NI EN SAP
    if(existeArticle($code_article,'SAP')==true){
      $data['EXISTE_ARTICLE']='ARTICULO YA REGISTRADO EN SAP, elija la OPCION MODIFICAR ARTICULO para cambier algun detalle o AGREGAR + SKU';
      echo json_encode($data); exit();
    }
    if(existArticle($code_article,'LISTA')===true){
      $data['EXISTE_ARTICLE']='ARTICULO AGREGADO A OTRA LISTA, Revise las Listas pendientes para Cargarlas a SAP o modificarlas';
      echo json_encode($data); exit();
    }

  if($lista==0){
    //SIMPLEMENTE CREAREMOS LA NUEVA LISTA CON LOS DATOS DE LA PERSONA QUE TIENE LA SESION + LA FECHA DE LA CREACION

  }







  //DE LO CONTRARIO SEGUIMOS
  // $query_insert_list="INSERT INTO lista VALUES(NULL,'creacion','$hoy')";
  // if(($mysqli->insert_easy($query_insert_list))==1){
  //   $arr_last_list=$mysqli->select('SELECT @@identity AS id','mysqli_a_o');
  //   if($arr_last_list!=0 AND $arr_last_list!=false)
  //     $id_list_inserted=$arr_last_list[0]['id'];
  // }

  $query_insert_article="INSERT INTO articulo VALUES (";
  $query_insert_article.="'$code_article',$id_list_inserted,'".$_POST['itemname']."', ".$_POST['marca_code'].",'".$_POST['marca_name']."',".$_POST['dpto_code'].",'".$_POST['dpto_name']."',";
  $query_insert_article.=$_POST['subdpto_code'].",'".$_POST['subdpto_name']."','".$_POST['prenda_code']."','".$_POST['prenda_name']."','".$_POST['categoria_code']."','".$_POST['categoria_name']."',";
  $query_insert_article.=$_POST['presentacion_code'].",'".$_POST['presentacion_name']."',".$_POST['material_code'].",'".$_POST['material_name']."',";
  $query_insert_article.=$_POST['tprenda_code'].",'".$_POST['tprenda_name']."',".$_POST['tcatalogo_code'].",'".$_POST['tcatalogo_name']."',";
  $query_insert_article.=$_POST['grupouso_code'].",'".$_POST['grupouso_name']."','".$_POST['caracteristica']."',".$_POST['composicion_code'].",'".$_POST['composicion_name']."',";
  $query_insert_article.="'".$_POST['talla_familia']."',".$_POST['peso'].",'$estado')";
  $data['query_article']=$query_insert_article;




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
  $querys=[];
  for ($i = 0; $i < $colores_length; $i++){
    for ($j = 0; $j < $tallas_length; $j++){
      $sku=$code_article.'-'.substr($colores_name[$i],0,3).'-'.$tallas_name[$j];
      if(existeSku($sku,'SAP')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN SAP');
      }elseif(existeSku($sku,'LISTA')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN LISTA');
      }else{
        $query_sku="INSERT INTO sku VALUES('$sku','$code_article',".$colores_code[$i].",'".$colores_name[$i]."','".$tallas_name[$j]."','".$tallas_orden[$j]."','$copa','$fcopa','$hoy')";        
        if($mysqli->insert_easy($query_sku)==1){//agregamos a la Base de datos y creamos la fila para dibujar el div_article
          $barcode=intval($first_barcode) + $sku_inserteds;
          $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$sku.'">';        
          $filas.="<div><div>".(($i*$tallas_length)+$j+1)."</div><div>$sku</div><div>$barcode</div><div>$colores_name[$i]</div><div>$tallas_name[$j]</div><div>$img_delete</div></div>"; 
          $sku_inserteds++;       
        }else{
          $data[errors]=$mysqli->getErrors();
          $sku_refused[]=array('sku'=>$sku, 'detalle'=>"ERROR EN INSERSION");    
        }
      }

      
      // $querys[]=$query_sku; 
      // $values['sku_code']=$sku; // $values['articulo_codigo']=$code_article; // $values['color_code']=$colores_code[$i]; // $values['color_name']=$colores_name[$i]; // $values['talla_name']=$tallas_name[$j]; // $values['talla_orden']=$tallas_orden[$j]; // $values['copa']=$copa;  // $values['fcopa']=$fcopa; // $values['fecha_creacion']=$hoy;

      ///---BUSCAR EN 192.168.0.13 y en MYSQL list y si no existe guardamos en TABLA
      $query_search_13="SELECT itemCode from OITM WHERE itemCode='$sku'";
      $arr_exist_13=$sqlsrv->select($query_search_13,'sqlsrv_n_p');
      $query_search_list="SELECT sku_code from sku WHERE sku_code='$sku'";
      $arr_exist_list=$mysqli->select($query_search_list,'mysqli_a_o');
      if($arr_exist_13!=0 AND $arr_exist_13!=false){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN 192.138.0.13');
      }elseif($arr_exist_list!=0 AND $arr_exist_list!=false){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN LISTA');
      }else{
        if($mysqli->insert_easy($query_sku)==1){
          $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$sku.'">';        
          $filas.="<div><div>".(($i*$tallas_length)+$j+1)."</div><div>$sku</div><div>barcode</div><div>$colores_name[$i]</div><div>$tallas_name[$j]</div><div>$img_delete</div></div>";        
        }else{
          $data[errors]=$mysqli->getErrors();
          $sku_refused[]=array('sku'=>$sku, 'detalle'=>"ERROR EN INSERSION");    
        }
      }         
    }
  }
  $data['lista'];
  $data['articulo']=$code_article;
  $data['itemname']=$itemname;
  // $data['skus']=$values;
  $data['filas']=$filas;
  // $data['querys_sku']=$querys;
  if(isset($sku_refused))
    $data['refused']=$sku_refused;
  echo json_encode($data);

}
if($_POST['option']=="save_list"){

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
function 
?>
