<?php
require_once("../shared/clases/config.php");
require_once("../shared/clases/DBConnection.php");
require_once("../shared/clases/HelpersDB.php");
require_once("../shared/clases/inflector.php");
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

// **************************   CARGA EN DATA EL Código del Articulo o SKU buscado ***************************
  $dpto=getIdFromName('Kayser_OITB',$_POST['padre']);
  $marca=$_POST['marca'];
  $subdpto=$_POST['subdpto'];
  $prenda=$_POST['Kayser_SEASON'];
  $categoria=$_POST['Kayser_DIV'];
  $presentacion=$_POST['presentacion'];

  $prefijo="";
  $first='';
  $data=[];
  $query4=" SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda'  AND Categoria_codigo='$categoria' AND Presentacion_id=$presentacion";
  ///--- NIVEL 4
  if(($arr_prefijos4=$mysqli->select($query4,"mysqli_a_o"))!=0){
    $prefijo=$arr_prefijos4[0]['prefijo'];
  }else{ ///--- NIVEL 3
    $query3_1="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda'  AND Categoria_codigo='$categoria'";
    $query3_2="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda'  AND Presentacion_id=$presentacion";
    $query3_3="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND SubDpto_id=$subdpto  AND Presentacion_id=$presentacion"; 
    echo $query3_1."<br>";       
    if(($arr_prefijos3=$mysqli->select($query3_1,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }elseif(($arr_prefijos3=$mysqli->select($query3_2,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }elseif(($arr_prefijos3=$mysqli->select($query3_3,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }else{ ///--- NIVEL 2
      $query2_1="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda'";
      $query2_2="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND SubDpto_id=$subdpto";
      if(($arr_prefijos2=$mysqli->select($query2_1,"mysqli_a_o"))!=0){
        $prefijo=$arr_prefijos2[0]['prefijo'];
      }elseif(($arr_prefijos2=$mysqli->select($query2_2,"mysqli_a_o"))!=0){
        $prefijo=$arr_prefijos2[0]['prefijo'];
      }
    }
  }

  if($prefijo!=''){
    ///--- OBTENEMOS LA ABREVIATURA DE LA MARCA PARA AGREGAR AL PREFIJO
    $query_marca="SELECT nombre,prefijo FROM marca WHERE id=$marca";
    $arr_marca=$mysqli->select($query_marca,'mysqli_a_o');
    $arr_marca[0]['nombre']=='SENS' ? $prefijo=$prefijo.$arr_marca[0]['prefijo'] : $prefijo=$arr_marca[0]['prefijo'].$prefijo;
    ///--- AGREGAMOS EL PUNTO DESPUES DEL PREFIJO SI Y SOLO SI CONTIENE PUROS DIJITOS NUMERICOS
    if(ctype_digit($prefijo))
      $prefijo=$prefijo.'.';
    ///--- OBTENEMOS EL ULTIMO NUMERO DE ARTICULO REGISTRADO CON ESTE PREFIJO
    $query_ultimo="SELECT TOP 1 SUBSTRING( itemCode ,0,CHARINDEX('-',U_APOLLO_SEG1)-1) FROM Kayser_OITM WHERE ItemCode like '$prefijo%' AND U_APOLLO_SEG1 IS NOT NULL GROUP BY itemCode ORDER BY SUBSTRING(itemCode,0,CHARINDEX('-',U_APOLLO_SEG1)-1) DESC";
    $arr_ultimo=$sqlsrv->select($query_ultimo,'sqlsrv_n_p');
    if($arr_ultimo!=0){
      $last=intval(substr($arr_ultimo[0][0],strlen($prefijo),strlen($arr_ultimo[0][0])));
      ($last>=1000) ? $first=(string)(ultimo+1) : $first='1000';
    }else{
      ($arr_ultimo==0) ? $first='1000' : $first='';
    }
  }
  $data['prefijo']=$prefijo;
  $data['ultimo']=$ultimo;
  echo json_encode($data);

?>
