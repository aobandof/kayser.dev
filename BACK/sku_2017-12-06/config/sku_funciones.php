<?php
///--- FUNCION QUE BUSCA EL ULTIMO BARCODE DE SAP Y DE LAS LISTAS, LE SUMA + 1 Y DEVIUELV EL PRIMER BARCODE APTO A UTILIZAR
function getFirstBarcode() {
  global $sqlsrv_33;
  global $mysqli;
  $last_barcode_sap=780001000000;
  $last_barcode_lista=780001000000;
  ///obtenemos el ultimo sin considerar el digito verificador
  $query_barcode="SELECT top 1 SUBSTRING(CodeBars,0,LEN(CodeBars)) as barcode from OITM WHERE CodeBars like '780001%' order by  SUBSTRING(CodeBars,0,LEN(CodeBars)) DESC";
  $arr_last_barcode=$sqlsrv_33->select($query_barcode,'sqlsrv_a_p');
  // var_dump($arr_last_barcode);
  if($arr_last_barcode!==false){
    if ($arr_last_barcode!==0){
      $last_barcode_sap=((double)$arr_last_barcode[0]['barcode']);
    }
  }
  // echo "lasta barcode despues de buscar en sap:  ".$last_barcode_sap."<br>";
  $query_barcode="SELECT SUBSTRING(barcode,1,LENGTH(barcode)-1) as barcode from sku order by barcode DESC LIMIT 1";
  $arr_last_barcode=$mysqli->select($query_barcode,'mysqli_a_o');
  if($arr_last_barcode!=false){
    if($arr_last_barcode!=0)
      $last_barcode_lista=((double)$arr_last_barcode[0]['barcode']);
  }
  // echo "lasta barcode despues de buscar en list:  ".$last_barcode_lista."<br>";  
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