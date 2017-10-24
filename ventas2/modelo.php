<?php

require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
ini_set('display_errors', '0');

$sqlsrv_33=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$sqlsrv_13=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$sqlsrv_promes=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv_33->getConnection())===false) { $data['errors'][]=$sqlsrv_33->getErrors(); $existe_error_conexion=1; }
if(($sqlsrv_13->getConnection())===false) { $data['errors'][]=$sqlsrv_13->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

if(isset($_POST['opcion'])){       
    $day=(int)date('d');
    $month=(int)date('m');
    $year=(int)date('Y')-1;
    if($_POST['opcion']=="detalle") {
        $fecha_anterior=$year.'-'.$month.'-'.$day;
        $thead='<thead><tr id="tr_head_ventas" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-5">TIENDA</th><th class="col-xs-3" id="th_hoy">VENTA<br>DIARIA</th><th class="col-xs-3" id="th_x_dia">VENTA<br>2016</th></tr></thead>';
        $tbody='<tbody id="tbody_ventas">';
        $query_detalle_diaria="SELECT A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct as total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC";
        $query_detalle_anterior="SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '$fecha_anterior', 20) group by Almacen, Bodega";        
        $arr_venta_diaria=$sqlsrv_33->select($query_detalle_diaria,"sqlsrv_a_p");
        $arr_venta_anterior=$sqlsrv_13->selectArrayUniAssocIdName($query_detalle_anterior);        
        if($arr_venta_diaria!==false){
            if($arr_venta_diaria!=0){
                $venta_diaria_total=0;
                $venta_anterior_total=0;
                for($i=0; $i<count($arr_venta_diaria);$i++){
                    $cod_tienda=$arr_venta_diaria[$i]['cod_tienda'];
                    $tbody.="<tr class='fila'><td class='col-xs-1'>".($i+1)."</td>";
                    $tbody.="<td class='col-xs-5'>".$arr_venta_diaria[$i]['tienda']."</td>";
                    $tbody.="<td class='col-xs-3'>".number_format ( floatval($arr_venta_diaria[$i]['total']), 0 , ',', '.')."</td>";
                    $tbody.="<td class='col-xs-3'>".number_format ( floatval($arr_venta_anterior[$cod_tienda]), 0 , ',', '.')."</td></tr>";
                    $venta_diaria_total+=$arr_venta_diaria[$i]['total'];
                    $venta_anterior_total+=$arr_venta_anterior[$cod_tienda];
                }
                $tbody.="</tbody>";
            }else
                $data['resp']="SIN RESULTADOS";
            $venta_diaria_total=number_format ( floatval($venta_diaria_total), 0 , ',', '.');
            $venta_anterior_total=number_format ( floatval($venta_anterior_total), 0 , ',', '.');
            $tfoot='<tfoot><tr class="pie" id="tr_pie_ventas"><td colspan="2" class="col-xs-6">VENTA TOTAL :&nbsp</td><td class="col-xs-3">'.$venta_diaria_total.'</td><td class="col-xs-3">'.$venta_anterior_total.'</td></tr></tfoot>';
            $data['table']=$thead.$tbody.$tfoot;

        }else{
            $data['errors'][]=$sqlsrv->POSTErrors();
        }
        echo json_encode($data);
    }
    if($_POST['opcion']=="promotoras") {
        $thead='<thead><tr id="tr_head_ventas_promotoras" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-5">TIENDA</th><th class="col-xs-2">VENTA<br>DIARIA</th><th  class="col-xs-3">VENTA<br>MENSUAL</th><th  class="col-xs-1">%</th></tr></thead>';
        $venta_diaria_total=0;
        $venta_mensual_total=0;
        
        $query_promotoras_diaria="SELECT A1.WhsCode, A2.VtaMinAct FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC";
        $query_promotoras_mensual="SELECT bodega as tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '$year' AND MONTH(Fecha) = '$month'  AND [Lista de Precios]='PROMOTORA CKL' group by bodega";
        $query_detalle_mensual="SELECT bodega as tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '$year' AND MONTH(Fecha) = '$month' group by bodega";

        //OBTENEMOS EL ARRAY CON LAS VENTAS AL DETALLE MENSUALES PARA SACER EL PORCENTAJE MENSUAL DE VENTAS DE PROMOTORAS

        /******************* PENDIENTE MAÑANA ************** 
         * ir a ssms y corregir la lista de venta de promotoras por minuto pero solo de tiendas habilitadas (ver como hacer con las tiendas que cerraron este mes)
         * la consulta principal sera la de de ventas de promotoras por minuto... las otras 2 seran array asociativos con keys = cod_tienda
         * 
         * 
        */
        $arr_venta_diaria_promotoras=
        $arr_venta_mensual_detalle = selectArrayUniAssocIdName($query_detalle_anterior); 

                                  




        $thead='<thead><tr id="tr_head_ventas_promotoras" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-5">TIENDA</th><th class="col-xs-2">VENTA<br>DIARIA</th><th  class="col-xs-3">VENTA<br>MENSUAL</th><th  class="col-xs-1">%</th></tr></thead>';
        $venta_total_mensual=0;
        $array_ventas_diarias=array();
        $total_porcentaje=0;
        $venta_total_diaria=0;
        //CONSULTA EN 192.168.0.13
        $query_venta_detalle_mensual="SELECT bodega as tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '2017' AND MONTH(Fecha) = '10' group by bodega";
        //CONSULTA EN 192.168.0.33        
        $query_venta_promotoras_diaria="SELECT A1.WhsCode, A2.VtaMinAct FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC";


        ###******* PRIMERO CARGAMOS EN UN ARRAY LA ACTIVIDAD DIARIA DE LAS PROMOTORAS POR TIENDA ******
        $query_promotoras="SELECT A1.WhsName, VtaMinAct FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC";# code...
        $registros = sqlsrv_query($conector, $query_promotoras);
        While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC)) {
            $array_ventas_diarias[]=array('tienda'=>$reg[0],'venta'=>$reg[1]);
            $venta_total_diaria+=$reg[1];
        }
        ###**** CONEXION A OTRO SERVIDOR PARA OBTENER EL ACUMULADO MENSUAL POR TIENDA que sera mi tabla principal ****
        $conexion2=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','GSP');
        $conector2=$conexion2->obtener_conector();
        if(!$conector2){
            if(sqlsrv_errors()!=null) {
                cargarErrores();
                exit;
            }
        }
        ##****** consulta para obtener la suma *****
        $query_suma="Select sum(Bruto) from MM_VentaPROMOTORAMes";
        $registros = sqlsrv_query($conector2, $query_suma);
        $reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC);
        $venta_total_mensual=$reg[0];
        ###****************************************###

        $query_mensual="SELECT * FROM MM_VentaPROMOTORAMes ORDER BY Bruto DESC";
        $registros = sqlsrv_query($conector2, $query_mensual);
        if( $registros === false ){
            if(sqlsrv_errors()!=null) {
                cargarErrores();
            }else $data[]=array( 'respuesta' => 'ERRORES' );
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
            $thead='<tfoot><tr class="pie" id="tr_pie_ventas_promotoras"><td colspan="2" class="col-xs-6">TOTAL PROMOTORAS</td><td class="col-xs-2">'.$venta_total_diaria.'</td><td class="col-xs-3">'.$venta_total_mensual.'</td><td class="col-xs-1">'.$total_porcentaje.'</td></tr></tfoot>';
            $data[]=array('cuerpo' => $cuerpo, 'pie' => $pie, 'venta_total_mensual' => $venta_total_mensual);
            $conexion->desconectar();
            echo json_encode($data);
        
        }
        $data['table']="TRABAJANDO...";
        echo json_encode($data);
    }
}
?>
