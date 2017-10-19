let arr_tallas_letras=['XS','S','M','L','XL','XXL','XXXL'];

function sortTallasLeters(arr_to_order) {
  // idx_menor=-1;
  arr_tallas=[];
  arr_to_order.forEach((function(item){
    indice=arr_tallas_letras.indexOf(item['talla']);
    // console.log(item['talla']);
    // console.log(indice);
    // arr_bidi.push({'indice':indice, 'talla':item});
    arr_tallas[indice]=item;
  }));
  // console.log(arr_tallas);
  // console.log("array disque ordenado: ",arr_tallas);
  return(arr_tallas);

}

function sortTallas(arr_to_order){//recibe un array asociativo bidimensional con llaves nombre y orden
  arr_conjunto=[];
  arr_numeros=[];
  arr_letras=[];
  arr_otros=[];
  //inicialmente separamos el arreglo en array numeros, array letras de tallas y array otros (como talla unica, etc)
  arr_to_order.forEach(function(item, index){
    talla = item['nombre'];
    orden = item['orden'];
    if(talla % 1 == 0)
      arr_numeros.push({ 'talla': parseInt(talla), 'orden': orden });// arr_numeros.push(parseInt(talla));
    else if(arr_tallas_letras.indexOf(talla)!=-1)
      arr_letras.push({'talla':talla, 'orden':orden});//arr_letras.push(talla);
    else
      arr_otros.push({ 'talla': talla, 'orden': orden });//arr_otros.push(talla);
  })
  if(arr_otros.length>0){
    // console.log("arr_otros",arr_otros);
    arr_otros.forEach(item => arr_conjunto.push(item));
  }
  if(arr_numeros.length>0){
    // console.log("arr_numeros", arr_numeros);
    arr_numeros = arr_numeros.sort(function (a, b) {
      return a.talla - b.talla;
    });
    arr_numeros.forEach(item => arr_conjunto.push(item));
  }
  if(arr_letras.length>0){
    // console.log("arr_letras", arr_letras);
    arr_letras = sortTallasLeters(arr_letras);
    arr_letras.forEach( item => arr_conjunto.push(item));
  }    
  // console.log("array conjunto: ",arr_conjunto);
  return arr_conjunto;

}
// var arr_obj_tallas=[
//   { familia:'T01',  tallas : ['XS','S','M','L','XL'] },
//   { familia:'T02',  tallas : ['28','30','32','34','36'] },
//   { familia:'T03',  tallas : ['8','10','12','14','16'] },
//   { familia:'T05',  tallas : ['XS','S','M','L','XL','XXL','XXXL'] }
// ];
//
// var obj_arr_tallas = {
//   'T01' : ['XS','S','M','L','XL'],
//   'T02' : ['28','30','32','34','36'],
//   'T03' : ['8','10','12','14','16']
// }
// // var contenedor_opciones;
// $(document).ready(function() {
//   $("#button_llenar_select_multiple").click(function() {
//     document.getElementById("div_sel_opciones").innerHTML="";
//     // fillSelectMultipleFromObjeto(obj_arr_tallas, "div_sel_opciones",true);
//     fillSelectMultipleFromArray(arr_obj_tallas, "div_sel_opciones",false);
//   });
// });
function fillSelectMultiplesGruposFromArray(arr_item,id_div_options,show_item_name){
  // console.log(arr_item);
  arr_tallas_ordenadas=[];
  arr_item.forEach(function(item,index){
    let div1=document.createElement('div');
    let div2=document.createElement('div');
    let div3=document.createElement('div');
    div1.id=item['familia'];//esto es el index del objeto recorrido
    div1.className='cont_sel_opcion';
    div2.className='cont_familia';
    div3.className='cont_tallas';
    var cont_div2='<input type="checkbox" name="" value="" class="check_familia">'+(show_item_name? '&nbsp<label>'+item['familia']+'</label>' : "" );
    var cont_div3="";
    arr_tallas_ordenadas=sortTallas(item['tallas']);
    arr_tallas_ordenadas.forEach(function(item, index){
      cont_div3+='<input type="checkbox" name="'+(item['talla']+'|'+item['orden'])+'" disabled="true" class="check_talla">&nbsp<label>'+item['talla']+'</label>&nbsp&nbsp&nbsp'
    });
    div2.innerHTML=cont_div2;
    div3.innerHTML=cont_div3;
    div1.appendChild(div2);
    div1.appendChild(div3);
    contenedor_opciones=document.getElementById(id_div_options);
    contenedor_opciones.appendChild(div1);
  });
  $(".check_familia").change(function() {
    $("#span_tallas_chosen").text(' ');
    var valor=$(this).prop('checked');
    $(this).parent().siblings(0).children('.check_talla').attr('disabled', !valor);//habilitar/deshabilitar los chek de las tallas segun si se chekea o no la familia
    if(valor===false)
      $(this).parent().siblings(0).children('.check_talla').prop('checked',false); //si se deschekea esta familia, todas las tallas tb se deschekean
    else {
      $(this).parent().parent().siblings().each(function() {
        $(this).children('.cont_familia').children('.check_familia').prop('checked',false);
        $(this).children('.cont_tallas').children('.check_talla').prop('checked',false);
        $(this).children('.cont_tallas').children('.check_talla').attr('disabled',true);
      });
    }
  });
  $(".check_talla").click(function(){
    let tallas_chosen="";
    if ($(".check_talla:checked").length===0)
      $("#span_tallas_chosen").text(' ');
    else {
      $(".check_talla:checked").each(function () {
        solo_talla = $(this).attr('name');
        tallas_chosen += solo_talla.slice(0,solo_talla.indexOf('|')) + ",  "//agregamos al span solamente los nombres de tallas y no las valores 'orden'
      })
      $("#span_tallas_chosen").text((tallas_chosen.trim()).slice(0, -1));
    }
  });
}
function fillSelectMultiplesGruposFromObjeto(obj_tallas,id_div_options,show_item_name){
  for (var index in obj_tallas) {
    let div1=document.createElement('div');
    let div2=document.createElement('div');
    let div3=document.createElement('div');
    div1.id=index;
    div1.className='cont_sel_opcion';
    div2.className='cont_familia';
    div3.className='cont_tallas';
    var cont_div2='<input type="checkbox" name="" value="" class="check_familia">'+(show_item_name? '&nbsp<label>'+index+'</label>' : "" );;
    var cont_div3="";
    obj_tallas[index].forEach(function(item,index){
      cont_div3+='<input type="checkbox" name="'+item+'" disabled="true" class="check_talla">&nbsp<label>'+item+'</label>&nbsp&nbsp&nbsp'
    });
    div2.innerHTML=cont_div2;
    div3.innerHTML=cont_div3;
    div1.appendChild(div2);
    div1.appendChild(div3);
    contenedor_opciones=document.getElementById(id_div_options);
    contenedor_opciones.appendChild(div1);
  }
  $(".check_familia").change(function() {
    var valor=$(this).prop('checked');
    $(this).parent().siblings(0).children('.check_talla').attr('disabled', !valor);//habilitar/deshabilitar los chek de las tallas segun si se chekea o no la familia
    if(valor===false)
      $(this).parent().siblings(0).children('.check_talla').prop('checked',false); //si se deschekea esta familia, todas las tallas tb se deschekean
    else {
      $(this).parent().parent().siblings().each(function() {
        $(this).children('.cont_familia').children('.check_familia').prop('checked',false);
        $(this).children('.cont_tallas').children('.check_talla').prop('checked',false);
        $(this).children('.cont_tallas').children('.check_talla').attr('disabled',true);
      });
    }
  });
}
