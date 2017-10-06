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

var sku_values = {
  'ItemCode': { 'cod': '', 'val': '' }, //codigo sku
  'BarCode': { 'val': '' }, //barcode ean13
  'U_APOLLO_SEG2': { 'cod': '', 'val': '' }, //color
  'U_APOLLO_SSEG3': { 'val': '' }, //talla
  'U_APOLLO_SSEG3VO': { 'val': '' }, //orden de talla
  'U_IDCopa': { 'val': '' }, //copa
  'U_GSP_SECTION': { 'val': '' }, //copa
}

function makeFillArticlePreview(){
  articulo = document.getElementById('txt_sku_caracteristicas').value + '.' + document.getElementById('txt_sku_prefijo').value;
  itemname = articulo + '-' + document.getElementById('txt_sku_descripcion');

  var articulo_values = {
    'ForeignName': { 'val': document.getElementById('txt_sku_caracteristicas').value }, // caracteristica
    'ItemName': { 'val': itemname }, //nombre
    'ItmsGrpCod': { 'cod': '', 'val': '' }, //dpto
    'SWW': { 'cod': '', 'val': '' }, //prenda (deprecated)    
    'U_APOLLO_SEG1': { 'cod': '', 'val': '' },  //codigo articulo
    'U_APOLLO_SEG3': { 'cod': '', 'val': '' }, // familia talla
    'U_APOLLO_SEASON': { 'cod': '', 'val': '' }, //prenda  
    'U_MARCA': { 'cod': '', 'val': '' }, //marca    
    'U_EVD': { 'cod': '', 'val': '' }, //temporada
    'U_MATERIAL': { 'cod': '', 'val': '' }, //material
    'U_ESTILO': { 'cod': '', 'val': '' }, //grupo uso
    'U_SUBGRUPO1': { 'cod': '', 'val': '' }, //supdpto
    'U_APOLLO_COO': { 'cod': '', 'val': '' }, //composicion 
    'U_APOLLO_DIV': { 'cod': '', 'val': '' }, //categoria
    'U_FILA': { 'cod': '', 'val': '' }, //presentacion
    'U_APOLLO_S_GROUP': { 'cod': '', 'val': '' }, //temporada catalogo
  }



  let body_modal = document.querySelector('#div_preview_save.body_modal');
  let div_articulo = document.createElement('div');
  let titulo=document.createElement('div');
  let tabla_sku=document.createElement('div');
  let head_tabla_sku = document.createElement('div');
  let body_tabla_sku = document.createElement('div');

  // let div2=document.createElement(div1);
  //...
  div_articulo.className = 'article_preview';
  titulo.className='title_article_preview';
  tabla_sku.className='tabla_sku_preview';

  
  

}