$(document).ready(function () {
  $('#datetimepicker1').datetimepicker({
    locale: 'es',
    focusOnShow: false,
    format: 'YYYY-MM-DD',
    widgetPositioning: {
      horizontal: 'left',
      vertical: 'bottom'
    }
  });
  $('#datetimepicker2').datetimepicker({
    locale: 'es',
    focusOnShow: false,
    format: 'YYYY-MM-DD',
    widgetPositioning: {
      horizontal: 'left',
      vertical: 'bottom'
    }
  });
  el_loading = document.getElementById('no_integrado_loader_full');
  el_txt_from=document.getElementById('text_calendar1');
  el_txt_to = document.getElementById('text_calendar2');
  document.getElementById('button_search_errors').onclick = function(btn){
    renderTable( function(result) {
      document.getElementById('div_table_no_integrado').innerHTML = result;
    });
  }
})

function renderTable(callback){
  parameters = { 'from': el_txt_from.value, 'to': el_txt_to.value }
  $.ajax({
    url: 'modelo.php', type: 'post', dataType: 'json', data: parameters,
    beforeSend: function () { el_loading.classList.toggle("cont_hidden"); },
    success: function (data) {
      el_loading.classList.toggle("cont_hidden");
      console.log(data);
      callback(data.table);
    },
    error: function () { console.log('error'); el_loading.classList.toggle("cont_hidden"); }
  });
}
