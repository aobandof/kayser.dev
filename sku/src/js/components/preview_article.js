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

  let body_modal = modal_preview_save.querySelector('.body_modal'); //referenciamos al body del modal  
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
  but_edit_detalle.className='btn btn-primary btn_edit_detalle';
  but_edit_color_talla.className = 'btn btn-success btn_add_color_talla';
  but_article_delete.className = 'btn btn-danger btn_delete_article';
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

  /////----- PIE DE TABLA CON DETALLE DE ARTICULO
  let dfoot_sku = document.createElement('div');
      det_art.className='dfoot_sku';
  dtable_sku.appendChild(dfoot_sku);

  body_modal.insertAdjacentElement('beforeend', article)  
}
