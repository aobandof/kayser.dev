var sku_buscado=""; // afuera del ready para hacerla global
$(document).ready(function() {
  cargarTiendas();
  document.getElementById('button_buscar_sku_consultar').onclick= function(){ // EVENTO CLICK PARA CARGA INICIALL AL BUSCAR SKU
    sku_buscado=document.getElementById('text_buscar_sku_consultar').value
    /* ANTES VALIDAR VALORES NO VACIOS Y TEXTO DE SKU, crear funcion que compruebe que busque desde articulo en adelante */
    if(sku_buscado!=""){
      cargarPrecio(document.getElementById('select_cliente_sku_consultar').value);
      cargarStock(document.getElementById('select_estado_sku_consultar').value);
    }else
      alert("Ingrese Articulo, Sku o Parte de Sku para mostrar RESULTADOS");
  }
  select_estado_stock=document.getElementById('select_estado_sku_consultar');
  select_estado_stock.onchange= function(){
    cargarStock(select_estado_stock.value);
  }
  select_cliente_precio=document.getElementById('select_cliente_sku_consultar');
  select_cliente_precio.onchange= function(){
    cargarPrecio(select_cliente_precio.value);
  }
  document.querySelectorAll('.sel_for_venta').forEach(function(el){
    el.onchange=function(){ document.getElementById('span_venta_sku').innerHTML="ELIGIO " + el.value; }
  });
});
function cargarPrecio(precio){
  parametros={ 'precio' : precio, 'sku' : sku_buscado }
  $.ajax({ url : 'sku_consultar.php', type : 'post', dataType : 'json', data : parametros,
    success : function(data) {
      if(!!data['errors']){
        console.log("Error al consultar PRECIOS, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {
        if(data['precio']==="SIN RESULTADOS")
          document.getElementById('span_precio_sku').innerHTML=data['precio'];
        else
          document.getElementById('span_precio_sku').innerHTML="$ "+data['precio'].toLocaleString('de-DE'); //agregamos el Precio
      }
    },
    error : function() { console.log("error"); }
  });
}
function cargarStock(stock){
  parametros={ 'stock' : stock, 'sku': sku_buscado };
  $.ajax({ url : 'sku_consultar.php', type : 'post', dataType : 'json', data : parametros,
    success : function(data) {
      if(!!data['errors']){
        console.log("Error al consultar STOCK, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {
        if(data['stock']==="SIN RESULTADOS")
          document.getElementById('span_stock_sku').innerHTML=data['stock'];
        else{
          let stock_total=0;
          data['stock'].forEach(function(item,index){ stock_total+=item['Cant']; });
          document.getElementById('span_stock_sku').innerHTML=stock_total;
        }
      }
    },
    error : function() { console.log("error"); }
  });
}
function cargarTiendas(){
  parametros={ 'opcion': 'cargar_select_tiendas' };
  $.ajax({ url : 'sku_consultar.php', type : 'post', dataType : 'json', data : parametros,
    success : function(data) {
      if(!!data['errors']){
        console.log("Error al cargar las Tiendas, en consulta o Conexion a BDx: ");
        console.log(data['errors']);
      }else {
        opciones="<option value='all'>TODAS LAS TIENDAS</option>";
        data['tiendas'].forEach(function(item,index){
          opciones+="<option value='"+item['Codigo']+"'>"+item["Nombre"]+"</option>"
        })
        document.getElementById('select_tiendas_sku_consultar').innerHTML=opciones;
      }
    },
    error : function() { console.log("error"); }
  });
}
function calcularVenta(){
  var parametros = { 'opcion' : 'calcular_venta', 'sku' : sku_buscado, 'periodo' : 'all', 'cliente' : 15, 'tienda' : 'all' };
  $.ajax({ url : 'sku_consultar.php', type : 'post', dataType: 'json', data : parametros,
    success : function(data) {
      console.log(data);
    },
    error : function() {
      console.log("error");
    }
  });
}
