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
function fillSelectOpciones(arr_item,id_div_otions){
  
}
function fillSelectMultiplesGruposFromArray(arr_item,id_div_options,show_item_name){
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
    item['tallas'].forEach(function(item, index){
      cont_div3+='<input type="checkbox" name="'+item+'" disabled="true" class="check_talla">&nbsp<label>'+item+'</label>&nbsp&nbsp&nbsp'
    });
    div2.innerHTML=cont_div2;
    div3.innerHTML=cont_div3;
    div1.appendChild(div2);
    div1.appendChild(div3);
    contenedor_opciones=document.getElementById(id_div_options);
    contenedor_opciones.appendChild(div1);
  });
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
