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
  'prod' => array( 'host' => 'localhost', 'user' => 'root', 'pass' => '12qwaszx' )
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
  'Kayser_OITB'      => Array( 'bd'=>'mssql', 'dep'=>'padre',        'campo_sku'=>'ItmsGrpCod',      'id'=>'ItmsGrpCod', 'type_id'=>'INT',     'campo'=>'ItmSGrpNam'),
  'Marca'            => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_Marca',         'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'Subdpto'          => Array( 'bd'=>'mysql', 'dep'=>'Kayser_OITB',  'campo_sku'=>'U_SUBGRUPO1',     'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'Dpto_Subdpto', 'nom_cod_rel'=>'Subdpto_id', 'nom_cod_padre_rel'=>'Dpto_codigo'),
  'Kayser_SEASON'    => Array( 'bd'=>'mssql', 'dep'=>'Subdpto',      'campo_sku'=>'U_APOLLO_SEASON', 'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'Subdpto_Prenda', 'nom_cod_rel'=>'Prenda_codigo', 'nom_cod_padre_rel'=>'Subdpto_id'),
  'Kayser_DIV'       => Array( 'bd'=>'mssql', 'dep'=>'Kayser_SEASON','campo_sku'=>'U_APOLLO_DIV',    'id'=>'Code',       'type_id'=>'STRING',  'campo'=>'Name', 'tabla_rel'=>'Prenda_Categoria', 'nom_cod_rel'=>'Categoria_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'Presentacion'     => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_FILA',          'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'Color'            => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_APOLLO_SEG2',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'Talla'            => Array( 'bd'=>'mysql', 'dep'=>'Kayser_SEASON','campo_sku'=>'U_APOLLO_SSEG3',  /*'id'=>'id',         'type_id'=>'INT',*/ 'campo'=>'codigo', 'tabla_rel'=>'Prenda_Talla', 'nom_cod_rel'=>'Talla_codigo', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'Copa'             => Array( 'bd'=>'mysql', 'dep'=>'Kayser_SEASON','campo_sku'=>'U_IDCopa',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'Prenda_Copa', 'nom_cod_rel'=>'Copa_nombre', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'FormaCopa'        => Array( 'bd'=>'mysql', 'dep'=>'Kayser_SEASON','campo_sku'=>'U_GSP_SECTION',   'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre', 'tabla_rel'=>'Prenda_FormaCopa', 'nom_cod_rel'=>'FormaCopa_nombre', 'nom_cod_padre_rel'=>'Prenda_codigo'),
  'Material'         => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_MATERIAL',      'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'TempPrenda'       => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_EVD',           'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'TempCatalogo'     => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_APOLLO_S_GROUP','id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre'),
  'GrupoUso'         => Array( 'bd'=>'mysql',                        'campo_sku'=>'U_ESTILO',        'id'=>'id',         'type_id'=>'INT',     'campo'=>'nombre')
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
  global $tablas_sku,$mysqli,$conector_mssql;
  $query_id="SELECT ".$tablas_sku["$nom_tabla"]["id"]." FROM $nom_tabla WHERE ".$tablas_sku["$nom_tabla"]["campo"]."='".$val_campo."';";
  if($tablas_sku["$nom_tabla"]["bd"]=="mysql") {
    if(!$registros=$mysqli->query($query_id)) {
      return -1;
    } else {
      $reg = $registros->fetch_assoc();
      return $reg[$tablas_sku["$nom_tabla"]["id"]];
    }
  }else
    if($registros=sqlsrv_query($conector_mssql, $query_id,array(), array( "Scrollable" => 'static' ))){
      $reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC);
      return $reg[$tablas_sku["$nom_tabla"]["id"]];
    }
    else {
      if(sqlsrv_errors()!=null) {
        return -1;
      }
    }
}

### FUNCION QUE CARGA LAS FILAS CON ID y NAME o NAME solmente DE UNA TABLA EN UN ARRAY ASOCIATIVO
function getArrayIdName($nom_tabla){
  global $tablas_sku,$mysqli,$conector_mssql;
  $array_tabla=[];
  if(isset($tablas_sku[$nom_tabla]['id']))
    $query_id_name="SELECT ".$tablas_sku[$nom_tabla]['id'].",".$tablas_sku[$nom_tabla]['campo']." from $nom_tabla";
  else
    $query_id_name="SELECT ".$tablas_sku[$nom_tabla]['campo']." from $nom_tabla";
  if($tablas_sku[$nom_tabla]['bd']=="mysql"){
    if(!$registros=$mysqli->query($query_id_name))
      return -1;
    else {
      while($reg=$registros->fetch_array()){
        $array_tabla[]=array("cod"=>$reg[0], "name"=>$reg[1]);
      }
    }
  }else {
    if(!$registros=sqlsrv_query($conector_mssql, $query_id_name,array(), array( "Scrollable" => 'static' ))){
      return -1;
    }else {
      while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_NUMERIC)){
        // $array_tabla[]=array("cod"=>$reg[0], "name"=>$reg[1]);
        $array_tabla["$reg[0]"]=$reg[1];
      }
    }
  }
  return $array_tabla;
}

### FUNCION QUE RETORNA UN ARRAY CON TODOS LAS FILAS ENCONTRADAS SEGUN LA LLAVE FORANEA
function cargarTallasToFamilia($value) { // SOLO PARA MYSQL
  global $mysqli;
  $arr_return=[];
  $query_coincidencias="SELECT nombre FROM DetalleTalla where Talla_codigo='".$value."'";
  // echo $query_coincidencias;
  if(!$registros=$mysqli->query($query_coincidencias))
    return -1;
  else {
    while ($reg=$registros->fetch_assoc())
      $arr_return[]=$reg['nombre'];
  }
  return $arr_return;
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
