<?php
$array_grand_child=[];
# ARRAY QUE CONTIENE LOS MOTORES DE BASES DE DATOS DE MICSOSOFT SQL SERVER Y SUS CREDENCIALES
$MSSQL = array(
  '13' => array( 'host' => '192.168.0.13', 'user' => 'sa', 'pass' => 'kayser@dm1n' ) ,
  '17' => array( 'host' => '192.168.0.17', 'user' => 'wms', 'pass' => 'pjc3l1' ),
  '33' => array( 'host' => '192.168.0.33', 'user' => 'sa', 'pass' => 'sa' ),
  '16' => array( 'host' => '192.168.0.16', 'user' => 'sa', 'pass' => 'sql2005' )
);
# ARRAY QUE CONTIENE LOS MOTORES DE BASES DE DATOS DE MYSQL Y SUS CREDENCIALES
$MYSQL = array(
  'dev' => array( 'host' => 'localhost', 'user' => 'root', 'pass' => '0013821' ),
  'prod' => array( 'host' => 'localhost', 'user' => 'root', 'pass' => 'qweasd' )
);

# ARRAY QUE CONTIENE DETALLES DE BDX, TABLAS, CAMPOS Y RELACIONES  SOBRE SKU
$tablas_sku = Array(
  'OITB'             => Array( 'bd'=>'mssql',                               'campo_sku'=>'ItmsGrpCod',      'id'=>'ItmsGrpCod', 'type_id'=>'INT',     'campo'=>'ItmSGrpNam'),
  'marca'            => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_Marca',         'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'subdpto'          => Array( 'bd'=>'mysql', 'dep'=>'OITB',                'campo_sku'=>'U_SUBGRUPO1',     'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'dpto_subdpto', 'nom_cod_rel'=>'Subdpto_id', 'nom_cod_padre_rel'=>'Dpto_codigo'),
  '[@APOLLO_SEASON]' => Array( 'bd'=>'mssql', 'dep'=>'subdpto',             'campo_sku'=>'U_APOLLO_SEASON', 'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'subdpto_prenda', 'nom_cod_rel'=>'Prenda_codigo', 'nom_cod_padre_rel'=>'Subdpto_id'),
  '[@APOLLO_DIV]'    => Array( 'bd'=>'mssql', 'dep'=>'[@APOLLO_SEASON]',    'campo_sku'=>'U_APOLLO_DIV',    'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'prenda_categoria', 'nom_cod_rel'=>'Categoria_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'presentacion'     => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_FILA',          'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'color'            => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_APOLLO_SEG2',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'talla'            => Array( 'bd'=>'mysql', 'dep'=>'[@APOLLO_SEASON]',    'campo_sku'=>'U_APOLLO_SSEG3',  /*'id'=>'id',         'type_id'=>'INT',*/ 'campo'=>'codigo', 'tabla_rel'=>'prenda_talla', 'nom_cod_rel'=>'Talla_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'copa'             => Array( 'bd'=>'mysql', 'dep'=>'[@APOLLO_SEASON]',    'campo_sku'=>'U_IDCopa',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'prenda_copa', 'nom_cod_rel'=>'Copa_id', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'formacopa'        => Array( 'bd'=>'mysql', 'dep'=>'[@APOLLO_SEASON]',    'campo_sku'=>'U_GSP_SECTION',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'prenda_formacopa', 'nom_cod_rel'=>'FormaCopa_id', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'material'         => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_MATERIAL',      'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'tprenda'          => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_EVD',           'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'tcatalogo'        => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_APOLLO_S_GROUP','id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'grupouso'         => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_ESTILO',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'composicion'      => Array( 'bd'=>'mysql',                               'campo_sku'=>'U_APOLLO_COO',    'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'caracteristica'   => Array( 'bd'=>'mysql',                               'campo_sku'=>'FrgnName',                'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'relacionprefijo'  => Array( 'bd'=>'mysql',                               'campo_sku'=>'',                'id'=>'id',         'type_id'=>'INT',     'campo'=>'')
);

function getCamposToQuery($nombre_tabla, $key_value, $as_tabla=""/*, ...*/){ // solo para 2 motores de base de datos
  $cadena="";
  global $$nombre_tabla;//si o si para que el array se pueda usar en esta funcion
  if($key_value=='key'){
      foreach ($$nombre_tabla as $key => $value) { $cadena.=$as_tabla.$key.",";  }
  }
  if($key_value=='value'){
      foreach ($$nombre_tabla as $key => $value) { $cadena.=$as_tabla.$value.","; }
  }
  $cadena=trim($cadena,",");
  return $cadena;
}
### FUNCION QUE RETORNA EL ID dado el nombre de la tabla y el valor del campo nombre
### el campo se obtendrá de la $tablas_sku y será aquel que detalle el nombre del registro de la tabla
function getIdFromName($nom_tabla, $val_campo){// solamente para aquellos que tienen nombre UNIQUE
  global $tablas_sku,$mysqli,$sqlsrv_33;
  $query_id="SELECT ".$tablas_sku["$nom_tabla"]["id"]." FROM $nom_tabla WHERE ".$tablas_sku["$nom_tabla"]["campo"]."='".$val_campo."';";
  if($tablas_sku["$nom_tabla"]["bd"]=="mysql")
    $arr_id=$mysqli->select($query_id,"mysqli_a_o");
  else{
    $arr_id=$sqlsrv_33->select($query_id,"sqlsrv_a_p");
  }if($arr_id===false)
      return -1;
  else{ 
      return $arr_id[0][$tablas_sku["$nom_tabla"]["id"]];
  }
}

function getNameFromId($nom_tabla,$val_id){
  global $tablas_sku,$mysqli,$sqlsrv_33;
  ($tablas_sku["$nom_tabla"]["type_id"]=='INT') ? $query_id="SELECT ".$tablas_sku["$nom_tabla"]["campo"] ." as name FROM $nom_tabla WHERE ".$tablas_sku["$nom_tabla"]["id"]."=$val_id" : $query_id="SELECT ".$tablas_sku["$nom_tabla"]["name"]." FROM $nom_tabla WHERE ".$tablas_sku["$nom_tabla"]["id"]."='".$val_id."'";
  if($tablas_sku["$nom_tabla"]["bd"]=="mysql")
    $arr_id=$mysqli->select($query_id,"mysqli_a_o");
  else{
    $arr_id=$sqlsrv_33->select($query_id,"sqlsrv_a_p");
  }if($arr_id===false)
      return -1;
  else{ 
      return $arr_id[0]["name"];
  }
}

?>
