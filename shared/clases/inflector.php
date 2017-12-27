<?php
function nombreMes($mes){
 setlocale(LC_TIME, 'spanish');
 $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
 return $nombre;
}

///--- FUNCION QUE RETORNA UNA SUBCADENA CON TODAS LAS LETRAS ENCONTRADAS DE IZQUIERA DA DERECHA (Ej. 109S, devolvera S)
function getLetersCadena($cadena){
  $cadena_length=strlen($cadena);
  $result='';
  for($i=0; $i<$cadena_length; $i++){
    if(!is_numeric($i)){
      $result.=$i;
    }
  } 
}

///--- FUNCION QUE OBTIENE EL DIGITO VERIFICADOR DEVOLVIENDO EL BARCODE COMPLETO INCLUYENDO ESTE VERIFICADOR 

function getControlDigit($bcde){
  $arr_barcode=str_split($bcde);
  $sum_dig_odd=0;
  $sum_dig_even=0;
  for($i=0; $i<count($arr_barcode); $i++)
    $i % 2 == 0 ? $sum_dig_even += intval($arr_barcode[$i]) : $sum_dig_odd += intval($arr_barcode[$i]);
  $sum_result=($sum_dig_odd*3)+$sum_dig_even;
  $rest = $sum_result % 10;
  $rest==0 ? $result=0 : $result=10-$rest;
  return $result;
}


///--- FUNCTION QUE INDICA SI UNA CADENA TIENE LETRAS
/*function haveLeters($cadena){
  $have=0; //flag que seteado en 0 que indica que no tiene letras
  $cadena_length=strlen($cadena);
  for($i=0; $i<$cadena_length; $i++){
    if(!is_numeric($i)){
      $have=1;
      break;
    }
  }  
}*/ ///--- ctype_digit($cadena) la reemplaza dado que devuelve true si son solo numeros o false si no

?>
