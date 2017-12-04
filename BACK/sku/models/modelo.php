<?php
require_once "../shared/clases/config.php";
require_once "../shared/clases/MssqlConexion.php";
require_once "../shared/clases/HelpersDB.PHP";
require_once "../shared/clases/inflector.php";
error_reporting(E_ALL ^ E_NOTICE); // inicialmente desactivamos esto ya que si queremos ver los notices, pero evita el funcionamiento de $AJAX YA QUE IMPRIME ANTES DEL HEADER
set_time_limit(90); // solo para este script, TIEMPO MAXIMO QUE DEMORA EN SOLICITAR UNA CONSULTA A LA BASE DE DATOS
$conexion_mssql=new MssqlConexion($MSSQL['13']['host'], $MSSQL['13']['user'], $MSSQL['13']['pass'],'Stock');
$conector_mssql=$conexion_mssql->obtener_conector();
$mysqli=new mysqli($MYSQL['dev']['host'], $MYSQL['dev']['user'], $MYSQL['dev']['pass'], 'kayser_articulos');
$mysqli->set_charset("utf8");
//$mysqli->query("SET NAMES utf8 COLLATE utf8_bin"); // para distinguir entre palabras iguales tildadas y no tildadas
// $mysqli->query("SET collation_connection = utf8_bin");
if(!$conector_mssql){
    if(sqlsrv_errors()!=null) {
        cargarErrores();
        exit;
    }
}
$campos_mssql=getCamposToQuery('sku','value','S.');
$campos_mysql=getCamposToQuery('sku','key');
if(isset($_POST['opcion'])) {
    if($_POST['opcion']=='cargar_select') {
        $options="";
        $query="";
        if($_POST['select']=='select_grupo')
            $query.="SELECT ItmsGrpCod,ItmSGrpNam FROM Kayser_OITB";
        if($_POST['select']=='select_sub_grupo')
            $query.="SELECT U_SubGrupo1 as VALUE, U_SubGrupo1 FROM Kayser_OITM WHERE U_SubGrupo1!='null' GROUP BY U_SubGrupo1 ORDER BY U_SubGrupo1";
        if($_POST['select']=='select_sub_sub_grupo')
            $query.="SELECT Code, Name FROM Kayser_SEASON ORDER BY Name";
        if($_POST['select']=='select_prenda')
            $query.="SELECT SWW as value, SWW FROM Kayser_OITM  WHERE SWW!='null' AND SWW!='0' GROUP BY SWW ORDER BY SWW";
        $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
        if($select){
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
                $options.="<option value='".$reg[0]."'>".$reg[1]."</option>";
            }
            $conexion_mssql->desconectar();
            header('Content-type: application/json');
            echo json_encode($options);
        }
    }//FIN IF $_POST['cargar_select']
    if($_POST['opcion']=='cargar_tabla_filtrada') {
        $query =""; $where=""; $like=""; $cuerpo="";
        $cantidad=0;
        if(isset($_POST['nombre'])){
            $like.=" AND S.ItemName LIKE '%".$_POST['nombre']."%'";
        }
        if(isset($_POST['grupo'])){
            $where.=" AND S.ItmsGrpCod=".$_POST['grupo'];
        }
        if(isset($_POST['sub_grupo'])){
            $where.=" AND S.U_SubGrupo1='".$_POST['sub_grupo']."'";
        }
        if(isset($_POST['prenda'])){
            $where.=" AND S.SWW='".$_POST['prenda']."'";
        }
        if(isset($_POST['sub_sub_grupo'])){
            $where.=" AND SSG.Code=".$_POST['sub_sub_grupo'];
        }
        $query.="SELECT S.ItemCode as codigo_sku, S.ItemName as nombre, G.ItmsGrpNam as grupo, S.U_SubGrupo1  as sub_grupo, SSG.Name as sub_sub_grupo, S.SWW as prenda,C.Name as cat_prenda,S.FrgnName AS marca, S.U_Material as material, S.CodeBars as codebar ";
        $query.="FROM Kayser_OITM AS S ";
        $query.="JOIN Kayser_OITB AS G ON S.ItmsGrpCod=G.ItmsGrpCod ";
        $query.="LEFT JOIN Kayser_SEASON AS SSG ON S.U_APOLLO_SEASON=SSG.Code ";
        $query.="LEFT JOIN Kayser_DIV AS C ON S.U_APOLLO_DIV=C.Code ";
        $query.="WHERE S.U_APOLLO_SEG1 IS NOT NULL".$where.$like;
        $query.=" ORDER BY nombre";
        $select=sqlsrv_query($conector_mssql, $query, array(), array( "Scrollable" => 'static' ));
        $cantidad=(int)sqlsrv_num_rows($select);
        if($select){
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
                $cuerpo.="<tr class='fila'><td>".$reg[0]."</td>";
                $cuerpo.="<td>".$reg[1]."</td><td>".$reg[2]."</td><td>".$reg[3]."</td><td>".$reg[4]."</td>";
                $cuerpo.="<td>".$reg[5]."</td><td>".$reg[6]."</td><td>".$reg[7]."</td><td>".$reg[8]."</td><td>".$reg[9]."</td></tr>";
            }
            $conexion_mssql->desconectar();
            $movimientos[]=array('cuerpo'=>$cuerpo, 'consulta'=>$query, 'cantidad'=> $cantidad);
            header('Content-type: application/json');
            echo json_encode($movimientos);
        }
    }//FIN IF $_POST['cargar_tabla']
    if($_POST['opcion']=='cargar_items') {
      $tabla="<table id='table_items' class='table table-bordered table-condensed'><thead><tr class='campos'>";
      $query="";
      if($_POST['tabla']=='categorias'){
          $query.="ItmSGrpNam FROM Kayser_OITB";
          $tabla.="<th>CATEGORIA</th><th><input type='checkbox' class='check_sel_desel_tabla'></th></tr></thead><tbody>";
      } if($_POST['tabla']=='grupos') {
          $query.="SELECT U_SubGrupo1 FROM Kayser_OITM WHERE U_SubGrupo1!='null' GROUP BY U_SubGrupo1 ORDER BY U_SubGrupo1";
          $tabla.="<th>GRUPO</th><th><input type='checkbox' class='check_sel_desel_tabla'></th></tr></thead><tbody>";
      } if($_POST['tabla']=='productos') {
          $query.="SELECT Name FROM Kayser_SEASON ORDER BY Name";
          $tabla.="<th>PRODUCTO</th><th><input type='checkbox' class='check_sel_desel_tabla'></th></tr></thead><tbody>";
      } if($_POST['tabla']=='prendas') {
          $query.="SELECT SWW FROM Kayser_OITM  WHERE SWW!='null' AND SWW!='0' GROUP BY SWW ORDER BY SWW";
          $tabla.="<th>PRENDA</th><th><input type='checkbox' class='check_sel_desel_tabla'></th></tr></thead><tbody>";
      }
        $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
        if($select){
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
                $tabla.="<tr class='fila'><td>".Strtoupper($reg[0])."</td><td><input type='checkbox' name='items[]' value='".$reg[0]."'></td></tr>";
            }
            $tabla.="</tbody></table>";
            $conexion_mssql->desconectar();
            header('Content-type: application/json');
            echo json_encode($tabla);
        }
    }//FIN IF $_POST['cargar_items']
    if($_POST['opcion']=='cargar_tabla_local') {
      $mensaje="SE CARGARON LA(S) TABLA(S): ";
      $array_insert=array();
      foreach ($_POST["tablas"] as $value) {
        $query="";
        $query_insert="";
        // $value="sku";
        if($value=="sku"){
          $query.="SELECT TOP 200 $campos_mssql ";
          $query.="FROM Kayser_OITM AS S ";
          $query.="WHERE S.U_APOLLO_SEG1 IS NOT NULL";
          $query.=" ORDER BY S.ItemName";
          $query_insert.="INSERT INTO Sku (".$campos_mysql.",estado) VALUES "; //estado es campos solo de mysql y no se setea en el array por eso se pone al final
          $select=sqlsrv_query($conector_mssql, $query, array(), array( "Scrollable" => 'static' ));
          $cantidad=(int)sqlsrv_num_rows($select);
          while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
            $valuecitos="(";
            foreach ($reg as $value)
              $valuecitos.="'".$value."',";
            $query_insert.=$valuecitos."'H'),";//'H' ES EL CAMPO DE LA TABLA MYSQL QUE INDICA QUE EL REGISTRO ESTA HABILITADO
          }
        }
        if($value=='categoria'){
            $query.="SELECT ItmsGrpCod, ItmSGrpNam FROM Kayser_OITB";
            $query_insert.="INSERT INTO Categoria VALUES ";
            $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
              $query_insert.="('".(string)$reg[0]."','".$reg[1]."','H'),";
            }
        }
        if($value=='grupo') {
            $query.="SELECT U_SubGrupo1 FROM Kayser_OITM WHERE U_SubGrupo1!='null' GROUP BY U_SubGrupo1 ORDER BY U_SubGrupo1";
            $query_insert.="INSERT INTO Grupo VALUES ";
            $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
              $query_insert.="('".(string)$reg[0]."','H'),";
            }
        }
        if($value=='producto') {
            $query.="SELECT Code, Name FROM Kayser_SEASON ORDER BY Name";
            $query_insert.="INSERT INTO Producto VALUES ";
            $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
              $query_insert.="('".(string)$reg[0]."','".$reg[1]."','H'),";
            }
        }
        if($value=='prenda') {
            $query.="SELECT SWW FROM Kayser_OITM  WHERE SWW!='null' AND SWW!='0' GROUP BY SWW ORDER BY SWW";
            $query_insert.="INSERT INTO Prenda VALUES ";
            $select=sqlsrv_query($conector_mssql, $query,array(), array( "Scrollable" => 'static' ));
            while($reg=sqlsrv_fetch_array($select,SQLSRV_FETCH_NUMERIC)){
              $query_insert.="('".(string)$reg[0]."','H'),";
            }
        }
        if($query_insert!='') {
          $query_insert=trim($query_insert,',');//  quitamos la coma al final
          $query_insert.=";";//le ponemos el punto y coma para mysql
          $array_insert[]=$query_insert;
          if($mysqli->query($query_insert))
             $mensaje.=strtoupper($value).",";
        }
      }
      $mensaje=trim($mensaje,",");//sacamos la coma del final de la cadena
      $mensaje.=" Y TODOS SUS REGISTROS";
      $respuesta[]=array('inserts'=>$array_insert, 'mensaje'=>$mensaje);
      echo json_encode($respuesta);

      //echo json_encode($query_insert);
      //echo json_encode($dprcn_tablas);
    }//FIN IF $_POST['cargar_tabla']
}//FIN IF ISSET $_POST['opcion']
else {
    echo json_encode('no se reconoce $_POST[opcion]'); //
}
function cargarErrores() {
  $errores[]=array( 'respuesta' => 'ERRORES' );
  foreach( sqlsrv_errors() as $error )
    $errores[]=array( "SQLSTATE" => $error['SQLSTATE'],"CODE"=>$error['code'],"MESSAGE"=>$error['message']);
  var_dump(json_encode($errores));
}
?>
