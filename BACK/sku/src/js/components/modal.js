// $(document).ready(function() {
//   alert("entro");
//   $("#select_item_crud").change(function() {
//     alert("vamos fine");
//   });
// });
///////  FUNCION QUE CREAR EL MODAL ALERT DINAMICAENTE //////////
function showModalAlert(title,message,id_cont){
  var cont_modal = document.createElement('div');
  cont_modal.id=id_cont;
  cont_modal.className="modal modal_alert";
  var modal=document.createElement('div');
  modal.className="content_modal";
  // if(title==''){
    var header_modal=document.createElement('div');
    header_modal.className="header_modal";
    header_modal.innerHTML="<span>"+title+"</span>";
    modal.appendChild(header_modal);
  // }
  var body_modal=document.createElement('div');
  body_modal.className="body_modal";
  body_modal.innerHTML="<p>"+message+"</p>"
  modal.appendChild(body_modal);
  var cont_close=document.createElement('div');
  cont_close.className="botonera_modal";
  crear_boton('OK',cont_close,cont_modal);
  body_modal.appendChild(cont_close);
  cont_modal.appendChild(modal);
  document.body.appendChild(cont_modal);
}

function crear_boton(texto,cont_añadir,contenedor_cerrar,retorno){ //tipo = si, no, close, submit (close es para los alert y formularios)
  var btn=document.createElement('button');
  btn.className="btn btn-success";
  btn.innerHTML=texto;
  var click= btn.addEventListener('click',function(){
    document.body.removeChild(contenedor_cerrar);
  });
  cont_añadir.appendChild(btn);
}

/***** FUNCIONES PARA EL MODAL CRUD ITEMS *******/
