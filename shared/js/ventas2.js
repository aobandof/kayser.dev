$(document).ready(function(){    
    el_loading=document.getElementById("div_loading_ventas");
    el_cont_table = document.getElementById('div_cont_table_ventas');
    el_select_venta = document.getElementById('select_venta');
    cargarTabla('detalle');//inicialmente cargamos la tabla VENTAS TOTALES AL DETALLE */
    el_select_venta.onchange = function(){
        switch(this.value){
            case 'detalle': this.style.backgroundColor= "#004080";  break;
            case 'promotoras': this.style.backgroundColor = "#8f2e2efa"; break;            
        }
        cargarTabla(this.value)                
    };
});

/*******   FUNCIONES  *********/
function cargarTabla(opcion) {
    var parametros = { "opcion": opcion };
    $.ajax({
        data: parametros,
        url: 'modelo.php',
        type: 'post',
        dataType: "json",
        beforeSend: function () {
            el_loading.classList.toggle("cont_hidden");
        },
        success: function (data) {
            console.log(data);
            el_loading.classList.toggle("cont_hidden")
            document.getElementById('table_ventas').innerHTML = data.table;
        },
        error: function () {
            // //  alert('Cargue Nuevamente la página, De no obtener respuesta, Por favor contactar a INFORMATICA');
            el_loading.classList.toggle("cont_hidden")
            console.log("error");
        }
    });
}
