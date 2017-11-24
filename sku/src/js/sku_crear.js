var color, campos_llenos, id_cat_before_click,id_cat_after_click, id_cat_actual, code_dpto, name_dpto, item_crud_selected, first_barcode, current_list;
// let active_list=0;
let el_sel_marca,el_sel_subdpto,el_sel_prenda, el_sel_categoria, el_sel_presentacion, el_sel_material, el_sel_color, el_sel_tallas, el_sel_tprenda, el_sel_tcatalogo, el_sel_grupouso, el_sel_caracteristica, el_sel_composicion, el_txt_prefijo, el_txt_correlativo, el_txt_sufijo;
let modal_preview_save, body_modal_preview_save; 
let el_div_loader_full;

$(document).ready(function() {

  getElementsControls();//INICIALIZAMOS ALGUNOS ELEMENTOS QUE USAREMOS DURANTE TODO EL PROGRAMA

  // $('#sku_loader_full').ajaxStart(function () { $(this).classList.add('cont_hidden') });
  // $('#sku_loader_full').ajaxComplete(function () { $(this).classList.remove('cont_hidden') });

  ///--- SI ESTA INTENTANDO VER UNA LISTA PENDIENTE, LA DIBUJAMOS Y MOSTRAMOS TODOS SUS ARTICULOS ----
  if(initial_option=='show_list' && perfil!='editor'){
    modal_preview_save.style.visibility = 'visible';
    ///--- CONSULTAREMOS A LA API, TODOS LOS SKUS AGRUPADOS POR ARTICULOS QUE ESTAN DENTRO DE ESTA LISTA
    parameters={ 'option':'get_articles', 'list':active_list };
    // console.log(parameters);
    $.ajax({ url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
      beforeSend: function (){ },
      success: function(data){
        // console.log(data);
        if(!!data.articulos){
          for (index in data.articulos ) {
            console.log(data.articulos[index]);
            renderArticleList(data.articulos[index].articulo, data.articulos[index].itemname, data.articulos[index].skus,'NEW');
          }
        }
      },
      error: function(){ console.log('error'); }
    });
  }
  if (initial_option == 'create_article'){
    document.getElementById('btn_show_list').disabled=true; //INICIALMENTE DESHABILITAMOS EL BOTON VER LISTA, DESPUES CUANDO AGREGAMOS OTRO ARTICULO VOLVERLO HA HABILITAR
  }

  /************************  EVENTO PARA MOSTRAR EL PANEL PREVIEW SAVE SKU con la nueva lista con ARTICULO(s) QUE SE CREARAR *************/
  document.getElementById('btn_create_article_list').onclick = function () {
    let empty = 0;
    let tallas = document.getElementById('span_tallas_chosen').innerHTML.trim();
    document.querySelectorAll('.sku_control').forEach(function (control) {
      if (control.parentNode.parentNode.style.display != 'none')// si el div que contiene estos controles, no se muestra, entonces no consideramos ese control.
        if (control.value == '') { 
          if (control.id != 'txt_sku_sufijo'){ //EXCEPCION ESTATICA, el sufijo puede o no ser necesario
            empty = 1;
            // console.log(control);
          }
        }
    });
    if (tallas == "") empty = 1;
    //sacar esto despues /
    // empty = 0; // LO PONEMOS PARA VER EL MODAL. el cual no debe mostrarse si no se seleccionaro todas las opciones del sku_crear
    if (empty === 0) {
      parameters = new Object();
      parameters = getObjectArticle();
      parameters['option'] = 'save_article';
      parameters['list'] = active_list;
      // console.log(parameters);
      $.ajax({
        url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
        success: function (data) {
          // el_div_loader_full.classList.remove('cont_hidden');
          console.log('FROM API (option: ' + parameters.option + ') ', data);
          if (!!data.nothing) {
            alert("NO SE PUDO AGREGAR EL ARTICULO");
            console.log(data.nothing);
          } else {
            if (!!data.filas && data.filas != '') {
              active_list = data.lista;
              resetAllControls();//agregaremos el articulo, veremos el modal pero antes limpiamos los controles
              renderArticleList(data.articulo, data.itemname, data.filas,'NEW'); // si es un articulo nuevo NEW, si es existente CREATED
              modal_preview_save.style.visibility = 'visible';
            }
            if (!!data.refused) {
              mensaje = 'LOS SIGUIENTES SKUS NO FUERON AGREGADOS\n\n';
              for (var item in data.refused) {
                mensaje += 'SKU: ' + data.refused[item]['sku'] + ' --- MOTIVO: ' + data.refused[item]['detalle'] + '\n';
              }
              alert(mensaje);
            }
          }
        },
        error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/ }
      });
    }
    else
      alert("TODOS LOS CAMPOS SON NECESARIOS");
  }
  //inicialmente ocultamos la caja que contiene las copas
  document.getElementById('div_copa').style.display = 'none'; 

  ///--- EVENTOS PARA ABRIR LOS MODALES ITEM, RELATIONS Y PREFIJOS
  $("#a_opcion_config_items").click(function() { $("#div_crud_item").css('visibility','visible' );  }); //MOSTRAMOS MODAL ITEMS
  $("#a_opcion_config_relations").click(function(){ $("#div_crud_relations").css('visibility','visible'); }); //MOSTRAMOS MODAL RELACIONES
  $("#a_opcion_config_prefix").click(function () { $("#div_crud_prefix").css('visibility', 'visible');  }); //MOSTRAMOS MODAL PREFIJOS

  /**************** EVENTOS SKU_CRUD_ITEM, cuando cambiamos de opcion de Tabla *****************/
  document.getElementById('select_item_crud').onchange= function() {
    item_crud_selected=this.value;
    $("#div_tabla_item>tbody_div").html('');
    $("#div_tabla_item").css('visibility', 'visible');
    cargarTablaSeccion($(this).val());
  }
  /****************** EVENTOS PARA CERRAR EL MODAL CRUD_ITEMS ****************/
  $("#button_close_crud_items, #img_close_crud_items").click(function () {
    if ( this.className == "close_modal")
      modal = this.parentNode.parentNode.parentNode; //obtenemos la referenca al modal para ocultarlo
    else 
      modal = this.parentNode.parentNode.parentNode.parentNode.parentNode; //obtenemos la referenca al modal para ocultarlo
    modal.style.visibility='hidden';
    if (modal.id == "div_crud_item"){
      document.getElementById("select_item_crud").value="";
      $("#div_tabla_item>tbody_div").html('');
      $("#div_tabla_item").css('visibility', 'hidden');
      document.getElementById("button_nuevo_seccion").classList.remove("disabled"); // removemos esta clase si estuvier (aveces, cuando se esta editando y se sale del modal, por ejemplo, este boton nuevo mantiene la clase disabled, por eso la borramos)
      document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
    }
  });
  /****************** FUNCIONA PARA ELIMINAR LA LISTA ACTUAL, YA SEA UNA ANTIGUA O U NA NUEVA QUE SE ESTE REVISANDO O FINALIZANDO */
  /****************** EVENTOS PARA CANCELAR Y delete LIST CUANDO SE ESTA EDITANTO****************/
  el_but_delete_list = document.getElementById('button_delete_list');
  if(!!el_but_delete_list){
    el_but_delete_list.onclick = function(){
      if(initial_option=="create_article" && perfil=='editor')
        message = "ESTA A PUNTO DE SALIR Y DESCARTAR ESTA LISTA DE TRABAJO, \n\n¿DESEA REALMENTE SALIR Y ELIMINAR ESTE LISTADO?";
      else 
        message = "DESEA REALMENTE ELIMINAR ESTA LISTA Y DESCARTAR SU CARGA EN SAP";
      if (confirm(message)) {        
        parameters = { 'option': 'delete_list', 'list': active_list }
        $.ajax({ url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
          beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
          success: function(data){
            // el_div_loader_full.classList.remove('cont_hidden');
            console.log(data);
            if(data.delete==false)             
              alert("NO SE PUDO ELIMINAR ESTE LISTADO DE LA BASES DE DATOS, INFORME A INFORMATICA POR FAVOR");
          },
          error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/}
        });
        //EN CUALQUIERA DE LOS CASOS, AUN SI NO SE PUDO ELIMINAR LA LISTA, ELIMINAMOS LE CONTENIDO DEL MODAL, LO OCULTAMOS Y RESETEAMOS LOS CONTROLES
        if (initial_option =='show_list')//ACCEDIO A  LA LISTA DESDE EL MODULO DE LISAS PENDIENTES, POR ENDE REGRESAMOS A ELLA
          location.href = "listas.php";
        else
          location.href = "menu.php";//OPTAMOS POR VOLER AL MENU PARA ELEGIR LA OPCION DESEADA
      }
    }
  }
  /****************** EVENTOS PARA AGREGAR OTRO ARTICULO CUANDO SE ESTA  EDITANDO ****************/
  el_but_add_article = document.getElementById('button_add_article');
  if(!!el_but_add_article){
    el_but_add_article.onclick = function () {
      resetAllControls();
      document.getElementById('btn_show_list').disabled = false;
      modal_preview_save.style.visibility = 'hidden';
    }
  }
  /****************** EVENTOS PARA OCULTAR LA LISTA Y SEGUIR EDITANDO  ****************/
  el_but_follow_editing = document.getElementById('button_follow_editing');
  if (!!el_but_follow_editing) {
    el_but_follow_editing.onclick = function () {
      // alert('deberia ocultar este modal');
      document.getElementById('btn_show_list').disabled = false;
      modal_preview_save.style.visibility = 'hidden';
    }
  }

  el_but_show_lists = document.getElementById('button_show_lists');
  if(!!el_but_show_lists){
    el_but_show_lists.onclick=function(){
      // alert("deberia verse esto ante el evento click");
      location.href = "listas.php";
    }
  }
  /************************   EVENTO PARA GUARDAR LOS SKU Y ENVIAR EL EXCEL   *********************/
  el_but_save_list = document.getElementById('button_save_list');
  if (!!el_but_save_list) {
    parameters = new Object();
    el_but_save_list.onclick = function () {
      if(initial_option=="show_list" && perfil=='reviser')
        parameters = { 'option': 'save_list', 'list': active_list, 'operation': 'review' };
      else if(initial_option=="create_article" && perfil=='editor')
        parameters = { 'option': 'save_list', 'list': active_list, 'operation': 'creation' };
      else if (initial_option == "create_article" && perfil == 'reviser')
        parameters = { 'option': 'save_list', 'list': active_list, 'operation': 'creation' };   
      else 
        console.log("no deberia entrar aca");
      console.log(parameters);             
      $.ajax({
        url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function () { el_div_loader_full.classList.add('cont_hidden'); },
        success: function (data) {
          console.log(data);
          el_div_loader_full.classList.remove('cont_hidden');
          if(data.submit==true){
            alert('SKUS ENVIADOS CORRECTAMENTE');
            location.href = "menu.php";
          }else
            alert('ERROR. No se pudo enviar este mail\n\nCONTACTE A INFORMATICA POR FAVOR');
        },
        error: function () { console.log('error'); el_div_loader_full.classList.remove('cont_hidden'); }
      });
    }
  }
  /************************   EVENTO PARA FINALIZAR LA LISTA, ELIMINARLA GUARDANDO EN EL LOG,LOS SKUS CARGADOS A SAP *****************/
  el_but_fin_list=document.getElementById('button_finalize_list');
  if(!!el_but_fin_list){
    el_but_fin_list.onclick=function(){
      //variable operation indica si se guardara en la tabla skucreated o skuupdated
      parameters={'option':'finalize_list', 'list':active_list, 'operation':'creation'};
      $.ajax({ url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function (){ },
        success: function(data){
          console.log(data);
          
        },
        error: function(){ console.log('error'); }
      });
    }
  }


  /*******************  EVENTO PARA VER LA LISTA YA CREADA ************/
  document.getElementById('btn_show_list').onclick=function(el_show_list){    
    if(active_list!=0)
    ///--- PENDIENTE HAY QUE DESHABILITAR ESTA OPCION CUANDO active_list=0;
      modal_preview_save.style.visibility = 'visible';
  }

  /*******************  EVENTO PARA CERRAR SESION ************/
  document.getElementById('a_opcion_menu_logout').onclick=function(){
    if (confirm("ESTA SEGURO DE CERRAR LA SESION (Si existen listas sin enviar, estas seran eliminadas")) {
      location.href = "./config/session.php?option=session_end";
    }
  }
  
  ///--- ################  EVENTOS PARA SKU_CREAR.HTML ###########################
  cargarCategoriaCrear("div_cat_dama");//cargamos los datos en el panel (SELECTS E INPUTS) en el panel CREAR SKU
  cargarSelectsSku('','');//inicialmente cargamos todos los select independientes //raro pero esta llamada se termina antes que la llamada en la funcion anterior
  $(".cont_img_categoria").click(function() {
    id_cat_after_click=$(this).attr('id');
    if(id_cat_actual!==id_cat_after_click){
      $(".cont_fila_crear_sku :input[type=text], .cont_fila_crear_sku select, .full_fila select").each(function () {
        if($(this).val()!="" /* $(this).val()!=null*/) {
          campos_llenos=1;
          // console.log('elemento: ',$(this));         
          return; // igual recorre todo el bucle
        }
      });
      if ($("#span_tallas_chosen").text().trim()!==''){
         campos_llenos = 1;
      }
      if(campos_llenos==1){
        if(confirm("Existen campos con contenido que se perderán si cambia opción.\nDesea cambiar de Departamento")){
          campos_llenos=0;
          cargarCategoriaCrear(id_cat_after_click);
          $("#select_sku_color").selectpicker("deselectAll");
          // $("#select_sku_composicion").selectpicker("deselect");
          $("#select_sku_composicion").attr("selected", false);
          $("#select_sku_composicion").selectpicker("refresh");
          $("#div_sel_grupo_opciones").html("");
          $("#span_tallas_chosen").text(' ');
          document.getElementById('div_copa').style.display = 'none';//ocultamos el div con las tallas
        }else { campos_llenos=0; }
      }else {
        $("#div_sel_grupo_opciones").html("");
        cargarCategoriaCrear(id_cat_after_click);
        document.getElementById('div_copa').style.display = 'none';//ocultamos el div con las tallas
        // $("#select_sku_color").selectpicker("deselectAll");
      }      
    }
  });
  /********* EVENTO PARA AUTORELLENAR LA DESCRIPCION *****/
  document.getElementById('select_sku_material').onchange=function(){
    if(this.value!=""){
      let vacio = 0; //inivar
      document.querySelectorAll('.prefijo').forEach(function (sel) { 
        if (sel.value == "") { vacio = 1; return false; }
      });
      if(vacio === 0 ) 
        autoFillDescription();
    } else document.getElementById('txt_sku_descripcion').value = "";
  };
  /********************* EVENTO PARA OBTENER EL PREFIJO CORRELATIVO Y SUFIJO *******/
  document.querySelectorAll(".prefijo").forEach(function(el){
    el.onchange=function(){
      if(el.value!=""){ // el valor cambiado del control select debe ser diferente de vacio
        resetInputTextCodeArticle();
        if (el.id === "select_sku_subdpto"){ //para inicializar y llenar los selects depedientes de Subdpto
          // resetInputTextCodeArticle()  
          cargarSelectsSku('subdpto', el.value);
          document.getElementById('txt_sku_descripcion').value = ""; // RESETEAOS DADO QUE VOLVEREMOS A SELECCIONAR
          document.getElementById('div_copa').style.display = 'none';   //SETEO ESTATICO  -- OCULTAMOS EL DIV Con los controles para copa
        }else if (el.id === "select_sku_prenda") { //para inicializar y llenar los selects depedientes de Prenda
          el.options[el.selectedIndex].text === "SOSTEN" ? document.getElementById('div_copa').style.display = 'flex' : document.getElementById('div_copa').style.display = 'none';     //SETEO ESTATICO                      
          // resetInputTextCodeArticle();
          document.getElementById('txt_sku_descripcion').value = "";
          cargarSelectsSku('[@APOLLO_SEASON]', el.value);
        } else {
          if (el.id === "select_sku_categoria"){
            el_prenda=document.getElementById('select_sku_prenda');
            (el_prenda.options[el_prenda.selectedIndex].text=="SOSTEN" || el.options[el.selectedIndex].text == "CON SOSTEN" || el.options[el.selectedIndex].text === "CON COPA" ) ? document.getElementById('div_copa').style.display = 'flex' : document.getElementById('div_copa').style.display = 'none';      //SETEO ESTATICO        
          }//verificaremos que todos esten llenos, si es asi, poner el prefijo
          let is_empty = 0;
          var valores = new Object();//valores necesarios para consultar la BDx y obtener el prefijo
          valores['padre'] = id_cat_actual.substr(8, id_cat_actual.length)
          document.querySelectorAll(".prefijo").forEach(function (ele) {
            if (ele.name == '[@APOLLO_SEASON]' || ele.name == '[@APOLLO_DIV]'){
              ele.name == '[@APOLLO_SEASON]' ? valores['prenda'] = ele.value: valores['categoria'] = ele.value;
            }else
              valores[ele.name] = ele.value;
            if (ele.value == "")
              is_empty = 1;
          });
          if (is_empty == 0) {
            getPrefix(valores);
            //ademas verificamos si select_sku_material tiene valors, si es asi, autorellenar la descripcion
            if(document.getElementById('select_sku_material').value!="")
              autoFillDescription();
          }
        }
      } else {
        document.getElementById('txt_sku_descripcion').value = "";
      }
    }
  });


/*********************************************************************************************************/
/****************************************     FUNCIONES    *********************************************/
/*******************************************************************************************************/
  ///--- FUNCION QUE INICIALIZA LOS CONTROLES SELECT Y TXT PARA SER USADOS EN TODA LA API  
  function getElementsControls() {
    el_div_loader_full = document.getElementById('sku_loader_full');
    el_sel_marca =  document.getElementById('select_sku_marca');
    el_sel_subdpto = document.getElementById('select_sku_subdpto'); 

    el_sel_prenda = document.getElementById('select_sku_prenda');
    el_sel_categoria = document.getElementById('select_sku_categoria');
    el_sel_presentacion = document.getElementById('select_sku_presentacion');
    el_sel_material = document.getElementById('select_sku_material');
    el_sel_color = document.getElementById('select_sku_color');
    ///tallas
    el_sel_copa = document.getElementById('select_sku_copa');
    el_sel_fcopa = document.getElementById('select_sku_fcopa');
    el_sel_tprenda = document.getElementById('select_sku_tprenda');
    el_sel_tcatalogo = document.getElementById('select_sku_tcatalogo');
    el_sel_grupouso = document.getElementById('select_sku_grupouso');
    el_sel_caracteristica = document.getElementById('select_sku_caracteristica');
    el_sel_composicion = document.getElementById('select_sku_composicion');
    el_txt_prefijo = document.getElementById('txt_sku_prefijo');
    el_txt_correlativo = document.getElementById('txt_sku_correlativo');
    el_txt_sufijo = document.getElementById('txt_sku_sufijo');
    el_txt_descripcion = document.getElementById('txt_sku_descripcion');

    modal_preview_save = document.getElementById('div_modal_article_creation');
    body_modal_preview_save = modal_preview_save.querySelector('.body_modal')
  }
///FUNCION PARA LLENAR LOS ARTCIULOS PREVIEWS
function renderArticleList(art,itn,rows,estado_article){ // el estado_article = ESTADO EN LISTA MOSTRADA, ES DECIR SI EL ARTICULO YA ESTA EN LISTA, SOLO AGREGAREMOS LOS NUEVOS SKUS
  if (estado_article=='NEW')
    makeArticlePreview(art, itn);  //si es nuevo el articulo, entonces lo creamos y dibujamos en el modal
  id_articulo=art;
  if(id_articulo.indexOf('.') != -1){
    id_articulo="div_"+id_articulo.replace('.','_');    //REEMPLAZAMOS EL PUNTO POR EL "_" DADO QUE NO SE PERMITEN PUNTOS EN EL NOMBRE DEL ARTICULO
  }     
  el_articulo=document.getElementById(id_articulo);//
  el_articulo.querySelector('.dbody_sku').insertAdjacentHTML('beforeend', rows); // AGREGAMOS LAS FILAS DENTRO DEL ARTICULO (AL FINAL SI YA EXISTIERAN)  
  ///CREAREMOS LOS EVENTOS PARA CADA LOS ARTICULOS_PREVIEW ( no se si crearlos aca o en js del componente)
  el_articulo.querySelectorAll('.icon_fila_tabla_modal').forEach(function(icon){
    icon.onclick=function(){
      console.log(this.id);
      ///ACA LLAMARESMOS A LA API ELIMINANDO
      

    }
  })
}
///--- FUNCION QUE OBTIENE UN OBJETO CON TODOS LOS CAMPOS LLENOS DE LA VITA SKU_CREAR.HTML
function getObjectArticle(){
  colores_code.length = 0; colores_text.length = 0;
  tallas_text.length = 0; tallas_orden.length = 0;
  code_article = el_txt_prefijo.value + el_txt_correlativo.value + el_txt_sufijo.value;
  itemname = code_article + '-' + el_txt_descripcion.value;
  colores_code=[];colores_text=[];
  ///--- OBTENEMOS 2 ARRAYS CON COLORES_CODE y COLORES_TEX que guardan los codigos y nombres respectivamente  
  for (var i = 0; i < el_sel_color.selectedOptions.length; i++){
    colores_code.push(el_sel_color.selectedOptions[i].value);
  }
  colores_text = document.querySelector('#div_row_colours .filter-option').innerHTML.split(',');
  colores_text = colores_text.map(item => item.trim());
  ///--- SI LA PRENDA TIENE COPA, ENTONCES HAY QUE AGREGAR EL LA LETRA DE COPA DESPUES DE LA ABREVIATURA DEL COLOR
  ///--- para esto creamos una variable que la contenga y que sera "" en caso de no haber copa
  let copa;
  el_sel_copa.value !== '' ? copa = el_sel_copa.options[el_sel_copa.selectedIndex].text : copa = '';

  ///--- OBTENEMOS VALOR DE LA FAMILIA, ARRAY_TALLAS Y $ARRAY_ORDENES RESPECTIVAMENTE
  list_check_familias = document.querySelectorAll('.check_familia');
  for (i = 0; i < list_check_familias.length; i++)
    if (list_check_familias[i].checked == true) check_familia = list_check_familias[i];
  familia = check_familia.parentNode.parentNode.id;

  list_check_tallas = check_familia.parentNode.nextSibling.querySelectorAll('.check_talla');
  for (i = 0; i < list_check_tallas.length; i++) {
    if (list_check_tallas[i].checked) {
      checked = list_check_tallas[i].name.split('|');
      tallas_orden.push(checked[1]);
      tallas_text.push(checked[0]);
    }
  }

  let obj_article=new Object();
  // obj_article['option'] = '';//DESPUES SE MODIFICARA ESTE DATO PARA LLAMAR A LA API
  obj_article['articulo'] = code_article;
  obj_article['itemname'] = itemname;
  obj_article['talla_familia'] = familia;
  obj_article['tallas_name'] = tallas_text.slice();
  obj_article['tallas_orden'] = tallas_orden.slice();
  obj_article['colores_code'] = colores_code.slice();
  obj_article['colores_name'] = colores_text.slice();
  obj_article['dpto_code'] = code_dpto;
  obj_article['dpto_name'] = name_dpto;
  obj_article['marca_code'] = el_sel_marca.value;
  obj_article['marca_name'] = el_sel_marca.options[el_sel_marca.selectedIndex].text;
  obj_article['subdpto_code'] = el_sel_subdpto.value;
  obj_article['subdpto_name'] = el_sel_subdpto.options[el_sel_subdpto.selectedIndex].text;
  obj_article['prenda_code'] = el_sel_prenda.value;
  obj_article['prenda_name'] = el_sel_prenda.options[el_sel_prenda.selectedIndex].text;
  obj_article['categoria_code'] = el_sel_categoria.value;
  obj_article['categoria_name'] = el_sel_categoria.options[el_sel_categoria.selectedIndex].text;
  obj_article['presentacion_code'] = el_sel_presentacion.value;
  obj_article['presentacion_name'] = el_sel_presentacion.options[el_sel_presentacion.selectedIndex].text;
  obj_article['material_code'] = el_sel_material.value;
  obj_article['material_name'] = el_sel_material.options[el_sel_material.selectedIndex].text;
  obj_article['tcatalogo_code'] = el_sel_tcatalogo.value;
  obj_article['tcatalogo_name'] = el_sel_tcatalogo.options[el_sel_tcatalogo.selectedIndex].text;
  obj_article['tprenda_code'] = el_sel_tprenda.value;
  obj_article['tprenda_name'] = el_sel_tprenda.options[el_sel_tprenda.selectedIndex].text;
  obj_article['grupouso_code'] = el_sel_grupouso.value;
  obj_article['grupouso_name'] = el_sel_grupouso.options[el_sel_grupouso.selectedIndex].text;
  obj_article['caracteristica_code'] = el_sel_caracteristica.value;
  obj_article['caracteristica_name'] = el_sel_caracteristica.options[el_sel_caracteristica.selectedIndex].text;
  obj_article['composicion_code'] = el_sel_composicion.value;
  obj_article['composicion_name'] = el_sel_composicion.options[el_sel_composicion.selectedIndex].text;

  if(el_sel_copa.value!=0){
    obj_article['copa'] = el_sel_copa.options[el_sel_copa.selectedIndex].text;
    obj_article['fcopa'] = el_sel_fcopa.options[el_sel_fcopa.selectedIndex].text;
  }
  return obj_article;
}
//FUNCION PARA AUTORELLENAR LA DESCRIPCION
function autoFillDescription(){
  let descripcion = "";//inivar
  let prenda = document.getElementById('select_sku_prenda');
  let categoria = document.getElementById('select_sku_categoria');
  let material = document.getElementById('select_sku_material');
  if ((prenda.options[prenda.selectedIndex].text == "CALZON") || (prenda.options[prenda.selectedIndex].text == categoria.options[categoria.selectedIndex].text))
    descripcion += categoria.options[categoria.selectedIndex].text + ' ' + material.options[material.selectedIndex].text;
  else
    descripcion += prenda.options[prenda.selectedIndex].text + ' ' + categoria.options[categoria.selectedIndex].text + ' ' + material.options[material.selectedIndex].text;
  // console.log(descripcion);
  document.getElementById('txt_sku_descripcion').value=descripcion;
}
//FUNCION PARA RESETEAR LOS INPUT DE CODIGO DE ARTICULO
function resetInputTextCodeArticle(){
  document.getElementById('txt_sku_prefijo').value = "";
  document.getElementById('txt_sku_correlativo').value = "";
  document.getElementById('txt_sku_sufijo').value = ""

}
//FUNCION PARA OBTENER EL PREFIJO
function getPrefix(values){
  // console.log(values);
  $.ajax({ url : './models/sku_prefijo.php', type : 'post', dataType : 'json', data : values,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success : function(data) {
      // console.log('FROM API: (api: sku_prefijo.php ) ', data);
      // el_div_loader_full.classList.remove('cont_hidden');
      if(!!data['errors']){
        console.log("Error al consultar PREFIJO, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {        
        document.getElementById('txt_sku_prefijo').value=data['prefijo'];
        document.getElementById('txt_sku_correlativo').value=data['first'];
        document.getElementById('txt_sku_sufijo').value = data['sufijo'];
      }
    },
    error: function () { 
      console.log("error");
      // el_div_loader_full.classList.remove('cont_hidden'); 
    }
  });
}
//FUNCION QUE MUESTRA EL PANEL CREAR SEGUN EL DPTO (mujer, varon, lola, ...)
function cargarCategoriaCrear(id_cat) {
  $(".cont_fila_crear_sku :input").val("");  // reseteamos los input
  id_cat_actual=id_cat;
  color=$("#"+id_cat).css('background-color');
  $(".cont_img_categoria").css('-webkit-transform', 'none');//quitamos a todos el efecto scale
  $(".cont_img_categoria").css('transform', 'none');//quitamos a todos el efecto scale
  $(".cont_img_categoria").css('-webkit-filer', 'opacity(.4)');//quitamos a todos el efecto scale
  $(".cont_img_categoria").css('filter', 'opacity(.4)');//quitamos a todos el efecto scale
  $("#"+id_cat).css('-webkit-filter', 'none)');//escalamos solo el cliqueado
  $("#"+id_cat).css('filter', 'none');//escalamos solo el cliqueado
  $("#"+id_cat).css('-webkit-transform', 'scale(1.1)');//escalamos solo el cliqueado
  $("#"+id_cat).css('transform', 'scale(1.1)');//escalamos solo el cliqueado
  $(".cont_img_categoria:hover").css('-webkit-filer', 'none !important');
  $(".cont_img_categoria:hover").css('filer', 'none !important');
  $(".comp_crear_sku").css('background-color', color);
  $('.borrar_contacto').attr('name');
  name_dpto = id_cat.substr(8, id_cat.length);
  cargarSelectsSku('OITB', name_dpto);
}
//FUNCION QUE CARGA LOS SELECT con las OPTIONS de la API.
function cargarSelectsSku(nombre_tabla_padre, valor_tabla_padre) {
  var recorrido=0;
  if(nombre_tabla_padre=="")
    var parametros = { 'option' : 'cargar_selects_independientes'};
  else
    var parametros = { 'option' : 'cargar_selects_dependientes', 'nom_tabla_padre' :  nombre_tabla_padre, 'val_tabla_padre' : valor_tabla_padre };
  // console.log(parametros);
  $.ajax({ url: './models/sku_crear.php', type: 'post', dataType: 'json', data: parametros,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success : function(data) {
      // console.log('FROM API: (option: '+ parametros.option +') ',data);
      // el_div_loader_full.classList.remove('cont_hidden');
      if(!!data.errors){ console.log(data.errors.length+" errores al obtener los options para los selects:");console.log(data.errors); }
      if(!!data.dpto) { code_dpto = data.dpto; }
      if(!!data.first_barcode) {first_barcode=data.first_barcode; }
      data.values.forEach(function(item,index){
        if(item['tabla']=="talla"){          
          document.getElementById('span_tallas_chosen').innerHTML='';
          document.getElementById("div_sel_grupo_opciones").innerHTML = "";
          // console.log(item);
          fillSelectMultiplesGruposFromArray(item['options'], "div_sel_grupo_opciones", false);   
        } else {
          // console.log(item['options']);
          if(item['options']!="SIN RESULTADOS"){
            // console.log(item['tabla']);
            optito="";
            if(item['tabla'] == 'color') {              
              item['options'].forEach(function (itm, idx) { optito += "<option value='" + itm['id'] + "'>" + itm['name'] + "</option>"; });
              $("select[name='" + item['tabla'] + "']").html(optito);
              $('#select_sku_color').selectpicker({ style: 'btn-default fla' }); // ESTABLECEMOS EL FUNCIONAMIENTO DEL selectpicker
            } else if (item['tabla'] == 'composicion') {
              optito += '<option value=""></option>';
              item['options'].forEach(function (itm, idx) { optito += "<option value='" + itm['id'] + "'>" + itm['name'] + "</option>"; });
              $("select[name='" + item['tabla'] + "']").html(optito);
              $('#select_sku_composicion').selectpicker({ style: 'btn-default fla' }); // ESTABLECEMOS EL FUNCIONAMIENTO DEL selectpicker
            }else {
              item['options'].forEach(function (itm, idx) { optito += "<option value='" + itm['id'] + "'>" + itm['name'] + "</option>"; });
              $("select[name='" + item['tabla'] + "']").html('<option value=""></option>'+ optito);
            }
          } //FIN if(item['options']!="SIN RESULTADOS")
          else { 
            //console.log("SIN RESULTADOS, Si cantidad de este log=2, posiblemente sean las copas y formacopa");
          }
        }
      });
      if (!!data.grand_childs) {
        data.grand_childs.forEach(function (item, index) {
          $("select[name='" + item + "']").html("<option value=''></option>"); //reseteamos las opciones a vacio
        }); 
      }     
    },
    /*complete: function () {
      el_div_loader_full.classList.remove('cont_hidden');
    },*/
    error: function() {
      console.log("ERROR obtenido de la la opcion: " + parametros.option);
      // el_div_loader_full.classList.remove('cont_hidden');
    }
  });
}
// FUNCION QUE CARGA LA TABLA SECCION CRUD EN EL MODAL (Dpto, Subdpto, Marca, Prenda, ...)
function cargarTablaSeccion(tabla) {
  //INICIALMENTE REMOVEMOS LAS CELDAS DE LA CABCERA Y LAS FILAS DE LA TABLA EXISTENTES
  fila_head=document.getElementById('div_head_tr_items')
  while (fila_head.firstChild) { fila_head.removeChild(fila_head.firstChild); }
  body=document.getElementById('div_tbody');
  while (body.firstChild) { body.removeChild(body.firstChild); }
  var parametros = { 'option' : 'cargar_seccion', 'nom_tabla' :  tabla };
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parametros,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success : function(data) {
      // el_div_loader_full.classList.remove('cont_hidden');
      if(!!data.errors){
        console.log(data.errors);
      }else {
        //creamos las celdas para las columnas
        data.cabeceras.forEach(function(item,index){
          div_celda=document.createElement('div');
          if(item=="Nombre")
            div_celda.className="th col";
          else
            div_celda.className="th col-1 col-lg-1";
          div_celda.innerHTML=item;
          fila_head.appendChild(div_celda);
        });
        fila_head.insertAdjacentHTML('beforeend','<div class="th col-1 col-lg-1"></div><div class="th col-1 col-lg-1"></div><div class="th col-1 col-lg-1"></div><div class="">&nbsp&nbsp&nbsp</div>')

        //ahora crearemos las filas con celdas para el tbody_div
        data.filas.forEach(function(item,index){
          div_fila=document.createElement('div');
          div_fila.className="row tr";
          !!item['Codigo']? codigo=item['Codigo'] : codigo=item['Nombre'];
          for (var index in item ) {
            div_celda=document.createElement('div');
            if(index=="Codigo")
              div_celda.className="td col-1 col-lg-1 col not_editable";
            else if(index=="Nombre")
              div_celda.className="td col editable";
            else
              div_celda.className="td col-1 col-lg-1 editable";
            div_celda.id=codigo+'_'+index;
            div_celda.innerHTML=item[index];
            div_fila.appendChild(div_celda);
          }
          celdas_img='<div class="td col-1 col-lg-1"><img src="../shared/img/save.png" alt="" disabled class="icon_fila icon_save disabled" id="img_save_'+codigo+'"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/edit.png" alt="" class="icon_fila icon_edit" id="img_edit_'+codigo+'"><img src="../shared/img/edit_cancel.png" alt="" class="icon_fila icon_edit_cancel invisible" id=""></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/delete.png" alt="" class="icon_fila icon_delete" id="img_delete_'+codigo+'"></div>';
          div_fila.insertAdjacentHTML('beforeend',celdas_img);
          body.appendChild(div_fila);
        })
        // *** CREAMOS LOS EVENTOS PARA LOS ICONOS CREADOS ***/
        var contenido_original=[];
        var contenido_actualizar=[];
        var contenido_guardar=[];
        var contenido_parametro=[];

        document.querySelectorAll(".icon_save").forEach(elemento => elemento.style.pointerEvents = "none");//desactivamos el evento click
        // ###################   EVENTO CLICK PARA LOS ICON_SAVE ##############################
        document.querySelectorAll(".icon_save").forEach(elemento => elemento.onclick = function() {
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1).toLocaleLowerCase();
            contenido_actualizar[keycita] = el.firstChild.value.toLocaleUpperCase();
          });
          if(contenido_actualizar['Nombre']!=""){
            if(confirm("¿Desea realmente continuar modificando?")) {
              codigo = (this.id).slice(9);
              updateRegistry(codigo, contenido_actualizar, function (resultado) {
                if(resultado===true){
                  for (var index in contenido_actualizar)
                    contenido_original[index] = contenido_actualizar[index]; //cargamos el contenido actualizado al contenido original                  
                  let img_save=document.getElementById('img_save_'+codigo);
                  img_save.style.pointerEvents = "none";
                  img_save.classList.toggle("disabled");
                  img_save.parentNode.nextSibling.lastChild.classList.toggle('invisible'); //hacemos visible los demas iconos de esta fila
                  img_save.parentNode.nextSibling.firstChild.classList.toggle('invisible'); //hacemos visible los demas iconos de esta fila
                  img_save.parentNode.parentNode.querySelectorAll('.editable').forEach(function (el) { //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
                    keycita = (el.id).slice((el.id).indexOf("_") + 1).toLocaleLowerCase(); //obtenemos el id de las celdas editables para agregar a esta celda, el valor actualizado correspondiente
                    el.innerHTML = contenido_original[keycita]; //cargamos el contenido origignal, actualizado a las celdas
                  });
                  contenido_original.length = 0; //vaciamos este arreglo
                  contenido_actualizar.length = 0; //vaciamos este arreglo
                  getAllNodesEqualType(img_save.parentNode.nextSibling.firstChild, 2, '.icon_edit, .icon_delete').forEach(function (ele) {
                    ele.style.pointerEvents = "auto";
                    ele.classList.toggle("disabled");
                  });
                  img_save.parentNode.parentNode.classList.toggle("editing"); // quitamos esta clase a la fila para devolverle el fondo normal
                  document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
                  document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
                  alert("REGISTRO ACTUALIZADO");
                }else 
                  alert("NO SE PUDO ACTUALIZAR");   
                //referenciamos a el elemento img_save que produde este evento guardar
              });
            }
          }
          else {
            alert('Campo Nombre o Codigo son necesarios para actualizar');
          }
        });
        // ###################   EVENTO CLICK PARA LOS ICON_EDIT ##############################
        document.querySelectorAll(".icon_edit").forEach(elemento => elemento.onclick = function() {
          contenido_original.length=0;
          contenido_actualizar.length=0;
          this.classList.toggle("invisible");//ocultamos el icon_edit
          this.nextSibling.classList.toggle("invisible");//mostramos el icon_edit_cancel
          this.parentNode.parentNode.classList.toggle("editing"); // agregamos esta clase a la fila para cambiarle el fondo
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1);
            contenido_original[keycita]=el.innerHTML;
            el.innerHTML="<input type='text' value='"+contenido_original[keycita]+"'/>";
          });
          this.parentNode.previousSibling.firstChild.classList.toggle("disabled"); // QUITAMOS LA CLASE disabled al icon_save
          this.parentNode.previousSibling.firstChild.style.pointerEvents = "auto"; // activamos el evento click en el icon_save
          getAllNodesEqualType(this,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "none";
            ele.classList.toggle("disabled");
          })
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "none"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo

        });
        // ###################   EVENTO CLICK PARA LOS ICON_EDIT_CANCEL ########################
        document.querySelectorAll(".icon_edit_cancel").forEach(elemento => elemento.onclick = function() {
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){
            keycita=(el.id).slice((el.id).indexOf("_")+1)
            el.innerHTML=contenido_original[keycita];
          });
          this.parentNode.parentNode.classList.toggle('editing'); // le quitamos a la fila la clase qe le cambia el background-color
          this.classList.toggle("invisible"); // ocultamos este icon_edit_cancel
          this.previousSibling.classList.toggle('invisible'); // mostramos icon_edit
          this.parentNode.previousSibling.firstChild.classList.toggle("disabled"); // agregamos clas disabled del icon_save
          this.parentNode.previousSibling.firstChild.style.pointerEvents = "none"; // bloqueamos el evento click del icon_save
          getAllNodesEqualType(this.previousSibling,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "auto";
            ele.classList.toggle("disabled");
          })
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo

        });
        // ###################   EVENTO CLICK PARA LOS ICON_DELETE ##############################
        document.querySelectorAll(".icon_delete").forEach(elemento => elemento.onclick = function() {
          codigo=(this.id).slice(11);
          if(confirm("DESEA REALMENTE ELIMINAR ESTE REGISTRO")){
            deleteRegistry(codigo);
          }
        });
        // ###################   EVENTO CLICK PARA LOS BUTTON_NUEVO ##############################
        document.getElementById("button_nuevo_seccion").onclick = function(){
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "none"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          body=document.getElementById("div_tbody");
          fila_modelo=document.createElement("div");
          fila_modelo.className="row tr";
          !!data.cabeceras['Codigo']? codigo=data.cabeceras['Codigo'] : codigo=data.cabeceras['Nombre']; //aunque ahora todas las tablas tienen codigo
          (data.cabeceras).forEach(function(item,index){
            div_celda=document.createElement("div");
            if(item=="Codigo")
              div_celda.className="td col-1 col-lg-1 col not_editable";
            else {
              if(item=="Nombre")
                div_celda.className="td col editable";
              else
                div_celda.className="td col-1 col-lg-1 editable";
              div_celda.innerHTML="<input type='text' value=''/>";
            }
            div_celda.id='N_'+item;
            fila_modelo.appendChild(div_celda);
          })
          celdas_img='<div class="td col-1 col-lg-1"><img src="../shared/img/save.png" alt="" class="icon_fila icon_save" id="img_save_N"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/cancel.ico" alt="" class="icon_fila" id="img_cancel_N"></div>';
          fila_modelo.insertAdjacentHTML('beforeend',celdas_img);
          body.insertBefore(fila_modelo,body.firstChild);
          body.querySelector('.editable').firstChild.focus();
          /***** agregamos el evento click al icono guardar y cancel de esta fila nueva  ****/
          document.getElementById("img_save_N").onclick = function(){
            this.parentNode.parentNode.querySelectorAll('.editable').forEach(function (el) { //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
              keycita = (el.id).slice((el.id).indexOf("_") + 1).toLocaleLowerCase();;
              contenido_guardar[keycita] = el.firstChild.value.toLocaleUpperCase();
            });
            if (contenido_guardar['Nombre'] != "") {
              if (confirm("¿Desea realmente ingresar este NUEVO VALOR?")) {
                createRegistry(contenido_guardar);
              }
            } else {
              alert('Campo Nombre es necesario para crear un nuevo valor');
            }
            
          }
          document.getElementById('img_cancel_N').onclick=function(){
            let fila_cancel=document.getElementById('img_cancel_N').parentNode.parentNode
            document.getElementById("div_tbody").removeChild(fila_cancel);
            document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
            document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          }
        }
      }
    },
    error: function() {
      console.log("error");
      // el_div_loader_full.classList.remove('cont_hidden');
    }
  });
}
// **** FUNCIION GENERAL ****
//FUNCION QUE OBTIENE TODOS LOS HERMANOS, pasandode los siguientes parametros:
//  nodo actual: El nodo capturado, de quien hay que encontrar sus hijos
//  alcance:     Hasta que padres buscamos,
//  selector: es para limitar la busqueda, seleccionando solo las que los selectores indiquen
//  si alcance=0, buscaremos hasta los hermanos, si alcance=1 buscaremos hermanos y primos hermanos, alcance=2 buscaremos, hermanos, primos hermanos y primos lejanos; y asi sucesivamentes
function getAllNodesEqualType(nodo,alcance,selector){
  let cousinsList=[];
  if(!selector || selector==''){
    selector=nodo.tagName;
  }
  if (alcance==0)
    cousins=nodo.parentNode.querySelectorAll(selector);
  else if(alcance==1)
    cousins=nodo.parentNode.parentNode.querySelectorAll(selector);
  else if (alcance==2)
    cousins=nodo.parentNode.parentNode.parentNode.querySelectorAll(selector);
  else
    cousins=nodo.parentNode.parentNode.parentNode.parentNode.querySelectorAll(selector);
  cousins.forEach( function(cous) {
    if(cous!==nodo)
      cousinsList.push(cous)
  })
  // console.log(cousinsList);
  return cousinsList;
}
//FUNCION PARA ACTUALIZAR EL REGISTRO
function updateRegistry(cod_registro, arr_contenido, callback) {
  let resultado;
  var parameters = new Object();
  parameters['option'] = 'update_item';
  parameters['table'] = item_crud_selected;
  parameters['id'] = cod_registro;
  for (var key in arr_contenido) {
    parameters[key] = arr_contenido[key];
  }
  // console.log(parameters);
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden'); */ },
    success: function(data){
      // console.log("datos desde api: ",data);
      // el_div_loader_full.classList.remove('cont_hidden');
      if (!!data.errors)
        console.log("Errores encontrados:"+data.errors);
      if (data.result === 1) {
        resultado=true;        
        // return true;
        // cargarTablaSeccion(item_crud_selected); //funcion recursiva que vuelve a cargar la tabla          
      } else {
        console.log(data.result);
        // return false;
        resultado=false;
      }
      // callback(resultado);
      // setTimeout(function () {
        callback(resultado);
      // }, 5000);
    },
    error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/ }
  });
}
//FUNCION PARA ELIMINAR REGISTRO
function deleteRegistry(cod_registro){
  var parameters={'option': 'delete_item','table': item_crud_selected, 'id':cod_registro}
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success: function(data){
      // el_div_loader_full.classList.remove('cont_hidden');
      if (!!data.errors)
        console.log("Errores encontrados:".data.errors);
      if (data.result === 1) {
        cargarTablaSeccion(item_crud_selected);//funcion recursiva que vuelve a cargar la tabla  
        alert("REGISTRO ELIMINADO SATISFACTORIAMENTE")
      } else {
        console.log(data.result);
        alert("NO SE PUDO ELIMINAR EL REGISTRO");
      }  
    },
    error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/}
  });
  return true;
}
//FUNCION PARA CREAR REGISTRO ENVIANDO PARAMETROS A LA API Y RECIBIENDO LA RESPUESTA DE QUERY.
function createRegistry(arr_contenido) {
  var parameters = new Object();
  parameters['option'] = 'create_item';
  parameters['table'] = item_crud_selected;
  for (var key in arr_contenido) {
    parameters[key] = arr_contenido[key];
  }
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success: function(data){
      // el_div_loader_full.classList.remove('cont_hidden');
      //if(!!data['errors']) quiere decir que existen errores / data["result"]=1 => DATOS INSERTADOS CORRECTAMENTE / data["result"]=-1 => NO SE PUEDEN REGISTRAR VALORES EXISTENTES, Revise por favor.
      if(!!data.errors)
        console.log("Errores encontrados:".data.errors);
      if(data.result===1){
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          cargarTablaSeccion(item_crud_selected);//funcion recursiva que vuelve a cargar la tabla  
          alert("REGISTRO CREADO SATISFACTORIAMENTE");               
      }else {
        console.log(data.result);
        alert("NO SE PUDO CREAR EL REGISTRO");   
      }        
    },
    error: function() {
      console.log("error");
      // el_div_loader_full.classList.remove('cont_hidden');
    }    
  });
  return true;
}

///--- FUNCION QUE RESETEA LOS CONTROLES COMO INICIO, DEJANDO EN EL DEPARTAMENTO QUE ANTES SE TRABAJO
function resetAllControls(){
  document.querySelectorAll('.ind').forEach( el_ind => el_ind.value="" );
  document.querySelectorAll('.dep').forEach( el_dep => el_dep.innerHTML="" );
  cargarSelectsSku('OITB', name_dpto);
  resetInputTextCodeArticle();
  el_txt_descripcion.value='';
  $("#select_sku_color").selectpicker("deselectAll");
  $("#select_sku_color").selectpicker("refresh");
  // $("#select_sku_composicion").selectpicker("deselect");
  $("#select_sku_composicion").attr("selected", false);
  $("#select_sku_composicion").selectpicker("refresh");
  $("#div_sel_grupo_opciones").html("");
  $("#span_tallas_chosen").text(' ');
}

});