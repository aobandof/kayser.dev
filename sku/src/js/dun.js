$(document).ready(function () {
  const el_btn_article_filter = document.getElementById('button_article_filter');
  const el_txt_article_filter = document.getElementById('txt_article_filter');
  const el_btn_dun_new = document.getElementById('button_dun_new');
  const el_btn_dun_edit = document.getElementById('button_dun_edit');
  const el_btn_dun_delete = document.getElementById('button_dun_delete');
  const el_div_dun_gestion_header_title = document.querySelector('#div_dun_gestion>.header_title')
  el_txt_article_filter.focus();

  el_btn_article_filter.onclick = function(btn){
    // alert(btn.target.id);
    article_filter = el_txt_article_filter.value;
    if(article_filter.length>=5){
      parameters = { 'option': 'read', 'filter': el_txt_article_filter.value };
      console.log(parameters);
      $.ajax({
        url: './models/dun_crud.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function () { },
        success: function (data) {
          console.log(data);
          if (!!data.rows) {
            document.querySelector('#div_dun_list_dtable .dbody').innerHTML = data.rows;
            // document.querySelectorAll('.icon_dtable').forEach(function(icon){
            //   icon.onclick = function(){
            //     location.href = "crear.php?option=ver_lista&lista="+icon.id;
            //   }           
            // });
          } else
            alert("NO EXISTEN DUN CON ESTE FILTRO");
        },
        error: function () { console.log('error'); }
      });       
    }else{
      alert("EL FILTRO TIENE QUE CONTENER ALMENOS 5 CARACTERES")
    }
     
  }

  el_btn_dun_new.onclick = function(btn){
    disabledAllDiv("#div_dun_list *")
    el_div_dun_gestion_header_title.innerHTML="<span>INGRESO DE NUEVOS DUN</span>";
  }


});

function disabledAllDiv(selectors){
  document.querySelectorAll(selectors).forEach(function (el) {
    el.disabled = true;
  });
}
