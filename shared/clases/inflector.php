<?php
function nombreMes($mes){
 setlocale(LC_TIME, 'spanish');
 $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
 return $nombre;
}
?>
