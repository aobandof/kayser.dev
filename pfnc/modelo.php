<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/HelpersDB.php";
require_once "../shared/clases/DBConnection.php";
require_once "../shared/clases/sku_db_sqlsrv_13.php";

error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(2000); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS u otro medio

if(isset($_POST['opcion'])) {
    if($_POST['opcion']=='filtrar') {
        $orden_actual="";
        $pedido_actual="";
        $factura_actual="";
        $nota_credito_actual="";
        $cantidad_facturada=0;
        $monto_facturado=0;
        $monto_orden_compra=0;
        $ventas_pareadas=0;
        $oc_pareadas=0;
        $like="";
        $tipo="";
        $cuerpo="";
        $array_ventas=[];
        $array_facturas=[];
        $array_nc=[];
        foreach ($_POST as $campo=>$value) {
            if($campo!='opcion' && $campo!='Mes'){
                $like.="$campo LIKE '%$value%' AND ";
            }
        }
        if(isset($_POST['Mes']))
            $like="Mes='".$_POST['Mes']."' AND ".$like;            
        $like=substr($like,0,count($like)-5); //quitamos el ultimo ' AND '
        $query1= "SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND $like ORDER BY P_Orden";
				$arr_ocs=selectObject($query1);
				if($arr_ocs===0 || $arr_ocs===false){
					echo json_encode('SIN RESULTADOS');
					exit;
				}
        // $table="<html><body><table border='1px'>";
        foreach($arr_ocs as $oc => $arr_oc){
					for($i=0; $i<count($arr_oc['facturas']);$i++){
						$table.="<tr class='fila'><td>".$arr_oc['anio']."</td><td>".$arr_oc['mes']."</td><td>".$arr_oc['cod']."</td><td>".$arr_oc['razon']."</td><td>$oc</td>";
						$table.="<td>".$arr_oc['facturas'][$i]['num']."</td><td>".$arr_oc['facturas'][$i]['cant']."</td><td>".$arr_oc['facturas'][$i]['neto']."</td>";
						for($j=0; $j<count($arr_oc['nventas']);$j++){
							if($arr_oc['facturas'][$i]['neto']==$arr_oc['nventas'][$j]['neto'] && $arr_oc['facturas'][$i]['cant']==$arr_oc['nventas'][$j]['cant']){
								$table.="<td>".$arr_oc['nventas'][$j]['num']."</td><td>".$arr_oc['nventas'][$j]['cant']."</td><td>".$arr_oc['nventas'][$j]['neto']."</td>";
								$arr_oc['nventas'][$j]['factura']=$arr_oc['facturas'][$i]['num'];
								$arr_oc['facturas'][$i]['nventa']=$arr_oc['nventas'][$j]['num'];
								$j=count($arr_oc['nventas'])-1;									
							}
						}
						if($arr_oc['facturas'][$i]['nventa']=='')
							$table.="<td></td><td></td><td></td>";
						for($j=0; $j<count($arr_oc['ncreditos']);$j++){
							if($arr_oc['facturas'][$i]['neto']==$arr_oc['ncreditos'][$j]['neto'] && $arr_oc['facturas'][$i]['cant']==$arr_oc['ncreditos'][$j]['cant']){
								$table.="<td>".$arr_oc['ncreditos'][$j]['num']."</td><td>".$arr_oc['ncreditos'][$j]['cant']."</td><td>".$arr_oc['ncreditos'][$j]['neto']."</td></tr>";
								$arr_oc['ncreditos'][$j]['factura']=$arr_oc['facturas'][$i]['num'];
								$arr_oc['facturas'][$i]['ncredito']=$arr_oc['ncreditos'][$j]['num'];
								$j=count($arr_oc['ncreditos'])-1;
							}
						}
						if($arr_oc['facturas'][$i]['ncredito']=='')
							$table.="<td></td><td></td><td></td></tr>";
					}
					for($j=0; $j<count($arr_oc['nventas']);$j++){
						if($arr_oc['nventas'][$j]['factura']==''){
							$table.="<tr class='fila fila_no_apareada'><td>".$arr_oc['anio']."</td><td>".$arr_oc['mes']."</td><td>".$arr_oc['cod']."</td><td>".$arr_oc['razon']."</td><td>$oc</td>";
							$table.="<td></td><td></td><td></td>";
							$table.="<td>".$arr_oc['nventas'][$j]['num']."</td><td>".$arr_oc['nventas'][$j]['cant']."</td><td>".$arr_oc['nventas'][$j]['neto']."</td>";
								$table.="<td></td><td></td><td></td></tr>";
						}
					}
					for($j=0; $j<count($arr_oc['ncreditos']);$j++){
						if($arr_oc['ncreditos'][$j]['factura']==''){
							$table.="<tr class='fila fila_no_apareada'><td>".$arr_oc['anio']."</td><td>".$arr_oc['mes']."</td><td>".$arr_oc['cod']."</td><td>".$arr_oc['razon']."</td><td>$oc</td>";
							$table.="<td></td><td></td><td></td>";
							$table.="<td></td><td></td><td></td>";
							$table.="<td>".$arr_oc['ncreditos'][$j]['num']."</td><td>".$arr_oc['ncreditos'][$j]['cant']."</td><td>".$arr_oc['ncreditos'][$j]['neto']."</td></tr>";								
						}						
					}
					/// PENDIENTE PARA LOS NVENTA U NCREDITOS QUE NO SE ASOCIARON, POR AHORA NO SE VEN											
        }
        // $table.="</table></body></html>";
        echo json_encode($table);

    }//FIN IF $_POST['opcion']
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  var_dump(json_encode($errores));
}

  function selectObject($query){
    global $sqlsrv_13;
    $arr_export=[];
    $key_current='';
    $registros=sqlsrv_query($sqlsrv_13->_connection, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));      
    if($registros===false){
      return false;
    }else {
      if(sqlsrv_num_rows($registros)>0){
        while($reg=sqlsrv_fetch_array($registros,SQLSRV_FETCH_ASSOC)) {
					if(!isset($arr_export[$reg['P_Orden']])){
						$arr_export[$reg['P_Orden']]['anio']=$reg['Anio'];
						$arr_export[$reg['P_Orden']]['mes']=$reg['Mes'];
						$arr_export[$reg['P_Orden']]['cod']=$reg['Cod'];
						$arr_export[$reg['P_Orden']]['razon']=$reg['Razon'];
					}
					if($reg['ObjType']==13){
						$arr_export[$reg['P_Orden']]['facturas'][]=array('num'=>$reg['P_Num'], 'neto'=>$reg['Neto'], 'cant'=>$reg['Cant'], 'nventa'=>'', 'ncredito'=>'' );
					}
					if($reg['ObjType']==17)
          	$arr_export[$reg['P_Orden']]['nventas'][]=array('num'=>$reg['P_Num'], 'neto'=>$reg['Neto'], 'cant'=>$reg['Cant'], 'factura'=>'');
					if($reg['ObjType']==14)
          	$arr_export[$reg['P_Orden']]['ncreditos'][]=array('num'=>$reg['P_Num'], 'neto'=>$reg['Neto'], 'cant'=>$reg['Cant'], 'factura'=>'');
        }
      }else
          return 0;
    }
    return $arr_export; 
  }  
?>
