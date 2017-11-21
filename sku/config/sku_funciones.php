<?php
function getFirstBarcode() {
  global $sqlsrv_33;
  global $mysqli;
  $last_barcode_sap=780001000000;
  $last_barcode_lista=780001000000;
  $query_barcode="SELECT top 1 CodeBars from OITM WHERE CodeBars like '780001%' order by  CodeBars DESC";
  $arr_last_barcode=$sqlsrv_33->select($query_barcode,'sqlsrv_a_p');
  if($arr_last_barcode!==false){
    if ($arr_last_barcode!=0)
      $last_barcode_sap=((double)$arr_last_barcode_sap[0]['CodeBars']);
  }
  $query_barcode="SELECT barcode from sku order by barcode DESC LIMIT 1";
  $arr_last_barcode=$mysqli->select($query_barcode,'mysqli_a_o');
  if($arr_last_barcode!=false){
    if($arr_last_barcode!=0)
      $last_barcode_lista=((double)$arr_last_barcode[0]['barcode']);
  }
  $last_barcode_lista>=$last_barcode_sap ? $first_barcode=$last_barcode_lista + 1 : $first_barcode=$last_barcode_sap + 1 ;
  return $first_barcode;
}

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