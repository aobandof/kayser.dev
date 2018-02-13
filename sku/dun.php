<?php
session_start();
if(!isset($_SESSION['user'])){
  header("Location: ./index.php");
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
  <link rel="stylesheet" href="./src/css/dun.css">
  <title>Gestión de DUN</title>
</head>
<body>
  <div id="div_dun">
    <div id="div_dun_list">
      <div id="div_dun_list_header" class="header">
        <div class="header_title"><h3>DUN Existentes</h3></div>
        <div class="header_filter">
          <input type="text" id="txt_article_filter" class="form-control" placeholder="Artículo" >
          <button id="button_article_filter" class="form-control btn btn-success" >FILTRAR</button>
        </div>
        <div class="header_menubar">
          <a href="menu.php"><button class="btn btn-danger">SALIR</button></a>
        </div>
      </div>
      <div id="div_dun_list_dtable" class="dtable">
        <div class="dhead">
          <div>SKU</div><div>BARCODE</div><div>DUN</div><div>ALT</div><div>ANCH</div><div>LARG</div><div>CANT</div><div>MEDIDA</div><div><input type="checkbox" name="" id="chb_all_dun"></div>
        </div>
        <div class="dbody"></div>      
      </div>
      <div id="div_dun_list_footer" >
        <button id="button_dun_new" class="btn btn-primary">AGREGAR DUN</button>
        <button id="button_dun_edit" class="btn btn-success">EDITAR SELECCION</button>
        <button id="button_dun_delete" class="btn btn-danger">ELIMINAR SELECCION</button>
      </div>
    </div>
    <div id="div_dun_gestion">
      <div class="header_title">REGISTRO/EDICION DUN</div>
      <div class="gestion_controls">
        <div class="row_gestion_controls"><span>ALTURA</span><input id="txt_dun_altura" type="number" class="form-control input_gestion_dun"></div>
        <div class="row_gestion_controls"><span>ANCHURA</span><input id="txt_dun_anchura" type="number" class="form-control input_gestion_dun"></div>
        <div class="row_gestion_controls"><span>LARGO</span><input id="txt_dun_largo" type="number" class="form-control input_gestion_dun"></div>
        <div class="row_gestion_controls"><span>CANTIDAD</span><input id="txt_dun_cantidad" type="number" class="form-control input_gestion_dun"></div>
        <div class="row_gestion_controls"><span>MEDIDA</span><input id="txt_dun_medida" type="text" class="form-control input_gestion_dun" value="CAJA"></div>
      </div>
      <div class="gestion_buttons">
        <button id="button_dun_guardar" class="btn btn-primary">GUARDAR</button>
        <button id="button_dun_cancelar" class="btn btn-danger">CANCELAR</button>
      </div>
      <!-- <div id="div_dun_gestion_dtable" class="dtable">
        <div class="dhead">
          <div>SKU</div><div>BARCODE</div><input type="checkbox" name="" id="chb_dun_pend"></div>
        </div>
        <div class="dbody"></div>      
      </div>
      <div id="div_dun_gestion_footer" >
        <button id="button_dun_new" class="btn btn-primary">NUEVOS DUN</button>
        <button id="button_dun_edit" class="btn btn-success">EDITAR SELECCION</button>
        <button id="button_dun_delete" class="btn btn-danger">ELIMINAR SELECCION</button>
      </div> -->
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>  
  <script src="./src/js/dun.js" type="text/javascript"></script>
</body>
</html>
