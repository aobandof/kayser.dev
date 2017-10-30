<?php
require_once ("../shared/clases/config.php");
require_once ("../shared/clases/HelpersDB.php");

require_once "../shared/clases/DBConnection.php";

if($_POST['option']=='load_select_store'){
  $options="<option value='105'>SAN DIEGO</option>";
  $options.="<option value='102'>ESTACION CENTRAL</option>";
  $data['options']=$options;
  echo json_encode($data);
}
?>