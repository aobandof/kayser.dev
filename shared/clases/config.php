<?php
// error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE); // desactivamos los notices para efectos de llamada Asincrona, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
ini_set('display_errors', '1');
date_default_timezone_set("America/Santiago");
/*** cualquiera de las 2 siguientes cabeceras funciona para peticiones http o ajax, dejarlas desactivadas cuando programemos para ir viendo los errores*/
// header("Content-Type: text/html;charset=utf-8");
header('Content-type: application/json');


?>
