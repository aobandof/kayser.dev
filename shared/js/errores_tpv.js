document.getElementById('button_export_analysis').onclick=function () {
  location.href = "../errores_tpv/errores_tpv.php?option=analisis";
}
document.getElementById('button_export_detail').onclick = function () {
  // location.href = "../errores_tpv/errores_tpv.php?option=detalle";
  window.open('../errores_tpv/errores_tpv.php?option=detalle');
  // window.open('../errores_tpv/errores_tpv.php?option=cabecera');
  location.href = "../errores_tpv/errores_tpv.php?option=cabecera";
}

