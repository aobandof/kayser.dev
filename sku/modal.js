
$(document).ready(function() {
  var confirmacion=false;
  $('#button_show_alert_saludo').click(function() {
    var param_modal={
      'id'            :   'div_modal_saludo',
      'title'         :   'Mensaje de Bienvenida',
      'message'       :   'Hola, Binvenido a esta pagina',
      'type'          :   'alert'
    }
    showModalAlertConfirm(param_modal);
  })
  $('#button_confirm').click(function() {
    var param_modal={
      'id'            :   'div_modal_confirm',
      'title'         :   'Responda si o no',
      'message'       :   'Esta seguro de avandonar esta pagina',
      'type'          :   'confirm'
    }
    if(showModalConfirm(param_modal)){

    }
  });
});
///////  FUNCION QUE CREAR EL MODAL ALERT DINAMICAENTE //////////
function showModalAlert(parametros){
  var cont_modal = document.createElement('div');
  cont_modal.id=parametros['id'];
  cont_modal.className="modal_alert_confirm";
  var modal=document.createElement('div');
  modal.className="content_modal";
  if(parametros['title']){
    var header_modal=document.createElement('div');
    header_modal.className="header_modal";
    header_modal.innerHTML="<span>"+parametros['title']+"</span>";
    modal.appendChild(header_modal);
  }
  var body_modal=document.createElement('div');
  body_modal.className="body_modal";
  body_modal.innerHTML="<p>"+parametros['message']+"</p>"
  modal.appendChild(body_modal);
  var cont_close=document.createElement('div');
  cont_close.className="close_modal";
  crear_boton('OK',cont_close,cont_modal);
  body_modal.appendChild(cont_close);
  cont_modal.appendChild(modal);
  document.body.appendChild(cont_modal);
}
///////  FUNCION QUE CREAR EL MODAL CONFIRM DINAMICAENTE  Y ... //////////
function showModalConfirm(parametros){
  var cont_modal = document.createElement('div');
  cont_modal.id=parametros['id'];
  cont_modal.className="modal_alert_confirm";
  var modal=document.createElement('div');
  modal.className="content_modal";
  if(parametros['title']){
    var header_modal=document.createElement('div');
    header_modal.className="header_modal";
    header_modal.innerHTML="<span>"+parametros['title']+"</span>";
    modal.appendChild(header_modal);
  }
  var body_modal=document.createElement('div');
  body_modal.className="body_modal";
  body_modal.innerHTML="<p>"+parametros['message']+"</p>"
  modal.appendChild(body_modal);
  var cont_close=document.createElement('div');
  cont_close.className="close_modal";
  crear_boton('SI',cont_close,cont_modal,true);
  crear_boton('NO',cont_close,cont_modal,false);
  // cont_close.appendChild(crear_boton('SI',cont_modal,true));
  // cont_close.appendChild(crear_boton('NO',cont_modal,false));
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


function setearConfirmacion(valor){
  confirmacion=valor;
}
