reporte= new Array();
$(document).ready(function() {
  $("#div_cargando, .cont_contenido ").css('visibility', 'hidden');//para que cuando inciie la pagina no muestre por milisegundos estos controles
  // $(".cont_contenido").css('visibility', 'hidden');//para que cuando inciie la pagina no muestre por milisegundos estos controles
  $("#div_cargando").hide();
  $(".cont_contenido").hide();
    /****** CARGAMOS EL SELECT AÑO DINAMICAMENTE SEGUN EL AÑO ACTUAL *****/
  var fecha = new Date();
  var anio = fecha.getFullYear();
  $("#sel_anio").append('<option value='+anio+">"+anio+"</option>"); //agregamos los ultimos 3 años DINAMICAMENTE
  $("#sel_anio").append('<option value='+(anio-1)+">"+(anio-1)+"</option>");
  $("#sel_anio").append('<option value='+(anio-2)+">"+(anio-2)+"</option>");
  $(".cont_filtro :input[type=checkbox]").eq(0).prop('checked', true);
  $(".cont_filtro :input[type=checkbox]").eq(1).prop('checked', true);
  $("#txt_orden_compra").attr('disabled', true);
  $("#txt_rut_cliente").attr('disabled', true);
  $("#txt_nombre_cliente").attr('disabled', true);
  $("#txt_pfnc").attr('disabled', true);
  //inicializamos evento change en los chexboxes
  $(".cont_filtro :input[type=checkbox]").change(function(e) {
      estado=$(this).prop('checked');
      $(this).parent().siblings('input,select').attr('disabled',!estado);
      $(this).parent().siblings('input,select').val("");
  });
  //inicializamos evento click en ell boton filtrar para mostrar la tabla
  $("#img_filtrar").click(function(){
      $("#div_cargando, .cont_contenido ").css('visibility', 'visible');//LOS VOLVEMOS VISIBLE
      $('#tbody_ordenes_compra tr').remove();
      if ($(".cont_filtro :input[type=checkbox]:checked").length > 0){
          var existe=false;
          $(".cont_filtro :input[type!=checkbox]:enabled").each(function() {
              if($(this).val()==""){
                  existe=true;
                  return false;//para salir del bucle apenas encuentre un campos vacio
              }
          });
          if(existe===true){
              alert("TODOS LOS FILTROS ACTIVADOS TIENEN QUE TENER DATOS");
          }else {
						var obj_filtros = new Object();
						obj_filtros['opcion'] = 'filtrar';
						$(".cont_filtro :input[type!=checkbox]:enabled").each(function () {
							obj_filtros[$(this).attr("name")] = $(this).val();
						});
						console.log(obj_filtros);
					  ajaxDrawTable(obj_filtros);			  
        }
      }
  });
});

function ajaxDrawTable(param){
	$.ajax({
		data: param, url: 'modelo.php', type: 'post', dataType: 'json',
		beforeSend: function () {
			$(".cont_contenido").hide();
			$("#div_cargando").show();
		},
		success: function (data) {
			console.log(data);
			fa = new Date();
			name_excel = 'Nt.Ventas_export ( ' + fa.getFullYear() + '-' + (fa.getMonth() + 1) + '-' + fa.getDate() + ' ' + fa.getHours() + '.' + fa.getMinutes() + '.' + fa.getSeconds() + ' )';
			$("#div_cargando").hide('500');
			if (data == "SIN RESULTADOS") {
				alert("SIN RESULTADOS");
			} else {
				document.getElementById('tbody_ordenes_compra').innerHTML = data;
				var table = $('#table_ordenes_compra').DataTable({
					dom: 'Bfrtip',
					stateSave: false,
					buttons: [{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel-o"></i>',
						titleAttr: 'Exportar Excel',
						className: 'exportExcel',
						filename: name_excel
					}],
					retrieve: true,//para que no hay error al crear datatable cuando ya existe
					paging: false,//para que no hay error al crear datatable cuando ya existe
					"ordering": false,
					"paging": false,
					"language": {
						"paginate": {
							"previous": "Anterior",
							"next": "Siguiente"
						},
						"search": "Buscar:",
						//"info": "Mostrando Página _PAGE_ de _PAGES_",
						"info": "",
						"infoEmpty": "Ningun documento a mostrar",
						"sLengthMenu": "Mostrar _MENU_ Registros",
						"emptyTable": "Sin datos para mostrar",
						"infoFiltered": "(Filtros Obtenidos de _MAX_ entradas)",
						"zeroRecords": "Filtro no encontrado"
					}
				});
				$(".dataTables_filter").show();//muestra el buscar datatable
				$(".dt-buttons").show();
				$("#table_ordenes_compra").fadeIn('700');
				$(".cont_contenido").fadeIn('3000');
			}
			///}
		},
		error: function () {
			alert('TIEMPO DE ESPERA AGOTADO, INTENTE NUEVAMENTE');
		}
	});


}
