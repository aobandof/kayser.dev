<?php
# ARRAY QUE CONTIENE LOS MOTORES DE BASES DE DATOS DE MICSOSOFT SQL SERVER Y SUS CREDENCIALES
$MSSQL = array(
  '13' => array( 'host' => '192.168.0.13', 'user' => 'sa', 'pass' => 'kayser@dm1n' ) ,
  '17' => array( 'host' => '192.168.0.17', 'user' => 'wms', 'pass' => 'pjc3l1' ),
  '33' => array( 'host' => '192.168.0.33', 'user' => 'sa', 'pass' => 'sa' )
);
# ARRAY QUE CONTIENE LOS MOTORES DE BASES DE DATOS DE MYSQL Y SUS CREDENCIALES
$MYSQL = array(
  'dev' => array( 'host' => 'localhost', 'user' => 'root', 'pass' => '0013821' ),
  'prod' => array( 'host' => 'localhost', 'user' => 'root', 'pass' => 'qweasd' )
);

$sku = array(
  "codigo"             =>  "ItemCode",       //0
  "nombre"             =>  "ItemName",       //1
  "articulo"           =>  "U_APOLLO_SEG1",  //2
  "material"           =>  "U_MATERIAL",     //3
  "color"              =>  "U_APOLLO_SEG2",
  "presentacion"       =>  "U_FILA",
  "marca"              =>  "U_Marca",
  "temporadaPrenda"    =>  "U_EVD",
  "temporadaCatalogo"  =>  "QryGroup44",
  "grupoUso"           =>  "U_ESTILO",
  "formaCopa"          =>  "QryGroup2",
  "caracteristica"     =>  "FrgnName",
  "barcode"            =>  "CodeBars",
  "composicion"        =>  "U_APOLLO_COO",
  "peso"               =>  "BWeight1",
  "Grupo_nombre"       =>  "U_SUBGRUPO1",
  "Prenda_nombre"      =>  "SWW",
  "Categoria_codigo"   =>  "ItmsGrpCod",
  "Producto_codigo"    =>  "U_APOLLO_SEASON"
);

# ARRAY QUE CONTIENE DETALLES DE BDX, TABLAS, CAMPOS Y RELACIONES  SOBRE SKU
$tablas_sku = Array(
  'OITB'             => Array( 'bd'=>'mssql',                        'campo_sku'=>'ItmsGrpCod',      'id'=>'ItmsGrpCod', 'type_id'=>'INT',     'campo'=>'ItmSGrpNam'),
  'marca'            => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_Marca',         'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'subdpto'          => Array( 'bd'=>'mysql', 'dep'=>'OITB',  'campo_sku'=>'U_SUBGRUPO1',     'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'dpto_subdpto', 'nom_cod_rel'=>'Subdpto_id', 'nom_cod_padre_rel'=>'Dpto_codigo'),
  'APOLLO_SEASON'    => Array( 'bd'=>'mssql', 'dep'=>'Subdpto',      'campo_sku'=>'U_APOLLO_SEASON', 'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'subdpto_prenda', 'nom_cod_rel'=>'Prenda_codigo', 'nom_cod_padre_rel'=>'Subdpto_id'),
  'APOLLO_DIV'       => Array( 'bd'=>'mssql', 'dep'=>'APOLLO_SEASON','campo_sku'=>'U_APOLLO_DIV',    'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'prenda_categoria', 'nom_cod_rel'=>'Categoria_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'presentacion'     => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_FILA',          'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'color'            => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_APOLLO_SEG2',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'talla'            => Array( 'bd'=>'mysql', 'dep'=>'APOLLO_SEASON','campo_sku'=>'U_APOLLO_SSEG3',  /*'id'=>'id',         'type_id'=>'INT',*/ 'campo'=>'codigo', 'tabla_rel'=>'prenda_talla', 'nom_cod_rel'=>'Talla_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'copa'             => Array( 'bd'=>'mysql', 'dep'=>'APOLLO_SEASON','campo_sku'=>'U_IDCopa',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'prenda_copa', 'nom_cod_rel'=>'Copa_id', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'formacopa'        => Array( 'bd'=>'mysql', 'dep'=>'APOLLO_SEASON','campo_sku'=>'U_GSP_SECTION',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'prenda_formacopa', 'nom_cod_rel'=>'FormaCopa_id', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'material'         => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_MATERIAL',      'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'tempprenda'       => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_EVD',           'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'tempcatalogo'     => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_APOLLO_S_GROUP','id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'grupouso'         => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_ESTILO',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'composicion'      => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_APOLLO_COO',    'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'relacionprefijo'  => Array( 'bd'=>'mysql',                        'campo_sku'=>'',                'id'=>'id',         'type_id'=>'INT',     'campo'=>'')
);

$array_grand_child=[];

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
  global $tablas_sku,$mysqli,$sqlsrv;
  $query_id="SELECT ".$tablas_sku["$nom_tabla"]["id"]." FROM $nom_tabla WHERE ".$tablas_sku["$nom_tabla"]["campo"]."='".$val_campo."';";
  if($tablas_sku["$nom_tabla"]["bd"]=="mysql")
    $arr_id=$mysqli->select($query_id,"mysqli_a_o");
  else{
    $arr_id=$sqlsrv->select($query_id,"sqlsrv_a_p");
  }if($arr_id===false)
      return -1;
  else{ 
      return $arr_id[0][$tablas_sku["$nom_tabla"]["id"]];
  }
}

### FUNCION QUE CARGA LAS FILAS CON ID y NAME o NAME solmente DE UNA TABLA EN UN ARRAY ASOCIATIVO
// function getArrayIdName($nom_tabla){
//   global $tablas_sku,$mysqli,$sqlsrv;
//   $array_tabla=[];
//   if(isset($tablas_sku[$nom_tabla]['id']))
//     $query_id_name="SELECT ".$tablas_sku[$nom_tabla]['id'].",".$tablas_sku[$nom_tabla]['campo']." from $nom_tabla";
//   else
//     $query_id_name="SELECT ".$tablas_sku[$nom_tabla]['campo']." from $nom_tabla";
//   echo $query_id_name."<br>";
//   if($tablas_sku[$nom_tabla]['bd']=="mysql")
//     $arr_tabla=$mysqli->select($query_id_name,"mysqli_a_o");
//   else 
//     $arr_tabla=$sqlsrv->select($query_id_name,"sqlsrv_a_p");
//   if($arr_tabla===false)
//     return -1;
//   else  
//     $arr_id_name[$arr_t]

//   return $arr_tabla;
// }

### FUNCION QUE RETORNA UN ARRAY CON TODOS LAS FILAS ENCONTRADAS SEGUN LA LLAVE FORANEA
function cargarTallasToFamilia($value) { // SOLO PARA MYSQL
  global $mysqli;
  $arr_return=[];
  $query_coincidencias="SELECT nombre,orden FROM detalletalla where Talla_codigo='".$value."' ORDER BY nombre";
  if(($arr_coincidencias=$mysqli->select($query_coincidencias,"mysqli_a_o"))===false)
    return -1;
  return $arr_coincidencias;
}
### FUNCION QUE BUSCA TODAS LAS TABLAS DEPENDIENTES DE LAS DEPENDIENTES
### de tal forma qe los dependientes se llenaran con datos relacionados con el padre
### y los select de tablas dependientes de los dependientes se vaciaran.
function addGrandChild($son){//funcion recursiva.
  global $array_grand_child,$tablas_sku;
  foreach ($tablas_sku as $n_tabla => $a_tabla) {
    if($a_tabla['dep']==$son){//agregamos nieto y buscamos bisnieto y tataranietos...
      ## OjO CON LAS MAYUSCULAS y MINUSCULAS (METODO: in_array las distingue)
      if(!in_array($n_tabla,$array_grand_child)) {
        $array_grand_child[]=$n_tabla;
        addGrandChild($n_tabla); // recursivamente buscamos dependientes de este dependiente
      }
    }
  }
}


?>
