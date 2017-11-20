<?php
require_once "../config/config.php";
require_once "../config/DBConnection.php";
require_once "../config/HelpersDB.php";
require_once "../config/inflector.php";
require_once "../config/sku_funciones.php";
$sqlsrv=new DBConnection('sqlsrv', $MSSQL['33']['host'], $MSSQL['33']['user'], $MSSQL['33']['pass'],'SBO_KAYSER');
$mysqli=new DBConnection('mysqli', $MYSQL[$env]['host'], $MYSQL[$env]['user'], $MYSQL[$env]['pass'], 'kayser_articulos');
$data=[]; $existe_error_conexion=0;
if(($sqlsrv->getConnection())===false) { $data['errors'][]=$sqlsrv->getErrors(); $existe_error_conexion=1; }
if(($mysqli->getConnection())===false)  {$data['errors'][]=$mysqli->getErrors(); $existe_error_conexion=1; }
if($existe_error_conexion){
  echo json_encode($data);
  exit;
}

// **************************   CARGA EN DATA EL Código del Articulo o SKU buscado ***************************
  $dpto=getIdFromName('OITB',$_POST['padre']);
  $marca=$_POST['marca'];
  $subdpto=$_POST['subdpto'];
  $prenda=$_POST["prenda"];
  $categoria=$_POST["categoria"];
  $presentacion=$_POST['presentacion'];

  $prefijo="";
  $sufijo='';
  $first='';
  $data=[];
  $querys_export=[];
  $query4="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda' AND Categoria_codigo='$categoria' AND SubDpto_id=$subdpto";
  $querys_export[]=$query4;

  ///--- NIVEL 4
  if(($arr_prefijos4=$mysqli->select($query4,"mysqli_a_o"))!=0){
    $prefijo=$arr_prefijos4[0]['prefijo'];
  }else{ ///--- NIVEL 3
    $query3_1="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND SubDpto_id='$subdpto'  AND Prenda_codigo='$prenda'";
    $query3_2="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND SubDpto_id='$subdpto'  AND Categoria_codigo=$categoria";
    $query3_3="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo=$prenda  AND Categoria_codigo=$categoria"; 
    // echo $query3_1."<br>";  
     $querys_export[]=$query3_1;     
     $querys_export[]=$query3_2;
     $querys_export[]=$query3_3;
    if(($arr_prefijos3=$mysqli->select($query3_1,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }elseif(($arr_prefijos3=$mysqli->select($query3_2,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }elseif(($arr_prefijos3=$mysqli->select($query3_3,"mysqli_a_o"))!=0){
      $prefijo=$arr_prefijos3[0]['prefijo'];
    }else{ ///--- NIVEL 2
      $query2_1="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND Prenda_codigo='$prenda'";
      $query2_2="SELECT prefijo from relacionprefijo WHERE Dpto_codigo=$dpto AND SubDpto_id=$subdpto";
      $querys_export[]=$query2_1;
      $querys_export[]=$query2_2;
      if(($arr_prefijos2=$mysqli->select($query2_1,"mysqli_a_o"))!=0){
        $prefijo=$arr_prefijos2[0]['prefijo'];
      }elseif(($arr_prefijos2=$mysqli->select($query2_2,"mysqli_a_o"))!=0){
        $prefijo=$arr_prefijos2[0]['prefijo'];
      }
    }
  }
  
  if($prefijo!=''){ 
    ///--- OBTENEMOS LA ABREVIATURA DE LA PRESENTACION
    $abrv_presentacion=$mysqli->getColumnFromColumn('presentacion','abreviatura','id','NUMBER',$presentacion); //SE SUPONE QUE ESTO DEVUELVE UNA CADENA Y NO UN ARRAY

    ///--- COMO EXCEPCION VERIFICAMOS SI EL SUBDPTO ES CALCETINES, si es asi, y ES UN PACK, el P2,P3 o P5 van despues del prefijo
    ///#######################################################################################################################///    
    $subdpto_name=getNameFromId('subdpto',$subdpto);
    if($subdpto_name=='CALCETINES'){
      $prefijo=$prefijo.$abrv_presentacion;      
    }else {
      $prefijo=$abrv_presentacion.$prefijo;
    }  
    ///#######################################################################################################################///
  
    $query_marca="SELECT nombre,simbolo,posicion,tipo FROM marca WHERE id=$marca";
    $querys_export[]=$query_marca;
    $arr_marca=$mysqli->select($query_marca,'mysqli_a_o');
    $kayser=0;
    if($arr_marca[0]['posicion']=='inicio'){
      $prefijo=$arr_marca[0]['simbolo'].$prefijo;
    }else{
      $sufijo=$arr_marca[0]['simbolo'];
    }
    if(ctype_digit($prefijo) AND $sufijo==''){ // ESCEPCION ESTATICA
      $prefijo=$prefijo.'.';
    }
    ///--- CONSULTAMOS EL MAYOR CODIGO DE ARTICULO DE ESTAS PRENDAS EN SAP
    $query_ultimo_sap="SELECT SUBSTRING( itemCode ,LEN('$prefijo')+1,CHARINDEX('-',itemCode)-LEN('$prefijo')-1) FROM OITM WHERE ItemCode LIKE '$prefijo%' GROUP BY SUBSTRING( itemCode ,LEN('$prefijo')+1,CHARINDEX('-',itemCode)-LEN('$prefijo')-1);";
    $querys_export[]=$query_ultimo_sap;
    $arr_ultimo=$sqlsrv->select($query_ultimo_sap,'sqlsrv_n_p');//Este array obtiene correlativos puros y con letras al final que corresponden a los sufijos de algunas marcas
    $mayor_sap=0;
    if($arr_ultimo!=0 && $arr_ultimo!=false){
      if($sufijo==''){ //para que cuando recorra el array buscando el mayor_sap correlativo, solo considere los que no tienen letras al final, 
        for($i=0;$i<count($arr_ultimo);$i++){      
          if(ctype_digit($arr_ultimo[$i][0])){
            if (intval($arr_ultimo[$i][0])>=$mayor_sap)
              $mayor_sap=intval($arr_ultimo[$i][0]);
          }
        }
      }else{        
        for($i=0;$i<count($arr_ultimo);$i++){
          $simbolo=getLetersCadena($cadena);
          if($simbolo==$arr_marca[0]['simbolo']){
            $correlito=substr($arr_ultimo[$i][0],0,strlen($arr_ultimo[$i][0])-strlen($simbolo)-1);
              if (intval($correlito)>=$mayor_sap)
                $mayor_sap=intval($correlito);
          }  
        }        
      }
    }
    ///--- CONSULTAMOS EL MAYOR CODIGO DE ARTICULO DE ESTAS PRENDAS EN LAS LISTAS
    $query_ultimo_lista="SELECT RIGHT(codigo, LENGTH(codigo)-LENGTH($prefijo)) from articulo";
    $querys_export[]=$query_ultimo_lista;
    $arr_ultimo=$mysqli->select($query_ultimo_lista,'mysqli_a_o');//Este array obtiene correlativos puros y con letras al final que corresponden a los sufijos de algunas marcas
    $mayor_lista=0;
    if($arr_ultimo!=0 && $arr_ultimo!=false){
      if($sufijo==''){ //para que cuando recorra el array buscando el mayor_lista correlativo, solo considere los que no tienen letras al final, 
        for($i=0;$i<count($arr_ultimo);$i++){      
          if(ctype_digit($arr_ultimo[$i][0])){
            if (intval($arr_ultimo[$i][0])>=$mayor_lista)
              $mayor_lista=intval($arr_ultimo[$i][0]);
          }
        }
      }else{        
        for($i=0;$i<count($arr_ultimo);$i++){
          $simbolo=getLetersCadena($cadena);
          if($simbolo==$arr_marca[0]['simbolo']){
            $correlito=substr($arr_ultimo[$i][0],0,strlen($arr_ultimo[$i][0])-strlen($simbolo)-1);
              if (intval($correlito)>=$mayor_lista)
                $mayor_lista=intval($correlito);
          }  
        }        
      }      
    }

    ($mayor_lista>=$mayor_sap) ? $first=$mayor_lista + 1 : $first=$mayor_sap + 1;
    if($first<1000) $first = 1000;

  }
  $data['prefijo']=$prefijo;
  $data['first']=$first;
  $data['sufijo']=$sufijo;
  $data['querys']=$querys_export;
  echo json_encode($data);
  // var_dump($data);
?>
