
$(document).ready(function() {
    $("#txt_prefijo").focus();
    $("#btn_modificar").click(function(){
        if($("#txt_prefijo").val()!="" & $("#txt_correlativo").val()!=""){
            var id_doc_salida = $("#txt_prefijo").val()+"TRF"+$("#txt_correlativo").val()
            if(confirm("Desea realmente modificar el Documento: "+id_doc_salida)) {
                $("#txt_prefijo").val("");
                $("#txt_correlativo").val("");
                modificar_trf(id_doc_salida);
            }
        } else  $("#txt_prefijo").focus();
    });
    $("#txt_prefijo").keyup(function (){ this.value = (this.value + '').replace(/[^0-9]/g, '');  });
    $("#txt_correlativo").keyup(function (){ this.value = (this.value + '').replace(/[^0-9]/g, ''); });
});

function modificar_trf(codigo){
  var parametros = { "solicitud" : 'modificar_trf', "codigo" : codigo };
  console.log(parametros);
  $.ajax({
      data:  parametros,
      url:   'modelo.php',
      type:  'post',
      dataType: "json",
      success: function(data){
        if(data[0].respuesta==="ERRORES"){
             var mensaje="ERRORES EN BASE DE DATOS:\n\n";
             for (i=1; i<data.length; i++){
               mensaje+="ESTADO: "+data[i].SQLSTATE +"\tCODIGO: "+data[i].CODIGO+"\tMENSAJE: "+data[i].MESSAGE+"\n";
             }
             alert(mensaje);
      }
        else {
          alert(data[0].respuesta);
         }
      },
      error: function() {
          alert('ERROR EN EL PROCEDIMIENTO \n\nPor Favor, Informe a Informática sobre esto');
      }
  });
}
