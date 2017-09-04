var sku_buscado=""; // afuera del ready para hacerla global
$(document).ready(function() {
  document.getElementById('button_buscar_sku_consultar').onclick= function(){ // EVENTO CLICK PARA CARGA INICIALL AL BUSCAR SKU
    sku_buscado=document.getElementById('text_buscar_sku_consultar').value
    /* ANTES VALIDAR VALORES NO VACIOS Y TEXTO DE SKU, crear funcion que compruebe que busque desde articulo en adelante */
    if(sku_buscado!="")
      cargarBusquedaSku();
    else
      alert("Ingrese Articulo, Sku o Parte de Sku para mostrar RESULTADOS");
  }
  select_cliente_precio=document.getElementById('select_cliente_sku_consultar')
  select_cliente_precio.onchange= function(){
    parametros={ 'precio' : select_cliente_precio.value, 'sku' : sku_buscado }
    $.ajax({ url : 'sku_consultar.php', type : 'post', dataType : 'json', data : parametros,
      success : function(data) {
        if(!!data['errors'])
          console.log(data['errors']);
        else {
          console.log(data['precio']);
          if(data['precio']=="")
            document.getElementById('span_precio_sku').innerHTML="SIN RESULTADOS"; //agregamos el Precio
          else
            document.getElementById('span_precio_sku').innerHTML="$ "+data['precio'].toLocaleString('de-DE'); //agregamos el Precio
        }
      },
      error : function() {
        console.log("error");
      }
    });
  }

});
function calcularVenta(){
  var parametros = {
    'opcion'    :   'calcular_venta',
    'sku'       :   sku_buscado,
    'periodo'   :   'all',
    'cliente'   :   15,
    'tienda'    :   'all'
  };
  $.ajax({
    url     :   'sku_consultar.php',
    type    :   'post',
    dataType:   'json',
    data    :   parametros,
    success : function(data) {
      console.log(data);
    },
    error : function() {
      console.log("error");
    }
  });
}
function cargarBusquedaSku(){
  var parametros = {
    'sku'      : sku_buscado,
    'precio'   : 15,
    'stock'    : 'disponible',
    'tiendas'  : 'si'
  };
  $.ajax({
    url: 'sku_consultar.php',
    type: 'post',
    dataType: 'json',
    data: parametros,
    success : function(data) {
      if(!!data['errors'])
        console.log(data['errors']);
      else {
        // *************************************************************************************
        document.getElementById('span_precio_sku').innerHTML="$ "+data['precio'].toLocaleString('de-DE'); //agregamos el Precio
        // *************************************************************************************
        let stock_total=0;
        data['stock'].forEach(function(item,index){
          stock_total+=item['Cant'];
        });
        document.getElementById('span_stock_sku').innerHTML=stock_total; // agregamos el stock total
        // *************************************************************************************
        opciones="<option value='all'>TODAS LAS TIENDAS</option>";
        data['tiendas'].forEach(function(item,index){
          opciones+="<option value='"+item['Codigo']+"'>"+item["Nombre"]+"</option>"
        })
        document.getElementById('select_tiendas_sku_consultar').innerHTML=opciones;
      }
    },
    error : function() {
      console.log("error");
    }
  });
}
