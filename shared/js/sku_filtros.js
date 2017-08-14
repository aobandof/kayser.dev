var obj_filtros=new Object();
$(document).ready(function() {
    //alert("INICIANDO...");
    $(".cont_botonera").hide();
    $(".cont_contenido").hide();
    cargarSelect("select_grupo");//llamamos a la funcion cargar select
    cargarSelect("select_sub_grupo");
    cargarSelect("select_sub_sub_grupo");
    cargarSelect("select_prenda");
    $(".cont_botonera").css('visibility', 'visible');
    $(".cont_botonera").fadeIn(500);

    /****** PARA EL GIF CARGANDO ********/
    var cargando = $("#div_cargando");
    cargando.hide();
    $(document).ajaxStart(function() {
        cargando.show();
        $(".dataTables_filter").hide();//para ocultar el buscar si se hace otra consulta con la datatable creada
        $(".dt-buttons").hide();
    });
    $(document).ajaxStop(function() {
        cargando.hide();
        $(".dataTables_filter").show();//muestra el buscar datatable
        $(".dt-buttons").show();
    });/**************************************/

    $("#img_filtrar").click(function(){
        obj_filtros={};//vaciamos el objeto
        var all_empty=true;
        $(".cont_filtro :input").each(function() {
            if($(this).val().trim()!==""){
                all_empty=false;
                obj_filtros[$(this).attr('name')]=$(this).val();
            }
        });
        if(all_empty!==true){
            if($("#select_item").val()=="sku") {
                $('#tbody_sku tr').remove();
                cargarTablaFiltrada('table_sku')

            } else
                cargarTablaFiltrada('table_articulos')
        }
    });
});
function cargarSelect(select){
    var parametros = {  "opcion" : "cargar_select", "select" : select };
    $.ajax({
        url: 'modelo.php',
        type: 'POST',
        dataType: 'json',
        data: parametros,
        success: function(data){
            $("#" + select).append(data);
        },
        error: function() {
            alert('Error Cargando Select ' + select);
        }
    })
}
function cargarTablaFiltrada(table){
    obj_filtros['opcion']="cargar_tabla_filtrada";
    // console.log(obj_filtros);
    $.ajax({
        data : obj_filtros,
        url  : 'modelo.php',
        type : 'post',
        dataType : 'json',
        success: function(data){
            if(data[0].respuesta==="ERRORES"){
                var mensaje="ERRORES EN BASE DE DATOS:\n\n";
                for (i=1; i<data.length; i++)
                    mensaje+="ESTADO: "+data[i].SQLSTATE +"\tCODIGO: "+data[i].CODIGO+"\tMENSAJE: "+data[i].MESSAGE+"\n";
                alert(mensaje);
            }else {
                if(data[0].cuerpo=="SIN RESULTADOS"){
                    alert("SIN RESULTADOS");
                    console.log(data[0].consulta);
                }else {
                    //alert(data[0].consulta);
                    console.log(data[0].cantidad);
                    var cuerpo = data[0].cuerpo;
                    $("#tbody_sku").append(cuerpo);
                    $(".cont_contenido").show();
                    $('#table_sku').css('visibility', 'visible');
                    $("#table_sku").fadeIn('700');
                    //console.log(data);

                    var table = $('#table_sku').DataTable({
                        dom: 'Bfrtip',
                        buttons: [{
                          extend: 'excelHtml5',
                          text:      '<i class="fa fa-file-excel-o"></i>',
                          titleAttr: 'Exportar Excel',
                          className: 'exportExcel',
                          filename: 'Nt.Ventas_export'
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
                            "infoEmpty": "Ningun despacho a mostrar",
                            "sLengthMenu": "Mostrar _MENU_ Registros",
                            "emptyTable":     "Sin datos para mostrar",
                            "infoFiltered":   "(Filtros Obtenidos de _MAX_ entradas)",
                            "zeroRecords":    "Filtro no encontrado"
                      }
                    });
                }
            }
        },
        error: function() {
            alert('NO SE PUDIERON MOSTRAR LOS DATOS, SI EL ERROR PERSISTE, POR FAVOR INFORMAR  A INFORMATICA');
        }
    });
}
