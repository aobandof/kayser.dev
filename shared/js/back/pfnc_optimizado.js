reporte= new Array();
$(document).ready(function() {
    $(".cont_filtro :input[type=checkbox]").eq(1).prop('checked', true);
    $("#sel_fecha").attr('disabled', true);
    $("#txt_rut_cliente").attr('disabled', true);
    $("#txt_nombre_cliente").attr('disabled', true);
    $("#txt_pfnc").attr('disabled', true);
    alert("iniciando...");

    $(".cont_filtro :input[type=checkbox]").change(function(e) {
        estado=$(this).prop('checked');
        $(this).parent().siblings('input,select').attr('disabled',!estado);
        $(this).parent().siblings('input,select').val("");
    });

    $("#img_filtrar").click(function(){
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
                cargarTabla();
            }
        }
    });
});

function cargarTabla(){
    //alert("entro a cargar tabla");
    var filtros = [];
    var obj_filtros=new Object();
    obj_filtros['opcion']='filtrar';
    filtros.push('opcion' + " : " + 'filtrar' );
     $(".cont_filtro :input[type!=checkbox]:enabled").each(function() {
        //alert($(this).attr("name") + " : " + $(this).val());
         filtros.push($(this).attr("name") + " : " + $(this).val() );
         obj_filtros[$(this).attr("name")]=$(this).val();
    });
    console.log(filtros);
    console.log(obj_filtros);
    $.ajax({
        data : obj_filtros,
        url  : 'modelo.php',
        type : 'post',
        dataType : 'json',
        success: function(data){
            if(data[0].prueba==='ENTRO')
                alert(data[0].valor);
            if(data[0].prueba==='NO ENTRO')
                alert(data[0].valor);
            if(data[0].respuesta==="ERRORES"){
                // var mensaje="ERRORES EN BASE DE DATOS:\n\n";
                // for (i=1; i<data.length; i++)
                //     mensaje+="ESTADO: "+data[i].SQLSTATE +"\tCODIGO: "+data[i].CODIGO+"\tMENSAJE: "+data[i].MESSAGE+"\n";
                // alert(mensaje);
            }else {

                reporte=Array.from(data);
                var filas="";
                var filas_factura="";
                console.log(reporte);
                //for(i=0;i<data.length;i++){
                $.each(data, function(index){
                    //var facturas=$.extend({},data[index].facturas);
                    facturas=data[index].facturas;
                    //console.log(facturas);
                    filas+="<tr><td rowspan='"+facturas.length+"'>"+data[index].fecha+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].rut+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].cliente+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].orden_compra+"</td>";
                    filas+="<td>"+data[index].facturas[0].numero+"</td>";
                    filas+="<td>"+data[index].facturas[0].cantidad+"</td>";
                    filas+="<td>"+data[index].facturas[0].monto+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].unidades_pedidas+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].unidades_facturadas+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+(data[index].unidades_facturadas-data[index].unidades_pedidas)+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].monto_facturado+"</td>";
                    filas+="<td rowspan='"+facturas.length+"'>"+data[index].monto_notas_credito+"</td>";
                    boton_detalle="<img src='../shared/img/mas_azul.png' alt='Detalle' class='img-responsive boton_detalle' id='"+data[index].orden_compra+"'>"
                    filas+="<td rowspan='"+facturas.length+"'>"+boton_detalle+"</td></tr>";

                    for(j=1;j<data[index].facturas.length;j++){
                        filas+="<tr><td>"+data[index].facturas[j].numero+"</td>";
                        filas+="<td>"+data[index].facturas[j].cantidad+"</td>";
                        filas+="<td>"+data[index].facturas[j].monto+"</td></tr>";
                    }
                });
                console.log(data);
            }
            console.log(filas);
            $("#tbody_ordenes_compra").append(filas);
        },
        error: function() {
            alert('NO SE ENCONTRARON RESULTADOS');
        }
    });
}
