<?php
session_start();
if(isset($_SESSION['user'])){
  $user=$_SESSION['user'];
}else {
  header("Location: ./index.php");
}

require_once "../config/require.php";
require_once "../config/sku_db_mysqli.php";
require_once "../config/sku_db_sqlsrv_33.php";

$hoy=date('Y-m-d H.i.s');

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
    $copa=$_POST['copa']; $fcopa=$_POST['fcopa'];
  }else {
    $copa=''; $fcopa='';
  }  
  $data=[];
  $sku_inserteds=[];
  $sku_refused=[];
  $first_barcode=getFirstBarcode();
  $filas='';
  $querys=[];
  $data['lista']=$lista; 
  $data['first_barcode']=$first_barcode;

  if($lista==0){
    //SIMPLEMENTE CREAREMOS LA NUEVA LISTA CON LOS DATOS DE LA PERSONA QUE TIENE LA SESION + LA FECHA DE LA CREACION
    $query_insert_list="INSERT INTO lista values (NULL,'','EDITADA')";
    $data['all_querys'][]=$query_insert_list;
    if($mysqli->insert_easy($query_insert_list)==1){
      $arr_last_list=$mysqli->select("SELECT @@identity AS id",'mysqli_a_o');
      if($arr_last_list!=0 AND $arr_last_list!=false){
        $lista=$arr_last_list[0]['id'];
        ///---agregamos el usuario, la lista, la operacion cuando se crea la lista por primera vez, el campo operacion y fecha se dejan como vacios, indicanto que aun no se envian por correo
        if($mysqli->insert_easy("INSERT INTO lista_has_usuario values ($lista,'$user','CREACION','$hoy')") != 1 ){
          $data['all_querys'][]="INSERT INTO lista_has_usuario values ($lista,'$user','CREACION','$hoy')";
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
  

  ///--- AHORA REGISTRAMOS EL ARTICULO
  $query_insert_article="INSERT INTO articulo VALUES (";
  $query_insert_article.="'$code_article',$lista,'".$_POST['itemname']."', ".$_POST['marca_code'].",'".$_POST['marca_name']."',".$_POST['dpto_code'].",'".$_POST['dpto_name']."',";
  $query_insert_article.=$_POST['subdpto_code'].",'".$_POST['subdpto_name']."','".$_POST['prenda_code']."','".$_POST['prenda_name']."','".$_POST['categoria_code']."','".$_POST['categoria_name']."',";
  $query_insert_article.=$_POST['presentacion_code'].",'".$_POST['presentacion_name']."',".$_POST['material_code'].",'".$_POST['material_name']."',";
  $query_insert_article.=$_POST['tprenda_code'].",'".$_POST['tprenda_name']."',".$_POST['tcatalogo_code'].",'".$_POST['tcatalogo_name']."',";
  $query_insert_article.=$_POST['grupouso_code'].",'".$_POST['grupouso_name']."',".$_POST['caracteristica_code'].",'".$_POST['caracteristica_name']."',".$_POST['composicion_code'].",'".$_POST['composicion_name']."',";
  $query_insert_article.="'".$_POST['talla_familia']."')";
  $data['all_querys'][]=$query_insert_article;

  $errors=[];///DE AQUI EN ADELANTE PUEDEN HABER ERRORES ACUMULATIVOS
  $reg_inserted=$mysqli->insert_easy($query_insert_article);
  if($reg_inserted!=1){
    $errors[]=$mysqli->getErrors();  
    $cant_registros_lista=$mysqli->quantityRecords("select codigo from articulo where lista_id=$lista");
    if($cant_registros_lista=false){
       $errors[]=$mysqli->getErrors();
       $data['msj']='no se creo el articulo y hubo error al consultar la cantidad de articulos en esta lista';  
    }elseif($cant_registros_lista==0){
      if(($mysqli->delete("DELETE FROM lista WHERE id=$lista"))==false){
        $errors[]=$mysqli->getErrors();
        $data['msj']='no se creo el articulo, la lista estaba nueva y no se pudo eliminar'; 
      }
    }  
    $data['errors']=$errors;
    $data['nothing']='NO SE PUDO CREAR EL ARTICULO';
    echo json_encode($data); exit();
  }

  ///--- AHORA REGISTRAMOS LOS SKUS
  for ($i = 0; $i < $colores_length; $i++){
    for ($j = 0; $j < $tallas_length; $j++){
      if($copa!='' && $copa!='S/P')
        $sku=$code_article.'-'.substr($colores_name[$i],0,3).$copa.'-'.$tallas_name[$j];
      else
        $sku=$code_article.'-'.substr($colores_name[$i],0,3).'-'.$tallas_name[$j];
      if(existSku($sku,'SAP')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN SAP');
      }elseif(existSku($sku,'LISTA')==true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN LISTA');
      }else{
        $barcode=$first_barcode + count($sku_inserteds);
        $barcode=(string)$barcode;
        $barcode=$barcode.getControlDigit($barcode);
        $query_sku="INSERT INTO sku VALUES('$sku','$code_article','$barcode',".$colores_code[$i].",'".$colores_name[$i]."','".$tallas_name[$j]."','".$tallas_orden[$j]."','$copa','$fcopa')"; 
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
  ///SUPONGO QUE SOLO BASTA CON ELIMINAR LA VISTA, Y POR CASCADA, SE ELIMINARAN LOS ARTICULOS Y LOS SKUS, ADEMAS DE LISTA_HAS_USER
  $query_delete_list="DELETE from lista WHERE id=$lista";
  $querys_all[]=$query_delete_list;
  $reg_deleted=$mysqli->delete($query_delete_list);
  if($reg_deleted!=1){
    $data['delete']=false;
    $data['errors']=$mysqli->getErrors();
  }else{
    $data['delete']=true;
  }
  $data["query_all"]=$querys_all;
  echo json_encode($data);

}

if($_POST['option']=="save_list") {
  $lista=$_POST['list'];
  ($_POST['operation']=='creation') ? $nomb_lista="PLANILLA_CREACION_SKUS_LISTA_N-$lista_($hoy)" :  $nomb_lista="PLANILLA_REVISION_SKUS_LISTA_N-$lista_($hoy)";

  ///--- OBTENEMOS TODOS LOS SKU 
  $query_skus_carga="SELECT S.codigo as cod_sku, S.barcode, A.caracteristica_name, A.itemname, A.dpto_code, A.prenda_name, A.codigo as cod_articulo, ";
  $query_skus_carga.="S.color_name, S.talla_name, A.talla_familia, A.prenda_code, S.talla_orden, A.marca_name, A.tprenda_name, A.material_name, A.grupouso_name, ";
  $query_skus_carga.="A.subdpto_name, A.composicion_name, A.categoria_code, S.copa, A.presentacion_name, A.tcatalogo_name, S.fcopa FROM sku as S ";
  $query_skus_carga.="INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=$lista ORDER BY S.barcode ASC";
  
  $data['querys_all'][]=$query_skus_carga;
  $arr_skus_carga=$mysqli->select($query_skus_carga,"mysqli_a_o");
  if($arr_skus_carga!=0 && $arr_skus_carga!=false){
    $send=sendMail($arr_skus_carga);
    if($send===true){
      $data['submit']=true;
      ($_POST['operation']=='creation') ? $estado_lista='CREADA' :  $estado_lista='REVISADA';
      $query_state="UPDATE lista SET estado='$estado_lista' WHERE id=$lista";
      $reg_updated=$mysqli->update_easy($query_state);
      if($reg_updated==1){      
        $data['list_updated']=true;
        ///AHORA GUARDAREMOS LA ACCION DE REVISADO EN LA RELACION LISTA USUARIO
        if($_POST['operation']=='review'){
          if($mysqli->insert_easy("INSERT INTO lista_has_usuario values ($lista,'$user','REVISION','$hoy')") != 1 ){
            $data['errors']=$mysqli->getErrors();
          }
        }
      }else{ $reg_updated==1 ? $data['errors']=$mysqli->getErrors() : $data['cant_lists_updated']=0; }
    }else
      $data['submit']=false;
  }else{
    $arr_skus_carga==false ? $data['errors']=$mysqli->getErrors() : $data['cant_skus']=0;
  }
  echo json_encode($data);
}

if($_POST['option']=='show_lists'){
  $creator='';
  $revisor='';
  $divs="";
  $query_lists = "SELECT L.id, COUNT(S.codigo) as cant_skus from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id";
  $all_querys[]=$query_insert_list;
  $arr_lists=$mysqli->select($query_lists,'mysqli_a_o');
  $data['cant_primera_consulta']=$arr_lists;
  if($arr_lists!=0 && $arr_lists!=false){
    for($i=0; $i<count($arr_lists); $i++){
      $cod_list=$arr_lists[$i]['id'];
      $cant_skus=$arr_lists[$i]['cant_skus'];
      $icon_show="<img id='$cod_list' class='icon_dtable' src='./src/img/lupa.png' alt='Ver contenido Lista'>";
      ///--- OBTENEMOS QUIEN LA CREO Y LA REVISO
      $query_relation="SELECT * FROM lista_has_usuario where lista_id=$cod_list";
      $all_querys[]=$query_relation;
      $arr_relation=$mysqli->select($query_relation,"mysqli_a_o");
      if($arr_relation!=0 && $arr_relation!=false){
        for($j=0; $j<count($arr_relation); $j++){
          if($arr_relation[$j]['operacion']=='CREACION'){
            $creator=$arr_relation[$j]['usuario_user']." ( ".$arr_relation[$j]['fecha']." )";
          }elseif($arr_relation[$j]['operacion']=='REVISION'){
            $revisor=$arr_relation[$j]['usuario_user']." ( ".$arr_relation[$j]['fecha']." )";
          }
        }
      }    
      $divs.="<div class='dtr'><div>$cod_list</div><div>".$creator."</div><div>".$revisor."</div><div>$cant_skus</div><div>$icon_show</div></div>";
    }
  }
  $data['rows']=$divs;
  $data['querys_all']=$all_querys;
  echo json_encode($data);
}

if($_POST['option']=='get_articles'){
  $lista=$_POST['list'];
  $s=1;//para enumerar los skus por columnas
  $a=0;//cantidad de articulos que usasremos para llenar el arr_export;
  $arr_export=[];
  $query_skus="SELECT S.codigo as cod_sku, S.barcode, S.color_name, S.talla_name, A.codigo as cod_articulo, A.itemname FROM sku as S INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=$lista ORDER BY S.barcode ASC";
  $arr_skus=$mysqli->select($query_skus,"mysqli_a_o");
  if($arr_skus!=0 && $arr_skus!=false){
    $cant_registros=count($arr_skus);
    $art_temp=$arr_skus[0]['cod_articulo'];
    for($i=0;$i<$cant_registros;$i++){
      if ($arr_skus[$i]['cod_articulo']!=$art_temp){
        $art_temp=$arr_skus[$i]['cod_articulo'];         
        $i--;      // que cuando itere aumentara en 1 y volvera  al valor correcto de $i pero sin entrar a esta condicion 
        $arr_export[$a]['articulo']=$arr_skus[$i]['cod_articulo'];
        $arr_export[$a]['itemname']=$arr_skus[$i]['itemname'];
        $arr_export[$a]['skus']=$rows;
        $a++;
        $rows='';
        $s=1;
      }else {
        $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$arr_skus[$i]['cod_sku'].'">';        
        $rows.="<div><div>".$s."</div><div>".$arr_skus[$i]['cod_sku']."</div><div>".$arr_skus[$i]['barcode']."</div><div>".$arr_skus[$i]['color_name']."</div><div>".$arr_skus[$i]['talla_name']."</div><div>$img_delete</div></div>"; 
        $s++;
      }
    }
    $arr_export[$a]['articulo']=$arr_skus[$i-1]['cod_articulo'];
    $arr_export[$a]['itemname']=$arr_skus[$i-1]['itemname'];
    $arr_export[$a]['skus']=$rows;
    $data['articulos']=$arr_export;
  }else{
    $arr_skus==false ? $data['errors']=$mysqli->getErrors() : $data['cant_skus']=0;
  }
  echo json_encode($data);

}

if($_POST['option']=='finalize_list'){
  $lista=$_POST['list']; $user_creator=''; $user_reviser='';
  ///--- OBTENEOS LOS SKU, LOS USUARIOS QUE LO CREARON, REVISARON
  $query_lista_usuario="SELECT usuario_user, operacion FROM lista_has_usuario WHERE lista_id=$lista";
  $arr_lista_usuario = $mysqli->select($query_lista_usuario,"mysqli_a_o");
  if($arr_lista_usuario!=0 && $arr_lista_usuario!=false){
    for($i=0; $i<$count($arr_lista_usuario); $i++){
      $arr_lista_usuario[$i]['operacion']=="CREACION" ? $user_creator=$arr_lista_usuario[$i]['usuario_user'] : $user_reviser=$arr_lista_usuario[$i]['usuario_user'];
    }
    $data['creator']=$user_creator;
    $data['reviser']=$user_reviser;
  }
  //// FALTA MAS, PERO PROBEMOS ESTO POR MIENTRAS

  echo json_encode($data);

}
function sendMail($arr_cont){
  global $nomb_lista;
  $content_csv="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalesUnit;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgStdPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";
  $content_csv.="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalUnitMsr;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";

  $cant_skus=count($arr_cont);
  for($i=0; $i<$cant_skus; $i++){
    $fila_csv="";
    $fila_csv.= ($i+1).";";
    $fila_csv.= $arr_cont[$i]['cod_sku'].";";
    $fila_csv.= strval($arr_cont[$i]['barcode']).";tNO;";                  ///---con columna default
    $fila_csv.= $arr_cont[$i]['caracteristica_name'].";C;tYES;tNO;M;1;";             ///---con columnaS default
    $fila_csv.= $arr_cont[$i]['itemname'].";"; 
    $fila_csv.= $arr_cont[$i]['dpto_code'].";tYES;M;";                          ///---con columnaS default                   
    $fila_csv.= $arr_cont[$i]['prenda_name'].";";
    $fila_csv.= $arr_cont[$i]['cod_articulo'].";"; 
    $fila_csv.= $arr_cont[$i]['color_name'].";";
    $fila_csv.= $arr_cont[$i]['talla_name'].";";
    $fila_csv.= $arr_cont[$i]['talla_familia'].";";
    $fila_csv.= $arr_cont[$i]['prenda_code'].";1;";                             ///---con columnaS default
    $fila_csv.= $arr_cont[$i]['talla_orden'].";Y;";                                           ///---con columnaS default
    $fila_csv.= $arr_cont[$i]['marca_name'].";";
    $fila_csv.= $arr_cont[$i]['tprenda_name'].";";
    $fila_csv.= $arr_cont[$i]['material_name'].";";
    $fila_csv.= $arr_cont[$i]['grupouso_name'].";";
    $fila_csv.= $arr_cont[$i]['subdpto_name'].";";
    $fila_csv.= $arr_cont[$i]['composicion_name'].";Y;;";                       ///---con columnaS default
    $fila_csv.= $arr_cont[$i]['categoria_code'].";;";
    $fila_csv.= $arr_cont[$i]['copa'].";";
    $fila_csv.= $arr_cont[$i]['presentacion_name'].";";
    $fila_csv.= $arr_cont[$i]['tcatalogo_name'].";";
    $fila_csv.= $arr_cont[$i]['fcopa']."\r\n"; 
    $content_csv.=$fila_csv;       
  }
  ///--- ############################### ---
  ///--- DATOS PARA ENVIO DE CSV AL MAIL ---
  ///--- ############################### ---
  $destinatario ="aobando@kayser.cl";
  $headers = "From: Creacion de SKU <sku@kayser.cl>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: application/octet-stream; name=".$nomb_lista.".csv\r\n"; //envio directo de datos
  $headers .= "Content-Disposition: attachment; filename=".$nomb_lista.".csv\r\n";
  $headers .= "Content-Transfer-Encoding: binary\r\n";
  $headers .= utf8_decode($content_csv);
  $headers .= "\r\n";

  if(mail($destinatario, $nomb_lista,"", $headers)){
    return true;
  }
  else{
    return false;
  }
  return true;
  // return($content_csv);   
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
