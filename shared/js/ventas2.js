$(document).ready(function(){  
    $('#datetimepicker1').datetimepicker({
        locale: 'es',
        focusOnShow: false,
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    el_loading=document.getElementById("div_loading_ventas");
    el_cont_table = document.getElementById('div_cont_table_ventas');
    el_select_venta = document.getElementById('select_venta');
    el_cont_calendar = document.getElementById('div_cont_calendario');

    document.getElementById('search_sale').onclick = function () {
        el_text_calendar = document.getElementById('text_calendar');
        if(el_text_calendar.value!='')
            cargarTabla(el_select_venta.value,el_text_calendar.value);
    };
    // cargarTabla('detalle',"");//inicialmente cargamos la tabla VENTAS TOTALES AL DETALLE */
    el_select_venta.onchange = function(){
        el_cont_table.classList.add('cont_hidden');
        switch(this.value){
            case 'detalle':
                el_cont_calendar.classList.add('cont_hidden');
                this.style.backgroundColor = "#004080";
                cargarTabla(this.value,"");
                break;
            case 'promotoras':
                el_cont_calendar.classList.add('cont_hidden');            
                this.style.backgroundColor = "#8f2e2e";
                cargarTabla(this.value,"");
                break;
            case 'busqueda': 
                el_cont_calendar.classList.remove('cont_hidden');
                break;             
        }
                    
    };
});

/*******   FUNCIONES  *********/
function cargarTabla(opcion,param_show) {
    (param_show != "") ? parameters = { "opcion": opcion, 'date': param_show } : parameters = { "opcion": opcion };
    console.log(parameters);
    $.ajax({
        data: parameters,
        url: 'modelo.php',
        type: 'post',
        dataType: "json",
        beforeSend: function () {
            el_loading.classList.toggle("cont_hidden");
        },
        success: function (data) {
            console.log(data);
            el_loading.classList.toggle("cont_hidden")
            el_cont_table.classList.remove('cont_hidden');
            document.getElementById('table_ventas').innerHTML = data.table;
        },
        error: function () {
            // //  alert('Cargue Nuevamente la página, De no obtener respuesta, Por favor contactar a INFORMATICA');
            el_loading.classList.toggle("cont_hidden")
            el_cont_table.classList.remove('cont_hidden');
            console.log("error");
        }
    });
}
