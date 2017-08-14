<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
date_default_timezone_set("America/Santiago");
$conexion=new MssqlConexion('192.168.0.17','wms','pjc3l1','WMSTEK_KAYSER');
$conector=$conexion->obtener_conector();
if(!$conector){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
// if($_POST['solicitud']==="modificar_trf"){
  // $codigo=$_POST['codigo'];
  $query1="select idAlmacen, idDocSalida, idCliente, EstadoInterfaz FROM [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida] where IdDocSalida ='$codigo'";
  $params = array();
  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $select=sqlsrv_query($conector, $query1,$params, $options);
  if($select) {
    if(sqlsrv_num_rows($select)===1){
        $row = sqlsrv_fetch_array($select, SQLSRV_FETCH_NUMERIC);

        $estado_inicial=$row[3];
        $text = "El: ".date("Y-m-d (H:i:s)")." Se actualizó idCodSalida=$codigo de estadoInterfaz= '$estado_inicial' a 'C'";
        $query2="update [WMSTEK_KAYSER_INTERFAZ].[dbo].[DocumentoSalida] set EstadoInterfaz = ? where IdDocSalida = ?";
        $params2 = array('C',$codigo);
        $update=sqlsrv_query($conector, $query2,$params2);
        $reg_afectados=sqlsrv_rows_affected($update);
        if($reg_afectados===false){
            cargarErrores();
            $conexion->desconectar();
            exit;
        }elseif($reg_afectados==-1)
            $datos[]=array('respuesta'=>'No se puedo Actualizar');
        elseif($reg_afectados==0)
            $datos[]=array('respuesta'=>'Ningun Registro Actualizado');
        else{
            //$datos[]=array('respuesta'=> $reg_afectados);
            $datos[]=array('respuesta'=>"Se actualizó idCodSalida=$codigo de estadoInterfaz= '$estado_inicial' a 'C'");
            $file = fopen("../shared/temp/trf_log.txt","a+");
            fwrite($file, $text."\r\n");
            fclose($file);
        }
    }else {
        $datos[]=array('respuesta'=>'Codigo no encontrado');
    }
    $conexion->desconectar();
    header('Content-type: application/json');
    echo json_encode($datos);
  }else {
      if(sqlsrv_errors()!= null) {
          cargarErrores();
          $conexion->desconectar();
          exit;
      } else {
          $datos=array('respuesta'=>'averiguando por que se produjo este error');
          $conexion->desconectar();
          header('Content-type: application/json');
          echo json_encode($datos);
          exit;
      }
  }
// }

function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  header('Content-type: application/json');
  echo json_encode($errores);
}
?>
