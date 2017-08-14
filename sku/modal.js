$(document).ready(function() {
  $('#button_show_alert_saludo').click(function() {
    var param_modal={
      'id'            :   'div_modal_saludo',
      'title'         :   'Saludo',
      'message'       :   'Hola, Binvenido a esta pagina',
    }
    var modal_alert_saludo=show_modal_alert_confirm(param_modal);
    // modal_alert_saludo.fadeIn('2000');
  })
});

function show_modal_alert_confirm(parametros){
  var cont_modal = document.createElement('div');
  cont_modal.id=parametros['id'];
  cont_modal.className="modal_alert_confirm";
  // cont_modal.style.display='none';
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

  // var cont_close=document.createElement('div');
  // cont_close.className="close_modal"
  // cont_close.appendChild(crear_boton('OK','close',));
  // modal.appendChild(cont_close);
  cont_modal.appendChild(modal);
  document.body.appendChild(cont_modal);
  return cont_modal;
}
function crear_boton(texto,tipo,id_contenedor){ //tipo = si, no, close, submit (close es para los alert y formularios)

}
