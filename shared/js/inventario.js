$(document).ready(function () {
  el_items_menu=document.querySelectorAll(".cont_menu_item");
  el_div_inventary_content=document.getElementById('div_inventary_content');
  // document.querySelectorAll(".cont_menu_item").forEach(elemento => elemento.onclick = function () {
  el_items_menu.forEach(elemento => elemento.onclick = function () {
    showContentInventary(elemento);
  });
})
/////----- FUNCTION QUE MUESTRA EL CONTENIDO segun la PESTAÑA (TAB) ELEGIDA
function showContentInventary(el){
  // console.log(el);
  el_div_inventary_content.style.backgroundColor='rgba(91, 212, 131, 0.349)';
  el_div_inventary_content.innerHTML='';
  el.classList.add('active');
  el_items_menu.forEach(function(item){
    if(item!=el) item.classList.remove('active');
  })
  if (el.id=='div_documents') {
    $("#div_inventary_content").load("documentos_procesados.html", function () {
      el_sel_store = document.getElementById('select_store');
      el_sel_state = document.getElementById('select_state');
      el_but_consult = document.getElementById('button_consult');
      el_loading = document.getElementById('div_loading_documents');
      el_cont_table = document.getElementById('div_cont_table');
      loadSelectStore(el_sel_store);
      el_but_consult.onclick = function () {
        console.log(el_sel_store);
        console.log(el_cont_table);
        console.log(el_sel_state.value);
        if (el_sel_store.value != "" && el_sel_state.value != "") {
          el_cont_table.classList.add('cont_hidden');
          showTableDocuments(el_sel_store.value, el_sel_state.value);
        }
      }
    });
  }
  else if(el.id=='div_guides'){
    $("#div_inventary_content").load("guias_emitidas.html", function () {
      $('#datetimepicker1').datetimepicker({
        locale: 'es',
        focusOnShow: false, //para quitarle el foco al cuadro de texto demodo que en celulares no muestre el teclado
        format: 'DD/MM/YYYY',
        minDate: '01/01/2016',
        maxDate: moment(),
        useCurrent: false,//para desactivar la fecha actual en el campo de texto
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        },                    
      }).on('dp.show dp.update', function () {
          $(".datepicker-months .picker-switch").removeAttr('title').on('click', function (e) {
              e.stopPropagation();
          });
      }); 
      $('#datetimepicker2').datetimepicker({
        locale: 'es',
        focusOnShow: false, //para quitarle el foco al cuadro de texto demodo que en celulares no muestre el teclado
        format: 'YYYY-MM-DD',
        minDate: '01/01/2016',
        maxDate: moment(),
        useCurrent: false,//para desactivar la fecha actual en el campo de texto
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        },                    
      }).on('dp.show dp.update', function () {
          $(".datepicker-months .picker-switch").removeAttr('title').on('click', function (e) {
              e.stopPropagation();
          });
      });               
    });
  }
}


//FUNCION QUE OTIENE LOS OPTION DE LAS TIENDAS EN FORMATO HTML
function loadSelectStore(el_select) {
  parameters = { 'option': 'load_select_store' }
  $.ajax({
    url: 'modelo.php', type: 'post', dataType: 'json', data: parameters,
    success: function (data) {
      if (!!data['errors']) {
        console.log("Error al cargar tiendas de la base de datos: ");
        console.log(data['errors']);
      } else {
        if (data['options'] != "SIN RESULTADOS") {
          let first_option = "<option value=''>Seleccione la Tienda</option>";
          el_select.innerHTML = first_option + data.options;
        }
      }
    },
    error: function () { console.log("error"); }
  });
}

function showTableDocuments(cod_store, type_doc) {
  parameters = { 'option': 'load_table', 'cod_store': cod_store, 'type_doc': type_doc }
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

