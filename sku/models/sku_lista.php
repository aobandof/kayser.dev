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
  $code_article=strtoupper($_POST['articulo']);
  $itemname=$_POST['itemname'];  
  $colores_name=$_POST['colores_name'];
  $colores_code=$_POST['colores_code'];
  $tallas_name=$_POST['tallas_name'];
  $tallas_orden=$_POST['tallas_orden'];
  $colores_length=count($colores_name);
  $tallas_length=count($tallas_name);
  (isset($_POST['existencia'])) ? $existencia=$_POST['existencia'] : $existencia='nuevo';  
  if(isset($_POST['copa'])){
    $copa=$_POST['copa'];
    $fcopa=$_POST['fcopa'];
  }else {
    $copa=''; $fcopa='';
  }  
  $data=[];
  $sku_inserteds=[];
  $sku_refused=[];
  $first_barcode=getFirstBarcode();
  $filas='';
  $querys=[];
  $data['first_barcode']=$first_barcode;
  $data['code_article']=$code_article;
  $data['existencia'] = $existencia;
  ///debido a que permitieron que los txt correlativo y prefijo sean editables, inicialmente preguntamos si el articulo existe o no
  ///en base a ello debemos antes qe nada preguntar si existe el articulo, sea en sap o en las listas, si sí entonces salimos de inmediato y si la lista no es nueva, entonces no se agrega, pero si no, simplemente no se agrega el articulo
  if(existArticle($code_article,'SAP') && $existencia!='sap'){
    $data['nothing']='ARTICULO EXISTE EN SAP, no es posible crearlo';        
    echo json_encode($data); exit();
  }/// SI NO SIGUE TRABAJANDO COMO ANTES
  if(existArticle($code_article,'LISTA')){
      $data['nothing']='ARTICULO ya existe en alguna Lista, Verificarla para su próxima Revisión, Carga a SAP y Liberación';        
      echo json_encode($data); exit();
  }
  
  if($lista==0){
    //SIMPLEMENTE CREAREMOS LA NUEVA LISTA CON LOS DATOS DE LA PERSONA QUE TIENE LA SESION + LA FECHA DE LA CREACION
    $query_insert_list="INSERT INTO lista values (NULL,'','INICIADA')";
    $data['all_querys'][]=$query_insert_list;
    $reg_list_inserted=$mysqli->insert_easy($query_insert_list);
    if($reg_list_inserted===1){
      $arr_last_list=$mysqli->select("SELECT @@identity AS id",'mysqli_a_o');
      if($arr_last_list!==0 && $arr_last_list!==false){
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
      $data['nothing']='NO SE PUDO CREAR LA LISTA';
      $reg_list_inserted===false ? $data['errors']=$mysqli->getErrors() : $data['cant_list_inserted']=$reg_list_inserted;
      echo json_encode($data); exit();   
    }
  }
  
  ///--- AHORA REGISTRAMOS EL ARTICULO
   
  if($existencia=='sap'){
    ///TENEMOS QUE OBTENER INFORMACION DE SAP
    // $query_articulo="SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, G.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, ";
    // $query_articulo.="S.U_APOLLO_SEASON as prenda_code, S.U_APOLLO_DIV as categoria_code, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, ";
    // $query_articulo.="S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name, S.U_ESTILO as grupouso_name, ";
    // $query_articulo.="S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name FROM OITM AS S JOIN OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '$code_article-%' "

    $query_articulo="SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, D.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, S.U_APOLLO_SEASON as prenda_code, P.Name as prenda_name , S.U_APOLLO_DIV as categoria_code,";
    $query_articulo.="C.Name as categoria_name, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name,";
    $query_articulo.="S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name FROM OITM AS S ";
    $query_articulo.="JOIN OITB AS D ON S.ItmsGrpCod=D.ItmsGrpCod ";
    $query_articulo.="LEFT JOIN [@APOLLO_SEASON] AS P ON S.U_APOLLO_SEASON=P.Code ";
    $query_articulo.="LEFT JOIN [@APOLLO_DIV] AS C ON S.U_APOLLO_DIV=C.Code ";
    $query_articulo.="WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '$code_article-%'";

    $data['all_querys'][]=$query_articulo;
    $arr_articulo=$sqlsrv_33->select($query_articulo,"sqlsrv_a_p");
    if($arr_articulo!==false && $arr_articulo!==0){
      $query_insert_article="INSERT INTO articulo ";
      $query_insert_article.="VALUES ('$code_article',$lista,'".$arr_articulo[0]['itemname']."', ";  
      $query_insert_article.="null,'".$arr_articulo[0]['marca_name']."',".$arr_articulo[0]['dpto_code'].",'".$arr_articulo[0]['dpto_name']."',";
      $query_insert_article.="null,'".$arr_articulo[0]['subdpto_name']."','".$arr_articulo[0]['prenda_code']."','".$arr_articulo[0]['prenda_name']."','".$arr_articulo[0]['categoria_code']."','".$arr_articulo[0]['categoria_name']."',";
      $query_insert_article.="null,'".$arr_articulo[0]['presentacion_name']."',null,'".$arr_articulo[0]['material_name']."',";
      $query_insert_article.="null,'".$arr_articulo[0]['tprenda_name']."',null,'".$arr_articulo[0]['tcatalogo_name']."',";
      $query_insert_article.="null,'".$arr_articulo[0]['grupouso_name']."',null,'".$arr_articulo[0]['caracteristica_name']."',null,'".$arr_articulo[0]['composicion_name']."',";
      $query_insert_article.="'".$arr_articulo[0]['talla_familia']."','sap')"; 

      $detail.="<div>";
      $detail.="<div><span class='span_title'>MARCA</span><span class='span_item'>".$arr_articulo[0]['marca_name']."</span></div>";
      $detail.="<div><span class='span_title'>DPTO</span><span class='span_item'>".$arr_articulo[0]['dpto_name']."</span></div>";
      $detail.="<div><span class='span_title'>SUBDPTO</span><span class='span_item'>".$arr_articulo[0]['subdpto_name']."</span></div>";
      $detail.="<div><span class='span_title'>PRENDA</span><span class='span_item'>".$arr_articulo[0]['prenda_name']."</span></div>";   
      $detail.="<div><span class='span_title'>CATEG.</span><span class='span_item'>".$arr_articulo[0]['categoria_name']."</span></div></div>"; 
      $detail.="<div>";
      $detail.="<div><span class='span_title'>T.PRENDA</span><span class='span_item'>".$arr_articulo[0]['tprenda_name']."</span></div>";
      $detail.="<div><span class='span_title'>T.CATALOG</span><span class='span_item'>".$arr_articulo[0]['tcatalogo_name']."</span></div>"; 
      $detail.="<div><span class='span_title'>GRUPO.USO</span><span class='span_item'>".$arr_articulo[0]['grupouso_name']."</span></div>";
      // $detail.="<div><span class='span_title'>CARACT</span><span class='span_item'>".$arr_articulo[0]['caracteristica_name']."</span></div>"; 
      $detail.="<div><span class='span_title'>COMPOS.</span><span class='span_item'>".$arr_articulo[0]['composicion_name']."</span></div></div>";
      $data['detail']=$detail;     

    }else {      
      $cant_registros_lista=$mysqli->quantityRecords("select codigo from articulo where lista_id=$lista");
      if($cant_registros_lista===false){
        $errors[]=$mysqli->getErrors();
        $data['msj']='no se creo el articulo y hubo error al consultar la cantidad de articulos en esta lista';  
      }elseif($cant_registros_lista===0){
        if(($mysqli->delete("DELETE FROM lista WHERE id=$lista"))===false){
          $errors[]=$mysqli->getErrors();
          $data['msj']='no se creo el articulo, la lista estaba nueva y no se pudo eliminar'; 
        }
      }  
      $data['errors']=$errors;
      $data['nothing']='NO SE PUDIERON AGREGAR SKUS NUEVOS DE ARTICULO EXISTENTE EN SAP';
      echo json_encode($data); exit();
    }
  }else{  
    $query_insert_article="INSERT INTO articulo VALUES ('$code_article',$lista,'".$_POST['itemname']."', "; 
    $query_insert_article.=$_POST['marca_code'].",'".$_POST['marca_name']."',".$_POST['dpto_code'].",'".$_POST['dpto_name']."',";
    $query_insert_article.=$_POST['subdpto_code'].",'".$_POST['subdpto_name']."','".$_POST['prenda_code']."','".$_POST['prenda_name']."','".$_POST['categoria_code']."','".$_POST['categoria_name']."',";
    $query_insert_article.=$_POST['presentacion_code'].",'".$_POST['presentacion_name']."',".$_POST['material_code'].",'".$_POST['material_name']."',";
    $query_insert_article.=$_POST['tprenda_code'].",'".$_POST['tprenda_name']."',".$_POST['tcatalogo_code'].",'".$_POST['tcatalogo_name']."',";
    $query_insert_article.=$_POST['grupouso_code'].",'".$_POST['grupouso_name']."',".$_POST['caracteristica_code'].",'".$_POST['caracteristica_name']."',".$_POST['composicion_code'].",'".$_POST['composicion_name']."',";
    $query_insert_article.="'".$_POST['talla_familia']."','nuevo')"; 
    ///--- APROVECHAMOS PARA HACER EL DETAIL
    $detail.="<div>";
    $detail.="<div><span class='span_title'>MARCA</span><span class='span_item'>".$_POST['marca_name']."</span></div>";
    $detail.="<div><span class='span_title'>DPTO</span><span class='span_item'>".$_POST['dpto_name']."</span></div>";
    $detail.="<div><span class='span_title'>SUBDPTO</span><span class='span_item'>".$_POST['subdpto_name']."</span></div>";
    $detail.="<div><span class='span_title'>PRENDA</span><span class='span_item'>".$_POST['prenda_name']."</span></div>";   
    $detail.="<div><span class='span_title'>CATEG.</span><span class='span_item'>".$_POST['categoria_name']."</span></div></div>"; 
    $detail.="<div>";
    $detail.="<div><span class='span_title'>T.PRENDA</span><span class='span_item'>".$_POST['tprenda_name']."</span></div>";
    $detail.="<div><span class='span_title'>T.CATALOG</span><span class='span_item'>".$_POST['tcatalogo_name']."</span></div>"; 
    $detail.="<div><span class='span_title'>GRUPO.USO</span><span class='span_item'>".$_POST['grupouso_name']."</span></div>";
    // $detail.="<div><span class='span_title'>CARACT</span><span class='span_item'>".$_POST['caracteristica_name']."</span></div>"; 
    $detail.="<div><span class='span_title'>COMPOS.</span><span class='span_item'>".$_POST['composicion_name']."</span></div></div>";
    $data['detail']=$detail;
  }

  $data['all_querys'][]=$query_insert_article;

  $errors=[];///DE AQUI EN ADELANTE PUEDEN HABER ERRORES ACUMULATIVOS
  $reg_art_inserted=$mysqli->insert_easy($query_insert_article);
  if($reg_art_inserted!=1){
    $reg_art_inserted===false ? $errors[]=$mysqli->getErrors() : $data['cant_art_inserted']=$reg_art_inserted; 
    $cant_registros_lista=$mysqli->quantityRecords("select codigo from articulo where lista_id=$lista");
    if($cant_registros_lista===false){
       $errors[]=$mysqli->getErrors();
       $data['msj']='no se creo el articulo y hubo error al consultar la cantidad de articulos en esta lista';  
    }elseif($cant_registros_lista===0){
      if(($mysqli->delete("DELETE FROM lista WHERE id=$lista"))===false){
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
    $abrev=$mysqli->getColumnFromColumn('color','abreviatura','nombre','STRING',$colores_name[$i]);
    for ($j = 0; $j < $tallas_length; $j++){      
      ($copa!='' && $copa!='S/C') ? $sku=$code_article.'-'.$abrev.$copa.'-'.$tallas_name[$j] : $sku=$code_article.'-'.$abrev.'-'.$tallas_name[$j];
      if(existSku($sku,'SAP')===true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN SAP');
        $data['exist_in_SAP']=true;
      }elseif(existSku($sku,'LISTA')===true){
        $sku_refused[]=array('sku'=>$sku, 'detalle'=>'EXISTE EN LISTA');
        $data['exist_in_LIST']=true;
      }else{
        //$data['stop_before_insert_sku']=true;                
        $data['exist_not_exist']=true;
        $barcode=$first_barcode + count($sku_inserteds);
        $barcode=(string)$barcode;
        $barcode=$barcode.getControlDigit($barcode);
        $query_sku="INSERT INTO sku VALUES('$sku','$code_article','$barcode',".$colores_code[$i].",'".$colores_name[$i]."','".$tallas_name[$j]."','".$tallas_orden[$j]."','$copa','$fcopa')"; 
        $data['all_querys'][]=$query_sku;
        $reg_sku_inserted= $mysqli->insert_easy($query_sku);    
        if($reg_sku_inserted==1){//agregamos a la Base de datos y creamos la fila para dibujar el div_article          
          $img_delete = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'.$sku.'">';        
          $filas.="<div><div>".(($i*$tallas_length)+$j+1)."</div><div>$sku</div><div>$barcode</div><div>$colores_name[$i]</div><div>$tallas_name[$j]</div><div>$img_delete</div></div>"; 
          $sku_inserteds[]=$sku;     
        }else{
          $sku_refused[]=array('sku'=>$sku, 'detalle'=>"ERROR AL GUARDAR SKU"); 
          $reg_sku_inserted===false ? $data['errors'][]=$mysqli->getErrors() : $data['errors'][]="SIMPLEMENTE NO SE REGISTRO $sku";             
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
      if($cant_registros_lista!==false){//quiere decir que exiten otros articulos en esta lista, por lo que solamente eliminamos este articulo
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
  $query_save_list="UPDATE lista SET estado='CREADA' WHERE id=$lista";
  $reg_updated=$mysqli->update_easy($query_save_list);
  if($reg_updated==1){
    $data['creation']=true;
    ///--- ############################### ---
    ///--- DATOS PARA ENVIO DE CSV AL MAIL ---
    $subject="NOTIFICACION DE CREACION DE SKUS (Lista N° $lista)";
    $link="http://192.168.0.19/sku/crear.php?list=$lista&status=CREADA&option=show";
    $message="Se creo la lista N° $lista con SKUS pendientes de Revisar.<br><br>Ingresar al sistema y elegir LISTAS PENDIENTES para revisar esta LISTA<br><br>O puede usar el siguiente enlace:<br><a href='$link'>$link</a> ";
    $destinatario ="aobando@kayser.cl,sku@kayser.cl";
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=UTF-8\r\n"; //PARA ENVIO EN FORMATO HTML
    $headers .= "From: Creación de SKUs <sku@kayser.cl>\r\n";
    if(mail($destinatario, $subject, $message, $headers)){
      $data['submit']=true;
    }
    else{
      $data['submit']=false;
    }     
  }else 
    $reg_updated===false ? $data['errors']=$mysqli->getErrors() : $data['cant_lists_saved']=$reg_updated;
  echo json_encode($data);
}
/// para crear la planilla carga sap
if($_POST['option']=="submit_excel") {
  $lista=$_POST['list'];
  $nomb_lista="CREACION_SKUS_LISTA_N-".$lista."_($hoy)";

  $query_skus_carga="SELECT S.codigo as cod_sku, S.barcode, A.caracteristica_name, A.itemname, A.dpto_code, A.prenda_name, A.codigo as cod_articulo, ";
  $query_skus_carga.="S.color_name, S.talla_name, A.talla_familia, A.prenda_code, S.talla_orden, A.marca_name, A.tprenda_name, A.material_name, A.grupouso_name, ";
  $query_skus_carga.="A.subdpto_name, A.composicion_name, A.categoria_code, S.copa, A.presentacion_name, A.tcatalogo_name, S.fcopa FROM sku as S ";
  $query_skus_carga.="INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=$lista ORDER BY S.barcode ASC";
  
  $data['querys_all'][]=$query_skus_carga;
  $arr_skus_carga=$mysqli->select($query_skus_carga,"mysqli_a_o");
  if($arr_skus_carga!==0 && $arr_skus_carga!==false){
    $send=sendMail($arr_skus_carga);
    if($send===true){
      $data['submit']=true;
      $estado_lista='REVISADA';
      $query_state="UPDATE lista SET estado='$estado_lista' WHERE id=$lista";
      $reg_updated=$mysqli->update_easy($query_state);
      if($reg_updated==1){
        $data['state_updated']=true;     
        ///--- ACA DEBERIAMOS VALIDAR SI YA EXISTE LA LISTA Y LA OPRERACION REVISION, SOLO ACTUALIZAR LA FECHA, SI NO, INGRESAR EL DATO
        $query_exists_rel="SELECT lista_id from lista_has_usuario WHERE lista_id=$lista AND operacion='REVISION'";
        $cant_exist_rel=$mysqli->quantityRecords($query_exists_rel);
        if($cant_exist_rel==1){
          $query_update_rel="UPDATE lista_has_usuario SET usuario_user=$user, fecha=$hoy WHERE lista_id=$lista AND operacion='REVISION'";
          $reg_rel_updated=$mysqli->update_easy($query_update_rel);
          if($reg_rel_updated==1){
            $data['relation_updated']==true;
          }else $reg_rel_updated===false ? $data['errors']=$mysqli->getErrors() : $data['cant_lists_rel_updated']=$reg_rel_updated; 
        }else {
          if($cant_exist_rel===false )
            $data['errors']=$mysqli->getErrors();
          else{
            $query_insert_rel="INSERT INTO lista_has_usuario values ($lista,'$user','REVISION','$hoy')";
            $reg_rel_inserted=$mysqli->insert_easy($query_insert_rel);
            if($reg_rel_inserted==1){
              $data['relation_inserted']==true;
            }else $reg_rel_inserted===false ? $data['errors']=$mysqli->getErrors() : $data['cant_lists_rel_inserted']=$reg_rel_inserted; 
          }
        }
      } else $reg_rel_inserted===false ? $data['errors']=$mysqli->getErrors() : $data['cant_lists_rel_inserted']=$reg_rel_inserted;
    }else
      $data['submit']=false;
  }else{
    $arr_skus_carga===false ? $data['errors']=$mysqli->getErrors() : $data['cant_skus']=0;
  }
  echo json_encode($data);
}

if($_POST['option']=='show_lists'){
  $divs="";
  // echo $_SESSION['user']."<br>".$_SESSION['perfil'];
  if($_SESSION['perfil']=='editor'){//SI UN USARIO ES EDITOR, SOLO PODRA VER LAS LISTAS QUE ESTA EDITANDO
    $query_lists = "SELECT L.id, COUNT(S.codigo) as cant_skus, estado from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo INNER JOIN lista_has_usuario as LU on L.id=LU.lista_id WHERE LU.usuario_user='$user' AND L.estado='INICIADA' GROUP BY L.id";
  }elseif($_SESSION['perfil']=='reviser' || $_SESSION['perfil']=='admin'){ //ver las que esta editando mas todas las que estan pendientes de revision o finalizacion
    $query_lists = "SELECT L.id, COUNT(S.codigo) as cant_skus, estado from lista as L INNER JOIN articulo as A on A.lista_id=L.id INNER JOIN sku as S on A.codigo=S.articulo_codigo GROUP BY L.id";
  }
  $data['perfil']=$_SESSION['perfil'];
  $all_querys[]=$query_lists;
  $arr_lists=$mysqli->select($query_lists,'mysqli_a_o');
  $data['cant_primera_consulta']=$arr_lists;
  if($arr_lists!=0 && $arr_lists!==false){    
    for($i=0; $i<count($arr_lists); $i++){
      $initiador="";
      $creator='';
      $revisor='';
      $code_list=$arr_lists[$i]['id'];
      $cant_skus=$arr_lists[$i]['cant_skus'];
      $stat_list=$arr_lists[$i]['estado'];
      // $icon_show="<img id='$code_list' class='icon_dtable' src='./src/img/lupa.png' alt='Ver contenido Lista'>";
      $link_list="<a href='crear.php?list=$code_list&status=$stat_list&option=show'><img class='icon_dtable' src='./src/img/lupa.png' alt='Ver contenido Lista'></a>";
      ///--- OBTENEMOS QUIEN LA CREO Y LA REVISO
      $query_relation="SELECT * FROM lista_has_usuario where lista_id=$code_list";
      $all_querys[]=$query_relation;
      $arr_relation=$mysqli->select($query_relation,"mysqli_a_o");
      if($arr_relation!==0 && $arr_relation!==false){
        if($stat_list!='REVISADA'){
          $stat_list=='INICIADA' ? $initiador=$arr_relation[0]['usuario_user']." ( ".$arr_relation[0]['fecha']." )" : $creator=$arr_relation[0]['usuario_user']." ( ".$arr_relation[0]['fecha']." )";
        }else{
          for($j=0; $j<count($arr_relation); $j++){
            if($arr_relation[$j]['operacion']=='CREACION'){
              $creator=$arr_relation[$j]['usuario_user']." ( ".$arr_relation[$j]['fecha']." )";
            }elseif($arr_relation[$j]['operacion']=='REVISION'){
              $revisor=$arr_relation[$j]['usuario_user']." ( ".$arr_relation[$j]['fecha']." )";
            }
          }
        }
      }    
      $divs.="<div class='dtr'><div>$code_list</div><div>".$initiador."</div><div>".$creator."</div><div>".$revisor."</div><div>$cant_skus</div><div>$link_list</div></div>";
    }
  } else { $arr_lists===false ? $data['errors']=$mysqli->getErrors() : $data['cant_listas']=$arr_lists; }
  $data['rows']=$divs;
  $data['querys_all']=$all_querys;
  echo json_encode($data);
}

if($_POST['option']=='get_articles'){
  $lista=$_POST['list'];
  $s=1;//para enumerar los skus por columnas
  $a=0;//cantidad de articulos que usasremos para llenar el arr_export;
  $arr_export=[];
  $articles=[];
  $query_skus="SELECT S.codigo as cod_sku, S.barcode, S.color_name, S.talla_name, A.codigo as cod_articulo, A.itemname, A.marca_name, A.dpto_name, A.subdpto_name, A.prenda_name, A.categoria_name, A.tprenda_name,  A.tcatalogo_name, A.grupouso_name, A.caracteristica_name, A.composicion_name, A.existencia FROM sku as S INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=$lista ORDER BY S.barcode ASC";
  $data['querys_all'][]=$query_skus;
  $arr_skus=$mysqli->select($query_skus,"mysqli_a_o");
  if($arr_skus!==0 && $arr_skus!==false){
    $cant_registros=count($arr_skus);
    $art_temp=$arr_skus[0]['cod_articulo'];
    $rows='';
    for($i=0;$i<$cant_registros;$i++){
      $detail='';
      if ($arr_skus[$i]['cod_articulo']!=$art_temp){
        $art_temp=$arr_skus[$i]['cod_articulo'];         
        $i--;      // que cuando itere aumentara en 1 y volvera  al valor correcto de $i pero sin entrar a esta condicion 
        $arr_export[$a]['articulo']=$arr_skus[$i]['cod_articulo'];
        $arr_export[$a]['itemname']=$arr_skus[$i]['itemname'];

        // if($arr_skus[$i]['existencia']=='nuevo'){ //si estan vacios quiere decir que es un articulo existente
          $detail.="<div>";
          $detail.="<div><span class='span_title'>MARCA</span><span class='span_item'>".$arr_skus[$i]['marca_name']."</span></div>";
          $detail.="<div><span class='span_title'>DPTO</span><span class='span_item'>".$arr_skus[$i]['dpto_name']."</span></div>";
          $detail.="<div><span class='span_title'>SUBDPTO</span><span class='span_item'>".$arr_skus[$i]['subdpto_name']."</span></div>";
          $detail.="<div><span class='span_title'>PRENDA</span><span class='span_item'>".$arr_skus[$i]['prenda_name']."</span></div>";   
          $detail.="<div><span class='span_title'>CATEG.</span><span class='span_item'>".$arr_skus[$i]['categoria_name']."</span></div></div>"; 
          $detail.="<div>";
          $detail.="<div><span class='span_title'>T.PRENDA</span><span class='span_item'>".$arr_skus[$i]['tprenda_name']."</span></div>";
          $detail.="<div><span class='span_title'>T.CATALOG</span><span class='span_item'>".$arr_skus[$i]['tcatalogo_name']."</span></div>"; 
          $detail.="<div><span class='span_title'>GRUPO.USO</span><span class='span_item'>".$arr_skus[$i]['grupouso_name']."</span></div>";
          // $detail.="<div><span class='span_title'>CARACT</span><span class='span_item'>".$arr_skus[$i]['caracteristica_name']."</span></div>"; 
          $detail.="<div><span class='span_title'>COMPOS.</span><span class='span_item'>".$arr_skus[$i]['composicion_name']."</span></div></div>";
          $arr_export[$a]['detail']=$detail; 
          $arr_export[$a]['existencia']=$arr_skus[$i]['existencia'];
        // }
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
    $i--;
    $arr_export[$a]['articulo']=$arr_skus[$i]['cod_articulo'];
    $arr_export[$a]['itemname']=$arr_skus[$i]['itemname'];
    // if($arr_skus[$i]['existencia']=='nuevo'){ //si estan vacios quiere decir que es un articulo existente
      $detail="<div>";
      $detail.="<div><span class='span_title'>MARCA</span><span class='span_item'>".$arr_skus[$i]['marca_name']."</span></div>";
      $detail.="<div><span class='span_title'>DPTO</span><span class='span_item'>".$arr_skus[$i]['dpto_name']."</span></div>";
      $detail.="<div><span class='span_title'>SUBDPTO</span><span class='span_item'>".$arr_skus[$i]['subdpto_name']."</span></div>";
      $detail.="<div><span class='span_title'>PRENDA</span><span class='span_item'>".$arr_skus[$i]['prenda_name']."</span></div>";   
      $detail.="<div><span class='span_title'>CATEG.</span><span class='span_item'>".$arr_skus[$i]['categoria_name']."</span></div></div>"; 
      $detail.="<div>";
      $detail.="<div><span class='span_title'>T.PRENDA</span><span class='span_item'>".$arr_skus[$i]['tprenda_name']."</span></div>";
      $detail.="<div><span class='span_title'>T.CATALOG</span><span class='span_item'>".$arr_skus[$i]['tcatalogo_name']."</span></div>"; 
      $detail.="<div><span class='span_title'>GRUPO.USO</span><span class='span_item'>".$arr_skus[$i]['grupouso_name']."</span></div>";
      // $detail.="<div><span class='span_title'>CARACT</span><span class='span_item'>".$arr_skus[$i]['caracteristica_name']."</span></div>"; 
      $detail.="<div><span class='span_title'>COMPOS.</span><span class='span_item'>".$arr_skus[$i]['composicion_name']."</span></div></div>";
      $arr_export[$a]['detail']=$detail;
      $arr_export[$a]['existencia']=$arr_skus[$i]['existencia'];
    // }
    $arr_export[$a]['skus']=$rows;
    $data['articulos']=$arr_export;
  }else{
    $arr_skus==false ? $data['errors']=$mysqli->getErrors() : $data['cant_skus']=0;
  }
  
  echo json_encode($data);

}

if($_POST['option']=='finalize_list'){
  $lista=$_POST['list']; 
  $user_creator=''; $user_reviser='';
  $excel="SKU;DESCRIPCION;COLOR;CODIGO DE BARRAS\r\n";
  ///--- OBTENEMOS LOS SKU, LOS USUARIOS QUE LO CREARON, REVISARON
  $query_lista_usuario="SELECT usuario_user, operacion FROM lista_has_usuario WHERE lista_id=$lista";
  $arr_lista_usuario = $mysqli->select($query_lista_usuario,"mysqli_a_o");
  $data['query_all'][]=$query_lista_usuario;
  $data['arr_select']=$arr_lista_usuario;
  if($arr_lista_usuario!==0 && $arr_lista_usuario!==false){
    for($i=0; $i<count($arr_lista_usuario); $i++){
      $arr_lista_usuario[$i]['operacion']=="CREACION" ? $user_creator=$arr_lista_usuario[$i]['usuario_user'] : $user_reviser=$arr_lista_usuario[$i]['usuario_user'];
    }
    $data['creator']=$user_creator;
    $data['reviser']=$user_reviser;
  } else $arr_lista_usuario === false ? $data['errors']=$mysqli->getErrors() : $data['NOTHING'][]="NO SE ENCONTRO INFORMACION DE LISTA N° $lista en la relacion LISTA_HAS_USUARIO";

  ///---AHORA OBTENEMOS EL ARRAY CON TODOS LOS SKUS QUE PERTENECEN A ESTA LISTA QUE SE VA A ELIMINAR
  $query_skus="SELECT S.codigo, S.articulo_codigo, A.itemname, S.color_name, S.barcode FROM sku AS S INNER JOIN articulo as A ON A.codigo=S.articulo_codigo WHERE A.lista_id=$lista";
  $data['query_all'][]=$query_skus;
  $arr_skus=$mysqli->select($query_skus,"mysqli_a_o");
  if($arr_skus!==false && $arr_skus!==0){
    ///---AHORA RECORREMOS EL ARRAY OBTENIDO E INSERTAMOS EN SKUCREATED
    $sku_not_backed=[];
    $cant_skus_backeds=0;
    $cant_skus=count($arr_skus);
    for($i=0; $i<$cant_skus; $i++){   
      ///de paso guardamos la tabla para el excel que se enviara el mail
      $len_arti=strlen($arr_skus[$i]['articulo_codigo']);
      $descripcion=substr($arr_skus[$i]['itemname'],$len_arti,strlen($arr_skus[$i]['itemname']));
      $excel.=$arr_skus[$i]['codigo'].";$descripcion;".$arr_skus[$i]['color_name'].";".$arr_skus[$i]['barcode']."\r\n";     
      $sku=$arr_skus[$i]['codigo'];
      $query_backed_sku="INSERT INTO skucreated values('$sku', '$hoy', '$user_creator', '$user_reviser', '$user')";
      $data['query_backs'][]=$query_backed_sku;
      $sku_backed=$mysqli->insert_easy($query_backed_sku);
      if($sku_backed!==false && $sku_backed!==0){
          $cant_skus_backeds++;
      } else $sku_backed===false ?  $data['errors'][]=$mysqli->getErrors() : $data['sku_refused_back'][]=$sku;      
    }
    $cant_skus_backeds===$cant_skus ? $data['back']=true : $data['cant_sku_backed']=$cant_skus_backeds;
    
    ///--- ############################### ---
    ///--- AHORA ENVIAMOS EL MAIL:
    $subject="SKUS CARGADOS A SAP EXITOSAMENTE ($hoy)";
    $destinatario ="aobando@kayser.cl,sku@kayser.cl";
    $headers = "From: Creación de SKUs <sku@kayser.cl>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: application/octet-stream; name=SKUS CARAGADOS A SAP\r\n"; //envio directo de datos
    $headers .= "Content-Disposition: attachment; filename=$subject.xls\r\n";
    $headers .= "Content-Transfer-Encoding: binary\r\n";
    $headers .= utf8_decode($excel);
    $headers .= "\r\n"; //retorno de carro y salto de linea
    if(mail($destinatario, $subject, $message, $headers)){
      $data['submit']=true;
      ///---ahora eliminamos la lista
      $query_delete_list="DELETE from lista WHERE id=$lista"; /// Y POR CASCADA, SE ELIMINARA EL ARTICULO Y LOS SKUS
      $list_delete=$mysqli->delete($query_delete_list);
      if($list_delete!==false && $list_delete!==0){
        $data['delete']=true;
      }else $list_delete===false ? $data['errors']=$mysqli->getErrors() : $data['NOTHING'][]="NO SE PUDO ELIMINAR LISTA N° $lista, ELIMINAR MANUALMENTE...";
    }
    else{
      $data['submit']=false;
    } 
  } else $arr_skus === false ? $data['errors']=$mysqli->getErrors() : $data['NOTHING'][]="NO SE ENCONTRARON SKUS EN ESTA LISTA";

  echo json_encode($data);
}

if($_POST['option']=='delete_article'){
  $code_article=$_POST['article'];
  $list=$_POST['list'];

  ///--- PROCEDEMOS A ELIMINAR EL ARTICULO, Y POR CASCADA TB SE ELIMINAN LOS SKUS
  $query_delete_article="DELETE FROM articulo WHERE codigo='$code_article'";
  $data['query_all'][]=$query_delete_article;
  $reg_del=$mysqli->delete($query_delete_article);
  if($reg_del===1){
    $data['del_art']=true;
    $query_quan_art="SELECT codigo from articulo WHERE lista_id=$list";
    $data['query_all'][]=$query_quan_art;
    $reg_quan_art=$mysqli->quantityRecords($query_quan_art);
    if($reg_quan_art===0){///QUIERE DECIR QUE LA LISTA QUEDO VACIA
      $query_del_list="DELETE FROM lista where id=$list";
      $data['query_all'][]=$query_del_list;
      $reg_del_list=$mysqli->delete($query_del_list);
      if($reg_del_list===1)
        $data['del_list']=true;
      else
        $reg_del_list===false ? $data['errors']=$mysqli->getErrors() : $data['NOTHING']='NO SE PUDO ELIMINAR LA LISTA QUE QUEDO VACIA';     
    }else
      $reg_quan_art===false ? $data['errors']=$mysqli->getErrors() : $data['cant_art_list']=$reg_quan_art;
  }else{
    if($reg_del===false ? $data['errors']=$mysqli->getErrosr() : $data['NOTHING']='NO SE PUDO ELIMINAR EL ARTICULO');
  }

  echo json_encode($data);
}

function sendMail($arr_cont){
  global $nomb_lista;
  global $lista;
  // $boundary=uniqid('np');
  $multipartSep = '-----'.md5(time()).'-----';
  $content_csv="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalesUnit;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgStdPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";
  $content_csv.="RecordKey;ItemCode;BarCode;ForceSelectionOfSerialNumber;ForeignName;GLMethod;InventoryItem;IsPhantom;IssueMethod;SalesUnit;ItemName;ItemsGroupCode;ManageStockByWarehouse;PlanningSystem;SWW;U_APOLLO_SEG1;U_APOLLO_SEG2;U_APOLLO_SSEG3;U_APOLLO_SEG3;U_APOLLO_SEASON;U_APOLLO_APPGRP;U_APOLLO_SSEG3VO;U_APOLLO_ACT;U_MARCA;U_EVD;U_MATERIAL;U_ESTILO;U_SUBGRUPO1;U_APOLLO_COO;U_GSP_TPVACTIVE;AvgStdPrice;U_APOLLO_DIV;U_IDDiseno;U_IDCopa;U_FILA;U_APOLLO_S_GROUP;U_GSP_SECTION\r\n";
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
    $fila_csv.= strtoupper($arr_cont[$i]['cod_articulo']).";"; 
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
  $subject="NOTIFICACION DE REVISION PARA CARGA DE SKUS (Lista N° $lista)";
  $link="http://192.168.0.19/sku/crear.php?list=$lista&status=REVISADA&option=show";
  $destinatario ="aobando@kayser.cl,sku@kayser.cl";

  $header  = "MIME-Version: 1.0\r\n"; 
  $header .= "From: Creación de SKUs <sku@kayser.cl>\r\n";
  $header .= "Content-type: multipart/mixed; charset=UTF-8; boundary=\"$multipartSep\"";
  
  $message = "--$multipartSep\r\n";
  $message .= "Content-type: text/html; charset=UTF-8\r\n"; //PARA ENVIO EN FORMATO HTML
  $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
  $message .= "lista N° $lista FUE REVISADA.<br><br>Después de Cargar la Información a SAP, es necesario FINALIZAR esta lista.<br><br>Ingresar al sistema y elegir LISTAS PENDIENTES<br><br>O puede usar el siguiente enlace:<br><a href='$link'>$link</a>\r\n";
  
  $attached = "--$multipartSep\r\n";
  $attached .= "Content-Type: application/octet-stream; name=\"".$nomb_lista.".csv\"\r\n"; //envio directo de datos
  $attached .= "Content-Transfer-Encoding: binary\r\n";
  $attached .= "Content-Disposition: attachment; filename=\"".$nomb_lista.".csv\"\r\n";
  $attached .= utf8_decode($content_csv);
  $attached .= "\r\n";
  $attached .= "--$multipartSep--";

  $content = $message.$attached;

  if(mail($destinatario, $subject, $content, $header)){
    return true;
  }
  else{
    return false;
  }
  // return true;
  // return($content_csv);   
}
function existArticle($cod_art,$serv){
  global $mysqli;
  global $sqlsrv_33;
  if($serv==='SAP'){
    ///---COMO EN SAP NO ES MUY SEGURO QUE LA SUBCADENA DEL CODIGO SKU QUE REPRESENTA AL ARTICULO, SEA IGUAL AL CODIGO DEL ARTICULO
    ///---ENTONCES BUSCAREMOS EN EL CODIGO SKU
    $query_exist_article="SELECT TOP 1 itemCode FROM OITM WHERE itemCode LIKE '$cod_art-%'"; 
    // echo "<br>".$query_exist_article; 
    $arr_exist_article=$sqlsrv_33->select($query_exist_article,'sqlsrv_n_p');
    // echo $query_exist_article."<br>";
  }elseif($serv==='LISTA'){ 
    $query_exist_article="SELECT codigo FROM articulo WHERE codigo='$cod_art'";
    $arr_exist_article=$mysqli->select($query_exist_article,'mysqli_a_o');
    // echo $query_exist_article."<br>";
  }
  if ( isset($arr_exist_article) && $arr_exist_article!==0 && $arr_exist_article!==false)
    return true;
  else
    return false;
}
function existSku($cod_sku, $serv){
  global $mysqli;
  global $sqlsrv_33;
  if($serv==='SAP'){
    $query_exist_sku="SELECT TOP 1 ItemCode FROM OITM WHERE ItemCode='$cod_sku'";
    $arr_exist_sku=$sqlsrv_33->select($query_exist_sku,'sqlsrv_n_p');
  }
  if($serv==='LISTA'){
    $query_exist_sku="SELECT codigo FROM sku WHERE codigo='$cod_sku'";
    $arr_exist_sku=$mysqli->select($query_exist_sku,'mysqli_a_o');
  }
  if(isset($arr_exist_sku) && $arr_exist_sku!==0 && $arr_exist_sku!==false)
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
