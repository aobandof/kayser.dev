var color, campos_llenos, id_cat_before_click,id_cat_after_click, id_cat_actual, code_dpto, name_dpto, item_crud_selected, first_barcode, current_list;
// let active_list=0;
let el_sel_marca,el_sel_subdpto,el_sel_prenda, el_sel_categoria, el_sel_presentacion, el_sel_material, el_sel_color, el_sel_tallas, el_sel_tprenda, el_sel_tcatalogo, el_sel_grupouso, el_sel_caracteristica, el_sel_composicion, el_txt_prefijo, el_txt_correlativo, el_txt_sufijo;
let modal_preview_save, body_modal_preview_save; 
let el_div_loader_full;

$(document).ready(function() {

  getElementsControls();//INICIALIZAMOS ALGUNOS ELEMENTOS QUE USAREMOS DURANTE TODO EL PROGRAMA

  ///--- SI ESTA INTENTANDO VER UNA LISTA PENDIENTE, LA DIBUJAMOS Y MOSTRAMOS TODOS SUS ARTICULOS ----
  if(initial_option == 'show'){
    modal_preview_save.style.visibility = 'visible'; 
    el_span_title_list.innerHTML='LISTA N° ' + active_list;
    ///--- CONSULTAREMOS A LA API, TODOS LOS SKUS AGRUPADOS POR ARTICULOS QUE ESTAN DENTRO DE ESTA LISTA
    parameters={ 'option':'get_articles', 'list':active_list };
    console.log(parameters);
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
    if(state_list=='INICIADA'){//SI LA LISTA ESTA SOLO INICIADA HAY QUE MOSTRAR TAMBIEN A REVISER Y A ADMIN LOS BOTONES PARA CREAR LOS EXCEL
      el_span_state_list.innerHTML='SKUs pendientes de Creación. Click en GUARDAR SKUs para enviar Notificación';
      el_span_state_list.style.color = 'rgb(241, 60, 132)';
      // el_span_state_list.style.backgroundColor='yellow';
      if(!!el_but_fin_list) el_but_fin_list.classList.add('cont_hidden')
      if(!!el_but_submit_excel) el_but_submit_excel.classList.add('cont_hidden');
      el_but_save_list.classList.remove('cont_hidden')
      // el_but_save_list.innerHTML="GUARDAR SKUs";

    }else{ //SI NO ESTA INICIADA
      if (perfil == 'admin') {
        el_but_save_list.classList.add('cont_hidden');
        // el_but_follow_editing.classList.add('cont_hidden');
        el_but_add_article.classList.add('cont_hidden');
        if (state_list == 'REVISADA') {
          el_but_fin_list.classList.remove('cont_hidden');
        }else{
          el_but_fin_list.classList.add('cont_hidden')
          el_span_state_list.innerHTML = 'SKUs pendientes de REVISION';
          el_span_state_list.style.color = 'blue';
          ///--- PENDIENTE, ACA DEBEMOS DESHABILITAR LOS BOTONES DE EDICION DE LOS ARTIUCLOS EN EL MODAL
        }
      } else {
        el_but_save_list.classList.add('cont_hidden');
        el_but_submit_excel.classList.remove('cont_hidden');
        if (state_list == 'REVISADA') {
          el_span_state_list.innerHTML = 'SKUs REVISADOS anteriormente, SI REALIZA CAMBIOS Click en REENVIAR PLANILLA EXCEL para actualizar modificaciones';
          el_but_submit_excel.innerHTML='REENVIAR EXCEL';
          el_span_state_list.style.color = 'green';
        }else {
          el_span_state_list.innerHTML = 'SKUs pendientes de REVISION, Click en ENVIAR PLANILLA EXCEL a Informática';
          el_span_state_list.style.color = 'green';
         }

      }
    }
  }
  if(initial_option == 'create'){
    el_but_show_lists.classList.add('cont_hidden')
    el_but_save_list.classList.remove('cont_hidden')
    if (perfil == 'admin')
      el_but_save_list.classList.remove('cont_hidden')
    else if (perfil == 'reviser')
      el_but_submit_excel.classList.add('cont_hidden')
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
              if (initial_option=="create"){
                if (!!el_but_fin_list) el_but_fin_list.classList.add('cont_hidden')
                if (!!el_but_submit_excel) el_but_submit_excel.classList.add('cont_hidden')
              }
              modal_preview_save.style.visibility = 'visible';
              el_span_title_list.innerHTML="LISTA N° " + active_list;
              if(typeof state_list === 'undefined')// SOLO EL REVISER TENDRA ESTA OPCION
                el_span_state_list.innerHTML = "EDITANDO LISTA, después click en ENVIAR SKUs para NOTIFICAR ESTA CREACION ";                
              else
                el_span_state_list.innerHTML = "EDITANDO LISTA YA CREADA, después click en ENVIAR PLANILLA EXCEL para NOTIFICAR ESTA REVISION";                
              el_span_state_list.style.color = 'rgba(29, 185, 100, 0.88)';
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
        if (initial_option =='show')//ACCEDIO A  LA LISTA DESDE EL MODULO DE LISAS PENDIENTES, POR ENDE REGRESAMOS A ELLA
          location.href = "listas.php";
        else
          location.href = "menu.php";//OPTAMOS POR VOLER AL MENU PARA ELEGIR LA OPCION DESEADA
      }
    }
  }
  /****************** EVENTOS PARA AGREGAR OTRO ARTICULO CUANDO SE ESTA  EDITANDO ****************/
  if(!!el_but_add_article){
    el_but_add_article.onclick = function () {
      // resetAllControls();
      document.getElementById('btn_show_list').disabled = false;
      modal_preview_save.style.visibility = 'hidden';
    }
  }

  ///--- EVENTO PARA VER REGRESAR AL ARCHIVO lista.php DESDE LOS BOTONES EN EL ARICULO_PREVIEW 
  if(!!el_but_show_lists){
    el_but_show_lists.onclick=function(){
      // alert("deberia verse esto ante el evento click");
      location.href = "listas.php";
    }
  }
  /************************   EVENTO PARA GUARDAR LOS SKU   *********************/
  if (!!el_but_save_list) {
    parameters = new Object();
    el_but_save_list.onclick = function () {
      if(confirm("SEGURO DE CREAR LOS SKUS")){
        parameters = { 'option': 'save_list', 'list': active_list };
        console.log(parameters);             
        ajax_save_list(parameters);
      }
    }
  }
  /************************   EVENTO PARA ENVIAR EL EXCEL   *********************/
  if (!!el_but_submit_excel) {
    parameters = new Object();
    el_but_submit_excel.onclick = function () {
      if (confirm("SEGURO DE CREAR Y ENVIAR PLANILLA EXCEL")) {
        parameters = { 'option': 'submit_excel', 'list': active_list };
        console.log(parameters);
        ajax_submit_excel(parameters) 
      }
    }
  }
  /************************   EVENTO PARA FINALIZAR LA LISTA, ELIMINARLA GUARDANDO EN EL LOG,LOS SKUS CARGADOS A SAP *****************/
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
    el_span_state_list = document.getElementById('span_state_list');
    el_span_title_list = document.getElementById('span_title_list');
    ///--- INICIALIZAMOS BOTONES DEL ARTICLE_PREVIEW
    el_but_delete_list = document.getElementById('button_delete_list');
    el_but_add_article = document.getElementById('button_add_article');
    // el_but_follow_editing = document.getElementById('button_follow_editing');
    el_but_show_lists = document.getElementById('button_show_lists');
    el_but_save_list = document.getElementById('button_save_list');
    el_but_fin_list = document.getElementById('button_finalize_list');
    el_but_submit_excel = document.getElementById('button_submit_excel');
    
    el_div_loader_full = document.getElementById('sku_loader_full');

    ////--- INICIALIZAMOS CONTROLES SKUS
    el_sel_marca =  document.getElementById('select_sku_marca');
    el_sel_subdpto = document.getElementById('select_sku_subdpto'); 
    el_sel_prenda = document.getElementById('select_sku_prenda');
    el_sel_categoria = document.getElementById('select_sku_categoria');
    el_sel_presentacion = document.getElementById('select_sku_presentacion');
    el_sel_material = document.getElementById('select_sku_material');
    el_sel_color = document.getElementById('select_sku_color');
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
function ajax_save_list(param){
  $.ajax({
    url: './models/sku_lista.php', type: 'post', dataType: 'json', data: param,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden'); },
    success: function (data) {
      console.log(data);
      el_div_loader_full.classList.add('cont_hidden');
      if (data.creation == true) {
        if (data.submit == true) {
          alert('SKUS GUARDADOS CORRECTAMENTE\n\nSE ENVIO MAIL CON NOTIFICACION PARA SU PROXIMA REVISION');
          location.href = "menu.php";
        } else {
          alert('SKUS GUARDADOS CORRECTAMENTE PERO NO SE ENVIO LA NOTIFICACION\n\nINFORME POR SU CUENTA SOBRE LA LISTA CREADA PARA SU PROXIMA REVISION ');
          location.href = "menu.php";
        }
      } else
        alert('ERROR. NO PUDO ENVIAR EL MAIL, revise esta LISTA PENDIENTE e intentelo otra vez,\n\nSINO CONTACTE A INFORMATICA POR FAVOR');
    },
    error: function () { console.log('error'); el_div_loader_full.classList.add('cont_hidden'); }
  });
}
function ajax_submit_excel(param) {
  $.ajax({
    url: './models/sku_lista.php', type: 'post', dataType: 'json', data: param,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden'); },
    success: function (data) {
      console.log(data);
      el_div_loader_full.classList.add('cont_hidden');
      if (data.submit===true){
        alert('SKUS GUARDADOS CORRECTAMENTE\n\nSE ENVIO MAIL CON PLANILLA EXCEL A INFORMATICA');
        location.href = "menu.php";
      } else
          alert('ERROR. NO PUDO ENVIAR EL MAIL, revise esta LISTA PENDIENTE e intentelo otra vez,\n\nSINO CONTACTE A INFORMATICA POR FAVOR');
    },
    error: function () { console.log('error'); el_div_loader_full.classList.add('cont_hidden'); }
  });
}