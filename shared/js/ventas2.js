// let ahora;
$(document).ready(function(){

    //creo que sera mejor obtener la fecha en el servidor, ya que el cliente puede tener la fecha desactualizada
    // ahora=new Date();
    // console.log(ahora);
    // console.log(ahora.getFullYear());
    // console.log(ahora.getMonth());
    // console.log(ahora.getDate());
    // console.log(ahora.getHours());
    // console.log(ahora.getMinutes());
    // console.log(ahora.getSeconds());


    $("#tr_head_ventas_promotoras").hide();
    $('#tr_pie_ventas_promotoras').hide();
    // $("#tr_head_ventas").hide();
    // $('#tr_pie_ventas').hide();
    $("#link_update").click(function(e) {
        if( $('#tr_head_ventas_promotoras').is (':hidden')){
            $('#tr_head_ventas').hide();
            $('#tr_pie_ventas').hide();
            $('#tr_head_ventas_promotoras').fadeIn(500);//show();
            $('#tr_pie_ventas_promotoras').fadeIn(500);//show();

            $(".pie td").css('background-color', 'rgba(98, 6, 6, 0.79)');
            $('#tbody_ventas tr').remove();
            cargarTabla('ventas_promotoras');
        } else {
            $('#tr_head_ventas_promotoras').hide();
            $('#tr_pie_ventas_promotoras').hide();
            $('#tr_head_ventas').fadeIn(500);//show();
            $('#tr_pie_ventas').fadeIn(500);//show();
            $(".pie td").css('background-color', '#004080');
            $('#tbody_ventas tr').remove();
            cargarTabla('ventas');
        }
    });
    cargarTabla('ventas');//inicialmente cargamos la tabla VENTAS TOTALES AL DETALLE */
    //cargarTabla("ventas_promotoras");

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
                    // alert("hasta aca el foot es rojo");
                    $('#tr_pie_ventas_promotoras').html(data[0].pie);
                    $(".pie td").css('background-color', 'rgba(98, 6, 6, 0.79)');
                    $("#tbody_ventas").append(data[0].cuerpo);
                    console.log(data[0].pie);
                }else {
                    $('#tr_pie_ventas').html(data[0].pie);
                    $(".pie td").css('background-color', '#004080');
                    $("#tbody_ventas").append(data[0].cuerpo);    
                    console.log(data[0].pie);
                }
            },
            error: function() {
                 alert('Cargue Nuevamente los datos, De no obtener respuesta, contacte con INFORMÁTICA por favor');
            }
         });
    }
});
