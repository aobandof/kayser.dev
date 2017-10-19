var excel_columns_default = {
  'RecordKey': { 'default': 1, 'column': 'A' },
  'ForceSelectionOfSerialNumber': { 'default': 'tNO', 'column': 'D' },
  'GLMethod': { 'default': 'C', 'column': 'F' },
  'InventoryItem': { 'default': 'tYES', 'column': 'G' },
  'IsPhantom': { 'default': 'tNO', 'column': 'H' },
  'IssueMethod': { 'default': 'M', 'column': 'I' },
  'SalUnitMsr': { 'default': '1', 'column': 'J' },
  'ManageStockByWarehouse': { 'default': 'tYES', 'column': 'M' },
  'PlanningSystem': { 'default': 'M', 'column': 'N' },
  'U_APOLLO_APPGRP': { 'default': '1', 'column': 'U' },
  'U_GSP_TPVACTIVE': { 'default': 'Y', 'column': 'AD' },
  'AvgPrice': { 'default': '', 'column': 'AE' },
  'U_IDDiseno': { 'default': '', 'column': 'AG' }
}
var excel_columns = {
  'ItemCode': 'B',
  'BarCode': 'C',
  'ForeignName': 'E',
  'ItemName': 'K',
  'ItmsGrpCod': 'L',
  'SWW': 'O',
  'U_APOLLO_SEG1': 'P',
  'U_APOLLO_SEG2': 'Q',
  'U_APOLLO_SSEG3': 'R',
  'U_APOLLO_SEG3': 'S',
  'U_APOLLO_SEASON': 'T',
  'U_APOLLO_SSEG3VO': 'V',
  'U_MARCA': 'X',
  'U_EVD': 'Y',
  'U_MATERIAL': 'Z',
  'U_ESTILO': 'AA',
  'U_SUBGRUPO1': 'AB',
  'U_APOLLO_COO': 'AC',
  'U_APOLLO_DIV': 'AF',
  'U_IDCopa': 'AH',
  'U_FILA': 'AI',
  'U_APOLLO_S_GROUP': 'AJ',
  'U_GSP_SECTION': 'AK'
}
var articulos_values=[];
var sku_values=[];
var itemname, val_article;


//FUNCTION PARA CREAR LOS CODIGOS SKU en un array
function getCodesSku() {
  colores = document.getElementById('select_sku_color').value;
  tallas = document.getElementById('span_tallas_chosen').innerHTML.split(',');
  console.log(tallas);
}
//FUNCION PARA SETEAR LOS VALORES DE LOS ARRAYS QUE SE USARANA PARA CREAR LOS EXCEL
function setValues(){
  console.log(code_dpto,name_dpto); //code_dpto se cargara cuando
  ele_marca=document.getElementById('select_marca');
  ele_subdpto=document.getElementById('select_sku_subdpto');
  ele_prenda = document.getElementById('select_sku_prenda');
  ele_categoria=document.getElementById('select_sku_categoria');
  ele_presentacion=document.getElementById('select_sku_presentacion');
  ele_material=document.getElementById('select_sku_material');
  arr_color=[];//ver de que se compone este array
  arr_talla=[];//aun esta pendiente que hacer con esto
  ele_tprenda=document.getElementById('select_sku_tprenda');
  ele_tcatalogo=document.getElementById('select_sku_tcatalogo');
  ele_grupo_uso=document.getElementById('select_sku_grupo_uso');
  ele_caracteristica = document.getElementById('txa_sku_caracteristicas');
  ele_composicion = document.getElementById('select_sku_composicion');
  else_peso = document.getElementById('txt_sku_peso');
  

  articulo_values = {
    'ForeignName': { 'val': ele_caracteristica.value }, // caracteristica
    'ItemName': { 'val': itemname }, //nombre
    'ItmsGrpCod': { 'cod': code_dpto, 'val': name_dpto }, //dpto
    'SWW': { 'val': ele_prenda.options[ele_prenda.selectedIndex].text }, //prenda (deprecated)    
    'U_APOLLO_SEG1': { 'val': val_article },  //codigo articulo
    'U_APOLLO_SEG3': { 'cod': '', 'val': '' }, // familia talla
    'U_APOLLO_SEASON': { 'cod': ele_prenda.value, 'val': ele_prenda.options[ele_prenda.selectedIndex].text }, //prenda  
    'U_MARCA': { 'cod': ele_marca.value, 'val': ele_marca.options[ele_marca.selectedIndex].text }, //marca    
    'U_EVD': { 'cod': ele_tprenda.value, 'val': ele_tprenda.options[ele_tprenda.selectedIndex].text  }, //temporada
    'U_MATERIAL': { 'cod': ele_material.value, 'val': ele_material.options[ele_material.selectedIndex].text  }, //material
    'U_ESTILO': { 'cod': ele_grupo_uso.value, 'val': ele_grupo_uso.options[ele_grupo_uso.selectedIndex].text  }, //grupo uso
    'U_SUBGRUPO1': { 'cod': ele_subdpto.value, 'val': ele_subdpto.options[ele_subdpto.selectedIndex].text  }, //supdpto
    'U_APOLLO_COO': { 'cod': ele_composicion.value, 'val': ele_composicion.options[ele_composicion.selectedIndex].text  }, //composicion 
    'U_APOLLO_DIV': { 'cod': ele_categoria.value, 'val': ele_categoria.options[ele_categoria.selectedIndex].text  }, //categoria
    'U_FILA': { 'cod': ele_presentacion.value, 'val': ele_presentacion.options[ele_presentacion.selectedIndex].text  }, //presentacion
    'U_APOLLO_S_GROUP': { 'cod': ele_tcatalogo.value, 'val': ele_tcatalogo.options[ele_tcatalogo.selectedIndex].text  }, //temporada catalogo
  }

  console.log(articulo_values);
  
  sku_values = {
    'ItemCode': { 'cod': '', 'val': '' }, //codigo sku
    'BarCode': { 'val': '' }, //barcode ean13
    'U_APOLLO_SEG2': { 'cod': '', 'val': '' }, //color
    'U_APOLLO_SSEG3': { 'val': '' }, //talla
    'U_APOLLO_SSEG3VO': { 'val': '' }, //orden de talla
    'U_IDCopa': { 'val': '' }, //copa
    'U_GSP_SECTION': { 'val': '' }, //copa
  }
}
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
  val_article = document.getElementById('txt_sku_prefijo').value + '.' + document.getElementById('txt_sku_correlativo').value;
  itemname = val_article + '-' + document.getElementById('txt_sku_descripcion').value;
  // console.log(itemname);
  let body_modal = document.querySelector('#div_preview_save .body_modal'); //referenciamos al body del modal
  let article = document.createElement('div'); //este es el componente articulo a mostrar
  let title=document.createElement('div');
      title.innerHTML="<span>"+itemname+"</span>"
  article.appendChild(title);
  let table_sku=document.createElement('div',{className:'table_article_preview'});
  let head_sku = document.createElement('div');
      head_sku.className='head_sku';
      let th_sku=document.createElement('div');      
      let th_barcode=document.createElement('div');
      let th_dun=document.createElement('div');
      th_sku.innerHTML = 'SKU';
      th_barcode.innerHTML='BARCODE';
      th_dun.innerHTML='DUN';
  head_sku.appendChild(th_sku);
  head_sku.appendChild(th_barcode);   
  head_sku.appendChild(th_dun);
  table_sku.appendChild(head_sku);
  article.appendChild(table_sku);      

  let body_sku = document.createElement('div');
  // arr_skus=getCodesSku();
  arr_skus = [  { 'sku': '50.1000-BLA-XS', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' },
                { 'sku': '50.1000-BLA-S', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' },
                { 'sku': '50.1000-BLA-M', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' },
                { 'sku': '50.1000-BLA-L', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' },
                { 'sku': '50.1000-BLA-XL', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' },
                { 'sku': '50.1000-BLA-XXL', 'barcode': 'BARCODEBARCODE', 'dun': 'DUNDUNDUNDUN' }] //ARRAY DE PRUEBA

  setValues(); //llamamos a funcion para llenar los arrays con los valores
                
  arr_skus.forEach(function(item) {

    
  });
  
  body_modal.appendChild(article);
  // console.log(body_modal);
  // let div2=document.createElement(div1);
  //...
  article.className = 'article_preview';
  title.className='title_article_preview';
  table_sku.className='table_sku_preview'; 

  // console.log(getControlDigit('780000005483'));

}