let itemname; let code_article;
let colores_text = []; let colores_code = [];
let familia;
let tallas_text = []; let tallas_orden = [];
let skus=[]; let barcodes=[]; let duns = [];

//FUNCION QUE OBTIENE EL DIGITO VERFICADOR DE UN BARCODE EAN-13
function getControlDigit(barcode){
  arr_barcode=barcode.split('');
  sum_dig_odd=0;
  sum_dig_even=0;
  for(let i=0; i<arr_barcode.length; i++)
    i % 2 == 0 ? sum_dig_even += parseInt(arr_barcode[i]) : sum_dig_odd += parseInt(arr_barcode[i]);
  sum_result=(sum_dig_odd*3)+sum_dig_even;
  rest = sum_result % 10;
  rest==0 ? result=0 : result=10-rest
  return result;
}

///--- FUNCION QUE SOLO CREA EL ARTICULO PREVIEW Y LO AGREGA AL MODAL
function makeArticlePreview(arti,desc){ 
  id_articulo=arti;
  if(id_articulo.indexOf('.') != -1)
    id_articulo='div_'+id_articulo.replace('.','_');
  colores_code.length=0; colores_text.length=0;
  tallas_text.length=0; tallas_orden.length=0;
  skus.length=0; barcodes.length=0; duns.length=0;
  code_article=arti;
  itemname=desc;

  let body_modal = document.querySelector('#div_modal_article_creaction .body_modal'); //referenciamos al body del modal  
  let article = document.createElement('div'); //este es el componente articulo a mostrar
  let article_title = document.createElement('div');
    article_title.innerHTML = "<span>" + itemname + "</span>"
  let article_content = document.createElement('div');
  let dtable_sku = document.createElement('div');
    dtable_sku.className = 'dtable_sku_preview';
  let article_button_container = document.createElement('div');
  
  article.appendChild(article_title);
  article.appendChild(article_content);
  article_content.appendChild(dtable_sku);
  article_content.appendChild(article_button_container);

  article.id = id_articulo;
  article.className = 'article_preview'; // flex row
  article_title.className = 'title_article_preview';
  article_content.className = 'article_content'; // flex column

  ///--- BOTONES DE ARTICLE_BUTTON_CONTAINER ---
  let but_edit_detalle = document.createElement('button');
  let but_edit_color_talla = document.createElement('button');
  let but_article_delete = document.createElement('button');
  but_edit_detalle.className='btn btn-primary';
  but_edit_color_talla.className = 'btn btn-success';
  but_article_delete.className = 'btn btn-danger';
  but_edit_detalle.innerHTML="EDITAR<br>DETALLE"
  but_edit_color_talla.innerHTML="AGREGAR<br>COLOR/TALLA";
  but_article_delete.innerHTML="QUITAR<br>ARTICULO";
  article_button_container.appendChild(but_edit_detalle);
  article_button_container.appendChild(but_edit_color_talla);
  article_button_container.appendChild(but_article_delete);
  article_button_container.className = 'article_button_container'; // flex column


  /////----- CABECERA DE TABLA
  let dhead_sku = document.createElement('div');
      dhead_sku.className='dhead_sku';
      let dth_cont = document.createElement('div');
      let dth_sku=document.createElement('div');      
      let dth_barcode=document.createElement('div');
      let dth_color = document.createElement('div');
      let dth_talla = document.createElement('div');
      let dth_delete = document.createElement('div');
      // let dth_dun=document.createElement('div');
      dth_cont.innerHTML = 'N°';
      dth_sku.innerHTML = 'SKU';
      dth_barcode.innerHTML='BARCODE';
      // dth_dun.innerHTML='DUN';
      dth_color.innerHTML = 'COLOR';
      dth_talla.innerHTML = 'TALLA';
      dth_delete.innerHTML = 'DEL';
  dhead_sku.appendChild(dth_cont);
  dhead_sku.appendChild(dth_sku);
  dhead_sku.appendChild(dth_barcode);   
  // dhead_sku.appendChild(dth_dun);
  dhead_sku.appendChild(dth_color);
  dhead_sku.appendChild(dth_talla);
  dhead_sku.appendChild(dth_delete);
  dtable_sku.appendChild(dhead_sku);

  /////----- CUERPO DE TABLA
  let dbody_sku = document.createElement('div');
      dbody_sku.className='dbody_sku';
  dtable_sku.appendChild(dbody_sku);
  body_modal.insertAdjacentElement('afterbegin', article)
}
// ####### NO USAR LA SIGUIENTE FUNCION ################
//FUNCION QUE CREA EL COMPONENTE ARTCULO  que contiene LA TABLA CON LOS SKUs a GENERAR
function makeFillArticlePreview(){ 
  colores_code.length=0; colores_text.length=0;
  tallas_text.length=0; tallas_orden.length=0;
  skus.length=0; barcodes.length=0; duns.length=0;
  
  code_article = document.getElementById('txt_sku_prefijo').value + '.' + document.getElementById('txt_sku_correlativo').value;  
  itemname = code_article + '-' + document.getElementById('txt_sku_descripcion').value;

  let body_modal = document.querySelector('#div_modal_article_creaction .body_modal'); //referenciamos al body del modal  
  let article = document.createElement('div'); //este es el componente articulo a mostrar
  let article_title = document.createElement('div');
    article_title.innerHTML = "<span>" + itemname + "</span>"
  let article_content = document.createElement('div');
  let dtable_sku = document.createElement('div');
    dtable_sku.className = 'dtable_sku_preview';
  let article_button_container = document.createElement('div');
  
  article.appendChild(article_title);
  article.appendChild(article_content);
  article_content.appendChild(dtable_sku);
  article_content.appendChild(article_button_container);

  ///--- BOTONES DE ARTICLE_BUTTON_CONTAINER ---
  let but_edit_detalle = document.createElement('button');
  let but_edit_color_talla = document.createElement('button');
  let but_article_delete = document.createElement('button');
  but_edit_detalle.className='btn btn-primary';
  but_edit_color_talla.className = 'btn btn-success';
  but_article_delete.className = 'btn btn-danger';
  but_edit_detalle.innerHTML="EDITAR<br>DETALLE"
  but_edit_color_talla.innerHTML="AGREGAR<br>COLOR/TALLA";
  but_article_delete.innerHTML="SACAR<br>ARTICULO";
  article_button_container.appendChild(but_edit_detalle);
  article_button_container.appendChild(but_edit_color_talla);
  article_button_container.appendChild(but_article_delete);
    
  article.id = code_article;
  article.className = 'article_preview'; // flex row
  article_content.className = 'article_content'; // flex column
  article_button_container.className = 'article_button_container'; // flex column
  article_title.className = 'title_article_preview'; 

  /////----- CABECERA DE TABLA
  let dhead_sku = document.createElement('div');
      dhead_sku.className='dhead_sku';
      let dth_cont = document.createElement('div');
      let dth_sku=document.createElement('div');      
      let dth_barcode=document.createElement('div');
      let dth_color = document.createElement('div');
      let dth_talla = document.createElement('div');
      let dth_delete = document.createElement('div');
      // let dth_dun=document.createElement('div');
      dth_cont.innerHTML = 'N°';
      dth_sku.innerHTML = 'SKU';
      dth_barcode.innerHTML='BARCODE';
      // dth_dun.innerHTML='DUN';
      dth_color.innerHTML = 'COLOR';
      dth_talla.innerHTML = 'TALLA';
      dth_delete.innerHTML = 'DEL';
  dhead_sku.appendChild(dth_cont);
  dhead_sku.appendChild(dth_sku);
  dhead_sku.appendChild(dth_barcode);   
  // dhead_sku.appendChild(dth_dun);
  dhead_sku.appendChild(dth_color);
  dhead_sku.appendChild(dth_talla);
  dhead_sku.appendChild(dth_delete);
  dtable_sku.appendChild(dhead_sku);

  /////----- CUERPO DE TABLA
  let dbody_sku = document.createElement('div');
      dbody_sku.className='dbody_sku';
  dtable_sku.appendChild(dbody_sku);
  body_modal.insertAdjacentElement('afterbegin', article)

  /*
  ///--- OBTENEMOS 2 ARRAYS CON COLORES_CODE y COLORES_TEX que guardan los codigos y nombres respectivamente
  el_sel_colors = document.getElementById('select_sku_color');
  for (var i = 0; i < el_sel_colors.selectedOptions.length; i++)
    colores_code.push(el_sel_colors.selectedOptions[i].value);
  colores_text = document.querySelector('#div_row_colours .filter-option').innerHTML.split(',');  
  colores_text = colores_text.map(item => item.trim());
  ///--- SI LA PRENDA TIENE COPA, ENTONCES HAY QUE AGREGAR EL LA LETRA DE COPA DESPUES DE LA ABREVIATURA DEL COLOR
  ///--- para esto creamos una variable que la contenga y que sera "" en caso de no haber copa
  let copa;
  el_copa = document.getElementById('select_sku_copa')
  el_copa.value!== '' ? copa = el_copa.options[el_copa.selectedIndex].text : copa='';

  ///--- OBTENEMOS VALOR DE LA FAMILIA, ARRAY_TALLAS Y $ARRAY_ORDENES RESPECTIVAMENTE
  list_check_familias = document.querySelectorAll('.check_familia');
  for (i = 0; i < list_check_familias.length; i++)
    if (list_check_familias[i].checked == true) check_familia = list_check_familias[i];
  familia = check_familia.parentNode.parentNode.id;

  list_check_tallas = check_familia.parentNode.nextSibling.querySelectorAll('.check_talla');
  for (i = 0; i < list_check_tallas.length; i++) {
    if (list_check_tallas[i].checked) {
      checked = list_check_tallas[i].name.split('|');
      tallas_orden.push(checked[1]);
      tallas_text.push(checked[0]);
    }
  }

  ///--- AHORA OBTENEMOS EL ARRAY CON LOS CODES SKU
  leng_colores = colores_text.length;
  leng_tallas = tallas_text.length;
  for (let i = 0; i < leng_colores; i++){
    for (var j = 0; j < leng_tallas; j++){
      skus.push(code_article + '-' + colores_text[i].substr(0, 3) + copa + '-' + tallas_text[j])
      barcode = parseInt(first_barcode) + (i * leng_tallas) + j;
      barcodes.push(String(barcode) + getControlDigit(String(barcode)));
    }
  }
  ///---AHORA CREAMOS LAS FILAS Y DIBUJAMOS LOS SKU
  leng_skus = skus.length;           
  for (i=0; i<leng_skus; i++) {
    img_delete_sku_list = '<img src="../shared/img/cancel.png" alt="" class="icon_fila_tabla_modal" id="'+ skus[i] +'">'
    let dtr_sku = "<div class='dtr_sku' id='" + skus[i] + "'><div>" + (i + 1) + "</div><div>" + skus[i] + "</div><div>" + barcodes[i] + "</div><div>" + colores_text[i] + "</div><div>" + tallas_text[i] + "</div><div>" + img_delete_sku_list + "</div></div>"
    dbody_sku.insertAdjacentHTML('beforeend', dtr_sku);
  }*/


}