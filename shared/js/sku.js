var color;
var id_cat_before_click,id_cat_after_click, id_cat_actual;
var campos_llenos=0;
$(document).ready(function() {
    cargarCategoriaCrear("div_cat_mujer")
    $(".cont_img_categoria").click(function() {
      id_cat_after_click=$(this).attr('id');
      console.log(id_cat_actual);
      console.log(id_cat_after_click);
      if(id_cat_actual!==id_cat_after_click){
        $(".cont_fila_crear_sku :input").each(function() {
          if($(this).val()!=="" && $(this).val()!==null) {
              console.log($(this).val());
              campos_llenos=1;
              return; // igual recorre todo el bucle
          }
        });
        if(campos_llenos==1){
          if(confirm("Existen campos con contenido que se perderán si cambia opción.\nDesea cambiar de Departamento")){
            campos_llenos=0;
            cargarCategoriaCrear(id_cat_after_click);
          }
        } else {
          cargarCategoriaCrear(id_cat_after_click);
        }
      }
    });
    /***************** EVENTOS PARA CARGAR INFORMACION A TABLAS DE BDX LOCAL **********/
    $('#button_cargar_tabla').click(function(event) {
        if(confirm("¿ Está seguro de cargar y reemplazar toda la información de esta tabla?")) {
            cargarTabla("aca debes mandar los select seleccionados")
        }
    });
    $(".check_sel_desel_tabla").click(function() {
        ischecked=this.checked;
        $(".check_sel_desel_tabla").parent().parent().parent().parent().children('tbody').find('tr>td>input[type=checkbox]').each(function() {
            $(this).prop('checked',ischecked);
        });
    });
    /***************** EVENTOS PARA HABILITAR ITEMS DE CAMPOS DE TABLAS **********/
    $("#table_items").hide();
    if(!!$("#ul_items>.active>a").attr('id')) // para qe no llame a la funcion cuando se encuentre en otro html
        cargarItems($("#ul_items>.active>a").attr('id')); //CARGAMOS POR DEFECTO LOS ITEMS DE CATEGORIA
    $('#ul_items a').click(function(event) {
        $("#table_items").hide();
        cargarItems($(this).attr('id'));
    });
});
function cargarItems(contenedor) {
    tabla=contenedor.substr(11,contenedor.length-11);
    var parametros = { "opcion" : 'cargar_items', 'tabla' :  tabla    };
    $.ajax({
        data  : parametros ,
        url   : 'modelo.php',
        type  : 'post',
        dataType : 'json',
        success : function(data){
            $("#div_cont_tabla_items").html(data);
            $("#table_items").fadeIn('1000');
            /**** creamos el evento despues de generar la tabla ***/
            $(".check_sel_desel_tabla").click(function() {
                ischecked=this.checked;
                $(".check_sel_desel_tabla").parent().parent().parent().parent().children('tbody').find('tr>td>input[type=checkbox]').each(function() {
                    $(this).prop('checked',ischecked);
                });
            });
        },
        error: function() {
            alert('TIEMPO DE ESPERA AGOTADO, INTENTE NUEVAMENTE');
        }
    });
}
function cargarTabla(tabla) {
    let check = document.querySelectorAll('input.check_items[type="checkbox"]:checked');
    // console.log(check);
    let tablas=Array.prototype.map.call(check, x => x.value);
    // console.log(tablas);
    var parametros = { 'opcion' : 'cargar_tabla_local',  'tablas': tablas };
    // console.log("parametros enviados");
    console.log(parametros);
    $.ajax({
        url: 'modelo.php',
        type: 'POST',
        dataType: 'json',
        data: parametros,
        success : function(data) {
            console.log(data);
            alert(data[0].mensaje);
        },
        error : function(){
            console.log("sucedió un error");
        }
    });
}
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
}
