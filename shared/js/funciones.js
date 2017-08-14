$(document).ready(function(){
    /*******   INICIO  **********/
    llenarSelect('tienda');
    $("#input_ciudad").prop('disabled', true);
    $("#button_ciudad").prop('disabled', true);

    /********   EVENTOS *********/
    $("#chb_ciudad").click(function(){                 
         if($(this).prop('checked')) {
            $('#select_tienda').children().remove(); 
             //$('#select_tienda option').remove();
             $('#tbody_promotores tr').remove();
             $("#select_tienda").prop('disabled',true);
             $("#input_ciudad").prop('disabled',false);
             $("#button_ciudad").prop('disabled',false);  
         } else {
            $('#select_tienda').children().remove();
            $("#input_ciudad").val("")
            $("#input_ciudad").prop('disabled',true);
             $("#button_ciudad").prop('disabled',true);
            resetearFiltros();
            $('#tbody_promotores tr').remove();
            $("#select_tienda").prop('disabled',false);
            llenarSelect('tienda');
       }            
    });
    $("#select_tienda").change(function(){         
         if($("#select_tienda").val()!='0'){
             resetearFiltros();
             $('#tbody_promotores tr').remove();
             cargarTabla("codigo_tienda",$("#select_tienda").val());
             $('#tbody_promotores tr').remove();
         }
    });
    $("#button_ciudad").click(function(){ 
        reportarPromotoresCiudad();        
    });
    $("#input_ciudad").keypress(function(e){   
        if(e.which == 13)
            reportarPromotoresCiudad();
    });
    $('#img_exportar').click(function(){        
        generarExcel();
    });
});

/********   FUNCIONES *********/
function reportarPromotoresCiudad(){
    if($("#input_ciudad").val().length > 2 ) {    
        resetearFiltros();
        $('#tbody_promotores tr').remove();
        cargarTabla("nombre_ciudad",$("#input_ciudad").val());
    }
}
function llenarSelect(tabla){
    $("#select_tienda").append('<option value=0></option><option value=-1>TODAS LAS TIENDAS</option>');
    var parametros = {  "solicitud" : "cargar_select", "tabla" : tabla };
    $.ajax({
        data:  parametros,
        url:   'modelo.php',
        type:  'post',
        dataType: "json",
        success: function(data){
            $.each(data, function(index){  
                var id = data[index].id;
                var value = data[index].value;   
                $("#select_tienda").append('<option value='+id+">"+value+"</option>");
            });
        },
        error: function (request, error) {
                console.log(arguments);
                alert(" Error: " + error);
        },
    });
}
function cargarTabla(campo,value){  
    var parametros = { "solicitud" : 'cargar_tabla', "campo" : campo, "value" : value };
     $.ajax({
         data:  parametros,
         url:   'modelo.php',
         type:  'post',
         dataType: "json",
         success: function(data){
             var i=1;
             var fila;
             $.each(data, function(index){              
                fila="<tr class='fila'><td>"+ i +"</td><td>"+data[index].tienda +"</td><td>"+data[index].rut +"</td><td>"+data[index].nombres +"</td><td>"+data[index].celular +"</td><td>"+data[index].telefono +"</td><td>"+data[index].email +"</td><td>"+data[index].direccion +"</td><td>"+data[index].comuna +"</td><td>"+data[index].ciudad +"</td><td>"+data[index].cumple +"</td><td>"+data[index].ultima +"</td></tr>"
                $("#tbody_promotores").append(fila);
                i++;                 
            });
        },
        error: function() {
            alert('NO EXISTEN PROMOTORAS EN TIENDA ELEGIDA');
        }
     });
}
function resetearFiltros(){
    $('.js-filter').val("");
    $('#tbody_promotores tr').css('display','table -row');
}
function generarExcel() {
    //alert("se clicqueo");
    var hoy = new Date().toJSON().slice(0,10);
    var creator = "aobandof";
    var owner = "aobandof@outlook.com";
    var subject = "Reporte Promotoras";
    var filename = "temp/promotoras_(Tienda_"+$("#select_tienda option:selected").text()+"_fecha_"+hoy+")";
    var array_tabla = new Array();
    var i=0;    
    $('#tbody_promotores tr').each(function() {
        if($(this).css('display')!='none'){
            var j=0;
            array_fila = new Array();
            $(this).children("td").each(function() {          
                array_fila[j]=$(this).text();
                j++;
            });
            array_tabla[i]=array_fila; 
            i++;
        }
    });
    var dataset = JSON.stringify(array_tabla);
    var position_title = 2;
    var content_title = "Reporte de Promotoras";
    var title_sheet = "promotoras";
    var columns = new Array('N°','TIENDA','RUT', 'NOMBRES', 'CELULAR','TELEFONO','EMAIL','DIRECCION','COMUNA', 'CIUDAD', 'CUMPLEAÑOS','F. CREACION');
    // switch a json
    $.post("ReportExcel.php", {
        creator: creator,
        owner: owner,
        subject: subject,
        filename: filename,
        dataset: dataset,
        position_title: position_title,
        content_title: content_title,
        title_sheet: title_sheet,
        columns: columns
    })
        .done(function(dataget) {
            var output = $.parseJSON(dataget);
            if (output.success) {
                document.location.href = (output.url);
            } else {
                alert("There is no response, check the post data");
            }
        });
}