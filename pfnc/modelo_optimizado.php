<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/inflector.php";

error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER

date_default_timezone_set("America/Santiago");
$conexion=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        //echo "error de conexion<br><br>";
        //var_dump(sqlsrv_errors());
        exit;
    }
}
if(isset($_POST['opcion'])){
    if($_POST['opcion']=='filtrar'){
        $orden_recorrida="";
        $orden_anterior="";
        $llenado=0; //este flag lo usamos para llenar por primera vez los datos de orden de compra que se repiten en todas los registros de ventas, facturas u o_c
        $unidades_pedidas=0;
        $unidades_facturadas=0;
        $unidades_notas_credito=0;
        $unidades_pendientes=0;
        $monto_pedido=0;
        $monto_facturado=0;
        $monto_notas_credito=0;
        $like="";
        foreach ($_POST as $campo=>$value) {
            if($campo!='opcion')
                $like.="$campo LIKE '%$value%' AND ";
        }
        $like=substr($like,0,count($like)-5); //quitamos el ultimo ' AND '
        $query= "SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND $like ORDER BY P_Orden";
        //$query= "SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND P_Orden LIKE '%79004201%'  ORDER BY P_Orden";
        $select=sqlsrv_query($conector, $query);
        if($select) {
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_ASSOC)){
                if($llenado==0){
                    $orden_anterior=$reg['P_Orden'];
                    $mes=nombreMes($reg['Mes']);
                    $anio=$reg['Anio'];
                    $cliente=$reg['Razon'];
                    $rut=$reg['Cod'];
                    $oc=$reg['P_Orden'];
                    $llenado=1;
                }
                if($orden_anterior==$reg['P_Orden']){
                    if($reg['ObjType']==13){
                        $unidades_facturadas+=$reg['Cant'];
                        $monto_facturado+=abs($reg['Bruto']);
                        $facturas[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                    if($reg['ObjType']==14){
                        $unidades_notas_credito+=$reg['Cant'];
                        $monto_notas_credito+=abs($reg['Bruto']);
                        $notas_credito[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                    if($reg['ObjType']==17){
                        $unidades_pedidas+=$reg['Cant'];
                        $monto_pedido+=$reg['Bruto'];
                        $pedidos[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                }else {
                    $llenado=0;
                    $orden_anterior=$reg['P_Orden'];
                    //AGREGAMOS EL ARRAY DE LA ORDEN DE COMPRA ANTERIOR
                    $unidades_pendientes=$unidades_facturadas-$unidades_pedidas;
                    $movimientos[]=array(
                                    'fecha'=>$anio.'-'.$mes,
                                    'rut'=>$rut,
                                    'cliente'=>$cliente,
                                    'orden_compra'=>$oc,
                                    'unidades_pedidas'=>$unidades_pedidas,
                                    'unidades_facturadas'=>$unidades_facturadas,
                                    'unidades_notas_credito'=>$unidades_notas_credito,
                                    'monto_pedido'=>$monto_pedido,
                                    'monto_facturado'=>$monto_facturado,
                                    'monto_notas_credito'=>$monto_notas_credito,
                                    'facturas'=>$facturas,'notas_credito'=>$notas_credito,'pedidos'=>$pedidos
                                );
                    //vaciamos los arreglos y los montos
                    $unidades_pedidas=0;
                    $unidades_facturadas=0;
                    $unidades_notas_credito=0;
                    $unidades_pendientes=0;
                    $monto_pedido=0;
                    $monto_facturado=0;
                    $monto_notas_credito=0;
                    unset($pedidos);unset($notas_credito);unset($facturas);
                    //AGREGAMOS NUEVOS VALORES PARA NUEVA ORDEN DE COMPRA
                    $orden_anterior=$reg['P_Orden'];
                    $mes=nombreMes($reg['Mes']);
                    $anio=$reg['Anio'];
                    $cliente=$reg['Razon'];
                    $rut=$reg['Cod'];
                    $oc=$reg['P_Orden'];
                    if($reg['ObjType']==13){
                        $unidades_facturadas+=$reg['Cant'];
                        $monto_facturado+=abs($reg['Bruto']);
                        $facturas[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                    if($reg['ObjType']==14){
                        $unidades_notas_credito+=$reg['Cant'];
                        $monto_notas_credito+=abs($reg['Bruto']);
                        $notas_credito[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                    if($reg['ObjType']==17){
                        $unidades_pedidas+=$reg['Cant'];
                        $monto_pedido+=$reg['Bruto'];
                        $pedidos[]=array('numero'=>$reg['P_Num'],'cantidad'=>$reg['Cant'],'monto'=>$reg['Bruto']);
                    }
                }
            }//FIN while
            $unidades_pendientes=$unidades_facturadas-$unidades_pedidas;
            $movimientos[]=array(
                            'fecha'=>$anio.'-'.$mes,
                            'rut'=>$rut,
                            'cliente'=>$cliente,
                            'orden_compra'=>$oc,
                            'unidades_pedidas'=>$unidades_pedidas,
                            'unidades_facturadas'=>$unidades_facturadas,
                            'unidades_notas_credito'=>$unidades_notas_credito,
                            'monto_pedido'=>$monto_pedido,
                            'monto_facturado'=>$monto_facturado,
                            'monto_notas_credito'=>$monto_notas_credito,
                            'facturas'=>$facturas,'notas_credito'=>$notas_credito,'pedidos'=>$pedidos
                        );
        } else {
            if(sqlsrv_errors()!=null) {
                cargarErrores();
            }else $movimientos[]=array( 'respuesta' => 'ERRORES' );
        }
        $conexion->desconectar();
        header('Content-type: application/json');
        echo json_encode($movimientos);
        //echo json_encode("no hay errores");
        // header('Content-type: application/json');
        // $data[]=array('prueba'=> 'ENTRO', 'valor' => $query );
        // echo json_encode($data);
    }//FIN IF $_POST['opcion']
    else {
        header('Content-type: application/json');
        $data[]=array('prueba'=> "NO ENTRO", 'valor' => 'POST_[opcion] existe pero no es igual a filtrar');
        echo json_encode($data);
    }
}else {
    header('Content-type: application/json');
    $data[]=array('prueba'=> "NO ENTRO", 'valor' => 'POST_[opcion] no existe');
    echo json_encode($data);
}

function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  header('Content-type: application/json');
  var_dump(json_encode($errores));
}
?>
