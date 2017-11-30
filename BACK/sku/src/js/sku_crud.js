///--- FUNCION QUE CARGA LA TABLA SECCION CRUD EN EL MODAL (Dpto, Subdpto, Marca, Prenda, ...)
function cargarTablaSeccion(tabla) {
  //INICIALMENTE REMOVEMOS LAS CELDAS DE LA CABCERA Y LAS FILAS DE LA TABLA EXISTENTES
  fila_head=document.getElementById('div_head_tr_items')
  while (fila_head.firstChild) { fila_head.removeChild(fila_head.firstChild); }
  body=document.getElementById('div_tbody');
  while (body.firstChild) { body.removeChild(body.firstChild); }
  var parametros = { 'option' : 'cargar_seccion', 'nom_tabla' :  tabla };
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parametros,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success : function(data) {
      // el_div_loader_full.classList.remove('cont_hidden');
      if(!!data.errors){
        console.log(data.errors);
      }else {
        //creamos las celdas para las columnas
        data.cabeceras.forEach(function(item,index){
          div_celda=document.createElement('div');
          if(item=="Nombre")
            div_celda.className="th col";
          else
            div_celda.className="th col-1 col-lg-1";
          div_celda.innerHTML=item;
          fila_head.appendChild(div_celda);
        });
        fila_head.insertAdjacentHTML('beforeend','<div class="th col-1 col-lg-1"></div><div class="th col-1 col-lg-1"></div><div class="th col-1 col-lg-1"></div><div class="">&nbsp&nbsp&nbsp</div>')

        //ahora crearemos las filas con celdas para el tbody_div
        data.filas.forEach(function(item,index){
          div_fila=document.createElement('div');
          div_fila.className="row tr";
          !!item['Codigo']? codigo=item['Codigo'] : codigo=item['Nombre'];
          for (var index in item ) {
            div_celda=document.createElement('div');
            if(index=="Codigo")
              div_celda.className="td col-1 col-lg-1 col not_editable";
            else if(index=="Nombre")
              div_celda.className="td col editable";
            else
              div_celda.className="td col-1 col-lg-1 editable";
            div_celda.id=codigo+'_'+index;
            div_celda.innerHTML=item[index];
            div_fila.appendChild(div_celda);
          }
          celdas_img='<div class="td col-1 col-lg-1"><img src="../shared/img/save.png" alt="" disabled class="icon_fila icon_save disabled" id="img_save_'+codigo+'"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/edit.png" alt="" class="icon_fila icon_edit" id="img_edit_'+codigo+'"><img src="../shared/img/edit_cancel.png" alt="" class="icon_fila icon_edit_cancel invisible" id=""></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/delete.png" alt="" class="icon_fila icon_delete" id="img_delete_'+codigo+'"></div>';
          div_fila.insertAdjacentHTML('beforeend',celdas_img);
          body.appendChild(div_fila);
        })
        // *** CREAMOS LOS EVENTOS PARA LOS ICONOS CREADOS ***/
        var contenido_original=[];
        var contenido_actualizar=[];
        var contenido_guardar=[];
        var contenido_parametro=[];

        document.querySelectorAll(".icon_save").forEach(elemento => elemento.style.pointerEvents = "none");//desactivamos el evento click
        // ###################   EVENTO CLICK PARA LOS ICON_SAVE ##############################
        document.querySelectorAll(".icon_save").forEach(elemento => elemento.onclick = function() {
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1).toLocaleLowerCase();
            contenido_actualizar[keycita] = el.firstChild.value.toLocaleUpperCase();
          });
          if(contenido_actualizar['Nombre']!=""){
            if(confirm("¿Desea realmente continuar modificando?")) {
              codigo = (this.id).slice(9);
              updateRegistry(codigo, contenido_actualizar, function (resultado) {
                if(resultado===true){
                  for (var index in contenido_actualizar)
                    contenido_original[index] = contenido_actualizar[index]; //cargamos el contenido actualizado al contenido original                  
                  let img_save=document.getElementById('img_save_'+codigo);
                  img_save.style.pointerEvents = "none";
                  img_save.classList.toggle("disabled");
                  img_save.parentNode.nextSibling.lastChild.classList.toggle('invisible'); //hacemos visible los demas iconos de esta fila
                  img_save.parentNode.nextSibling.firstChild.classList.toggle('invisible'); //hacemos visible los demas iconos de esta fila
                  img_save.parentNode.parentNode.querySelectorAll('.editable').forEach(function (el) { //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
                    keycita = (el.id).slice((el.id).indexOf("_") + 1).toLocaleLowerCase(); //obtenemos el id de las celdas editables para agregar a esta celda, el valor actualizado correspondiente
                    el.innerHTML = contenido_original[keycita]; //cargamos el contenido origignal, actualizado a las celdas
                  });
                  contenido_original.length = 0; //vaciamos este arreglo
                  contenido_actualizar.length = 0; //vaciamos este arreglo
                  getAllNodesEqualType(img_save.parentNode.nextSibling.firstChild, 2, '.icon_edit, .icon_delete').forEach(function (ele) {
                    ele.style.pointerEvents = "auto";
                    ele.classList.toggle("disabled");
                  });
                  img_save.parentNode.parentNode.classList.toggle("editing"); // quitamos esta clase a la fila para devolverle el fondo normal
                  document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
                  document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
                  alert("REGISTRO ACTUALIZADO");
                }else 
                  alert("NO SE PUDO ACTUALIZAR");   
                //referenciamos a el elemento img_save que produde este evento guardar
              });
            }
          }
          else {
            alert('Campo Nombre o Codigo son necesarios para actualizar');
          }
        });
        // ###################   EVENTO CLICK PARA LOS ICON_EDIT ##############################
        document.querySelectorAll(".icon_edit").forEach(elemento => elemento.onclick = function() {
          contenido_original.length=0;
          contenido_actualizar.length=0;
          this.classList.toggle("invisible");//ocultamos el icon_edit
          this.nextSibling.classList.toggle("invisible");//mostramos el icon_edit_cancel
          this.parentNode.parentNode.classList.toggle("editing"); // agregamos esta clase a la fila para cambiarle el fondo
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){ //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
            keycita=(el.id).slice((el.id).indexOf("_")+1);
            contenido_original[keycita]=el.innerHTML;
            el.innerHTML="<input type='text' value='"+contenido_original[keycita]+"'/>";
          });
          this.parentNode.previousSibling.firstChild.classList.toggle("disabled"); // QUITAMOS LA CLASE disabled al icon_save
          this.parentNode.previousSibling.firstChild.style.pointerEvents = "auto"; // activamos el evento click en el icon_save
          getAllNodesEqualType(this,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "none";
            ele.classList.toggle("disabled");
          })
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "none"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo

        });
        // ###################   EVENTO CLICK PARA LOS ICON_EDIT_CANCEL ########################
        document.querySelectorAll(".icon_edit_cancel").forEach(elemento => elemento.onclick = function() {
          this.parentNode.parentNode.querySelectorAll('.editable').forEach(function(el){
            keycita=(el.id).slice((el.id).indexOf("_")+1)
            el.innerHTML=contenido_original[keycita];
          });
          this.parentNode.parentNode.classList.toggle('editing'); // le quitamos a la fila la clase qe le cambia el background-color
          this.classList.toggle("invisible"); // ocultamos este icon_edit_cancel
          this.previousSibling.classList.toggle('invisible'); // mostramos icon_edit
          this.parentNode.previousSibling.firstChild.classList.toggle("disabled"); // agregamos clas disabled del icon_save
          this.parentNode.previousSibling.firstChild.style.pointerEvents = "none"; // bloqueamos el evento click del icon_save
          getAllNodesEqualType(this.previousSibling,2,'.icon_edit, .icon_delete').forEach(function(ele) {
            ele.style.pointerEvents = "auto";
            ele.classList.toggle("disabled");
          })
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo

        });
        // ###################   EVENTO CLICK PARA LOS ICON_DELETE ##############################
        document.querySelectorAll(".icon_delete").forEach(elemento => elemento.onclick = function() {
          codigo=(this.id).slice(11);
          if(confirm("DESEA REALMENTE ELIMINAR ESTE REGISTRO")){
            deleteRegistry(codigo);
          }
        });
        // ###################   EVENTO CLICK PARA LOS BUTTON_NUEVO ##############################
        document.getElementById("button_nuevo_seccion").onclick = function(){
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "none"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          body=document.getElementById("div_tbody");
          fila_modelo=document.createElement("div");
          fila_modelo.className="row tr";
          !!data.cabeceras['Codigo']? codigo=data.cabeceras['Codigo'] : codigo=data.cabeceras['Nombre']; //aunque ahora todas las tablas tienen codigo
          (data.cabeceras).forEach(function(item,index){
            div_celda=document.createElement("div");
            if(item=="Codigo")
              div_celda.className="td col-1 col-lg-1 col not_editable";
            else {
              if(item=="Nombre")
                div_celda.className="td col editable";
              else
                div_celda.className="td col-1 col-lg-1 editable";
              div_celda.innerHTML="<input type='text' value=''/>";
            }
            div_celda.id='N_'+item;
            fila_modelo.appendChild(div_celda);
          })
          celdas_img='<div class="td col-1 col-lg-1"><img src="../shared/img/save.png" alt="" class="icon_fila icon_save" id="img_save_N"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"></div>';
          celdas_img+='<div class="td col-1 col-lg-1"><img src="../shared/img/cancel.ico" alt="" class="icon_fila" id="img_cancel_N"></div>';
          fila_modelo.insertAdjacentHTML('beforeend',celdas_img);
          body.insertBefore(fila_modelo,body.firstChild);
          body.querySelector('.editable').firstChild.focus();
          /***** agregamos el evento click al icono guardar y cancel de esta fila nueva  ****/
          document.getElementById("img_save_N").onclick = function(){
            this.parentNode.parentNode.querySelectorAll('.editable').forEach(function (el) { //RECORREMOS TODAS LAS CELDAS QUE SON EDITABLES
              keycita = (el.id).slice((el.id).indexOf("_") + 1).toLocaleLowerCase();;
              contenido_guardar[keycita] = el.firstChild.value.toLocaleUpperCase();
            });
            if (contenido_guardar['Nombre'] != "") {
              if (confirm("¿Desea realmente ingresar este NUEVO VALOR?")) {
                createRegistry(contenido_guardar);
              }
            } else {
              alert('Campo Nombre es necesario para crear un nuevo valor');
            }
            
          }
          document.getElementById('img_cancel_N').onclick=function(){
            let fila_cancel=document.getElementById('img_cancel_N').parentNode.parentNode
            document.getElementById("div_tbody").removeChild(fila_cancel);
            document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
            document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          }
        }
      }
    },
    error: function() {
      console.log("error");
      // el_div_loader_full.classList.remove('cont_hidden');
    }
  });
}

//FUNCION QUE OBTIENE TODOS LOS HERMANOS, pasandode los siguientes parametros:
//  nodo actual: El nodo capturado, de quien hay que encontrar sus hijos
//  alcance:     Hasta que padres buscamos,
//  selector: es para limitar la busqueda, seleccionando solo las que los selectores indiquen
//  si alcance=0, buscaremos hasta los hermanos, si alcance=1 buscaremos hermanos y primos hermanos, alcance=2 buscaremos, hermanos, primos hermanos y primos lejanos; y asi sucesivamentes
function getAllNodesEqualType(nodo,alcance,selector){
  let cousinsList=[];
  if(!selector || selector==''){
    selector=nodo.tagName;
  }
  if (alcance==0)
    cousins=nodo.parentNode.querySelectorAll(selector);
  else if(alcance==1)
    cousins=nodo.parentNode.parentNode.querySelectorAll(selector);
  else if (alcance==2)
    cousins=nodo.parentNode.parentNode.parentNode.querySelectorAll(selector);
  else
    cousins=nodo.parentNode.parentNode.parentNode.parentNode.querySelectorAll(selector);
  cousins.forEach( function(cous) {
    if(cous!==nodo)
      cousinsList.push(cous)
  })
  // console.log(cousinsList);
  return cousinsList;
}

///--- FUNCION PARA ACTUALIZAR EL REGISTRO
function updateRegistry(cod_registro, arr_contenido, callback) {
  let resultado;
  var parameters = new Object();
  parameters['option'] = 'update_item';
  parameters['table'] = item_crud_selected;
  parameters['id'] = cod_registro;
  for (var key in arr_contenido) {
    parameters[key] = arr_contenido[key];
  }
  console.log(parameters);
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden'); */ },
    success: function(data){
      // console.log("datos desde api: ",data);
      // el_div_loader_full.classList.remove('cont_hidden');
      if (!!data.errors)
        console.log("Errores encontrados:"+data.errors);
      if (data.update === true) {
        resultado=true;                
      }else {
        console.log(data.result);
        resultado=false;
      }
        callback(resultado);
    },
    error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/ }
  });
}
//FUNCION PARA ELIMINAR REGISTRO
function deleteRegistry(cod_registro){
  var parameters={'option': 'delete_item','table': item_crud_selected, 'id':cod_registro}
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success: function(data){
      // el_div_loader_full.classList.remove('cont_hidden');
      if (!!data.errors)
        console.log("Errores encontrados:".data.errors);
      if (data.result === 1) {
        cargarTablaSeccion(item_crud_selected);//funcion recursiva que vuelve a cargar la tabla  
        alert("REGISTRO ELIMINADO SATISFACTORIAMENTE")
      } else {
        console.log(data.result);
        alert("NO SE PUDO ELIMINAR EL REGISTRO");
      }  
    },
    error: function () { console.log('error'); /*el_div_loader_full.classList.remove('cont_hidden');*/}
  });
  return true;
}
//FUNCION PARA CREAR REGISTRO ENVIANDO PARAMETROS A LA API Y RECIBIENDO LA RESPUESTA DE QUERY.
function createRegistry(arr_contenido) {
  var parameters = new Object();
  parameters['option'] = 'create_item';
  parameters['table'] = item_crud_selected;
  for (var key in arr_contenido) {
    parameters[key] = arr_contenido[key];
  }
  console.log(parameters);  
  $.ajax({ url: './models/sku_seccion_crud.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { /*el_div_loader_full.classList.add('cont_hidden');*/ },
    success: function(data){
      if(!!data.errors)
        console.log("Errores encontrados:".data.errors);
      if(data.insert===true){
          document.getElementById("button_nuevo_seccion").style.pointerEvents = "auto"; // desactivamos el evento click en el boton nuevo
          document.getElementById("button_nuevo_seccion").classList.toggle("disabled"); // deshabilitamos el boton nuevo
          cargarTablaSeccion(item_crud_selected);//funcion recursiva que vuelve a cargar la tabla  
          alert("REGISTRO CREADO SATISFACTORIAMENTE");               
      }else {
        console.log(data.repeated);
        alert("UNO DE DE LOS VALORES INGRESADOS YA EXISTE Y NO PUEDE REPETIRSE");   
      }        
    },
    error: function() {
      console.log("error");
      // el_div_loader_full.classList.remove('cont_hidden');
    }    
  });
  return true;
}
