$(document).ready(function(){
    $("#tr_head_ventas_promotoras").hide();
    $('#tr_pie_ventas_promotoras').hide();
    // $("#tr_head_ventas").hide();
    // $('#tr_pie_ventas').hide();
    $("#link_update").click(function(e) {
        if( $('#tr_head_ventas_promotoras').is (':hidden')){
            $('#tr_head_ventas_promotoras').show();
            $('#tr_pie_ventas_promotoras').show();
            $('#tr_head_ventas').hide();
            $('#tr_pie_ventas').hide();
            $(".pie").css('background-color', 'rgba(98, 6, 6, 0.79)');
            $(".pie td:first").html('VENTA TOTAL PROMOTORAS :');
            $('#tbody_ventas tr').remove();
            $('#celda_venta_total').text("");
            cargarTabla('ventas_promotoras');
        } else {
            $('#tr_head_ventas_promotoras').hide();
            $('#tr_pie_ventas_promotoras').hide();
            $('#tr_head_ventas').show();
            $('#tr_pie_ventas').show();
            $(".pie").css('background-color', '#004080');
            $(".pie td:first").html('VENTA TOTAL : ');
            $('#tbody_ventas tr').remove();
            $('#celda_venta_total').text("");
            cargarTabla('ventas');
        }
    });
    cargarTabla("ventas");

    /*******   FUNCIONES  *********/
    function cargarTabla(opcion){
         var parametros = { "opcion" : opcion };
         $.ajax({
            data:  parametros,
            url:   'modelo.php',
            type:  'post',
            dataType: "json",
            success: function(data){
                var fila;
                if(opcion=='ventas_promotoras'){
                    $("#tbody_ventas").append(data[0].cuerpo);
                    $('#tr_pie_ventas_promotoras').html(data[0].pie);
                    console.log(data[0].venta_total_mensual);
                }else {
                    $("#tbody_ventas").append(data[0].cuerpo);
                    $('#tr_pie_ventas').html(data[0].pie);
                }
            },
            error: function() {
                 alert('Cargue Nuevamente los datos');
            }
         });
    }
});
