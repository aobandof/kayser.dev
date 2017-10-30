$(document).ready(function(){  
    // $('#datetimepicker1').datetimepicker({
    //     locale: 'es',
    //     focusOnShow: false,
    //     format: 'YYYY-MM-DD HH:mm:ss',
    //     widgetPositioning: {
    //         horizontal: 'right',
    //         vertical: 'bottom'
    //     }
    // });

    $('#datetimepicker2').datetimepicker({
        locale: 'es',
        focusOnShow: false,
        format: 'YYYY-MM-DD HH:mm:ss',
        // sideBySide: true, //para que se pueda ver la eleccion de la hora abajo del calendario
        // autoclose: 1,
        // weekStart: 1,
        // startView: 2,
        // todayBtn: 1,
        // todayHighlight: 1,
        // forceParse: 0,
        // minView: 2,
        // pickerPosition: "top-right"
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }

    });

    el_loading=document.getElementById("div_loading_ventas");
    el_cont_table = document.getElementById('div_cont_table_ventas');
    el_select_venta = document.getElementById('select_venta');
    el_cont_calendar = document.getElementById('div_cont_calendario');
    // document.getElementById('link_update').onclick = function () {
    //     if (el_select_venta.value!="busqueda"){
    //         el_cont_table.classList.add('cont_hidden');
    //         cargarTabla(el_select_venta.value, "");
    //     }
    // }
    document.getElementById('search_sale').onclick = function () {
        el_text_calendar = document.getElementById('text_calendar');
        if(el_text_calendar.value!=''){
            el_cont_table.classList.add('cont_hidden');
            cargarTabla(el_select_venta.value,el_text_calendar.value);
        }
    };
    cargarTabla('total',"");//inicialmente cargamos la tabla VENTAS TOTALES AL DETALLE */
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
                this.style.backgroundColor ="#05a872";
                break;             
        }
                    
    };
});

/*******   FUNCIONES  *********/
function cargarTabla(opcion,param_show) {
    // data=new Object();
    // console.log(param_show);
    parameters = { "opcion": opcion, 'date': param_show };
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
            // console.log(data.fecha);
            el_loading.classList.toggle("cont_hidden")
            el_cont_table.classList.remove('cont_hidden');
            document.getElementById('table_ventas').innerHTML = data.table;
            if(parameters['opcion']=='total'){ 
                console.log("entro");  
                // document.getElementById('div_cont_calendario1').innerHTML = data.calendar;
                $('#datetimepicker1').datetimepicker({
                    locale: 'es',
                    focusOnShow: false,
                    format: 'YYYY-MM-DD HH:mm:ss',
                    // sideBySide: true, //para que se pueda ver la eleccion de la hora abajo del calendario
                    // autoclose: 1,
                    // startView: 2,
                    // todayBtn: 1,
                    // todayHighlight: 1,
                    // forceParse: 0,
                    widgetPositioning: {
                        horizontal: 'right',
                        vertical: 'bottom'
                    }
                });
                
                // console.log(document.getElementById('button_search'));
                // document.getElementById('button_search').onclick=function(){
                //     el_cont_table.classList.add('cont_hidden');
                //     cargarTabla("total", document.getElementById('text_calendar').value)
                // };
            }
        },
        error: function () {
            // //  alert('Cargue Nuevamente la página, De no obtener respuesta, Por favor contactar a INFORMATICA');
            document.getElementById('table_ventas').innerHTML = "";
            el_loading.classList.toggle("cont_hidden")
            el_cont_table.classList.remove('cont_hidden');
            console.log("error");
        }
    });
}
