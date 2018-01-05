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


if($_POST['option']=="cargar_selects_independientes"){
  $options=[];
  $nombre_name="";
  $nombre_id="";
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $arr_ops=[];
    if(!isset($array_tabla['dep']) && $tabla!="relacionprefijo"){
      $nombre_name=$array_tabla['campo'];
      if(!isset($array_tabla['id']))//TABLA SIN DEPENDENCIA SIN ID
        $query="select ".$array_tabla['campo']." as id,".$array_tabla['campo']." as name from $tabla ORDER BY ".$array_tabla['campo'];
      else {
        $nombre_id=$array_tabla['id'];
        $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla ORDER BY ".$array_tabla['campo'];
      }
      if($array_tabla['bd']=="mysql"){// SI LA TABLA ES MYSQL
        if(($arr_ops=$mysqli->select($query,"mysqli_a_o"))===false){
          $data['errors'][]=$mysqli->getErrors();
          continue;
        }
      }else {
        if(($arr_ops=$sqlsrv_33->select($query,"sqlsrv_a_p"))===false){
          $data['errors'][]=$sqlsrv_33->getErrors();
          continue;
        }
      }
      if($arr_ops==0)
          $arr_ops="SIN RESULTADOS";
      $options[]=array('tabla'=>$tabla, 'options'=>$arr_ops);
    }
  }// fin foreach
  $mysqli->closeConnection();
  $sqlsrv_33->closeConnection();
  $data['values']=$options;
  echo json_encode($data);
}

if($_POST['option']=="cargar_selects_dependientes") {
  $querys_export=[];
  // echo "hola";
  array_splice($array_grand_child,0);//vaciamos el array nietos para buscar nuevos nietos
  //array $array_grand_child es global, declarado en un asset y contendrá los descendientes de las tablas qe se veran afectadas a peticion de la vista
  //es decir segun el nombre y valor del padre, se buscarán tablas dependientes y se cargarán valores relacionados al padre
  //y en  $array_grand_child se guardaran los nombres de descendientes de estas tablas y se enviaran a la vista para resetearse //este array con nombres se enviara en el index 0 del array data enviado a la vista
  $array_tabla_extraida=[];
  $options=[];
  $padre=$_POST['nom_tabla_padre'];
  if($padre=='OITB'){//se buscará dependientes de DEPARTAMENTO (el padre supremo)
    $codigo_padre=getIdFromName($padre,$_POST['val_tabla_padre']); //en este caso, la vista envió el valor del nombre del departamente y no el id
    $data['dpto']=$codigo_padre;
  } else
    $codigo_padre=$_POST['val_tabla_padre']; // para este caso, se pasó el id de la tabla obtenido del val del option padre
  foreach ($tablas_sku as $tabla => $array_tabla) { // recorremos todo el array con las tablas, campos y relaciones
    $ops="";//"<option value=''></option>";
    $arr_ops=[];
    $data['all_tables'][]=$tabla;
    if(isset($array_tabla['dep'])){//si la tabla recorrida tiene padre
      if($padre==$array_tabla['dep']){//si la tabla padre de la tabla recorrida es padre que vino de la vista
        addGrandChild($tabla); // buscamos descendientes dependientes de esta tabla y la agregamos al array $array_grand_child
        $nombre_id=$array_tabla['id'];
        $nombre_name=$array_tabla['campo'];
        if($array_tabla['bd']=="mysql") { // la tabla y la relacion estan en MOTOR MYSQL
          if(!isset($array_tabla['id'])) { //TABLA CON ID o CODIGO distinto al NOMBRE
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE SIN ID PERO CON ID PADRE ENTERO
              $query = "select ".$array_tabla['campo']." as id, ".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre ORDER BY ".$array_tabla['campo'];
            else
              $query = "select ".$array_tabla['campo']." as id, ".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['campo']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."' ORDER BY ".$array_tabla['campo'];
            $querys_export[]=$query;
          }else {// TABLA DEPENDIENTE CON ID
            if($tablas_sku[$padre]['type_id']=="INT") //TABLA DEPENDIENTE CON ID PROPIO E ID PADRE ENTERO
              $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."=$codigo_padre ORDER BY ".$array_tabla['campo'];
            else
              $query="select ".$array_tabla['id']." as id,".$array_tabla['campo']." as name from $tabla AS T INNER JOIN ".$array_tabla['tabla_rel']." AS R ON T.".$array_tabla['id']."=".$array_tabla['nom_cod_rel']." where R.".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."' ORDER BY ".$array_tabla['campo'];
            $querys_export[]=$query;
          }
          if(($arr_ops=$mysqli->select($query,"mysqli_a_o"))===false){
            $data['errors'][]=$mysqli->getErrors();
            continue;//pasamos al siguiente recorrido de foreach
          }else {
            if($arr_ops==0)
              $arr_ops="SIN RESULTADOS";
          }
          // $querys_export[]=$query;
        }//fin if if($array_tabla['bd']=="mysql")
        else {//cargamos en un array el id y name de la tabla en mencion
          $query_id_name="SELECT ".$array_tabla['id'].",".$array_tabla['campo']." from $tabla ORDER BY ".$array_tabla['campo'];
          $querys_export[]=$query_id_name;
          if($tablas_sku[$tabla]['bd']=="mysql")
            $array_tabla_extraida=$mysqli->selectArrayUniAssocIdName($query_id_name);
          else 
            $array_tabla_extraida=$sqlsrv_33->selectArrayUniAssocIdName($query_id_name);         
          // var_dump($array_tabla_extraida);
          if($array_tabla_extraida!==false && $array_tabla_extraida!==0){
            if($tablas_sku[$padre]['type_id']=="INT")
              $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."=$codigo_padre";
            else
              $query_relacion="SELECT * FROM ".$array_tabla['tabla_rel']." WHERE ".$array_tabla['nom_cod_padre_rel']."='".$codigo_padre."'"; 
            $arr_rel=$mysqli->select($query_relacion,"mysqli_b_o");           
            if ($arr_rel === false || $arr_rel ===0 ) {
              $arr_rel === false ? $data['errors'][]=$mysqli->getErrors() : $data['rel'][]="no existe en tabla: ".$array_tabla['tabla_rel']." tupla relacionada con el valor : ".$codigo_padre." de la tabla padre".$padre."y la tabla hija: ".$tabla;
              continue;//pasamos al siguiente recorrido de foreach
            }else{
              foreach ($arr_rel as $value) {# code...
                $nom_id_dep_rel=$value[1];
                $arr_ops[]=Array('id'=>$value[1], 'name'=> $array_tabla_extraida[$nom_id_dep_rel]);
              }
            }
          }else $array_tabla_extraida === false ? $data['errors'][]=$sqlsrv_33->getErrors() : $data['tabla_extraida']=$array_tabla_extraida;
          $querys_export[]=$query_relacion;
        }
        if($tabla=='talla' && $arr_ops!="SIN RESULTADOS"){
          $arr_tallas=[];
          foreach ($arr_ops as $value)
            $arr_tallas[]=array('familia'=>$value['id'], 'tallas'=>cargarTallasToFamilia($value['id']));
          $data['values'][]=array('tabla'=>$tabla, 'options'=>$arr_tallas);
        } else
          $data['values'][]=array('tabla'=>$tabla, 'options'=>$arr_ops);
      }//fin if($_POST['nom_tabla_padre']==$array_tabla['dep'])
    }
  }//fin foreach
  // array_unshift($options, $array_grand_child);//agregmos los descendientes al inicio de la data a enviar por json
  // $data['grand_childs']=$array_grand_child;
  // $data['querys']=$querys_export;
  $mysqli->closeConnection();
  $sqlsrv_33->closeConnection();
  header("Content-Type: text/html;charset=utf-8");
  echo json_encode($data);
}

/// OPTION QUE SOLO ENVIA LOS OPTIONS DE UN SELECT
if($_POST['option']=="render_select") {
  $table=$_POST['table'];
  $options='';
  ///--- POR AHORA SOLO TRABAJARAMOS CON TABLAS MYSQL
  $query_select="SELECT ".$tablas_sku[$table]['id'].",".$tablas_sku[$table]['campo']." from $table";
  $data['query']=$query_select;
  $options=$mysqli->selectOptions($query_select);
  if($options!==false)
    $data['options']=$options;
  else 
    if ($options===false )
      $data['errors']= $mysqli->getErrors();
  echo json_encode($data);
}
/////----- OPTION QUE DEVELE TODOS LOS OPTION SELECT SIN SELECCION:
if($_POST['option']=="cargar_selects_all") {
  $data['selects'][]=array('select'=> 'dpto', 'options' => getOptionsSelected('OITB','') );
  $data['selects'][]=array('select'=> 'prenda', 'options' => getOptionsSelected('[@APOLLO_SEASON]','') );
  $data['selects'][]=array('select'=> 'categoria', 'options' => getOptionsSelected('[@APOLLO_SEASON]','') );
  $data['selects'][]=array('select'=> 'marca', 'options' => getOptionsSelected('marca','') );
  $data['selects'][]=array('select'=> 'subdpto', 'options' => getOptionsSelected('subdpto','') );
  $data['selects'][]=array('select'=> 'presentacion', 'options' => getOptionsSelected('presentacion','') );
  $data['selects'][]=array('select'=> 'material', 'options' => getOptionsSelected('material','') );    
  $data['selects'][]=array('select'=> 'color', 'options' => getOptionsSelected('color','') );
  $data['selects'][]=array('select'=> 'copa', 'options' => getOptionsSelected('copa','') );
  $data['selects'][]=array('select'=> 'formacopa', 'options' => getOptionsSelected('formacopa','') ); 
  $data['selects'][]=array('select'=> 'tprenda', 'options' => getOptionsSelected('tprenda','') );
  $data['selects'][]=array('select'=> 'tcatalogo', 'options' => getOptionsSelected('tcatalogo','') );
  $data['selects'][]=array('select'=> 'grupouso', 'options' => getOptionsSelected('grupouso','') );
  $data['selects'][]=array('select'=> 'caracteristica', 'options' => getOptionsSelected('caracteristica','') );
  $data['selects'][]=array('select'=> 'composicion', 'options' => getOptionsSelected('composicion','') );
  $query_tallas="SELECT * FROM talla";
  $arr_tallas=$mysqli->select($query_tallas,"mysqli_a_o");
  $cont_tallas=count($arr_tallas);
  for($i=0;$i<$cont_tallas;$i++){
    $data['tallas'][]=array('familia'=>$arr_tallas[$i]['codigo'], 'tallas'=>cargarTallasToFamilia($arr_tallas[$i]['codigo']));
  }
  echo json_encode($data);
}
///--- OPTION DE DEVUELVE TODOS LOS OPTIONS CON EL ITEM SELECCIONADO DEL ARTICULO ENCONTRADO
if($_POST['option']=="fill_selects") {
  ///--- INICIALMENTE OBTENEMOS TODOS LOS SKUs que tengan el codigo de articulo
  $arr_arti=[];
  $articulo=$_POST['articulo'];
  $query_articulo="SELECT S.ItemCode as sku_code, S.U_APOLLO_SEG1 as articulo_code,S.ItemName as itemname, S.ItmsGrpCod as dpto_code, G.ItmsGrpNam as dpto_name,  S.U_SubGrupo1  as subdpto_name, S.U_APOLLO_SEASON as prenda_code, S.U_APOLLO_DIV as categoria_code, S.U_Marca AS marca_name, S.U_FILA as presentacion_name, S.U_Material as material_name, S.CodeBars as barcode, S.U_IDCopa as copa_name, S.U_GSP_SECTION as forma_copa, S.U_EVD as tprenda_name, S.U_APOLLO_SEG2 as color, S.U_APOLLO_S_GROUP as tcatalogo_name, S.U_ESTILO as grupouso_name, S.U_APOLLO_COO as composicion_name, S.FrgnName as caracteristica_name FROM OITM AS S JOIN OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod WHERE (S.U_APOLLO_SEG1 IS NOT NULL) AND s.ItemCode like '$articulo-%' ORDER BY S.CodeBars DESC";
  $arr_articulo=$sqlsrv_33->select($query_articulo,"sqlsrv_a_p");
  if($arr_articulo!==false && $arr_articulo!==0){
    $data['select']=$arr_articulo;
    $data['dpto_codigo']=$arr_articulo[0]['dpto_code'];
    $data['dpto_nombre']=strtoupper($arr_articulo[0]['dpto_name']);
    $data['articulo_codigo']=strtoupper($arr_articulo[0]['articulo_code']);
    $data['marca_nombre']=strtoupper($arr_articulo[0]['marca_name']);
    $data['itemname']=strtoupper($arr_articulo[0]['itemname']);
    
    // $data['itemname'];    
    $data['selects'][]=array('select'=> 'prenda', 'options' => getOptionsSelected('[@APOLLO_SEASON]',$arr_articulo[0]['prenda_code']) );
    $data['selects'][]=array('select'=> 'categoria', 'options' => getOptionsSelected('[@APOLLO_SEASON]',$arr_articulo[0]['categoria_code']) );
    $data['selects'][]=array('select'=> 'marca', 'options' => getOptionsSelected('marca',$arr_articulo[0]['marca_name']) );
    $data['selects'][]=array('select'=> 'subdpto', 'options' => getOptionsSelected('subdpto',$arr_articulo[0]['subdpto_name']) );
    $data['selects'][]=array('select'=> 'presentacion', 'options' => getOptionsSelected('presentacion',$arr_articulo[0]['presentacion_name']) );
    $data['selects'][]=array('select'=> 'material', 'options' => getOptionsSelected('material',$arr_articulo[0]['material_name']) );    
    $data['selects'][]=array('select'=> 'color', 'options' => getOptionsSelected('color','') );
    $data['selects'][]=array('select'=> 'copa', 'options' => getOptionsSelected('copa','') );
    $data['selects'][]=array('select'=> 'formacopa', 'options' => getOptionsSelected('formacopa','') ); 
    $data['selects'][]=array('select'=> 'tprenda', 'options' => getOptionsSelected('tprenda',$arr_articulo[0]['tprenda_name']) );
    $data['selects'][]=array('select'=> 'tcatalogo', 'options' => getOptionsSelected('tcatalogo',$arr_articulo[0]['tcatalogo_name']) );
    $data['selects'][]=array('select'=> 'grupouso', 'options' => getOptionsSelected('grupouso',$arr_articulo[0]['grupouso_name']) );
    $data['selects'][]=array('select'=> 'caracteristica', 'options' => getOptionsSelected('caracteristica',$arr_articulo[0]['caracteristica_name']) );
    $data['selects'][]=array('select'=> 'composicion', 'options' => getOptionsSelected('composicion',$arr_articulo[0]['composicion_name']) );

    $query_tallas="SELECT * FROM talla";
    $arr_tallas=$mysqli->select($query_tallas,"mysqli_a_o");
    $cont_tallas=count($arr_tallas);
    for($i=0;$i<$cont_tallas;$i++){
      $data['tallas'][]=array('familia'=>$arr_tallas[$i]['codigo'], 'tallas'=>cargarTallasToFamilia($arr_tallas[$i]['codigo']));
    }
    ///OBTENEMOS LOS SKUS Y LOS DEVOLVEMOS COMO UN CONJUNTO DE FILAS DIV
    $cant_skus=count($arr_articulo);
    $divs='';
    for($i=0;$i<$cant_skus;$i++){
      $divs.="<div><div>".$arr_articulo[$i]['sku_code']."</div><div>".$arr_articulo[$i]['color']."</div><div>".$arr_articulo[$i]['barcode']."</div></div>";
    }
    $data['skus']=$divs;

  }else ($arr_articulo===false) ? $data['errors']=$sqlsrv_33->getErrors() : $data['cant_skus']=$arr_articulo;
  $data['articulo']=$arr_arti;
  echo json_encode($data);
}
/////----- OPTION QUE DEVUELVE LOS OPTIONS DE LOS DPTOS MENOS HOMBRE, MUJER, LOLO, LOLA, NIÑO, NIÑA
if($_POST['option']=="cargar_selects_otros_dptos"){
  // $arr_dptos_excluded=[ 106,108,127,128,129,130 ];
  $query_excluded = 
  $query=" SELECT ItmsGrpCod as code_dpto, UPPER(ItmsGrpNam) as name_dpto FROM OITB WHERE ItmsGrpCod != 106 AND ItmsGrpCod != 108 AND ItmsGrpCod != 127 AND ItmsGrpCod != 128 AND ItmsGrpCod != 129 AND ItmsGrpCod != 130 AND ItmsGrpCod != 140 AND ItmsGrpCod != 121 AND ItmsGrpCod != 135 AND ItmsGrpNam NOT LIKE '01-INS%' AND ItmsGrpNam NOT LIKE 'INSUMOS%'";
  $arr_query=$sqlsrv_33->selectArrayUniAssocIdName($query);
  $html="";  
  foreach ( $arr_query as $key => $value ){
    $html.="<a class='opcion_other_dpto dropdown-item' id='dpto_".$key."' href='#'>".$value."</a>";
  }
  echo json_encode($html);
}


function getOptionsSelected($table,$valor){
  global $tablas_sku; global $mysqli; global $sqlsrv_33;
  $nombre_id=$tablas_sku[$table]['id'];
  $nombre_campo=$tablas_sku[$table]['campo'];
  if ($table == 'color')
    $option="";
  else
    $option="<option value=''></option>";
  $query_table="SELECT $nombre_id, $nombre_campo FROM $table";  
  if ($tablas_sku[$table]['bd']=='mssql') {    
    $arr_table=$sqlsrv_33->select($query_table,"sqlsrv_a_p");    
    if($arr_table!==false && $arr_table!==0){
      $cant_options=count($arr_table);      
      for($i=0; $i<$cant_options;$i++)
        ($arr_table[$i][$nombre_id]==$valor) ? $option.="<option value='".$arr_table[$i][$nombre_id]."' selected>".$arr_table[$i][$nombre_campo]."</option>" : $option.="<option value='".$arr_table[$i][$nombre_id]."'>".$arr_table[$i][$nombre_campo]."</option>";
    }else ($arr_articulo===false) ? $data['errors']=$sqlsrv_33->getErrors() : $data['cant_skus']=$arr_articulo;
  }else{    
    $arr_table=$mysqli->select($query_table,"mysqli_a_o");
    if($arr_table!==false && $arr_table!==0){      
      $val=@iconv('UTF-8','ASCII//TRANSLIT',$valor);
      $val=strtoupper($val);
      // echo "<br>val despues de iconv: ".$val;  
      $val=str_replace('~N','Ñ',$val);
      // echo "<br>val despues de str_replace: ".$val; 
      $val=preg_replace('/[\'`^]/',null,$val);      
      // echo "<br>val despues de preg_replace: ".$val; 
      $cant_options=count($arr_table);          
      for($i=0; $i<$cant_options;$i++){
        ($arr_table[$i][$nombre_campo]==$val) ? $option.="<option value='".$arr_table[$i][$nombre_id]."' selected>".$arr_table[$i][$nombre_campo]."</option>" : $option.="<option value='".$arr_table[$i][$nombre_id]."'>".$arr_table[$i][$nombre_campo]."</option>";
      }
    }else ($arr_articulo===false) ? $data['errors']=$mysqli->getErrors() : $data['cant_skus']=$arr_articulo;    
  }
  // echo "\n".$option;
  return($option);        
}
?>
