var color;
var id_cat_before_click,id_cat_after_click, id_cat_actual;
var campos_llenos;
$(document).ready(function() {
  $("#a_opcion_config_items").click(function() { $("#div_crud_item").css('visibility','visible' );  }); //MOSTRAMOS MODAL ITEMS
  $("#a_opcion_config_relations").click(function(){ $("#div_crud_relations").css('visibility','visible');  }); //MOSTRAMOS MODAL RELACIONES
  $("#a_opcion_config_prefix").click(function () { $("#div_crud_prefix").css('visibility', 'visible');  }); //MOSTRAMOS MODAL PREFIJOS
  /**************** EVENTOS DENTRO DE LOS MODALES *****************/
  $("#select_item_crud").change(function() {
    $("#div_tabla_item>tbody_div").html('');
    $("#div_tabla_item").css('visibility', 'visible');
    cargarTablaSeccion($(this).val());
  });
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

  cargarCategoriaCrear("div_cat_dama");//cargamos los datos en el panel (SELECTS E INPUTS) en el panel CREAR SKU
  cargarSelectsSku('','');//inicialmente cargamos todos los select independientes //raro pero esta llamada se termina antes que la llamada en la funcion anterior
  $(".cont_img_categoria").click(function() {
    id_cat_after_click=$(this).attr('id');
    if(id_cat_actual!==id_cat_after_click){
      $(".cont_fila_crear_sku :input").each(function() {
        if($(this).val()!=="" && $(this).val()!==null) {
            campos_llenos=1;
            return; // igual recorre todo el bucle
        }
      });
      if(campos_llenos==1){
        if(confirm("Existen campos con contenido que se perderán si cambia opción.\nDesea cambiar de Departamento")){
          campos_llenos=0;
          cargarCategoriaCrear(id_cat_after_click);
          $("#select_sku_color").selectpicker("deselectAll");
          $("#select_sku_composicion").selectpicker("deselectAll");
        }
      }else {
        cargarCategoriaCrear(id_cat_after_click);
        $("#select_sku_color").selectpicker("deselectAll");
        $("#select_sku_composicion").selectpicker("deselectAll");
      }
    }
  });
  $("#select_sku_subdpto").change(function() { cargarSelectsSku('Subdpto', $(this).val()) });
  $("#select_sku_prenda").change(function() { cargarSelectsSku('Kayser_SEASON', $(this).val()) });

  /********************* COMENTAR SI NO FUNCAN: EVENTO PARA AGREGAR EL PREFIJO, SEGUN LA RELACION *******/
  document.querySelectorAll(".prefijo").forEach(function(el){
    el.onchange=function(){
      //verificaremos que todos esten llenos, si es asi, poner el prefijo
      let is_empty=0;
      var valores=new Object();
      valores['padre']=id_cat_actual.substr(8,id_cat_actual.length)
      document.querySelectorAll(".prefijo").forEach(function(eli){
        valores[eli.name]=eli.value;
        if(eli.value=="")
          is_empty=1;
      });
      if(is_empty==0)
        getPrefix(valores);
    }
  });
/*******************************************************************************************************/
});

//FUNCION PARA OBTENER EL PREFIJO
function getPrefix(values){
  console.log(values);
  $.ajax({ url : 'prefijo.php', type : 'post', dataType : 'json', data : values,
    success : function(data) {
      if(!!data['errors']){
        console.log("Error al consultar PRECIOS, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {
        console.log(data);
        if(data['prefijo']!="SIN RESULTADOS")
          document.getElementById('txt_sku_articulo').value=data['prefijo'];
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
  cargarSelectsSku('Kayser_OITB', id_cat.substr(8,id_cat.length));
}

//FUNCION QUE CARGA LOS SELECT con las OPTIONS de la API.
function cargarSelectsSku(nombre_tabla_padre, valor_tabla_padre) {
  var recorrido=0;
  if(nombre_tabla_padre=="")
    var parametros = { 'opcion' : 'cargar_selects_independientes'};
  else
    var parametros = { 'opcion' : 'cargar_selects_dependientes', 'nom_tabla_padre' :  nombre_tabla_padre, 'val_tabla_padre' : valor_tabla_padre };
  $.ajax({ url: 'sku_crear.php', type: 'post', dataType: 'json', data: parametros,
    success : function(data) {
      if(data[0].error){
        console.log(data[0].error);
      }else {
        console.log(data);
        var long_data=data.length;
        if(parametros['opcion']=='cargar_selects_dependientes'){
         recorrido=1;//para que no considere el primer elemento de la data obtenida de la api
         for (var valor of data[0])
           $("select[name='"+valor+"']").html("<option value=''></option>");//reseteamos las opciones a vacio
        }
        for (i=recorrido; i<long_data; i++) {
          if(data[i].tabla=='Talla'){
            document.getElementById("div_sel_grupo_opciones").innerHTML="";
            fillSelectMultiplesGruposFromArray(data[i].options, "div_sel_grupo_opciones",false);
          }else {
            optito="";
            if(data[i].tabla=='Color'){
              data[i].options.forEach(function(item,index){ optito+="<option value='" + item['id'] +"'>" + item['name'] + "</option>"; });
              $("select[name='"+data[i].tabla+"']").html(optito);
              $('#select_sku_color').selectpicker({style: 'btn-default fla'}); // ESTABLECEMOS EL FUNCIONAMIENTO DEL selectpicker
            }else if(data[i].tabla=='Composicion') {
              data[i].options.forEach(function(item,index){ optito+="<option value='" + item['id'] +"'>" + item['name'] + "</option>"; });
              $("select[name='"+data[i].tabla+"']").html(optito);
              $('#select_sku_composicion').selectpicker({style: 'btn-default fla'}); // ESTABLECEMOS EL FUNCIONAMIENTO DEL selectpicker

            }else {
              data[i].options.forEach(function(item,index){ optito+="<option value='" + item['id'] +"'>" + item['name'] + "</option>"; });
              $("select[name='"+data[i].tabla+"']").html('<option value=""></option>'+optito);
            }
          }
        }
      }
    },
    error: function() {
      console.log("error");
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
  var parametros = { 'opcion' : 'cargar_seccion', 'nom_tabla' :  tabla };
  $.ajax({ url: 'sku_seccion_crud.php', type: 'post', dataType: 'json', data: parametros,
    success : function(data) {
      if(!!data['error']){
        console.log(data[0].error);
      }else {
        console.log(data);
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
        contenido_original=[];
        contenido_actualizar=[];
        contenido_guardar=[];
        document.querySelectorAll(".icon_save").forEach(elemento => elemento.style.pointerEvents = "none");//desactivamos el evento click
        // ###################   EVENTO CLICK PARA LOS ICON_SAVE ##############################
        document.querySelectorAll(".icon_save").forEach(elemento => elemento.onclick = function() {
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1);
            contenido_actualizar[keycita]=el.firstChild.value;
          });
          console.log("contenido_actualizar obtenido de las cajas: ");
          console.log(contenido_actualizar);
          if(contenido_actualizar['Nombre']!=""){
            if(confirm("¿Desea realmente continuar modificando?")) {
              if(updateRegistry(contenido_actualizar)){
                for (var index in contenido_actualizar)
                  contenido_original[index]=contenido_actualizar[index];//cargamos el contenido actualizado al contenido original
                alert("Datos actualizados correctamente");
              }else {
                alert("No se pudo Actualizar");
              }
            }
          }
          else {
            alert('Campo Nombre o Codigo son necesarios para actualizar');
          }
          console.log("contenido_original:");
          console.log(contenido_original);
          this.style.pointerEvents = "none";
          this.classList.toggle("disabled");
          this.parentNode.nextSibling.lastChild.classList.toggle('invisible');
          this.parentNode.nextSibling.firstChild.classList.toggle('invisible');
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1); //obtenemos el id de las celdas editables para agregar a esta celda, el valor actualizado correspondiente
            el.innerHTML = contenido_original[keycita]; //cargamos el contenido origignal, actualizado a las celdas
          });
          contenido_original.length=0; //vaciamos este arreglo
          contenido_actualizar.length=0; //vaciamos este arreglo
          getAllNodesEqualType(this.parentNode.nextSibling.firstChild,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "auto";
            ele.classList.toggle("disabled");
          }) ;
          this.parentNode.parentNode.classList.toggle("editing"); // quitamos esta clase a la fila para devolverle el fondo normal
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
          console.log("contenido original, despues de activar la edicion:");
          console.log(contenido_original);
          this.parentNode.previousSibling.firstChild.classList.toggle("disabled"); // QUITAMOS LA CLASE disabled al icon_save
          this.parentNode.previousSibling.firstChild.style.pointerEvents = "auto"; // activamos el evento click en el icon_save
          getAllNodesEqualType(this,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "none";
            ele.classList.toggle("disabled");
          })
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
        });
        // ###################   EVENTO CLICK PARA LOS ICON_DELETE ##############################
        document.querySelectorAll(".icon_delete").forEach(elemento => elemento.onclick = function() {
          codigo=(this.id).slice(11);
          if(confirm("DESEA REALMENTE ELIMINAR ESTE REGISTRO")){
            if(deleteRegistry(codigo)){
              alert("REGISTRO ELIMINADO EXITOSAMENTE!")
              fila=this.parentNode.parentNode;
              // console.log(fila);
              document.getElementById("div_tbody").removeChild(fila);
            }
            else {
                alert("No se pudo Eliminar");
            }
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
          /***** agregamos el evento click al icono guardar y cancel de esta fila nueva  ****/
          document.getElementById("img_save_N").onclick = function(){
            // console.log("1 validamos / 2 preguntamos / 3 guardamos y devolvemos confirmacion / si si, cargamos nuevamente la tabla, si no, dejamos la fila para cualquier modificacion o cancelacion");
            this.parentNode.parentNode.querySelectorAll('.editable').forEach(function (el) { //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
              keycita = (el.id).slice((el.id).indexOf("_") + 1);
              contenido_guardar[keycita] = el.firstChild.value;
              console.log(keycita);
            });
            if (contenido_guardar['Nombre'] != "") {
              if (confirm("¿Desea realmente ingresar este NUEVO VALOR?")) {
                if (createRegistry(contenido_guardar)) {
                  cargarTablaSeccion(tabla);//funcion recursiva que vuelve a cargar la tabla
                  document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
                  document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
                  alert("Registro creado Correctamente");
                } else {
                  alert("No se pudo crear el nuevo valor");
                }
              }
            } else {
              alert('Campo Nombre es necesario para crear un nuevo valor');
            }
            
          }
          document.getElementById('img_cancel_N').onclick=function(){
            // console.log("1 removemos esta fila con las celdas, inputs e imagenes");
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

function updateRegistry(arr_contenido){
  return true;
}
function deleteRegistry(cod_registro){
  return true;
}
function createRegistry(cod_registro) {
  return true;
}