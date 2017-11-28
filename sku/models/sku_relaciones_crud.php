<?php
session_start();
if(isset($_SESSION['user'])){
  $user=$_SESSION['user'];
}else {
  header("Location: ./index.php");  
}

require_once "../config/require.php";
require_once "../config/sku_db_mysqli.php";
// require_once "../config/sku_db_sqlsrv_33.php";
$cant_items_rel=5; //SETEO ESTATICO -- indica la cantidad de columnas que tienen la tabla a recorrer
if($_POST['option']=='show_relations'){
  $query_rel="SELECT * FROM relacionprefijo";
  $arr_rel=$mysqli->select($query_rel,"mysqli_a_o");
  if($arr_rel!==false && $arr_rel!=0){
    ///---una vez aca, teemos que obtener los nombres de cada codigo que aparece en las relaciones
    $cant_rel=count($arr_rel);
    for($i=0;$i<$cant_rel;$i++){
      for($j=0; $j<$cant_items_rel; $j++){
       
        
      }
    }
  }
}