<?php
session_start();
if(isset($_SESSION['user'])){
  // echo "<h1>Sitio en Mantención... </h1>";
  header("Location: menu.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="./src/css/normalize.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="./src/css/sku.css">
  <title>SKU / Articulos</title>
</head>
<body>
  <div id="div_sku_menu">
    <div class="cont_titulo">
      <span>GESTIÓN DE ARTÍCULOS Y SKUs</span>
    </div>
    <div class="cont_logo">
      <img src="./src/img/logo_kayser_azul.png" alt="">
    </div>
    <div class="cont_login">
      <input type="text" name="user" id="txt_user" class="form-control" placeholder="Usuario">
      <input type="password" name="pass" id="txt_pass" class="form-control" placeholder="Contraseña">
      <button class="btn btn-primary" id="button_login">INGRESAR</button>
    </div>
    <div class="cont_answer"></div>
  </div>


  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>  
  <script type="text/javascript">
    $(document).ready(function () {
      el_txt_user= document.getElementById('txt_user');
      el_txt_pass= document.getElementById('txt_pass');
      el_txt_user.focus();
      document.getElementById('button_login').onclick=function(){
        (el_txt_user.value.trim() != '' && el_txt_pass.value.trim() != '') ? login() : alert("TIENE QUE LLENAR LOS CAMPOS");
      }
      el_txt_user.onkeydown = function(event) {
        if (event.key === 'Enter'){ (el_txt_user.value.trim() != '' && el_txt_pass.value.trim() != '') ? login() : alert("TIENE QUE LLENAR LOS CAMPOS"); }
      }
      el_txt_pass.onkeydown = function(event) {
        if (event.key === 'Enter'){ (el_txt_user.value.trim() != '' && el_txt_pass.value.trim() != '') ? login() : alert("TIENE QUE LLENAR LOS CAMPOS"); }
      }
    });
    
    function login() {
      parameters = { 'option': 'session_start', 'user': el_txt_user.value.toLowerCase(), 'pass': el_txt_pass.value };
      $.ajax({
        url: './config/session.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function () { },
        success: function (data) {
          console.log(data);
          if(data.login===true )
            location.href = "menu.php" 
          else { 
            alert('DATOS INCORRECTOS, Intente Nuevamente');
            el_txt_pass.value=""; el_txt_user.focus(); 
          }          
        },
        error: function () { console.log('error'); }
      });
    }
  </script>
</body>
</html>-->