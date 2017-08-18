<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
// $conexion=new MssqlConexion('192.168.0.33','sa','sa','SBO_KAYSER');
$conexion=new MssqlConexion('192.168.0.17','wms','pjc3l1','WMSTEK_KAYSER_INTERFAZ');
$conexion_precios=new MssqlConexion('192.168.0.33','sa','sa','SBO_KAYSER');
$conector=$conexion->obtener_conector();
$conector_precios=$conexion_precios->obtener_conector();
// if(!$conector){
//   echo "NO SE ESTABLECIO LA CONEXION CON LA VISTA WMSTEK_KAYSER_INTERFAZ <br>";
//   if( (sqlsrv_errors() ) != null) {
//     exit("error en la conexion");
//   }
// }
// if(!$conector2){
//   echo "NO SE ESTABLECIO LA CONEXION CON LA BDx SBO_KAYSER<br>";
//   if( (sqlsrv_errors() ) != null) {
//     exit("error en la conexion");
//   }
// }
$contenido="";
$query="select * from MM_StockVrWeb";
$query_precios="select ItemCode,ROUND((Price*1.19),0) as precio from ITM1 where PriceList=15 and ROUND((Price*1.19),0)>300";//"select * from ITM1 where PriceList=15";
// $arr_skus=[];
$arr_precios=[];

/*** PRIMERO CARGAMOS LA LISTA DE PRECIOS A UN ARRAY CON CODIGO Y PRECIO NETO * 1.19 ****/
$registros_precios=sqlsrv_query($conector_precios, $query_precios);
while ($reg = sqlsrv_fetch_array($registros_precios, SQLSRV_FETCH_ASSOC)) {
  // $arr_skus[]=$reg['ItemCode'];
  $arr_precios[]=$reg;
}
// var_dump($arr_precios);
/***** consultamos a la base de datos y cargamos el string con el contenido delimitato por ";" *****/
$registros=sqlsrv_query($conector, $query);
if( $registros === false ) {
    if( (sqlsrv_errors() ) != null) {
        exit("Error en la consulta a la base de datos)");
    }
}
else {
  $file = fopen("plantilla_stock_kayser.csv", 'w'); //abrimos el archivo vacio o lleno, pero con "w" sobreescribimos
  //OBTENEMOS LOS NOMBRES DE CAMPOS Y LO CARGAMOS EN LA PRIMERA LINEA DEL ARCHIVO
  //fputcsv($file, sqlsrv_field_metadata($registros));
  $columna="";
  $cantidad=0;
  foreach( sqlsrv_field_metadata( $registros) as $fieldMetadata ) {
      if($fieldMetadata['Name']!='NomArticulo')
         $columna.=$fieldMetadata['Name'].";";
  }
  $columna.="PRECIO DETALLE".";\r\n";
  // $columna=$columna."\r\n";
  fwrite($file, $columna);
  $indice=-1;
  while ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) {
      $cant_reporte=((int)$reg['Cantidad'])-50;
      if($cant_reporte > 0) {
        if($cant_reporte>1000)
          $cant_reporte=1000;
        $indice=array_search($reg['IdArticulo'], array_column($arr_precios,'ItemCode'));
        $fila=$reg['IdArticulo'].";".$cant_reporte.";".(int)$arr_precios[$indice]['precio'].";";
        $fila=$fila."\r\n";
        fwrite($file, $fila);
      }
  }
}
fclose($file);
$ruta="stock"; // cuando la conexion sea con un usuario a quien se le asigno un directorio global, $ruta sería la carpeta a donde se accederia para subir el archivo
$hoy=date('d-m-Y H-m-s');
$nombre_archivo_destino="stock_kayser.csv";//_".date('Y-m-d H-i-s').".csv";//date_format($hoy, 'Y-m-d H-m-s');
/**** datos para conexion ftp ******/
// $host="kayserps.vrserver7.cl";
$host="200.72.246.165";
$port=21;
// $user="kayserps";
$user="ftpvrweb";
// $password="ht658r9p62fb6s";
$password="K4ys3rVrw3b235";
// $ruta="/stock";
// $ruta="";
/**** conexion *****/
$conn_id=@ftp_connect($host,$port);
if($conn_id)
	{
		# Realizamos el login con nuestro usuario y contraseña
		if(@ftp_login($conn_id,$user,$password))
		{
			# Cambiamos al directorio especificado
			///if(@ftp_chdir($conn_id,$ruta))
			///{
        ftp_pasv($conn_id, true);//parece ser que siempre hay errores, se agregó para se sea en modo pasivo
				# Subimos el fichero
				if(@ftp_put($conn_id,$nombre_archivo_destino,"plantilla_stock_kayser.csv",FTP_ASCII/*FTP_BINARY*/))
					echo "Fichero subido correctamente";
				else
					echo "No ha sido posible subir el fichero";
			///}else
			  ///echo "No existe el directorio especificado";
		}else
			echo "El usuario o la contraseña son incorrectos";
		# Cerramos la conexion ftp
		ftp_close($conn_id);
	}else
		echo "No ha sido posible conectar con el servidor";
// var_dump($arr_skus);
// var_dump($arr_precios);
