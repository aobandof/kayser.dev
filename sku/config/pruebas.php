<?php
require_once "require.php";
require_once "sku_db_mysqli.php";

// echo "cantidad de registros";
// echo $mysqli->quantityRecords('SELECT codigo FROM articulo WHERE lista_id=2');

// $first=getFirstBarcode();
// echo json_encode($first);
// echo "<br>\n";
// echo gettype($first);


// echo getNameFromId('subdpto',3);

echo $mysqli->getColumnFromColumn('presentacion','abreviatura','id','NUMBER',2);

