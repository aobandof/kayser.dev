var arr_obj_tallas=[
  { 'T01' : ['XS','S','M','L','XL'] },
  { 'T02' : ['28','30','32','34','36'] },
  { 'T03' : ['8','10','12','14','16'] }
]; // array de objetos

var obj_arr_tallas = {
  'T01' : ['XS','S','M','L','XL'],
  'T02' : ['28','30','32','34','36'],
  'T03' : ['8','10','12','14','16']
}
// var contenedor_opciones;

$(document).ready(function() {
  $("#button_llenar_select_multiple").click(function() {
    document.getElementById("div_sel_opciones").innerHTML="";
    fillSelectMultipleFromObjeto(obj_arr_tallas, "div_sel_opciones");
  });
});
function fillSelectMultipleFromArray(arr_tallas,div_opciones_tallas){
  arr_tallas.forEach(function(item,index){
    console.log("pendiente de implementar");
  });
}

function fillSelectMultipleFromObjeto(obj_tallas,id_div_opciones){
  for (var index in obj_tallas) {
    let div1=document.createElement('div');
    let div2=document.createElement('div');
    let div3=document.createElement('div');
    div1.id=index;
    div1.className='cont_sel_opcion';
    div2.className='cont_familia';
    div3.className='cont_tallas';
    var cont_div2='<input type="checkbox" name="" value="" class="check_familia">&nbsp<label>'+index+'</label>';
    // document.getElementsByClassName("check_familia").addEventListener("click",function(){
    //   console.log(this.checked);
    // })
    var cont_div3="";
    obj_tallas[index].forEach(function(item,index){
      cont_div3+='<input type="checkbox" name="'+item+'" disabled="true" class="check_talla">&nbsp<label>'+item+'</label>&nbsp&nbsp&nbsp'
    });
    div2.innerHTML=cont_div2;
    div3.innerHTML=cont_div3;
    div1.appendChild(div2);
    div1.appendChild(div3);
    $(".check_familia").change(function() {
      var valor=$(this).prop('checked');
      $(".check_talla").prop('disabled',!valor);
      // $(".check_familia").prop('checked',false);
      // $(".check_talla").prop('checked',false);
      // $(this).prop('checked',valor)
    });
    contenedor_opciones=document.getElementById(id_div_opciones);
    contenedor_opciones.appendChild(div1);
  }
}
