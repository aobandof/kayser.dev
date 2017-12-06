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
    $year_actual=(int)date('Y');
    $current_time= date ("H:i:s");
    $fecha_dia_anterior=$year.'-'.$month.'-'.$day;
    $fecha_dia_actual=$year_actual.'-'.$month.'-'.$day;

    if($_POST['opcion']=="total") {
        if($_POST['date']==''){        	                      
            if( date('L')) {//SI ESTE AÑO ES BISIESTO: ENTONCES AVANZAMOS 2 DIAS EN LA FECHA DEL DIA ANTERIOR PARA QUE SE COMPAREN DIAS DE LA SEMANA IGUALES (lunes con lunes, martes con martes ... )
            	$fecha_dia_anterior= date('Y-m-d', strtotime("$fecha_dia_anterior + 2 day"));
			}else {
				$fecha_dia_anterior = date('Y-m-d', strtotime("$fecha_dia_anterior + 1 day"));
			}
			$fecha_anterior=$fecha_dia_anterior." ".$current_time;
        }else{
            $fecha_anterior=$_POST['date'];
            $fecha_dia_anterior=substr($fecha_anterior,0,10);
            $current_time=substr($fecha_anterior,11,strlen($fecha_anterior));//LA HORA YA NO SERA LA ACTUAL, SINO LA OBTENIDA DEL TEXT CALENDAR  
        }           
        // $fecha_anterior=substr($fecha_anterior,0,strlen($fecha_anterior)-3);
        $data['fecha']=$fecha_anterior;
        $data['time']=$current_time;
        $fecha_actual=date('Y-m-d H:i:s');
        $div_calendar="<div class='input-group date' id='datetimepicker1'>
				<input type='text' class='' id='text_calendar1' value='".$fecha_anterior."'/><br>
                <button class='input-group-addon btn btn-success' id='button_calendar'><span class='fa fa-calendar'></span></button>                                   
            </div>";
        // <button class='btn btn-warning btn-sm' id='button_search'><i class='fa fa-search' aria-hidden='true'></i></button>                    
        $thead='<thead><tr id="tr_head_ventas" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-5">TIENDA</th><th class="col-xs-3" id="th_hoy">VENTA AL<br>MINUTO</th><th class="col-xs-3" id="th_x_dia">'.$div_calendar.'</th></tr></thead>';
        $tbody='<tbody id="tbody_ventas">';
        $query_total_diaria="SELECT  A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct as total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinuto AS A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY A2.VtaMinAct DESC,A1.WhsCode ASC";
        $query_total_anterior="SELECT bodega as cod_tienda,CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '$fecha_dia_anterior', 20) AND Horas<=CONVERT(datetime, '$current_time', 20) group by Almacen, Bodega";        
        $arr_venta_diaria=$sqlsrv_33->select($query_total_diaria,"sqlsrv_a_p");
        $arr_venta_anterior=$sqlsrv_13->selectArrayUniAssocIdName($query_total_anterior);        
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
            $data['query']=$query_total_anterior;
        }else{
            $data['errors'][]=$sqlsrv->POSTErrors();
        }
        $sqlsrv_13->closeConnection();
        $sqlsrv_33->closeConnection();
        echo json_encode($data);
    }
    elseif($_POST['opcion']=="promotoras") {
        $thead='<thead><tr id="tr_head_ventas_promotoras" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-5">TIENDA</th><th class="col-xs-2">VENTA<br>DIARIA</th><th  class="col-xs-3">VENTA<br>MENSUAL</th><th  class="col-xs-1">%</th></tr></thead>';
        $tbody='<tbody id="tbody_ventas">';
        $venta_diaria_total=0;
        $venta_mensual_total=0;
        $query_promotoras_diaria="SELECT A1.WhsCode as cod_tienda, A1.WhsName AS tienda, A2.VtaMinAct AS total FROM OWHS AS A1 LEFT JOIN MM_KAYSER_VentaMinutoPromotoras as A2 ON A1.WhsName=A2.Tienda where A1.U_GSP_SENDTPV = 'Y' ORDER BY VtaMinAct DESC";
        $query_promotoras_mensual="SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '$year_actual' AND MONTH(Fecha) = '$month'  AND [Lista de Precios]='PROMOTORA CKL' group by bodega";
        $query_total_mensual="SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total  FROM [GSP].[dbo].[Gsp_SboKayserResumen] where YEAR(Fecha) = '$year_actual' AND MONTH(Fecha) = '$month' group by bodega";
        $arr_venta_diaria_promotoras=$sqlsrv_33->select($query_promotoras_diaria,"sqlsrv_a_p");
        $arr_venta_mensual_total = $sqlsrv_13->selectArrayUniAssocIdName($query_total_mensual); 
        $arr_venta_mensual_promotoras=$sqlsrv_13->selectArrayUniAssocIdName($query_promotoras_mensual);                                  
        if($arr_venta_diaria_promotoras!==false){
            if($arr_venta_diaria_promotoras!==0){
             for ($i=0; $i < count($arr_venta_diaria_promotoras); $i++) { 
                $cod_tienda=$arr_venta_diaria_promotoras[$i]['cod_tienda'];
                $tbody.="<tr class='fila_promotoras'>";
                $venta_diaria_total+=$arr_venta_diaria_promotoras[$i]['total'];
                $venta_mensual_total+=$arr_venta_mensual_promotoras[$cod_tienda];
                
                if(isset($arr_venta_mensual_promotoras[$cod_tienda]) && isset($arr_venta_mensual_total[$cod_tienda]) && (int)$arr_venta_mensual_total[$cod_tienda]!=0){
                    $porcentaje=(((float)($arr_venta_mensual_promotoras[$cod_tienda]))*100)/$arr_venta_mensual_total[$cod_tienda]; 
                }else
                    $porcentaje=0;                              
                // $total_porcentaje+=$porcentaje;
                $tbody.="<tr class='fila_promotoras'><td class='col-xs-1'>".($i+1)."</td><td class='col-xs-5'>".$arr_venta_diaria_promotoras[$i]['tienda']."</td><td class='col-xs-2'>".number_format ( floatval($arr_venta_diaria_promotoras[$i]['total']), 0 , ',', '.')."</td><td class='col-xs-3'>".number_format ( floatval($arr_venta_mensual_promotoras[$cod_tienda]), 0 , ',', '.')."</td><td class='col-xs-1'>".round($porcentaje)."</td></tr>";
             }                      
            }else
                $data['resp']="SIN RESULTADOS";
        }else 
            $data['errors'][]=$sqlsrv->POSTErrors();
        $venta_diaria_total=number_format ( floatval($venta_diaria_total), 0 , ',', '.');
        $venta_mensual_total=number_format ( floatval($venta_mensual_total), 0 , ',', '.');
        $tfoot='<tfoot><tr class="pie" id="tr_pie_ventas_promotoras"><td colspan="2" class="col-xs-6">TOTAL PROMOTORAS</td><td class="col-xs-2">'.$venta_diaria_total.'</td><td class="col-xs-3">'.$venta_mensual_total.'</td><td class="col-xs-1">&nbsp</td></tr></tfoot>';
        $data['table']=$thead.$tbody.$tfoot;
        // $data['query']=$query_total_mensual;
        $sqlsrv_13->closeConnection();
        $sqlsrv_33->closeConnection();
        // $data['table']=$thead;
        echo json_encode($data);        
    }
    elseif ($_POST['opcion']=="busqueda") {
        $date=$_POST['date'];
        // $date_from =substr($date_to,0,10)." 00:00:00";
        $date_day=substr($date,0,10);
        $date_hour=substr($date,11,strlen($date));
        // echo $date_to."<br>";
        $thead='<thead><tr id="tr_head_busqueda" class="nombre_campos"><th class="col-xs-1">N°</th><th class="col-xs-7">TIENDA</th><th class="col-xs-4">VENTA DIARIA<br>'.$date_day.' '.$date_hour.'</th></tr></thead>';
        $tbody='<tbody id="tbody_ventas">';
        $query_stores="SELECT WhsCode AS cod_tienda, WhsName AS tienda from OWHS where U_GSP_SENDTPV = 'Y' ORDER BY WhsCode";
        $query_diax="SELECT bodega as cod_tienda, CAST(SUM(Total) AS INT) AS total FROM [GSP].[dbo].[Gsp_SboKayserResumen] where fecha=CONVERT(datetime, '$date_day', 20) AND Horas<=CONVERT(datetime, '$date_hour', 20) group by Almacen, Bodega ORDER BY total DESC";        
        // $arr_venta_diaria=$sqlsrv_13->select($query_diax,"sqlsrv_a_p"); 
        $arr_venta_diaria=$sqlsrv_13->selectArrayUniAssocIdName($query_diax); 
        $arr_stores=$sqlsrv_33->selectArrayUniAssocIdName($query_stores);
        if($arr_venta_diaria!==false){
            if($arr_venta_diaria!=0){
                $venta_diaria_total=0;
                $i=1;
                foreach($arr_venta_diaria as $item=>$value){
                    $tbody.="<tr class='fila'><td class='col-xs-1'>".$i."</td>";
                    $tbody.="<td class='col-xs-7'>".$arr_stores[$item]."</td>";
                    $tbody.="<td class='col-xs-4'>".number_format ( floatval($value), 0 , ',', '.')."</td></tr>";
                    $venta_diaria_total+=$value;
                    $i++;
                }
                foreach($arr_stores as $item=>$value){//PARA IMPRIMIR LAS TIENDAS QUE NO TUVIERON VENTAS PERO QUEREMOS QUE APARESCAN EN EL REPORTE
                    if(!isset($arr_venta_diaria[$item])){
                        $tbody.="<tr class='fila'><td class='col-xs-1'>".$i."</td>";
                        $tbody.="<td class='col-xs-7'>".$value."</td>";
                        $tbody.="<td class='col-xs-4'>0</td></tr>";
                        $i++;
                    }
                }
                $tbody.="</tbody>";
            }else
                $data['resp']="SIN RESULTADOS";
            $venta_diaria_total=number_format ( floatval($venta_diaria_total), 0 , ',', '.');
            $tfoot='<tfoot><tr class="pie" id="tr_pie_busqueda"><td colspan="2" class="col-xs-8">VENTA TOTAL :&nbsp</td><td class="col-xs-4">'.$venta_diaria_total.'</td></tr></tfoot>';
            $data['table']=$thead.$tbody.$tfoot;
            // $data['query']=$query_diax;
        }else{
            $data['errors'][]=$sqlsrv->POSTErrors();
        }
        $sqlsrv_13->closeConnection();
        // echo "<table border='1px'>$tbody</table>";
        echo json_encode($data);                
    }

}
?>
