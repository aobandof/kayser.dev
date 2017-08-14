<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER

/****** INSTANCIAMOS LOS PARAMATROS PARA LA CONEXION EN ESTE SCRIPT **************/
$conexion=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}

if(isset($_POST['solicitud'])){
    ///////////////// CARGAR EL SELECT CON LOS CODIGOS Y NOMBRES DE LAS TIENDAS
    if($_POST['solicitud']=="cargar_select") {
        if($_POST['tabla']=='tienda') {
            $tabla="Kayser_OWHS";
            $codigo="WhsCode";
            $nombre="WhsName";
            $query = "select WhsCode,WhsName from Kayser_OWHS where (U_GSP_SENDTPV='Y') order by WhsName" ;
        }
        $registros = sqlsrv_query($conector, $query);
        if( $registros === false ){
            die( print_r( "Error de consulta".sqlsrv_errors(), true));
        } else {
            While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC))
                $datos[]=array('id' => $reg[0], 'value' => $reg[1]);
        }
        $conexion->desconectar();
        //$datos[]=array('mensaje' => "para ver si entro a tienda";
        header('Content-type: application/json');
        echo json_encode($datos);
    }
    ////////////////  CARGAR LA TABLA SEGUN LA CONSULTA ///////////////////
    if($_POST['solicitud']=='cargar_tabla'){
        $value=$_POST['value'];
        $query ="select ISNULL(t.WhsName,''),p.LicTradNum, p.CardName,ISNULL(p.Cellular,''),ISNULL(p.Phone1,''),ISNULL(LOWER(p.E_Mail),''),p.Address,p.County,p.City ,ISNULL(CONVERT(VARCHAR(10),p.U_GSP_BIRTHDATE,103),''), ISNULL(CONVERT(VARCHAR(10),UltimaCompra,103),''), ISNULL(Bruto,0)
            from Kayser_OCRD as p
            LEFT JOIN Kayser_OWHS as t ON  p.U_GSP_TPVWHSCODE=t.WhsCode
            LEFT JOIN Cte_Fec ON P.LicTradNum=Rut
            where p.GroupCode=6  and p.U_GSP_SENDTPV='Y'";
        if($_POST['campo']=='codigo_tienda'){
            $campo='WhsCode';
            if('-1'==strval($value)){
                $query.=" AND t.WhsName IS NOT NULL";

            }else if('-2'==strval($value)){
                $query.=" AND t.WhsName IS NULL";
            }
            else {
                $query.=" AND t.$campo='$value'";
            }
        }
        if($_POST['campo']=='nombre_ciudad'){
            $campo='City';
            if($value=="santiago")
                $query.=" AND ( p.$campo LIKE '%saa%' OR p.$campo LIKE '%sann%' OR p.$campo LIKE '%sant%' OR p.$campo LIKE '%stg%' OR p.$campo LIKE '%sat%')";
            else {
                $likes=explode("*",$value);
                if(count($likes)==0) {
                    $query.=" AND p.$campo LIKE '%$value%'";
                }else {
                    foreach($likes as $like){
                        $query.=" AND p.$campo LIKE '%$like%' ";
                    }
                }
            }
        }
        $registros = sqlsrv_query($conector, $query);
        if( $registros === false ){
            die( print_r( sqlsrv_errors(), true) );
        } else {
            While ($reg = sqlsrv_fetch_array( $registros, SQLSRV_FETCH_NUMERIC)) {
                $monto=number_format ( floatval(abs($reg[11])), 0 , ',', '.');
                $datos[]=array('tienda'=> $reg[0],'rut'=> $reg[1],'nombres'=> $reg[2],'celular'=> $reg[3],'telefono'=> $reg[4],'email'=> $reg[5],'direccion'=> $reg[6],'comuna'=> $reg[7],'ciudad'=> $reg[8],'cumple'=> $reg[9],'ultima'=> $reg[10], 'monto'=> $monto );
            }
            $conexion->desconectar();
            header('Content-type: application/json');
            echo json_encode($datos);
        }
    }
    if($_POST['solicitud']=='exportar_excel'){
        require_once 'clases/PHPExcel.php';
        //Propiedades del Documento
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
        ->setCreator("Códigos de Programación")
        ->setTitle("Excel en PHP")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("excel phpexcel php")
        ->setCategory("Ejemplos");
        //propiedades de la hoja
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Hoja1');
        //agregamos la informacion a celdas
        $array_filas=explode('</tr><tr>',$_POST['cont_tabla']);
        foreach($array_filas as $fila) {
             $i=2;
             $array_celdas=explode('</td><td>',$fila);
             foreach($array_celdas as $celda){
                 $j=0;
                 $objPHPExcel->getActiveSheet()->setCellValue((string)(chr(65+$j).(string)$i), $celda);
                 $j++;
             }
             $i++;
        }
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="Excel.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

         header('Content-type: application/json');
         echo json_encode($i);
      }
}
?>
