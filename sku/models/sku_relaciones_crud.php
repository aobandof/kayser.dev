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

$cant_items_rel=6; //SETEO ESTATICO -- indica la cantidad de columnas que tienen la tabla a recorrer
if($_POST['option']=='get_prefix_relations'){
  $query_rel="SELECT * FROM relacionprefijo";
  $arr_rel=$mysqli->select($query_rel,"mysqli_a_o");
  if($arr_rel!==false && $arr_rel!=0){
    ///---una vez aca, teemos que obtener los nombres de cada codigo que aparece en las relaciones
    $cant_rel=count($arr_rel);
    for($i=0;$i<$cant_rel;$i++){
      $id_rel=$arr_rel[$i]['id'];
      $fila="<div id='tr_".$id_rel."'><div>".$arr_rel[$i]['id']."</div>";
      if($arr_rel[$i][1]!==''){
        /// pendiente
      }
      for($j=1; $j<$cant_items_rel-1; $j++){        
        if($arr_rel[$i][$j]!==''){
          ///pendiente
        }
        else 
        $fila.="<div></div>";
      }
      $fila.="<div><img class='icon_dtable' src='./src/img/edit.png' alt=''><img class='icon_dtable' src='./src/img/edit_cancel.png' alt=''></div>";
      $fila.="<div><img class='icon_dtable' src='./src/img/delete.png' alt=''></div>";      
    }
  }
}