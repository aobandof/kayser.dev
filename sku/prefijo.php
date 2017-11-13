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
  $sufijo='';
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
    // echo $query3_1."<br>";       
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
    $query_marca="SELECT nombre,simbolo,posicion,tipo FROM marca WHERE id=$marca";
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
    ///--- CONSULTAMOS LOS CODIGOS DE ESTA PRENDA
    $query_ultimo="SELECT SUBSTRING( itemCode ,LEN('$prefijo')+1,CHARINDEX('-',itemCode)-LEN('$prefijo')-1) FROM Kayser_OITM WHERE ItemCode LIKE '$prefijo%' GROUP BY SUBSTRING( itemCode ,LEN('$prefijo')+1,CHARINDEX('-',itemCode)-LEN('$prefijo')-1);";
    $arr_ultimo=$sqlsrv->select($query_ultimo,'sqlsrv_n_p');//Este array obtiene correlativos puros y con letras al final que corresponden a los sufijos de algunas marcas
    $mayor=0;
    if($arr_ultimo!=0){
      if($sufijo==''){ //para que cuando recorra el array buscando el mayor correlativo, solo considere los que no tienen letras al final, 
        for($i=0;$i<count($arr_ultimo);$i++){      
          if(ctype_digit($arr_ultimo[$i][0])){
            if (intval($arr_ultimo[$i][0])>=$mayor)
              $mayor=intval($arr_ultimo[$i][0]);
          }
        }
      }else{        
        for($i=0;$i<count($arr_ultimo);$i++){
          $simbolo=getLetersCadena($cadena);
          if($simbolo==$arr_marca[0]['simbolo']){
            $correlito=substr($arr_ultimo[$i][0],0,strlen($arr_ultimo[$i][0])-strlen($simbolo)-1);
              if (intval($correlito)>=$mayor)
                $mayor=intval($correlito);
          }  
        }        
      }
      ($mayor>=1000) ? $first=$mayor+1 : $first=1000;
    }else{
      ($arr_ultimo==0) ? $first='1000' : $first='';
    }
  }
  $data['query_ultimo']=$query_ultimo;
  $data['prefijo']=$prefijo;
  $data['first']=$first;
  $data['sufijo']=$sufijo;
  echo json_encode($data);
?>
