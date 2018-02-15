const el_btn_article_filter = document.getElementById('button_article_filter');
const el_txt_article_filter = document.getElementById('txt_article_filter');
const el_btn_dun_new = document.getElementById('button_dun_new');
const el_btn_dun_edit = document.getElementById('button_dun_edit');
const el_btn_dun_delete = document.getElementById('button_dun_delete');
const el_div_dun_gestion_header_title = document.querySelector('#div_dun_gestion>.header_title')
const el_txt_dun_altura = document.getElementById('txt_dun_altura');
const el_txt_dun_anchura = document.getElementById('txt_dun_anchura');
const el_txt_dun_largo = document.getElementById('txt_dun_largo');
const el_txt_dun_cantidad = document.getElementById('txt_dun_cantidad');
const el_txt_dun_medida = document.getElementById('txt_dun_medida');
const el_btn_dun_guardar = document.getElementById('button_dun_guardar');
const el_btn_dun_cancelar = document.getElementById('button_dun_cancelar');
const el_div_loader_full = document.getElementById('div_loader_full');
var operation=''; //create or update
var article_search;

$(document).ready(function () {
  el_txt_article_filter.focus();
  disableAllDiv("div_dun_gestion");

  /////----- EVENTO SELECCIONAR Y DESELECCIONAR TODAS LAS FILAS DE LA TABLA -----/////
  document.getElementById('chb_all_dun').onchange = function(chb) {
    document.querySelectorAll(".chb_table").forEach(function (chbt) {
      chbt.checked = chb.target.checked;
      (chb.target.checked === true) ? chbt.parentNode.parentNode.classList.add('row_checked'): chbt.parentNode.parentNode.classList.remove('row_checked')
    })
  }
  /////----- EVENTO PARA MOSTRAR EL o LOS ARTICULOS FILTRADOS EN LA TABLA -----/////
  el_btn_article_filter.onclick = function(btn){
    article_filter = el_txt_article_filter.value;
    if(article_filter.length>=2){
      article_search = el_txt_article_filter.value;
      parameters = { 'option': 'read', 'filter': el_txt_article_filter.value };
      ajaxCargarTabla(parameters);      
    }else{
      alert("EL FILTRO TIENE QUE CONTENER ALMENOS 2 CARACTERES")
    }     
  }
  /////----- EVENTO PARA GESTIONAR NUEVOS REGISTRO -----/////
  el_btn_dun_new.onclick = function(btn){
    let checked = false;
    document.querySelectorAll(".chb_table").forEach(function (chbt) {
      if(chbt.checked === true){
        checked = true;
        return;
      }
    })
    if(checked === true){
      operation = 'create';      
      el_div_dun_gestion_header_title.innerHTML = "<span>REGISTRO DE NUEVOS DUNs</span>";  
      disableAllDiv("div_dun_list") //DESHABILITAMOS EL PANEL TABLA DUNS FILTRADOS
      enableAllDiv("div_dun_gestion");
      el_txt_dun_altura.focus();      
    }else{
      alert("TIENE QUE SELECCIONAR ALGUN SKU PARA TRABAJAR")
    }    
  }
  /////----- EVENTO PARA GESTIONAR EDICION -----/////
  el_btn_dun_edit.onclick = function (btn) {
    let checked = false;
    document.querySelectorAll(".chb_table").forEach(function (chbt) {
      if (chbt.checked === true) {
        checked = true;
        return;
      }
    })
    if (checked === true) {
      operation = 'update'      
      el_div_dun_gestion_header_title.innerHTML = "<span>EDICION DE DUNs EXISTENTES</span>";
      disableAllDiv("div_dun_list") //DESHABILITAMOS EL PANEL TABLA DUNS FILTRADOS
      enableAllDiv("div_dun_gestion");
      el_txt_dun_altura.focus();
    } else {
      alert("TIENE QUE SELECCIONAR ALGUN SKU PARA TRABAJAR")
    }      
  }
  /////----- EVENTO PARA ELIMINAR REGISTROS SELECCOINADOS -----/////
  el_btn_dun_delete.onclick = function (btn) {
    /////----- EN ESTE CASO PARA COMPROBAR QUE EXISTEN FILAS SELECCIONADAS, RECORREREMOS TALES FILAS E IREMOS GUARDANDO LOS BARCODES EN UN ARRAY
    /////----- DESPUES COMPARAMOS LA LONGITUD DEL ARRAY Y SI ESTA VACIO ENTONCES MOSTRAMOS EL ALERT
    let arr_barcodes = [];
    document.querySelectorAll(".chb_table").forEach(function (chbt) {
      if (chbt.checked === true) {
        arr_barcodes.push(chbt.parentNode.parentNode.id);
      }
    })
    if (arr_barcodes.length>0) {   
      if(confirm("CONFIRMA ELIMINAR LOS REGISTROS")){
        parameters = { option : 'delete', barcodes : arr_barcodes.slice() }
        ajaxDeleteDuns(parameters)
      }
    } else {
      alert("TIENE QUE SELECCIONAR ALGUN SKU PARA TRABAJAR")
    }      
  }  
  /////----- EVENTO PARA GUARDAR O EDITAR DUNS -----/////
  el_btn_dun_guardar.onclick = function(btn){
    let empty = false;
    document.querySelectorAll('.input_dun_gestion').forEach (function(txt){
      if(txt.value.trim()=="" ){
        empty=true;
        return;
      }
    })
    if(empty===false){
      operation === 'create' ? message = "CONFIRMA REALIZAR EL REGISTRO DE NUEVOS DUNs" : message = 'CONFIRMA ACTUALIZAR DUNs EXISTENTES';
      if (confirm(message)) {
        let arr_skus=[];
        let arr_barcodes=[];
        if (operation === 'create'){ 
          document.querySelectorAll(".chb_table").forEach(function (chbt) {
            if(chbt.checked === true){
              father = chbt.parentNode.parentNode
              arr_skus.push(father.firstChild.innerHTML)
              arr_barcodes.push(father.id);
            }
          })
          arr_duns = getArrayDun(arr_barcodes);
          parameters = {
            option: 'create',
            skus: arr_skus.slice(),
            duns: arr_duns.slice(),
            barcodes: arr_barcodes.slice(),
            altura: el_txt_dun_altura.value.toString(),
            anchura: el_txt_dun_anchura.value.toString(),
            largo: el_txt_dun_largo.value.toString(),
            cantidad: el_txt_dun_cantidad.value.toString(),
            medida: el_txt_dun_medida.value.toUpperCase()
          }
        }else{
          document.querySelectorAll(".chb_table").forEach(function (chbt) {
            if (chbt.checked === true) {
              father = chbt.parentNode.parentNode
              arr_barcodes.push(father.id);
            }
          })
          parameters = {
            option: 'update',
            barcodes: arr_barcodes.slice(),
            altura: el_txt_dun_altura.value.toString(),
            anchura: el_txt_dun_anchura.value.toString(),
            largo: el_txt_dun_largo.value.toString(),
            cantidad: el_txt_dun_cantidad.value.toString(),
            medida: el_txt_dun_medida.value.toUpperCase()
          }
        }
        resetControlsText();
        disableAllDiv("div_dun_gestion");
        enableAllDiv("div_dun_list");
        operation === 'create' ? ajaxGuardarDuns(parameters) : ajaxActualizarDuns(parameters) 
      }
    }else{
      alert("TONDOS LOS CAMPOS SON NECESARIOS")
    }
  }
  /////----- EVENTO PARA CANCELAR EL INGRESO O EDICION DE DUN Y VOLVER AL PANEL DUN LIST -----/////
  el_btn_dun_cancelar.onclick = function(btn){
    resetControlsText();
    disableAllDiv("div_dun_gestion");
    enableAllDiv("div_dun_list");
  }
  /*******************  EVENTO PARA CERRAR SESION ************/
  document.getElementById('button_dun_session_close').onclick = function () {
    if (confirm("¿CONFIRMA CERRAR LA SESION?")) {
      location.href = "./config/session.php?option=session_end";
    }
  }
});

function disableAllDiv(selector_father){
  selectors="#"+selector_father + " *"
  document.querySelectorAll(selectors).forEach(function (el) { el.disabled = true; });
}
function enableAllDiv(selector_father) {
  selectors = "#" + selector_father + " *"
  document.querySelectorAll(selectors).forEach(function (el) { el.disabled = false; });
}
function resetControlsText(){
  document.querySelector("#div_dun_gestion>.header_title").innerHTML = "GESTION DE DUNs"
  document.querySelectorAll(".input_dun_gestion").forEach(function (el) { el.value = ""; });
  el_txt_dun_medida.value="CAJA";
}

function getArrayDun(codebars) {
  let dun = [];
  for (index in codebars) {
    step1 = 1 + codebars[index].slice(0, -1);
    arr_elements = step1.split('');
    arr_factor = arr_elements.map((n, i) => {
      return (i % 2 === 0) ? n * 3 : n * 1
    }) //ITERA y DEVUELVE ELEMENTOS MULTIPLICADOS X 3 ó 1, dependiendo de la posicion (par o impar)
    sum = arr_factor.reduce((a, b) => a + b);
    sum % 10 === 0 ? sum2 = sum : sum2 = sum + (10 - (sum % 10));
    sum3 = sum2 - sum;
    dun.push(step1 + sum3);
  }
  return dun;
}

/****** LLAMADAS A LA API  *******/
function ajaxCargarTabla(params){
  $.ajax({
    url: './models/dun_crud.php',
    type: 'post',
    dataType: 'json',
    data: params,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden') },
    success: function (data) {
      el_div_loader_full.classList.add('cont_hidden');
      if (!!data.rows) {
        document.querySelector('#div_dun_list_dtable .dbody').innerHTML = data.rows;
        /////----- EVENTO PARA CAMBIAR DE COLOR LA FILA SELECCIONADA -----/////
        document.querySelectorAll(".chb_table").forEach(function (el) {
          el.onchange = function (chb) {
            chb.target.parentNode.parentNode.classList.toggle('row_checked')
          }
        })
      } else
        alert("NO EXISTEN DUN CON ESTE FILTRO");
    },
    error: function () {
      console.log('error');
      el_div_loader_full.classList.add('cont_hidden');
    }
  });
}

function ajaxGuardarDuns(params){
  console.log(params);
  $.ajax({ url: './models/dun_crud.php', type: 'post', dataType: 'json', data: params,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden') },
    success: function(data){
      el_div_loader_full.classList.add('cont_hidden');
      console.log('from api',data);
      if(!!data.refused){//SI EXISTE elementos rechazados entonces NO DEBEMOS RENDERIZARLOS
        msg = "LOS SIGUIENTES BARCODES NO PUDIERON REGISTRARSE: \n";
        for(index in data.refused){ msg+= data.refused[index] + " --- " }
        alert(msg)
      }
      for(index in data.inserted){
        idx=params.barcodes.indexOf(data.inserted[index])
        if(idx===-1) continue;
        let row_dad = document.getElementById(data.inserted[idx]);
        row_dad.childNodes[2].innerHTML = params.duns[idx];
        row_dad.childNodes[3].innerHTML = params.altura;
        row_dad.childNodes[4].innerHTML = params.anchura;
        row_dad.childNodes[5].innerHTML = params.largo; 
        row_dad.childNodes[6].innerHTML = params.cantidad;
        row_dad.childNodes[7].innerHTML = params.medida;
      }
    },
    error: function(){
      console.log('error');
      el_div_loader_full.classList.add('cont_hidden');
    }
  });
}
function ajaxActualizarDuns(params) {
  console.log(params);
  $.ajax({
    url: './models/dun_crud.php', type: 'post', dataType: 'json', data: params,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden') },
    success: function (data) {
      el_div_loader_full.classList.add('cont_hidden');
      console.log('from api', data);
      if (!!data.refused) {//SI EXISTE elementos rechazados entonces NO DEBEMOS RENDERIZARLOS
        msg = "LOS SIGUIENTES BARCODES NO PUDIERON ACTUALIZARSE: \n";
        for (index in data.refused) { msg += data.refused[index] + " --- " }
        alert(msg)
      }
      for (index in data.updated) {
        idx = params.barcodes.indexOf(data.updated[index])
        if (idx === -1) continue;
        let row_dad = document.getElementById(data.updated[idx]);
        row_dad.childNodes[3].innerHTML = params.altura;
        row_dad.childNodes[4].innerHTML = params.anchura;
        row_dad.childNodes[5].innerHTML = params.largo;
        row_dad.childNodes[6].innerHTML = params.cantidad;
        row_dad.childNodes[7].innerHTML = params.medida;
      }
    },
    error: function () {
      console.log('error');
      el_div_loader_full.classList.add('cont_hidden');
    }
  });
}
function ajaxDeleteDuns(params){
  $.ajax({
    url: './models/dun_crud.php', type: 'post', dataType: 'json', data: params,
    beforeSend: function () { el_div_loader_full.classList.remove('cont_hidden') },
    success: function (data) {
      el_div_loader_full.classList.add('cont_hidden');
      console.log('from api', data);
      if (!!data.refused) {//SI EXISTE elementos rechazados entonces NO DEBEMOS RENDERIZARLOS
        msg = "LOS SIGUIENTES BARCODES NO PUDIERON ELIMINARSE: \n";
        for (index in data.refused) { msg += data.refused[index] + " --- " }
        alert(msg)
      }
      for (index in data.deleted) {
        let row_dad = document.getElementById(data.deleted[index]);
        row_dad.innerHTML='';
        row_dad.style.display = 'none';
      }
      ajaxCargarTabla({ 'option': 'read', 'filter': article_search })        
    },
    error: function(){ 
      console.log('error'); 
      el_div_loader_full.classList.add('cont_hidden');      
    }
  });
}
