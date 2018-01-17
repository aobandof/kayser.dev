<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
// $conexion=new MssqlConexion('192.168.0.33','sa','sa','SBO_KAYSER');
$conexion_stock=new MssqlConexion('192.168.0.17','wms','pjc3l1','WMSTEK_KAYSER');
$conexion_linea=new MssqlConexion('192.168.0.13','sa','kayser@dm1n','Stock');
// CUANDO SE APRUEBE LO DEL PRECIO, CAMBIAR LA CONSULTA HACIENDO UN INER ENTRE TABLAS

$conector_stock=$conexion_stock->obtener_conector();
$conector_linea=$conexion_linea->obtener_conector();
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
// $query_stock="select IdArticulo, CAST(SUM(Cantidad)-30 AS int) as Cant from Existencia where idAlmacen = '01' AND IdUbicacion LIKE '01%' GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo";
$query_stock="select t0.IdArticulo, CAST(SUM(t0.Cantidad)-30 AS int) as Cant from   Existencia as t0 inner join Ubicacion as t1 on t0.IdUbicacion=t1.IdUbicacion where t0.IdAlmacen = '01' AND t0.IdUbicacion LIKE '01%' and t1.Nivel in ('1','2') GROUP BY IdArticulo HAVING SUM(Cantidad)>30 ORDER BY IdArticulo";
$query_linea="select ItemCode from Kayser_OITM where U_tipoarticulo='L' ORDER BY ItemCode";
$query_precio_detalle="SELECT  ItemCode, CAST(ROUND((Price*1.19),0) AS int) as DETALLE FROM Kayser_ITM1  where PriceList=15 AND ROUND((Price*1.19),0) IS NOT NULL and ROUND((Price*1.19),0)>1 ORDER BY ROUND((Price*1.19),0) ASC";
$query_precio_promotoras="SELECT	ItemCode, CAST(ROUND((Price*1.19),0) AS int) AS PROMOTORAS FROM Kayser_ITM1  where PriceList=17 AND ROUND((Price*1.19),0) IS NOT NULL and ROUND((Price*1.19),0)>1 ORDER BY ROUND((Price*1.19),0) ASC";

/*** PRIMERO CARGAMOS LA LISTA DE PRECIOS A UN ARRAY CON CODIGO Y PRECIO NETO * 1.19 ****/
$arr_stock=[];
$arr_precio_detalle=[];
$arr_precio_promotoras=[];

$registros_stock=sqlsrv_query($conector_stock, $query_stock);
while ($reg = sqlsrv_fetch_array($registros_stock, SQLSRV_FETCH_ASSOC)) {
  $key=$reg['IdArticulo'];
  if($reg['Cant']>1000)
    $reg['Cant']=1000;
  $arr_stock[$key]=$reg['Cant'];
}
$registros_linea=sqlsrv_query($conector_linea, $query_precio_detalle);
while ($reg = sqlsrv_fetch_array($registros_linea, SQLSRV_FETCH_ASSOC)) {
  $key=$reg['ItemCode'];
  $arr_precio_detalle[$key]=$reg['DETALLE'];
}
$registros_linea=sqlsrv_query($conector_linea, $query_precio_promotoras);
while ($reg = sqlsrv_fetch_array($registros_linea, SQLSRV_FETCH_ASSOC)) {
  $key=$reg['ItemCode'];
  $arr_precio_promotoras[$key]=$reg['PROMOTORAS'];
}
/***** consultamos a la base de datos y cargamos el string con el contenido delimitato por ";" *****/
$registros_linea=sqlsrv_query($conector_linea, $query_linea);
if( $registros_linea === false ) {
    if( (sqlsrv_errors() ) != null) { exit("Error en la consulta a la base de datos)"); }
}
else {
  $file = fopen("plantilla_stock_kayser.csv", 'w'); //abrimos el archivo vacio o lleno, pero con "w" sobreescribimos
  //OBTENEMOS LOS NOMBRES DE CAMPOS Y LO CARGAMOS EN LA PRIMERA LINEA DEL ARCHIVO
  /// $columna="IdArticulo;Cantidad;Precio Detalle;Precio Promotoras;\r\n";
    $columna="IdArticulo;Cantidad;\r\n";///comentar esta linea cuando se agregue precio
  $cantidad=0;
  fwrite($file, $columna);
  $indice=-1;
  while ($reg = sqlsrv_fetch_array($registros_linea, SQLSRV_FETCH_ASSOC)) {
      $fila="";
      $sku=$reg['ItemCode'];
      if(isset($arr_stock[$sku])){
        $fila=$fila.$sku.";".$arr_stock[$sku].";";
        /*if(isset($arr_precio_detalle[$sku]))
          $fila=$fila.$arr_precio_detalle[$sku].";";
        else
          $fila=$fila.";";# code...
        if(isset($arr_precio_promotoras[$sku]))
          $fila=$fila.$arr_precio_promotoras[$sku].";";
        else
          $fila=$fila.";";# code...*/
      }
      else {
        $fila=$fila.$sku.";0;";
      }
      $fila=$fila."\r\n";
      fwrite($file, $fila);
  }
}
fclose($file);
$ruta="stock"; // cuando la conexion sea con un usuario a quien se le asigno un directorio global, $ruta sería la carpeta a donde se accederia para subir el archivo
$hoy=date('d-m-Y H-m-s');
$nombre_archivo_destino="stock_kayser.csv";//_".date('Y-m-d H-i-s').".csv";//date_format($hoy, 'Y-m-d H-m-s');
/**** datos para conexion ftp ******/
$host="kayserps.vrserver7.cl";
// $host="200.14.252.15";
$port=21;
$user="kayserps";
// $user="ftpvrweb";
$password="ht658r9p62fb6s";
// $password="K4ys3rVrw3b235";
$ruta="/stock";
// $ruta="";
/**** conexion *****/
$conn_id=@ftp_connect($host,$port);
if($conn_id)
	{
		# Realizamos el login con nuestro usuario y contraseña
		if(@ftp_login($conn_id,$user,$password))
		{
			# Cambiamos al directorio especificado
			if(@ftp_chdir($conn_id,$ruta))
			{
        ftp_pasv($conn_id, true);//por defecto en Linux, la conexion se establece en modo activo ( servidor ->cliente), por esto la cambiamos modo pasivo ya que nosotros estableceremos la conexion
				# Subimos el fichero
				if(@ftp_put($conn_id,$nombre_archivo_destino,"plantilla_stock_kayser.csv",FTP_ASCII/*FTP_BINARY*/))
					echo "Fichero subido correctamente";
				else
					echo "No ha sido posible subir el fichero";
			}else
			  echo "No existe el directorio especificado";
		}else
			echo "El usuario o la contraseña son incorrectos";
		# Cerramos la conexion ftp
		ftp_close($conn_id);
	}else
		echo "No ha sido posible conectar con el servidor";
// var_dump($arr_skus);
// var_dump($arr_precios);
