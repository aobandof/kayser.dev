<?php
session_start();
if(!isset($_SESSION['user'])){
  echo "<br><h3>NO TIENE AUTORIZACION PARA ENTRAR EN ESTA PAGINA</h3>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="./src/css/normalize.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="./src/css/styles.css">
  <link rel="stylesheet" href="./src/css/sku.css">
  <title>SKU / Articulos</title>
</head>
<body>
    <div class="container_row" id="div_sku_index">
      <img src="src/img/fondo_sku.png" alt="kayser" id="img_kayser_familia" class="">
      <div class="container_column">
          <button type="button" name="btn_crear" class="btn btn-lg btn-primary " id="button_crear_sku"><a href="crear.php?option=crear_articulo">CREAR SKU</a></button>
          <button type="button" name="btn_modificar" class="btn btn-lg btn-danger" id="button_modificar_sku"><a href="#">MODIFICAR SKU</a></button>
          <button type="button" name="btn_consultar" class="btn btn-lg btn-success" id="button_consultar_sku"><a href="sku_consultar.html">CONSULTAR SKU</a></button>
          <button type="button" name="btn_modificar" class="btn btn-lg btn-info" id="button_proveedor_sku"><a href="sku_proveedor.html">ENVIO A PROVEEDOR</a></button>
          <button type="button" name="btn_ver_listas" class="btn btn-lg btn-warning" id="button_show_list"><a href="listas.php">VER LISTAS</a></button>
          <button type="button" name="btn_ver_listas" class="btn btn-lg btn-danger" id="button_logout"><a href="#">CERRAR SESION</a></button>
      </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <!-- <script type="text/javascript" src="./src/js/sku.js" ></script> -->
    <script type="text/javascript">      
      document.getElementById('button_logout').onclick=function(){
        if(confirm("ESTA SEGURO DE CERRAR LA SESION")){
          location.href = "./config/session.php?option=session_end";
        }
      }
    </script>
</body>
</html>
