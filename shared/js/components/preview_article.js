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
//FUNCION QUE CREA EL COMPONENTE ARTCULO  que contiene LA TABLA CON LOS SKUs a GENERAR
function makeFillArticlePreview(){ 
  colores_code.length=0; colores_text.length=0;
  tallas_text.length=0; tallas_orden.length=0;
  skus.length=0; barcodes.length=0; duns.length=0;

  code_article = document.getElementById('txt_sku_prefijo').value + '.' + document.getElementById('txt_sku_correlativo').value;
  itemname = code_article + '-' + document.getElementById('txt_sku_descripcion').value;
  let body_modal = document.querySelector('#div_preview_save .body_modal'); //referenciamos al body del modal
  let article = document.createElement('div'); //este es el componente articulo a mostrar
  let title=document.createElement('div');
      title.innerHTML="<span>"+itemname+"</span>"
  article.appendChild(title);

  article.className = 'article_preview';
  title.className = 'title_article_preview';
    
  /////----- TABLA
  let dtable_sku=document.createElement('div');
  dtable_sku.className = 'dtable_sku_preview';
  /////----- CABECERA DE TABLA
  let dhead_sku = document.createElement('div');
      dhead_sku.className='dhead_sku';
      let dth_sku=document.createElement('div');      
      let dth_barcode=document.createElement('div');
      let dth_dun=document.createElement('div');
      dth_sku.innerHTML = 'SKU';
      dth_barcode.innerHTML='BARCODE';
      dth_dun.innerHTML='DUN';
  dhead_sku.appendChild(dth_sku);
  dhead_sku.appendChild(dth_barcode);   
  dhead_sku.appendChild(dth_dun);
  dtable_sku.appendChild(dhead_sku);
  /////----- CUERPO DE TABLA
  let dbody_sku = document.createElement('div');
      dbody_sku.className='dbody_sku';
  ///--- OBTENEMOS 2 ARRAYS CON COLORES_CODE y COLORES_TEX que guardan los codigos y nombres respectivamente
  el_sel_colors = document.getElementById('select_sku_color');
  // console.log(el_sel_colors);
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
  // console.log('first_barcode: ',first_barcode);
  leng_colores = colores_text.length;
  leng_tallas = tallas_text.length;
  for (let i = 0; i < leng_colores; i++){
    for (var j = 0; j < leng_tallas; j++){
      skus.push(code_article + '-' + colores_text[i].substr(0, 3) + copa + '-' + tallas_text[j])
      barcode = parseInt(first_barcode) + (i * leng_tallas) + j;
      barcodes.push(String(barcode) + getControlDigit(String(barcode)));
    }
  }
  // console.log(familia);
  // console.log(tallas_orden);
  // console.log(tallas_text);
  // console.log(colores_code);
  // console.log(colores_text);
  // console.log(skus);
  // console.log(barcodes);

  leng_skus = skus.length;           
  for (i=0; i<leng_skus; i++) {
    let dtr_sku = "<div class='dtr_sku' id='" + skus[i] + "'><div>" + skus[i] + "</div><div>" + barcodes[i] + "</div><div>" + 'DUN_PENDIENTE' +"</div></div>"
    dbody_sku.insertAdjacentHTML('beforeend', dtr_sku);
  }
  dtable_sku.appendChild(dbody_sku);
  article.appendChild(dtable_sku);
  body_modal.insertAdjacentElement('afterbegin',article)
}