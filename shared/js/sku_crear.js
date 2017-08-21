var color;
var id_cat_before_click,id_cat_after_click, id_cat_actual;
var campos_llenos;
$(document).ready(function() {
  // showModalAlert('Mensaje de Bienvenida','Hola!, Empieza a crear tu SKU', 'div_modal_alert1');//esta funcion esta en otro archivo: modal.js
  $('#select_sku_color').selectpicker();
  $(".opcion_config").click(function() {
    $("#div_crud_item").css('visibility','visible' );
  });
  $("#select_item_crud").change(function() {
    $("#div_tabla_item>tbody_div").html('');
    $("#div_tabla_item").css('visibility', 'visible');
  });
  $("#img_close_crud_item").click(function() {
    /* Act on the event */
    $("#select_item_crud").val("");
    $("#div_crud_item").css('visibility','hidden');
    $("#div_tabla_item>tbody_div").html('');
    $("#div_tabla_item").css('visibility','hidden');
  });
  cargarCategoriaCrear("div_cat_dama");
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
          document.getElementById("div_sel_opciones").innerHTML="";
          cargarCategoriaCrear(id_cat_after_click);
        }
      }else { cargarCategoriaCrear(id_cat_after_click); }
    }
  });
  $("#select_sku_subdpto").change(function() { cargarSelectsSku('Subdpto', $(this).val()) });
  $("#select_sku_prenda").change(function() { cargarSelectsSku('Kayser_SEASON', $(this).val()) });
});
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
  $.ajax({
    url: 'modelo_sku_crear.php',
    type: 'post',
    dataType: 'json',
    data: parametros,
    beforeSend : function () {
    },
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
            data[i].options.forEach(function(item,index){ optito+="<option value='" + item['id'] +"'>" + item['name'] + "</option>"; });
            $("select[name='"+data[i].tabla+"']").html("<option value=''></option>"+ optito);
          }
        }
      }
    },
    error: function() {
      console.log("error");
    }
  });
}
