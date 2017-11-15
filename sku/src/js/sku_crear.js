var color, campos_llenos, id_cat_before_click,id_cat_after_click, id_cat_actual, code_dpto, name_dpto, item_crud_selected, first_barcode, current_list;
let active_list=0;
$(document).ready(function() {
  //inicialmente ocultamos la caja que contiene las copas
  document.getElementById('div_copa').style.display = 'none'; 
  ///--- EVENTOS PARA ABRIR LOS MODALES ITEM, RELATIONS Y PREFIJOS
  $("#a_opcion_config_items").click(function() { $("#div_crud_item").css('visibility','visible' );  }); //MOSTRAMOS MODAL ITEMS
  $("#a_opcion_config_relations").click(function(){ $("#div_crud_relations").css('visibility','visible'); }); //MOSTRAMOS MODAL RELACIONES
  $("#a_opcion_config_prefix").click(function () { $("#div_crud_prefix").css('visibility', 'visible');  }); //MOSTRAMOS MODAL PREFIJOS

  /**************** EVENTOS DENTRO DE LOS MODALES *****************/
  document.getElementById('select_item_crud').onchange= function() {
    item_crud_selected=this.value;
    $("#div_tabla_item>tbody_div").html('');
    $("#div_tabla_item").css('visibility', 'visible');
    cargarTablaSeccion($(this).val());
  }
  /****************** EVENTOS PARA CERRAR LAS VENTANAS MODALES ****************/
  $(".close_modal, .close_modal2").click(function () {
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
  });/********************* FIN CERRAR EVENTOS MODALES  ***********************/
  // $("#select_sku_composicion").change(function(){
  //   console.log($("#select_sku_composicion").val());
  // });
  // $("#select_sku_color").change(function(){
  //   console.log($("#select_sku_color").val());
  // })
  
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
  /********************* EVENTO PARA CARGAR LOS VALORES DE LOS ITEMS QUE SE RELACIONAN PARA OBTENER EL PREFIJO  *******/
  document.querySelectorAll(".prefijo").forEach(function(el){
    el.onchange=function(){
      if(el.value!=""){ // el valor cambiado del control select debe ser diferente de vacio
        if (el.id === "select_sku_subdpto"){ //para inicializar y llenar los selects depedientes de Subdpto
          resetInputTextCodeArticle()  
          cargarSelectsSku('subdpto', el.value);
          document.getElementById('txt_sku_descripcion').value = ""; // RESETEAOS DADO QUE VOLVEREMOS A SELECCIONAR
          document.getElementById('div_copa').style.display = 'none';   //SETEO ESTATICO  -- OCULTAMOS EL DIV Con los controles para copa
        }else if (el.id === "select_sku_prenda") { //para inicializar y llenar los selects depedientes de Prenda
          el.options[el.selectedIndex].text === "SOSTEN" ? document.getElementById('div_copa').style.display = 'flex' : document.getElementById('div_copa').style.display = 'none';     //SETEO ESTATICO                      
          resetInputTextCodeArticle();
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
          } else
            resetInputTextCodeArticle()
        }
      } else {
        document.getElementById('txt_sku_descripcion').value = "";
      }
    }
  });
/**************************   EVENTOS PARA GUARDAR Y ENVIAR ************************************/
/***********************************************************************************************/
  let modal_preview_save = document.getElementById('div_preview_save');
  let body_modal_preview_save = modal_preview_save.querySelector('.body_modal')
  /////----- EVENTO PARA GUARDAR LOS SKU Y ENVIAR EL EXCEL 
  // document.getElementById('button_cancel_save_sku').onclick=function(){  
  //   modal_preview_save.style.visibility = 'hidden';
  //   body_modal_preview_save.removeChild(body_modal_preview_save.firstChild);
  // };

  document.getElementById('button_save_new_list').onclick=function(){
    ///--- ACA SIMPLEMENTE SE BUSCARA LA LISTA EN MYSQL Y SE ENVIARA EL MAIL CON LOS ARTICULOS CON SKU QUE PERTENESCAN A ESA LISTA
    parameters = new Object();
    parameters['option']='save_list';
    parameters['list']=active_list;
    $.ajax({ url: 'sku_lista.php', type: 'post', dataType: 'json', data: parameters,
      beforeSend: function (){ },
      success: function(data){
        console.log(data.resp);
        alert('SKUS ENVIADOS CORRECTAMENTE');
        location.reload();
      },
      error: function(){ console.log('error'); }
    });

  }
  /////----- EVENTO PARA MOSTRAR EL PANEL PREVIEW SAVE SKU con la nueva lista con ARTICULO(s) QUE SE CREARAR
  document.getElementById('btn_create_article_list').onclick=function(){
    let empty=0;
    let tallas = document.getElementById('span_tallas_chosen').innerHTML.trim();
    document.querySelectorAll('.sku_control').forEach(function(control){
       if(control.parentNode.parentNode.style.display!='none')// si el div que contiene estos controles, no se muestra, entonces no consideramos ese control.
         if(control.value=='')  empty=1;
    });
    if (tallas=="") empty=1;
    //sacar esto despues /
    empty=0; // LO PONEMOS PARA VER EL MODAL. el cual no debe mostrarse si no se seleccionaro todas las opciones del sku_crear
    if(empty===0) { 
      parameters=new Object();
      parameters=getObjectArticle();                 
      parameters['option']='save_article';
      parameters['list']=active_list;
      console.log(parameters);
      $.ajax({ url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function (){ },
        success: function(data){          
          console.log('FROM API (option: ' + parameters.option + ') ',data);
          if(data.filas!='') 
            renderArticleList(data.articulo,data.itemname,data.filas); 
          if(!!data.refused){
            mensaje='LOS SIGUIENTES SKUS NO FUERON AGREGADOS\n\n';
            for (var item in data.refused) {
              mensaje += 'SKU: ' + data.refused[item]['sku'] + ' --- MOTIVO: ' + data.refused[item]['detalle'] +'\n';                
            }
            alert(mensaje);
          }
        },
        error: function(){ console.log('error'); }
      });
    }
    else
      alert("TODOS LOS CAMPOS SON NECESARIOS");
  }

  document.getElementById('button_save_new_list').onclick = function () {
    console.log(params);
    $.ajax({
      url: './models/sku_lista.php',
      type: 'post',
      dataType: 'json',
      data: params,
      beforeSend: function () {},
      success: function (data) {
        console.log('FROM API (sku_lista.php)',data);
        if(data.resp=='READY')
          alert('DATOS GUARDADOS CON EXITO ( archivo con SKUs fueron enviados correctamente )');          
        else  
          alert(data.resp);
      },
      error: function () {
        console.log('error');
      }
    });
  }

  loadToModifyArticleList('');
/*********************************************************************************************************/
/*******************************************************************************************************/

///FUNCION PARA LLENAR LOS ARTCIULOS PREVIEWS
function renderArticleList(art,itn,rows){
  makeArticlePreview(art, itn);  
  id_articulo=art;
  if(id_articulo.indexOf('.') != -1){
    id_articulo="div_"+id_articulo.replace('.','_');    
  }     
  el_articulo=document.getElementById(id_articulo);//
  el_articulo.querySelector('.dbody_sku').innerHTML=rows;
  modal_preview_save.style.visibility = 'visible';

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
                            ///--- SACAR ESTO DESPUES ---
                            /*obj_static=new Object();
                            tallas_text = ['XS', 'S', 'M', 'L'];
                            tallas_orden = [7, 1, 2, 3];
                            colores_code = [1, 8];
                            colores_text = ['ACERO', 'AZUL'];
                            obj_static['articulo'] = '50.8017';
                            obj_static['itemname'] = '50.8017-SOSTE MATERNAR ALGODON';
                            obj_static['familia'] = 'T03';
                            obj_static['tallas_name'] = tallas_text.slice();
                            obj_static['tallas_orden'] = tallas_orden.slice();
                            obj_static['colores_code'] = colores_code.slice();
                            obj_static['colores_name'] = colores_text.slice();
                            obj_static['dpto_code'] = 106;
                            obj_static['dpto_name'] = 'dama';
                            obj_static['marca_code'] = '3';
                            obj_static['marca_name'] = 'SENS';
                            obj_static['subdpto_code'] = '5';
                            obj_static['subdpto_name'] = 'CORSETERIA';
                            obj_static['prenda_code'] = '25';
                            obj_static['prenda_name'] = 'SOSTEN';
                            obj_static['categoria_code'] = '54';
                            obj_static['categoria_name'] = 'MATERNAL';
                            obj_static['presentacion_code'] = '1';
                            obj_static['presentacion_name'] = 'UNITARIO';
                            obj_static['material_code'] = '2';
                            obj_static['material_name'] = 'ALGOD0N';
                            obj_static['tcatalogo_code'] = '';
                            obj_static['tcatalogo_name'] = '';
                            obj_static['grupo_uso_code'] = '';
                            obj_static['grupo_uso_name'] = '';
                            obj_static['composicion_code'] = '';
                            obj_static['composicion_name'] = '';
                            obj_static['caracteristica'] = '';
                            obj_static['peso'] = '';
                            obj_static['copa'] = '';
                            obj_static['fcopa'] = '';
                            return obj_static;*/
                            ///--- SACAR LO ANTERIOR

  colores_code.length = 0; colores_text.length = 0;
  tallas_text.length = 0; tallas_orden.length = 0;
  code_article = document.getElementById('txt_sku_prefijo').value + document.getElementById('txt_sku_correlativo').value + document.getElementById('txt_sku_sufijo').value;
  itemname = code_article + '-' + document.getElementById('txt_sku_descripcion').value;
  colores_code=[];colores_text=[];
  ///--- OBTENEMOS 2 ARRAYS CON COLORES_CODE y COLORES_TEX que guardan los codigos y nombres respectivamente
  el_sel_colors = document.getElementById('select_sku_color');
  for (var i = 0; i < el_sel_colors.selectedOptions.length; i++){
    colores_code.push(el_sel_colors.selectedOptions[i].value);
  }
  colores_text = document.querySelector('#div_row_colours .filter-option').innerHTML.split(',');
  colores_text = colores_text.map(item => item.trim());
  ///--- SI LA PRENDA TIENE COPA, ENTONCES HAY QUE AGREGAR EL LA LETRA DE COPA DESPUES DE LA ABREVIATURA DEL COLOR
  ///--- para esto creamos una variable que la contenga y que sera "" en caso de no haber copa
  let copa;
  el_copa = document.getElementById('select_sku_copa')
  el_copa.value !== '' ? copa = el_copa.options[el_copa.selectedIndex].text : copa = '';

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
  el_marca = document.getElementById('select_marca');
  obj_article['marca_code'] = el_marca.value;
  obj_article['marca_name'] = el_marca.options[el_marca.selectedIndex].text;
  el_subdpto = document.getElementById('select_sku_subdpto');
  obj_article['subdpto_code'] = el_subdpto.value;
  obj_article['subdpto_name'] = el_subdpto.options[el_subdpto.selectedIndex].text;
  el_prenda = document.getElementById('select_sku_prenda');
  obj_article['prenda_code'] = el_prenda.value;
  obj_article['prenda_name'] = el_prenda.options[el_prenda.selectedIndex].text;
  el_categoria = document.getElementById('select_sku_categoria');
  obj_article['categoria_code'] = el_categoria.value;
  obj_article['categoria_name'] = el_categoria.options[el_categoria.selectedIndex].text;
  el_presentacion = document.getElementById('select_sku_presentacion');
  obj_article['presentacion_code'] = el_presentacion.value;
  obj_article['presentacion_name'] = el_presentacion.options[el_presentacion.selectedIndex].text;
  el_material = document.getElementById('select_sku_material');
  obj_article['material_code'] = el_material.value;
  obj_article['material_name'] = el_material.options[el_material.selectedIndex].text;
  el_tcatalogo = document.getElementById('select_sku_tcatalogo');
  obj_article['tcatalogo_code'] = el_tcatalogo.value;
  obj_article['tcatalogo_name'] = el_tcatalogo.options[el_tcatalogo.selectedIndex].text;
  el_grupo_uso = document.getElementById('select_sku_grupo_uso');
  obj_article['grupo_uso_code'] = el_grupo_uso.value;
  obj_article['grupo_uso_name'] = el_grupo_uso.options[el_grupo_uso.selectedIndex].text;
  obj_article['caracteristica'] = document.getElementById('txa_sku_caracteristicas').value;
  el_composicion = document.getElementById('select_sku_composicion');
  obj_article['composicion_code'] = el_composicion.value;
  obj_article['composicion_name'] = el_composicion.options[el_composicion.selectedIndex].text;
  obj_article['peso'] = document.getElementById('txt_sku_peso').value;
  el_copa=document.getElementById('select_sku_copa');
  el_fcopa = document.getElementById('select_sku_fcopa');
  if(el_copa.value!=0){
    obj_article['copa'] = el_copa.options[el_copa.selectedIndex].text;
    obj_article['fcopa'] = el_fcopa.options[el_fcopa.selectedIndex].text;
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
  console.log(descripcion);
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
  console.log(values);
  $.ajax({ url : './models/sku_prefijo.php', type : 'post', dataType : 'json', data : values,
    success : function(data) {
      console.log('FROM API: (api: sku_prefijo.php ) ', data);
      if(!!data['errors']){
        console.log("Error al consultar PREFIJO, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {
        document.getElementById('txt_sku_prefijo').value=data['prefijo'];
        document.getElementById('txt_sku_correlativo').value=data['first'];
        document.getElementById('txt_sku_sufijo').value = data['sufijo'];
      }
    },
    error : function() { console.log("error"); }
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
  console.log(parametros);
  $.ajax({ url: './models/sku_crear.php', type: 'post', dataType: 'json', data: parametros,
    success : function(data) {
      console.log('FROM API: (option: '+ parametros.option +') ',data);
      if(!!data.errors){ console.log(data.errors.length+" errores al obtener los options para los selects:");console.log(data.errors); }
      if(!!data.dpto) { code_dpto = data.dpto; }
      if(!!data.first_barcode) {first_barcode=data.first_barcode; }
      data.values.forEach(function(item,index){
        if(item['tabla']=="talla"){          
          document.getElementById('span_tallas_chosen').innerHTML='';
          document.getElementById("div_sel_grupo_opciones").innerHTML = "";
          console.log(item);
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
          else { console.log("SIN RESULTADOS, Si cantidad de este log=2, posiblemente sean las copas y formacopa") ;}
        }
      });
      if (!!data.grand_childs) {
        data.grand_childs.forEach(function (item, index) {
          $("select[name='" + item + "']").html("<option value=''></option>"); //reseteamos las opciones a vacio
        }); 
      }     
    },
    error: function() {
      console.log("ERROR obtenido de la la opcion: " + parametros.option);
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
    success : function(data) {
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
    success: function(data){
      console.log("datos desde api: ",data);
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
    error: function(){ console.log('error'); }
  });
}
//FUNCION PARA ELIMINAR REGISTRO
function deleteRegistry(cod_registro){
  var parameters={'option': 'delete_item','table': item_crud_selected, 'id':cod_registro}
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    success: function(data){
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
    error: function(){ console.log('error'); }
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
    success: function(data){
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
    }    
  });
  return true;
}

function loadToModifyArticleList(articulo){
  tablas=[];
  document.querySelectorAll('.sku_control').forEach(function (control) {
    if (control.name != 'color' && control.name != 'talla' && control.name != 'copa' && control.name != 'caracteristicas' && control.name != 'peso' && control.name != 'prefijo' && control.name != 'correlativo' && control.name != 'descripcion') {
      tablas.push(control.name);  
    } 
  });
  // console.log(tablas);  
  parameters = { 'option': 'load_article_list', 'tables' : tablas }; 
  $.ajax({ url: './models/articulo_cargar.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function (){ },
    success: function(data){
      // console.log(data);
    },
    error: function(){ console.log('error'); }
  });
}


function fillSkusInArticle(articulo){


}
});