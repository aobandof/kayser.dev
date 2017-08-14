var enlace_exportar=document.createElement('a');
enlace_exportar.setAttribute("href","#");
enlace_exportar.setAttribute("id","a_exportar");
var img_excel = document.createElement("img");
img_excel.setAttribute("src","../shared/img/excel_logo_azul.png");
enlace_exportar.appendChild(img_excel);

$(document).ready(function() {
  $("#table_despacho").hide();
  cargarTabla();
});

function cargarTabla(){
  var parametros = { "solicitud" : 'cargar_tabla' };
  console.log(parametros);
  $.ajax({
      data:  parametros,
      url:   'modelo.php',
      type:  'post',
      dataType: "json",
      success: function(data){
        var fila;
        if(data[0].ERRORES==="SI"){
           var mensaje="ERRORES EN BASE DE DATOS:\n\n";
           for (i=1; i<data.length; i++){
             mensaje+="ESTADO: "+data[i].SQLSTATE +"\tCODIGO: "+data[i].CODIGO+"\tMENSAJE: "+data[i].MESSAGE+"\n";
           }
           alert(mensaje);
        }
        else {
          for (i=0; i<data.length-1; i++){
            if(data[i].dias_transcurridos>=6)
               fila="<tr class='fila'><td>"+data[i].nombre_tienda+"</td><td>"+data[i].codigo_tienda+"</td><td>"+data[i].ultimo_despacho+"</td><td>"+data[i].dias_transcurridos+"</td><td class='celda_alerta'>ALERTA</td></tr>";
            else {
              if(data[i].dias_transcurridos === null ){
                if(data[i].ultimo_despacho===null)
                    fila="<tr class='fila'><td>"+data[i].nombre_tienda+"</td><td>"+data[i].codigo_tienda+"</td><td></td><td></td><td></td></tr>";
              } else {
                  if(data[i].dias_transcurridos===0)
                    fila="<tr class='fila'><td>"+data[i].nombre_tienda+"</td><td>"+data[i].codigo_tienda+"</td><td>"+data[i].ultimo_despacho+"</td><td>Despachado Hoy</td><td></td></tr>";
                  else {
                    fila="<tr class='fila'><td>"+data[i].nombre_tienda+"</td><td>"+data[i].codigo_tienda+"</td><td>"+data[i].ultimo_despacho+"</td><td>"+data[i].dias_transcurridos+"</td><td></td></tr>";
                  }
              }
            }
               $("#tbody_despacho").append(fila);
          }
          $("#table_despacho").fadeIn('1000');
          var table = $('#table_despacho').DataTable({
              dom: 'Bfrtip',
              buttons: [{
                extend: 'excelHtml5',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Exportar Excel',
                className: 'exportExcel',
                filename: 'Excel_ultimos_Despachos'
              }],
              "columnDefs": [ {
                "targets": 'no-sort',
                "orderable": false,
              } ],
              "language": {
                  "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                  },
                  "search": "Buscar:",
                  "info": "Mostrando Página _PAGE_ de _PAGES_",
                  "infoEmpty": "Ningun despacho a mostrar",
                  "sLengthMenu": "Mostrar _MENU_ Registros",
                  "emptyTable":     "Sin datos para mostrar",
                  "infoFiltered":   "(Filtros Obtenidos de _MAX_ entradas)",
                  "zeroRecords":    "Filtro no encontrado"
            }
          });
         }
      },
      error: function() {
          alert('error en el procedimiento llamado por $Ajax');
      }
  });
}
