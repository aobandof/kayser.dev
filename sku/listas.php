<?php
session_start();
if(!isset($_SESSION['user'])){
  header("Location: ./index.php");
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
  <link rel="stylesheet" href="./src/css/styles.css">
  <link rel="stylesheet" href="./src/css/sku.css">
  <title></title>
</head>
<body>
  <div id="div_sku_listas">
    <div class="">
      <span>LISTAS PENDIENTES DE REVISION Y CARGA A SAP</span>
      <a href="menu.php"><button class="btn btn-warning">VOLVER AL MENU</button></a>
    </div>
    <div class="dtable">
      <div class="dhead">
        <div>Cod.</div><div>Iniciada por</div><div>Creada por</div><div>Revisada por</div><div>cant. SKUs</div><div>Ver</div>
      </div>
      <div class="dbody"></div>      
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>  
  <script type="text/javascript">   
    $(document).ready(function () { 
      parameters={ 'option': 'show_lists'};
      $.ajax({ url: './models/sku_lista.php', type: 'post', dataType: 'json', data: parameters,
        beforeSend: function (){ },
        success: function(data){
          console.log(data);
          if(!!data.rows) {
            document.querySelector('#div_sku_listas .dbody').innerHTML=data.rows;
            // document.querySelectorAll('.icon_dtable').forEach(function(icon){
            //   icon.onclick = function(){
            //     location.href = "crear.php?option=ver_lista&lista="+icon.id;
            //   }           
            // });
          }else
            alert("NO EXISTEN LISTAS PENDIENTES");
        },
        error: function(){ console.log('error'); }
      });
    });
  </script>
</body>
</html>
