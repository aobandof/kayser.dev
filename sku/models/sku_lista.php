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
  $lista=$_POST['list'];
  $code_article=$_POST['articulo'];
  $itemname=$_POST['itemname'];  
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

  $data=[];
  $sku_inserteds=[];
  $sku_refused=[];
  $first_barcode=getFirstBarcode();
  $hoy=date('Y-m-d h:i:s');
  $filas='';
  $querys=[];

  $data['first_barcode']=$first_barcode;
  

  ///--- YA SEA PARA UNA NUEVA LISTA O UNA LISTA EXISTENTE, ES NECESARIO QUE EL NUEVO ARTICULO QUE SE INTENTA AGREGAR A LA LISTA, NO SE ENCUENTRE EN LA MISMA LISTA NI EN OTRA LISTA NI EN SAP
  // if(existArticle($code_article,'SAP')===true){
  //   $data['nothing']='ARTICULO YA REGISTRADO EN SAP, elija la OPCION MODIFICAR ARTICULO para cambiar algun detalle o AGREGAR + SKU';
  //   echo json_encode($data); exit();
  // }
  // if(existArticle($code_article,'LISTA')===true){
  //   $data['nothing']='ARTICULO AGREGADO A OTRA LISTA, Revise las Listas pendientes para Cargarlas a SAP o modificarlas';
  //   echo json_encode($data); exit();
  // }
  ///--- CANCELAMOS ESTA OPCION, dado que puede ingresarse un mismo tipo de prenda con las mismas caracteristicas pero se trata de otro otro articulo con otros acabados y diseño
  #######################################################################################################################################################
  
  if($lista==0){
    //SIMPLEMENTE CREAREMOS LA NUEVA LISTA CON LOS DATOS DE LA PERSONA QUE TIENE LA SESION + LA FECHA DE LA CREACION
    $query_insert_list="INSERT INTO lista values (NULL,'','EDITADA')";
    $data['all_querys'][]=$query_insert_list;
    if($mysqli->insert_easy($query_insert_list)==1){
      $arr_last_list=$mysqli->select("SELECT @@identity AS id",'mysqli_a_o');
      if($arr_last_list!=0 AND $arr_last_list!=false){
        $lista=$arr_last_list[0]['id'];
        ///---agregamos el usuario, la lista, la operacion cuando se crea la lista por primera vez, el campo operacion y fecha se dejan como vacios, indicanto que aun no se envian por correo
        if($mysqli->insert_easy("INSERT INTO lista_has_usuario values ($lista,'EDITOR','','$hoy')") != 1 ){
          $data['all_querys'][]="INSERT INTO lista_has_usuario values ($lista,'EDITOR','','$hoy')";
          $data['errors']=$mysqli->getErrors();
          $data['nothing']='ERROR AL RELACIONAR LA LISTA CON EL USUARIO';        
          echo json_encode($data); exit();
        }
      }else{
        $data['errors']=$mysqli->getErrors();
        $data['nothing']='no se pudo obtener el id de la ultima fila ingresada';           
        echo json_encode($data); exit();

      }
    }else{
      $data['errors']=$mysqli->getErrors();
      $data['nothing']='NO SE PUDO CREAR LA LISTA';
      echo json_encode($data); exit();
    }
  }
  $data['lista']=$lista; 
  
  
  ///--- AHORA REGISTRAMOS EL ARTICULO
  $query_insert_article="INSERT INTO articulo VALUES (";
  $query_insert_article.="'$code_article',$lista,'".$_POST['itemname']."', ".$_POST['marca_code'].",'".$_POST['marca_name']."',".$_POST['dpto_code'].",'".$_POST['dpto_name']."',";
  $query_insert_article.=$_POST['subdpto_code'].",'".$_POST['subdpto_name']."','".$_POST['prenda_code']."','".$_POST['prenda_name']."','".$_POST['categoria_code']."','".$_POST['categoria_name']."',";
  $query_insert_article.=$_POST['presentacion_code'].",'".$_POST['presentacion_name']."',".$_POST['material_code'].",'".$_POST['material_name']."',";
  $query_insert_article.=$_POST['tprenda_code'].",'".$_POST['tprenda_name']."',".$_POST['tcatalogo_code'].",'".$_POST['tcatalogo_name']."',";
  $query_insert_article.=$_POST['grupouso_code'].",'".$_POST['grupouso_name']."',".$_POST['caracteristica_code'].",'".$_POST['caracteristica_name']."',".$_POST['composicion_code'].",'".$_POST['composicion_name']."',";
  $query_insert_article.="'".$_POST['talla_familia']."')";
  $data['all_querys'][]=$query_insert_article;
  // echo json_encode($data); 
  
  if($mysqli->insert_easy($query_insert_article)!=1){
    $cant_registros_lista=quantityRecords("select codigo from articulo where lista_id=$lista");
    if($cant_registros_lista==0)
      $mysqli->delete("DELETE FROM lista WHERE id=$lista");      
    $data['errors']=$mysqli->getErrors();
    $data['nothing']='NO SE PUDO CREAR EL ARTICULO';
    echo json_encode($data); exit();
  }
  
  ///--- AHORA REGISTRAMOS LOS SKUS
  for ($i = 0; $i < $colores_length; $i++){
    for ($j = 0; $j < $tallas_length; $j++){
      $sku=$code_article.'-'.substr($colores_name[$i],0,3).'-'.$tallas_name[$j];
      if(existSku($sku,'SAP')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN SAP');
      }elseif(existSku($sku,'LISTA')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN LISTA');
      }else{
        $barcode=$first_barcode + count($sku_inserteds);
        $barcode=(string)$barcode;
        $query_sku="INSERT INTO sku VALUES('$sku','$code_article','$barcode',".$colores_code[$i].",'".$colores_name[$i]."','".$tallas_name[$j]."','".$tallas_orden[$j]."','$copa','$fcopa','$hoy')"; 
        $data['all_querys'][]=$query_sku;       
        if($mysqli->insert_easy($query_sku)==1){//agregamos a la Base de datos y creamos la fila para dibujar el div_article          
          $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$sku.'">';        
          $filas.="<div><div>".(($i*$tallas_length)+$j+1)."</div><div>$sku</div><div>$barcode</div><div>$colores_name[$i]</div><div>$tallas_name[$j]</div><div>$img_delete</div></div>"; 
          $sku_inserteds[]=$sku;     
        }else{
          $data['errors']=$mysqli->getErrors();
          $sku_refused[]=array('sku'=>$sku, 'detalle'=>"ERROR EN INSERSION");    
        }
      }
    }
  }
  
  #######################################################################################################################################################
  ///--- como llegamos hasta aca, ya registramos el articulo y/o la lista (si fuera nueva) y los sku siempre y cuando count($sku_inserteds) sea distinto 
  if(count($sku_inserteds)==0){ 
    //SI LA LISTA ES NUEVA, ELIMINAMOS LA LISTA, Y POR CASCADA TB EL ARTICULO, y la relacion lista_has_usuario
    //para saber si una lista es nueva, consultamos este numero de lista en todos los articulos menos este
    $cant_registros_lista=quantityRecords("select codigo from articulo where lista_id=$lista AND codigo!='$code_article'");
    $data['all_querys'][]="select codigo from articulo where lista_id=$lista AND codigo!='$code_article'";
    if($cant_registros_lista==0){
      if(($mysqli->delete("DELETE FROM lista WHERE id=$lista"))==1)
        $data['all_querys'][]="DELETE FROM lista WHERE id=$lista";
      else{
        $data['errors']=$mysqli->getErrors();
        $data['nothing']="AL NO HABER SKUS GUARDADOS, Y AL INTENTAR ELIMINAR LA LISTA, QUE ES NUEVA, SE ENCONTRARON ERRORES";
        echo json_encode($data); exit();
      }
    }else{
      if($cant_registros_lista!=false){//quiere decir que exiten otros articulos en esta lista, por lo que solamente eliminamos este articulo
        if($mysql->delete("delete from articulo where codigo='$code_article'")==1){
          $data['nothing']="NO SE PUDO AGREGAR EL ARTICULO DADO QUE TODOS LOS SKUS ELEGIDOS ESTAN EN LISTA O EN SAP";          
        }
        else{
          $data['nothing']="NO SE PUDO ELIMINAR EL ARTICULO $code_article, REVISAR Y ELIMINARLO MANUALMENTE POR FAVOR";
        }
        echo json_encode($data); exit();
      }else {
        $data['errors']=$mysqli->getErrors();
        echo json_encode($data); exit();
      }        
    }
    echo json_encode($data);
  }else {
    $data['skus_insertados']=$sku_inserteds;
    $data['articulo']=$code_article;
    $data['itemname']=$itemname;
    $data['filas']=$filas;
    $data['lista']=$lista;
    if(count($sku_refused)!=0) { $data['refused']=$sku_refused; }
    echo json_encode($data);
  }
}

if($_POST['option']=="delete_list") {
  $lista=$_POST['list'];
  /// ACA NOS QUEDAMOS
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
function existArticle($cod_art,$serv){
  global $mysqli;
  if($serv=='SAP'){
    $query_exist_article="SELECT TOP 1 U_APOLLO_SEG1 FROM OITM WHERE U_APOLLO_SEG1='$cod_art'";    
    $arr_exist_article=$mysqli->select($query_exist_article,'mysqli_a_o');
    // echo $query_exist_article."<br>";
  }elseif($serv=='LISTA'){ 
    $query_exist_article="SELECT codigo FROM articulo WHERE codigo='$cod_art'";
    $arr_exist_article=$mysqli->select($query_exist_article,'mysqli_a_o');
    // echo $query_exist_article."<br>";
  }
  
  if($arr_exist_article!=0 AND $arr_exist_article!=false)
    return true;
  else
    return false;
}
function existSku($cod_sku, $serv){
  global $mysqli;
  if($serv='SAP'){
    $query_exist_sku="SELECT TOP 1 ItemCode FROM OITM WHERE ItemCode='$cod_sku'";
    $arr_exist_sku=$mysqli->select($query_exist_sku,'mysqli_a_o');
  }else {
    $query_exist_sku="SELECT codigo FROM sku WHERE codigo='$cod_sku'";
    $arr_exist_article=$mysqli->select($query_exist_sku,'mysqli_a_o');
  }
  if($arr_exist_sku!=0 AND $arr_exist_sku!=false)
    return true;
  else
    return false;         
}
function deleteSkus($skus){
  global $mysqli;
  for ($i=0; $i<count($skus); $i++){
    $mysqli->delete("delete from sku where codigo="+$skus[$i]);
  }
}
?>
