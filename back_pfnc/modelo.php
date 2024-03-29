<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(1000); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS u otro medio

/****** INSTANCIAMOS LOS PARAMATROS PARA LA CONEXION EN ESTE SCRIPT **************/
$conexion=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}

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
        $query= "SELECT * FROM PFNC WHERE ObjType='13' AND P_Orden!='NULL' AND P_Orden!='' AND $like ORDER BY P_Orden";
        // echo $query."<br />";
        //$query= "SELECT * FROM PFNC WHERE P_Orden!='NULL' AND P_Orden!='' AND P_Orden LIKE '%79004201%'  ORDER BY P_Orden";
        $select=sqlsrv_query($conector, $query,array(), array( "Scrollable" => 'static' ));
        $cantidad=(int)sqlsrv_num_rows($select);
        if($select) {
            if($cantidad > 0 ) {
                while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_ASSOC)){
                    if($reg['P_Orden']!=$orden_actual){
                      $orden_actual=$reg['P_Orden'];
                      $query2="SELECT * FROM PFNC WHERE P_Orden='$orden_actual' AND $like ORDER BY ObjType";
                      // echo $query2."<br />";
                      $select2=sqlsrv_query($conector, $query2,array(), array( "Scrollable" => 'static' ));
                      $cantidad_oc=(int)sqlsrv_num_rows($select2);
                      if($select2){
                        $rut=$reg['Cod'];
                        $cliente=$reg['Razon'];
                        unset($array_ventas);unset($array_facturas);unset($array_nc);
                        while($reg2=sqlsrv_fetch_array($select2,SQLSRV_FETCH_ASSOC)){
                            if($reg2['ObjType']==17){
                                $montito=/*number_format ( */floatval(abs($reg2['Bruto']))/*, 0 , ',', '.')*/;
                                $array_ventas[]=array($reg2['Anio'],$reg2['Mes'],$reg2['P_Num'],$reg2['Cant'],$montito,"no");
                            }
                            if($reg2['ObjType']==14){
                                $montito=/*number_format ( */floatval(abs($reg2['Bruto']))/*, 0 , ',', '.')*/;
                                $array_nc[]=array($reg2['Anio'],$reg2['Mes'],$reg2['P_Num'],$reg2['Cant'],$montito,"no");
                            }
                            if($reg2['ObjType']==13){
                                $montito=/*number_format ( */floatval(abs($reg2['Bruto']))/*, 0 , ',', '.')*/;
                                $array_facturas[]=array($reg2['Anio'],$reg2['Mes'],$reg2['P_Num'],$reg2['Cant'],$montito,"","","","","","","no","no");
                            }
                        }//fin while consulta 2
                        //cargamos el array FACTURAS
                        foreach($array_facturas as $indice => $valor){
                            $monto=$array_facturas[$indice][4];
                            if(isset($array_ventas)){
                                foreach($array_ventas as $indice2 => $valor2 ){
                                    if($array_ventas[$indice2][4]==$monto && $array_ventas[$indice2][5]!="si" && $array_facturas[$indice][11]!="si"){
                                        $array_facturas[$indice][5]=$array_ventas[$indice2][2];
                                        $array_facturas[$indice][6]=$array_ventas[$indice2][3];
                                        $array_facturas[$indice][7]=$array_ventas[$indice2][4];
                                        $array_facturas[$indice][11]="si";
                                        $array_ventas[$indice2][5]="si";
                                        $ventas_pareadas++;
                                    }
                                }
                            }
                            if(isset($array_nc)){
                                foreach($array_nc as $indice3 => $valor3 ){
                                    if($array_nc[$indice3][4]==$monto && $array_nc[$indice3][5]!="si" && $array_facturas[$indice][12]!="si"){
                                        $array_facturas[$indice][8]=$array_nc[$indice3][2];
                                        $array_facturas[$indice][9]=$array_nc[$indice3][3];
                                        $array_facturas[$indice][10]=$array_nc[$indice3][4];
                                        $array_facturas[$indice][12]="si";
                                        $array_nc[$indice3][5]="si";
                                        $nc_pareadas++;
                                    }
                                }
                            }
                        }//fin foreach
                        //rRecorremos el array nota de venta para dibujar la tabla
                        //$cuerpo.=""
                        foreach($array_facturas as $indice => $valor ){
                            $cuerpo.="<tr class='fila'>";
                            $cuerpo.="<td>".$array_facturas[$indice][0]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][1]."</td>";
                            $cuerpo.="<td>".$rut."</td>";
                            $cuerpo.="<td>".$cliente."</td>";
                            $cuerpo.="<td>".$orden_actual."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][2]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][3]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][4]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][5]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][6]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][7]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][8]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][9]."</td>";
                            $cuerpo.="<td>".$array_facturas[$indice][10]."</td></tr>";
                        }
                        if(isset($array_ventas)){
                            foreach($array_ventas as $indice => $valor){
                                if($array_ventas[$indice][5]=="no"){
                                    $cuerpo.="<tr class='fila fila_no_apareada'>";
                                    $cuerpo.="<td>".$array_ventas[$indice][0];
                                    $cuerpo.="<td>".$array_ventas[$indice][1];
                                    $cuerpo.="<td>".$rut."</td>";
                                    $cuerpo.="<td>".$cliente."</td>";
                                    $cuerpo.="<td>".$orden_actual."</td>";
                                    $cuerpo.="<td></td><td></td><td></td>";
                                    $cuerpo.="<td>".$array_ventas[$indice][2]."</td><td>".$array_ventas[$indice][3]."</td><td>".$array_ventas[$indice][4]."</td>";
                                    $cuerpo.="<td></td><td></td><td></td></tr>";
                                }
                            }
                        }
                        if(isset($array_nc)){
                            foreach($array_nc as $indice => $valor){
                                if($array_nc[$indice][5]=="no") {
                                    $cuerpo.="<tr class='fila fila_no_apareada'>";
                                    $cuerpo.="<td>".$array_nc[$indice][0];
                                    $cuerpo.="<td>".$array_nc[$indice][1];
                                    $cuerpo.="<td>".$rut."</td>";
                                    $cuerpo.="<td>".$cliente."</td>";
                                    $cuerpo.="<td>".$orden_actual."</td>";
                                    $cuerpo.="<td></td><td></td><td></td><td></td><td></td><td></td>";
                                    $cuerpo.="<td>".$array_nc[$indice][2]."</td><td>".$array_nc[$indice][3]."</td><td>".$array_nc[$indice][4]."</td></tr>";
                                }
                            }
                        }
                      }
                    }
                }//FIN while
                $movimientos[]=array('cuerpo'=>$cuerpo, 'consulta'=>$query, 'cantidad'=> $cantidad);
                $conexion->desconectar();
                echo json_encode($movimientos);
                // echo "<table border='1px'>".$cuerpo."</table>";
            } else {
                $movimientos[]=array('cuerpo'=>"SIN RESULTADOS", 'consulta'=>$query, 'cantidad'=> $cantidad);
                $conexion->desconectar();
                echo json_encode($movimientos);
            }
        } else {
            if(sqlsrv_errors()!=null) {
                cargarErrores();
            }else $movimientos[]=array( 'respuesta' => 'ERRORES' );
        }
    }//FIN IF $_POST['opcion']
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  var_dump(json_encode($errores));
}
?>
