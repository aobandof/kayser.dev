$(document).ready(function(){
  document.querySelectorAll(".cont_menu_item").forEach(elemento => elemento.onclick = function (el) {
    el.classList.add('active');
    console.log(el);
    el_div_content = document.getElementById('div_inventario_content');
    el_div_content.innerHTML='';
    el_div_content.style.backgroundColor = 'rgba(91, 212, 131, 0.349)'; // this.style.backgroundColor;
    // let hermanos = getSiblings(this, this.parentNode.childNodes);

    // siblings.forEach(elemento => { elemento.style.backgroundColor = rgba(192, 192, 192, 0.5)});
    if(this.id=='div_documentos'){
      $("#div_inventario_content").load("documentos_procesados.html", function (){
        el_sel_store = document.getElementById('select_store');
        el_sel_state = document.getElementById('select_state');
        el_but_consult = document.getElementById('button_consult');
        el_loading = document.getElementById('div_loading_documents');
        el_cont_table = document.getElementById('div_cont_table');
        loadSelectStore();
        el_but_consult.onclick = function () {
          if (el_sel_store.value != "" && el_sel_state.value != "") {
            el_cont_table.classList.add('cont_hidden');
            showTableDocuments(el_sel_store.value, el_sel_state.value);
          }
        }
      });
    }
  });


  // el_sel_store=document.getElementById('select_store');
  // el_sel_state=document.getElementById('select_state');
  // el_but_consult=document.getElementById('button_consult');
  // el_loading = document.getElementById('div_loading_documents');
  // el_cont_table = document.getElementById('div_cont_table');
  
  //LLAMAMOS A LA FUNCION QUE CARGA EL SELECT_TIENDAS
  // loadSelectStore();  

  // el_but_consult.onclick = function(){
  //   // alert("COMENZAREMOS LA BUSQUEDA CON: tienda="+el_sel_store.value + " Y estado doc=" + el_sel_state.value);
  //   if(el_sel_store.value!=""  && el_sel_state.value!=""){
  //     el_cont_table.classList.add('cont_hidden');
  //     showTableDocuments(el_sel_store.value, el_sel_state.value);
  //   }
  // }
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
          let first_option="<option value=''>Seleccione la Tienda</option>";
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
      console.log(data);        
        el_loading.classList.toggle("cont_hidden")
        el_cont_table.classList.remove('cont_hidden');
        document.getElementById('table_documents').innerHTML = data.table;
        if (!!data.resp)
          alert(data.resp)
    },
    error: function () {
        document.getElementById('table_documents').innerHTML = "";
        el_loading.classList.toggle("cont_hidden")
        el_cont_table.classList.remove('cont_hidden');
        console.log("error");
    }
  });
}