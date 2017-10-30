$(document).ready(function(){
  el_sel_store=document.getElementById('select_store');
  el_sel_state=document.getElementById('select_state');
  el_but_consult=document.getElementById('button_consult');
  el_loading = document.getElementById('div_loading_documents');
  el_cont_table = document.getElementById('div_cont_table');
  
  //LLAMAMOS A LA FUNCION QUE CARGA EL SELECT_TIENDAS
  loadSelectStore();  

  el_but_consult.onclick = function(){
    // alert("COMENZAREMOS LA BUSQUEDA CON: tienda="+el_sel_store.value + " Y estado doc=" + el_sel_state.value);
    if(el_sel_store.value!=""  && el_sel_state.value!=""){
      showTableDocuments(el_sel_store.value, el_sel_state.value);
    }
  }
})
//FUNCION QUE OTIENE LOS OPTION DE LAS TIENDAS EN FORMATO HTML
function loadSelectStore(){
  parameters={ 'option': 'load_select_store'}
  $.ajax({ url : 'modelo.php', type : 'post', dataType : 'json', data : parameters,
    success : function(data) {
      if(!!data['errors']){
        console.log("Error al cargar tiendas de la base de datos: ");
        console.log(data['errors']);
      }else {
        if(data['options']!="SIN RESULTADOS"){
          let first_option="<option value=''>SELECCIONE LA TIENDA</option>";
          el_sel_store.innerHTML=first_option + data.options;
        }
      }
    },
    error : function() { console.log("error"); }
  });
}

function showTableDocuments(cod_store, type_doc){  
  parameters = { 'option' : 'load_table', 'cod_store': cod_store, 'type_doc': type_doc}
  console.log(parameters);
  $.ajax({
    data: parameters,
    url: 'modelo.php',
    type: 'post',
    dataType: "json",
    beforeSend: function () {
        el_loading.classList.toggle("cont_hidden");
    },
    success: function (data) {
        el_loading.classList.toggle("cont_hidden")
        el_cont_table.classList.remove('cont_hidden');
        // document.getElementById('table_documents').innerHTML = data.table;
    },
    error: function () {
        document.getElementById('table_documents').innerHTML = "";
        el_loading.classList.toggle("cont_hidden")
        el_cont_table.classList.remove('cont_hidden');
        console.log("error");
    }
  });
}