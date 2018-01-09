<?php
session_start();
echo json_encode($_SESSION);

if(isset($_SESSION['user']) && $_SESSION['user']=='admin'){
  echo "SI ES ADMINISTRADOR, DEBERIA VER ESTO";
}