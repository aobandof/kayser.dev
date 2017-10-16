<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER

/****** INSTANCIAMOS LOS PARAMATROS PARA LA CONEXION EN ESTE SCRIPT **************/
$conexion=new MssqlConexion('192.168.0.33','sa','sa','SBO_KAYSER');
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
if(isset($_POST['opcion'])){
    $cuerpo="";
    $pie="";
    $cont=0;
    if($_POST['opcion']=="ventas") {
        $query="SELECT A1.WhsName, A2.VtaMinAct FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC";
        $registros = sqlsrv_query($conector, $query);
        if( $registros === false ){
            if(sqlsrv_errors()!=null) {
                cargarErrores();
            }else $datos[]=array( 'respuesta' => 'ERRORES' );
        } else {
            $venta_total=0;
            While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC)) {
                $cont++;
                $cuerpo.="<tr class='fila'><td class='col-xs-1'>$cont</td><td class='col-xs-6'>".$reg[0]."</td><td class='col-xs-3'>".number_format ( floatval($reg[1]), 0 , ',', '.')."</td><td class='col-xs-2'>lll</td></tr>";
                $venta_total+=$reg[1];
            }
            $venta_total=number_format ( floatval($venta_total), 0 , ',', '.');
            $pie.="<td colspan='2' class='col-xs-7'>VENTA TOTAL :&nbsp</td><td class='col-xs-3'>".$venta_total."</td><td class='col-xs-2'>nnnnn</td>";
            $datos[]=array('cuerpo' => $cuerpo, 'pie' => $pie);
            $conexion->desconectar();
            echo json_encode($datos);
        }
    }
    if($_POST['opcion']=="ventas_promotoras") {
        $venta_total_mensual=0;
        $array_ventas_diarias=array();
        $total_porcentaje=0;
        $venta_total_diaria=0;
        /******* PRIMERO CARGAMOS EN UN ARRAY LA ACTIVIDAD DIARIA DE LAS PROMOTORAS POR TIENDA *******/
        $query_promotoras="SELECT A1.WhsName, VtaMinAct FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC";# code...
        $registros = sqlsrv_query($conector, $query_promotoras);
        While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC)) {
            $array_ventas_diarias[]=array('tienda'=>$reg[0],'venta'=>$reg[1]);
            $venta_total_diaria+=$reg[1];
        }
        /****** CONEXION A OTRO SERVIDOR PARA OBTENER EL ACUMULADO MENSUAL POR TIENDA que sera mi tabla principal *****/
        $conexion2=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','GSP');
        $conector2=$conexion2->obtener_conector();
        if(!$conector2){
            if(sqlsrv_errors()!=null) {
                cargarErrores();
                exit;
            }
        }
        /****** consulta para obtener la suma *****/
        $query_suma="Select sum(Bruto) from MM_VentaPROMOTORAMes";
        $registros = sqlsrv_query($conector2, $query_suma);
        $reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC);
        $venta_total_mensual=$reg[0];
        /******************************************/

        $query_mensual="SELECT * FROM MM_VentaPROMOTORAMes ORDER BY Bruto DESC";
        $registros = sqlsrv_query($conector2, $query_mensual);
        if( $registros === false ){
            if(sqlsrv_errors()!=null) {
                cargarErrores();
            }else $datos[]=array( 'respuesta' => 'ERRORES' );
        } else {
            While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC)) {
                $cont++;
                $index=array_search($reg[0], array_column($array_ventas_diarias, 'tienda')); //buscamos el indice del sub array que contiene el nombre de la tienda
                $venta_diaria=number_format ( floatval($array_ventas_diarias[$index]['venta']), 0 , ',', '.');
                $porcentaje=(((float)($reg[1]))*100)/$venta_total_mensual;
                $cuerpo.="<tr class='fila_promotoras'><td class='col-xs-1'>$cont</td><td class='col-xs-5'>".$reg[0]."</td><td class='col-xs-2'>$venta_diaria</td><td class='col-xs-3'>".number_format ( floatval($reg[1]), 0 , ',', '.')."</td><td class='col-xs-1'>".round($porcentaje)."</td></tr>";
                $total_porcentaje+=$porcentaje;
            }
            $venta_total_diaria=number_format ( floatval($venta_total_diaria), 0 , ',', '.');
            $venta_total_mensual=number_format ( floatval($venta_total_mensual), 0 , ',', '.');
            $pie.="<td colspan='2' class='col-xs-6'>TOTAL PROMOTORAS: </td><td class='col-xs-2'>".$venta_total_diaria."</td><td class='col-xs-3'>$venta_total_mensual</td><td class='col-xs-1'>$total_porcentaje</td>";
            $datos[]=array('cuerpo' => $cuerpo, 'pie' => $pie, 'venta_total_mensual' => $venta_total_mensual);
            $conexion->desconectar();
            echo json_encode($datos);
        }
    }
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  header('Content-type: application/json');
  var_dump(json_encode($errores));
}
?>
