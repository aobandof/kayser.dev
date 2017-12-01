<?php
session_start();

if(isset($_SESSION['user'])){
  // echo $_SESSION['user']." --- ".$_SESSION['perfil'];
  $perfil=$_SESSION['perfil'];
  if(isset($_GET['option'])){    
    if($_GET['option']=='create'){      
      echo "<script type='text/javascript'>let initial_option='create'; let active_list=0; let perfil='$perfil';</script>";
    }if($_GET['option']=='show'){
      echo "<script type='text/javascript'>let initial_option='show'; let active_list=".$_GET['list']."; let perfil='$perfil'; let state_list='".$_GET['status']."';</script>";
    }
  }else
    header("Location: ./menu.php");
}else {
  header("Location: ./index.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <meta http-equiv="refresh" content="1"> ESto lo usamos para refrescar la pagina cada 1 segundo -->
  <link rel="stylesheet" href="./src/css/normalize.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./src/css/bootstrap-select.css">
  <link rel="stylesheet" href="./src/css/styles.css">
  <!-- <link rel="stylesheet" href="./src/css/sku.css"> -->
  <link rel="stylesheet" href="./src/css/sku_crear.css">
  <link rel="stylesheet" href="./src/css/controls.css">
  <link rel="stylesheet" href="./src/css/modal.css">
  <link rel="stylesheet" href="./src/css/components.css">

  <title>SKU / Articulos</title>
</head>
<body>
    <!-- COMPONENTE MODAL UPLOAD -->
    <div class="modal_loading_full cont_hidden" id="sku_loader_full">
      <img src="../shared/img/loader_azul.gif" alt="">
    </div>


    <div class="container_column" id="div_sku_crear">
      <div class="container_row">
        <div class="cont_imgs_categorias">
            <div class="cont_img_categoria" id="div_cat_dama"><img src="../shared/img/img_mujer.jpg" alt=""><span>Mujer</span></div>
            <div class="cont_img_categoria" id="div_cat_hombres"><img src="../shared/img/img_hombre.jpg" alt=""><span>Hombre</span></div>
            <div class="cont_img_categoria" id="div_cat_lola"><img src="../shared/img/img_lola.jpg" alt=""><span>Lola</span></div>
            <div class="cont_img_categoria" id="div_cat_lolo"><img src="../shared/img/img_lolo.jpg" alt=""><span>Lolo</span></div>
            <div class="cont_img_categoria" id="div_cat_niña"><img src="../shared/img/img_niña.jpg" alt=""><span>Niña</span></div>
            <div class="cont_img_categoria" id="div_cat_niño"><img src="../shared/img/img_niño.jpg" alt=""><span>Niño</span></div>
            <div class="cont_img_categoria" id="div_cat_otro"><img src="../shared/img/K.jpg" alt=""><span>Otro</span></div>
        </div>
        <div class="cont_config">
          <div class="dropdown">
          <img src="../shared/img/settings.png" alt="" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="opcion_config dropdown-item" id="a_opcion_config_items" href="#">Items</a>
            <a class="opcion_config dropdown-item" id="a_opcion_config_relations" href="#">Relaciones</a>
            <a class="opcion_config dropdown-item" id="a_opcion_config_prefix" href="#">Prefijos</a>
          </div>
        </div>
        </div>
        <div class="cont_menu">
          <div class="dropdown">
            <img src="../shared/img/menu.png" alt="" class="dropdown-toggle" id="dropdownMenuButton_menu" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="opcion_menu dropdown-item" id="a_opcion_menu_items" href="menu.php">Inicio</a>
              <a class="opcion_menu dropdown-item" id="a_opcion_menu_relations" href="sku_consultar.html">Consulstar SKU</a>
              <a class="opcion_menu dropdown-item" id="a_opcion_menu_prefix" href="#">Modificar SKU</a>
              <a class="opcion_menu dropdown-item" id="" href="sku_proveedor.html">Envío a Proveedor</a>
              <a class="opcion_menu dropdown-item" id="" href="listas.php">Listas Pendientes</a>
              <a class="opcion_menu dropdown-item" id="a_opcion_menu_logout" href="#">Cerrar Sesion</a>
            </div>
          </div>
        </div>
      </div>
        <div class="comp_crear_sku container_row">
          <div class="container_column col-md-8 col-lg-8 col-xl-8">
            <div class="cont_fila_crear_sku container_row  ">
              <div><span class="">MARCA&nbsp</span><select name="marca" id="select_sku_marca" class="form-control sku_control prefijo ind"><option value=""></option></select></div>
              <div><span class="">SUBDPTO&nbsp</span><select name="subdpto" id="select_sku_subdpto" class="form-control sku_control prefijo dep"><option value=""></option></select></div>
            </div>
            <div class="cont_fila_crear_sku container_row  ">
              <div><span class="">PRENDA&nbsp</span><select name="[@APOLLO_SEASON]" id="select_sku_prenda" class="form-control sku_control prefijo dep"><option value=""></option></select></div>
              <div><span class="">CAT.PRENDA&nbsp</span><select name="[@APOLLO_DIV]" id="select_sku_categoria" class="form-control sku_control prefijo dep"><option value=""></option></select></div>
            </div>
            <div class="cont_fila_crear_sku container_row  ">
              <div><span class="">PRESENT.&nbsp</span><select name="presentacion" id="select_sku_presentacion" class="form-control sku_control prefijo ind"><option value=""></option></select></div>
              <div><span class="">MATERIAL&nbsp</span><select name="material" id="select_sku_material" class="form-control sku_control ind"><option value=""></option></select></div>
            </div>
            <div class="full_fila" id="div_row_colours">
                <span class="">COLOR&nbsp</span>
                <select name="color" id="select_sku_color" class="form-control sku_control" data-live-search="true" title="" multiple></select>
            </div>
            <div class="full_fila">
              <span class="">TALLA&nbsp</span>
              <div class="multiples_grupos_opciones" id="div_multiples_grupos_opciones">
                <div class="cont_sel_boton" data-toggle="collapse" data-target="#div_sel_grupo_opciones"><span id="span_tallas_chosen"></span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                <div class="collapse" id="div_sel_grupo_opciones"></div>
              </div>
            </div>
            <div class="cont_fila_crear_sku container_row " id="div_copa">
              <div><span class="">COPA&nbsp</span><select name="copa" id="select_sku_copa" class="form-control sku_control dep"><option value=""></option></select></div>
              <div><span class="">FORMA COPA.&nbsp</span><select name="formacopa" id="select_sku_fcopa" class="form-control sku_control dep"><option value=""></option></select></div>
            </div>
            <div class="cont_fila_crear_sku container_row  ">
              <div><span class="">T.PRENDA&nbsp</span><select name="tprenda" id="select_sku_tprenda" class="form-control sku_control ind"><option value=""></option></select></div>
              <div><span class="">T.CATALOGO&nbsp</span><select name="tcatalogo" id="select_sku_tcatalogo" class="form-control sku_control ind"><option value=""></option></select></div>
            </div>
            <div class="cont_fila_crear_sku container_row  ">
              <div><span class="">GRUPO USO&nbsp</span><select name="grupouso" id="select_sku_grupouso" class="form-control sku_control ind"><option value=""></option></select></div>
              <div><span class="">CARATERIS.&nbsp</span><select name="caracteristica" id="select_sku_caracteristica" class="form-control sku_control ind"><option value=""></option></select></div>
              <!-- <div class="cont_fila_textarea"><span class="">CARACT.&nbsp</span><textarea id="txa_sku_caracteristicas" name="caracteristicas" rows="1" cols="10" class="form-control sku_control"></textarea></div> -->
            </div>
            <!-- <div class="cont_fila_crear_sku container_row d75-25"> -->
            <div class="full_fila">
              <span class="">COMPOSIC.&nbsp</span>
              <select name="composicion" id="select_sku_composicion" class="form-control sku_control" data-live-search="true" title=""></select>
              <!-- <div><span class="">PESO(gr)&nbsp</span><input type="number" id="txt_sku_peso" name="peso" value="" class="form-control sku_control"></div> -->
            </div>
          </div>
          <div class="container_column col-md-4 col-lg-4 col-xl-4">
            <div class="cont_fila_crear_sku container_row" >
              <span>ARTICULO&nbsp</span>
              <div id="div_codigo_articulo">
                <input name='prefijo' type="text" id="txt_sku_prefijo" class="form-control sku_control" value="" >  
                <input name='correlativo' type="number" id="txt_sku_correlativo" class="form-control sku_control" value="" min="1000" max="9999">
                <input name='sufijo' id="txt_sku_sufijo" class="form-control sku_control" value="" readonly>
              </div>
            </div>
            <div class="cont_fila_crear_sku container_row" ><span>DESCRIP.&nbsp</span><input type="text" id="txt_sku_descripcion" name="descripcion" value="" class="form-control sku_control"></div>
            <!-- <div class="cont_fila_crear_sku container_row" ><span>BARCODE&nbsp</span><input type="text" id="txt_sku_barcode" name="" value="" class="form-control"></div> -->
            <!-- <div class="cont_fila_crear_sku container_row" ><span>DUN&nbsp</span><input type="text" id="txt_sku_dun" name="" value="" class="form-control"></div>&nbsp -->
            <!-- <div class="cont_fila_crear_sku container_row" ><button class="btn btn-primary" >VER CÓDIGOS SKU A GENERAR:</button></div><br> -->
            <!-- <div id="div_rel_skus">Relación de códigos SKUs aquí..</div> -->
          </div>
        </div>
        <div class="cont_botonera_inf container_row">
          <button type="button" name="button" id="btn_create_article_list" class="btn btn-primary">GUARDAR EN LISTA</button>
          <button type="button" name="button" id="btn_show_list" class="btn btn-warning">VER LISTA</button>
        </div>
    </div>


    <div class="modal modal_panel modal_upload" id="div_modal_upload">
      <div class="cont_logo">
        <a href="#" id="link_update">
          <img src="../shared/img/logo_kayser_azul.png" alt="logo">
        </a>
      </div>
    </div>


    <!-- COMPONENTE MODAL ITEM -->
    <div class="modal modal_panel" id="div_crud_item">
      <div class="content_modal">
        <div class="header_modal">
          <span>EDICION DE ITEMS DE ARTICULOS</span>
          <img src="../shared/img/close.png" class="close_modal" id="img_close_crud_items" alt="">
        </div>
        <div class="body_modal">
          <div class="comp_filters container_row">
            <span>Sección a Editar: </span>&nbsp&nbsp&nbsp
            <select name="" id="select_item_crud" class="form-control">
              <option></option>
              <option value="OITB">Departamentos</option><option value="marca">Marcas</option><option value="subdpto">Sub Departamentos</option>
              <option value="[@APOLLO_SEASON]">Prendas</option><option value="[@APOLLO_DIV]">Categ. Prenda</option><option value="presentacion">Presentación</option>
              <option value="color">Colores</option><option value="talla">Tallas</option><option value="copa">Copas</option>
              <option value="formacopa">Formas Copa</option><option value="material">Materiales</option><option value="tprenda">Temp. Prenda</option>
              <option value="tcatalogo">Temp. Catalogo</option><option value="grupouso">Grupos Uso</option><option value="composicion">Composicion</option>
            </select>
          </div>
          <div class="tabla_div" id="div_tabla_item">
            <div class="thead_div"><div class="row tr" id="div_head_tr_items"></div></div>
            <div class="tbody_div" id="div_tbody"><div class="row tr"></div></div>
            <div class="tfooter_div">
              <button class="btn btn-primary btn_footer" id="button_nuevo_seccion">Nuevo</button>
              <!-- <button class="btn btn-primary btn_footer">Limpiar</button> -->
              <button class="btn btn-primary btn_footer close_modal2" id="button_close_crud_items">Salir</button>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- COMPONENTE MODAL RELACION -->
    <div class="modal modal_panel" id="div_crud_relations">
      <div class="content_modal">
        <div class="header_modal">
          <span>CONFIGURACION PREFIJOS DE CODIGO DE ARTCULOS</span>
          <img src="../shared/img/close.png" class="close_modal" id="img_close_crud_relations" alt="">
        </div>
        <div class="body_modal">
          <div class="comp_filters container_row">
            <span>Sección a Editar: </span>&nbsp&nbsp&nbsp
          </div>
          <div class="tabla_div" id="div_tabla_relations">
            <div class="thead_div"><div class="row tr" id="div_head_tr_relations"></div>
              <div>N°</div><div>Dpto</div><div>Subdpto</div><div>Prenda</div><div>Categoria</div><div>Temporada</div><div>Prefijo</div><div>Edit</div><div>Delete</div>
            </div>
            <div class="tbody_div" id="div_tbody_relations"><div class="row tr"></div></div>
            <div class="tfooter_div">
              <button class="btn btn-primary btn_footer" id="button_nuevo_relation">Nuevo</button>
              <button class="btn btn-primary btn_footer close_modal2" id="#button_close_crud_relations">Salir</button>
            </div>
          </div>
        </div>
      </div>
    </div>
>

    <!-- COMPONENTE MODAL PREFIJOS -->
    <!-- <div class="modal modal_panel" id="div_crud_prefix">
      <div class="content_modal">
        <div class="header_modal">
          <span>EDICION DE PREFIJOS PARA CODIGO DE ARTICULO</span>
          <img src="../shared/img/close.png" class="close_modal" id="img_close_crud_prefix" alt="">
        </div>
        <div class="body_modal">
          <div class="comp_filters container_row">
            <span>Sección a Editar: </span>&nbsp&nbsp&nbsp
          </div>
          <div class="tabla_div" id="div_tabla_prefix">
            <div class="thead_div">
              <div class="row tr" id="div_head_tr_prefix"></div>
            </div>
            <div class="tbody_div" id="div_tbody">
              <div class="row tr"></div>
            </div>
            <div class="tfooter_div">
              <button class="btn btn-primary btn_footer" id="button_nuevo_seccion">Nuevo</button>
              <button class="btn btn-primary btn_footer close_modal2" id="button_close_crud_prefix">Salir</button>
            </div>
          </div>
        </div>
      </div>
    </div> -->


    <!-- COMPONENTE MODAL PREVIEW GUARDAR SKU -->
    <div class="modal modal_panel" id="div_modal_article_creation">
      <div class="content_modal">
        <div class="header_modal">
          <span id="span_title_list"></span>
          <span id="span_state_list" ></span>
          <!-- <img src="../shared/img/close.png" class="close_modal" id="img_close_article_creation" alt=""> -->
        </div>
        <div class="body_modal">

        </div>
        <div class="footer_modal">          
          <?php
            if($_SESSION['perfil']=='admin'){
              echo '<button class="btn btn-primary btn_footer" id="button_save_list">GUARDAR SKUs</button>';
              echo '<button class="btn btn-primary btn_footer" id="button_finalize_list">FINALIZAR Y LIBERAR</button>';
              // echo '<button class="btn btn-warning btn_footer" id="button_follow_editing">SEGUIR EDITANDO</button>';
              echo '<button class="btn btn-success btn_footer" id="button_add_article">+ ARTICULO</button>';
              echo '<button class="btn btn-warning btn_footer" id="button_show_lists">VOLVER A LISTAS</button>';
              echo '<button class="btn btn-danger btn_footer" id="button_delete_list">ELIMINAR LISTA</button>';
            }elseif($_SESSION['perfil']=='reviser'){
              echo '<button class="btn btn-primary btn_footer" id="button_save_list">GUARDAR SKUs</button>';
              echo '<button class="btn btn-primary btn_footer" id="button_submit_excel">ENVIAR PLANILLA EXCEL</button>';
              // echo '<button class="btn btn-warning btn_footer" id="button_follow_editing">SEGUIR EDITANDO</button>';
              echo '<button class="btn btn-success btn_footer" id="button_add_article">+ ARTICULO</button>';
              echo '<button class="btn btn-warning btn_footer" id="button_show_lists">VOLVER A LISTAS</button>';
              echo '<button class="btn btn-danger btn_footer" id="button_delete_list">ELIMINAR LISTA</button>';
            }elseif($_SESSION['perfil']=='editor'){
              echo '<button class="btn btn-primary btn_footer" id="button_save_list">GUARDAR SKUs</button>';
              // echo '<button class="btn btn-warning btn_footer" id="button_follow_editing">SEGUIR EDITANDO</button>';
              echo '<button class="btn btn-success btn_footer" id="button_add_article">+ ARTICULO</button>';
              echo '<button class="btn btn-warning btn_footer" id="button_show_lists">VOLVER A LISTAS</button>';
              echo '<button class="btn btn-danger btn_footer" id="button_delete_list">ELIMINAR LISTA</button>';
            }            
          ?>
        </div>
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="./src/js/sku_crear.js"></script>
    <script type="text/javascript" src="./src/js/sku_crud.js"></script>
    <script type="text/javascript" src="./src/js/components/modal.js"></script>
    <script type="text/javascript" src="./src/js/components/controls.js"></script>
    <script type="text/javascript" src="./src/js/components/preview_article.js"></script>
</body>
</html>
