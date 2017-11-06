$(document).ready(function(){  
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
    // el_cont_calendar1 = document.getElementById('div_cont_calendario1');
    el_cont_calendar2 = document.getElementById('div_cont_calendario2');

    document.getElementById('link_update').onclick = function () {
        console.log(this);
        if (el_select_venta.value!="busqueda"){
            console.log(el_select_venta.value);
            if (el_select_venta.value == "total") {
                el_text_calendar1 = document.getElementById('text_calendar1');
                console.log(el_text_calendar1.value);
                if (el_text_calendar1.value != '') {
                    el_cont_table.classList.add('cont_hidden');
                    cargarTabla(el_select_venta.value, el_text_calendar1.value);
                }
            } else {
                el_cont_table.classList.add('cont_hidden');
                cargarTabla(el_select_venta.value, "");
            }
        }

    }
    document.getElementById('search_sale').onclick = function () {
        el_text_calendar2 = document.getElementById('text_calendar2');
        if(el_text_calendar2.value!=''){
            el_cont_table.classList.add('cont_hidden');
            cargarTabla(el_select_venta.value,el_text_calendar2.value);
        }
    };
    cargarTabla('total',"");//inicialmente cargamos la tabla VENTAS TOTALES AL DETALLE */
    el_select_venta.onchange = function(){
        el_cont_table.classList.add('cont_hidden');
        console.log(this.value);
        switch(this.value){
            case 'total':
                el_cont_calendar2.classList.add('cont_hidden');
                this.style.backgroundColor = "#004080";
                cargarTabla(this.value,"");
                break;
            case 'promotoras':
                el_cont_calendar2.classList.add('cont_hidden');            
                this.style.backgroundColor = "#8f2e2e";
                cargarTabla(this.value,"");
                break;
            case 'busqueda': 
                el_cont_calendar2.classList.remove('cont_hidden');
                this.style.backgroundColor ="#05a872";
                break;             
        }                    
    };
});

/*******   FUNCIONES  *********/
function cargarTabla(opcion,param_show) {
    parameters = { "opcion": opcion, 'date': param_show };
    // console.log(parameters);
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
            console.log(data.query);
            // console.log(data.time);
            // console.log(data.fecha);
            // console.log(data.query);
            if(!!data.query)
                console.log(data.query);
            el_loading.classList.toggle("cont_hidden")
            el_cont_table.classList.remove('cont_hidden');
            document.getElementById('table_ventas').innerHTML = data.table;
            if(parameters['opcion']=='total'){   
                // document.getElementById('div_cont_calendario1').innerHTML = data.calendar;
                $('#datetimepicker1').datetimepicker({
                    locale: 'es',
                    focusOnShow: false, //para quitarle el foco al cuadro de texto demodo que en celulares no muestre el teclado
                    format: 'YYYY-MM-DD HH:mm:ss',
                    // sideBySide: true, //para que se pueda ver la eleccion de la hora abajo del calendario
                    minDate: '01/01/2015',
                    maxDate: moment(),
                    useCurrent: false,//para desactivar la fecha actual en el campo de texto
                    // collapse: false, //para mostrar el panel de horas debajo de el de dias
                    // sideBySide: true,  // para mostrar el panel de horas a un costado
                    // viewMode: 'years',   // para indicar que panel se muestra predeterminado, panel de: 'decades','years','months','days'              
                    widgetPositioning: {
                        horizontal: 'right',
                        vertical: 'bottom'
                    },                    
                }).on('dp.show dp.update', function () {
                    $(".datepicker-months .picker-switch").removeAttr('title').on('click', function (e) {
                        e.stopPropagation();
                    });
                });               
                // document.getElementById('button_search').onclick=function(){
                //     el_cont_table.classList.add('cont_hidden');
                //     cargarTabla("total", document.getElementById('text_calendar1').value)
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
