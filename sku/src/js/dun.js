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
    if(article_filter.length>=5){
      parameters = { 'option': 'read', 'filter': el_txt_article_filter.value };
      ajaxCargarTabla(parameters);      
    }else{
      alert("EL FILTRO TIENE QUE CONTENER ALMENOS 5 CARACTERES")
    }     
  }
  /////----- EVENTO PARA INGRESAR NUEVOS DUN -----/////
  el_btn_dun_new.onclick = function(btn){
    let checked = false;
    //verificamos que existen filas seleccionadas
    document.querySelectorAll(".chb_table").forEach(function (chbt) {
      if(chbt.checked === true){
        checked = true;
        return;
      }
    })
    if(checked === true){
      el_div_dun_gestion_header_title.innerHTML = "<span>REGISTRO DE DUNs</span>";  
      disableAllDiv("div_dun_list") //DESHABILITAMOS EL PANEL TABLA DUNS FILTRADOS
      enableAllDiv("div_dun_gestion");
      el_txt_dun_altura.focus();      
    }else{
      alert("TIENE QUE SELECCIONAR ALGUN SKU PARA TRABAJAR")
    }    
  }
  /////----- EVENTO PARA GUARDAR DUNS -----/////
  el_btn_dun_guardar.onclick = function(btn){
    let empty = false;
    document.querySelectorAll('.input_dun_gestion').forEach (function(txt){
      if(txt.value.trim()=="" ){
        empty=true;
        return;
      }
    })
    if(empty===false){
      "ACA DEBEMOS ENVIAR LOS, SKU, BARCODE Y LOS TXT INGRESADOS, A LA API QUE TENDRA QUE REALIZAR EL REGISTRO Y DEVOLVERNOS EL OBJETO PARA RELLENAR LA TABLA"
      ///alistar los parametros
      let arr_skus=[];
      let arr_barcodes=[];      
      document.querySelectorAll(".chb_table").forEach(function (chbt) {
        if(chbt.checked === true){
          father = chbt.parentNode.parentNode
          console.log(father);
          arr_skus.push(father.firstChild.innerHTML)
          arr_barcodes.push(father.id);
        }
      })
      arr_duns = getArrayDun(arr_barcodes);
      resetControlsText()
      disableAllDiv("div_dun_gestion");
      enableAllDiv("div_dun_list");
      parameters = {
        option: 'create',
        skus: arr_skus.slice(),
        duns: arr_duns.slice(),
        barcodes: arr_barcodes.slice(),
        altura: el_txt_dun_altura.value,
        anchura: el_txt_dun_anchura.value,
        largo: el_txt_dun_largo.value,
        cantidad: el_txt_dun_cantidad.value,
        medida: el_txt_dun_medida.value
      }
      ajaxGuardarDuns(parameters);
    }else{
      alert("TONDOS LOS CAMPOS SON NECESARIOS")
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
  document.querySelectorAll(".input_gestion_dun").forEach(function (el) { el.value = ""; });
  el_txt_dun_medida.value="CAJA";
}

/****** LLAMADAS A LA API  *******/
function ajaxCargarTabla(params){
  $.ajax({
    url: './models/dun_crud.php',
    type: 'post',
    dataType: 'json',
    data: params,
    beforeSend: function () {},
    success: function (data) {
      // console.log(data);
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
    }
  });
}

function ajaxGuardarDuns(params){
  // console.log(params);
  $.ajax({ url: './models/dun_crud.php', type: 'post', dataType: 'json', data: params,
    beforeSend: function (){ },
    success: function(data){
      console.log('from api',data);
      document.querySelectorAll(".chb_table").forEach(function(chb){
        if(!!data.refused){//SI EXISTE elementos rechazados entonces NO DEBEMOS RENDERIZARLOS
          msg = "LOS SIGUIENTES BARCODES NO PUDIERON REGISTRARSE: \n";
          for(index in data.refused){ msg+= data.refused[index] + " --- " }
          alert(msg)
        }else {
          for(index in data.inserted){
            document.getElementById(data.inserted[index]);
            /////----- ACA NOS QUEDAMOS -----/////
            /////----- ACA NOS QUEDAMOS -----/////
            /////----- ACA NOS QUEDAMOS -----/////
            /////----- ACA NOS QUEDAMOS -----/////
            /////----- ACA NOS QUEDAMOS -----/////
            /////----- ACA NOS QUEDAMOS -----/////
          }
        }
      })
    },
    error: function(){ console.log('error'); }
  });
}

function getArrayDun(codebars){
  let dun=[];
  for(index in codebars){
    step1 = 1 + codebars[index].slice(0,-1);
    arr_elements=step1.split('');
    arr_factor=arr_elements.map( (n,i) => { return (i % 2 === 0) ? n*3 : n*1 }) //ITERA y DEVUELVE ELEMENTOS MULTIPLICADOS X 3 ó 1, dependiendo de la posicion (par o impar)
    sum = arr_factor.reduce( (a,b) => a+b );
    sum2 = sum + (10-(sum % 10));
    sum3 = sum2 - sum;
    dun.push(step1 + sum3);
  }
  return dun;
}