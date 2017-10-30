<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
// require_once("../shared/clases/inflector.php");
// ini_set('display_errors', '0');

$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

if($_POST['option']=='load_select_store'){
  $options='';
  $query_store="SELECT WhsCode as cod_tienda, WhsName AS tienda FROM OWHS where U_GSP_SENDTPV = 'Y' ORDER BY WhsName";
  $arr_store=$sqlsrv->select($query_store,'sqlsrv_a_p');
  for ($i=0; $i<count($arr_store); $i++){    
    $options.="<option value='".$arr_store[$i]['cod_tienda']."'>".$arr_store[$i]['tienda']."</option>";
  }
  $data['options']=$options;
  echo json_encode($data);
}

if($_POST['option']=='load_table'){
  
  $cod_store=$_POST['cod_store'];
  $type_doc=$_POST['type_doc'];
  $type_doc=='con_errores' ? $is_null='IS NOT NULL' : $is_null='IS NULL';
  $query_documents="select DISTINCT   U_GSP_CABOTI as cod_tienda, convert(varchar(10), U_GSP_CADATA,101) as fecha, U_GSP_ERROR as error
                    from [SBO_KAYSER].[dbo].[@GSP_TPVCAP]
                    where U_GSP_CADOCU<>'VRG' AND U_GSP_CAESTA<>'x' AND convert(varchar(10), U_GSP_CADATA,101)<>convert(varchar(10), GETDATE(),101) AND U_GSP_ERROR $is_null AND U_GSP_CABOTI='$cod_store'
                    ORDER by U_GSP_CABOTI";
  $arr_documentos=$sqlsrv->select($query_documents,"sqlsrv_a_p");
  if($arr_documentos!==false){
      if($arr_documentos!=0){
        $thead="<thead><tr><th class='col-xs-1'>N°</th><th class='col-xs-3'>TIENDA</th><th class='col-xs-2'>FECHA</th><th class='col-xs-6'>ERROR</th></tr></thead>";
        $venta_diaria_total=0;
        $venta_anterior_total=0;
        for($i=0; $i<count($arr_documentos);$i++){
            $tbody.="<tr class='fila'><td class='col-xs-1'>".($i+1)."</td>";
            $tbody.="<td class='col-xs-3'>".$arr_documentos[$i]['cod_tienda']."</td>";
            $tbody.="<td class='col-xs-2'>".$arr_documentos[$i]['fecha']."</td>";
            $tbody.="<td class='col-xs-6'>".$arr_documentos[$i]['error']."</td></tr>";
        }
        $tbody.="</tbody>";
      }else
          $data['resp']="SIN RESULTADOS";
      $data['table']=$thead.$tbody;
      // $data['query']=$query_total_anterior;
  }else{
      $data['errors'][]=$sqlsrv->getErrors();
  }
  $sqlsrv->closeConnection();
  echo json_encode($data);
}