<?php
require_once "require.php";
require_once "sku_db_mysqli.php";
$data=[];

if($_GET['option']=='session_start'){
  $user=$_GET['user'];
  $pass=$_GET['pass'];
  $query_login="SELECT user, perfil FROM usuario WHERE user='$user' AND password='$pass'";
  echo $query_login."<br>";
  $arr_session=$mysqli->select($query_login,"mysqli_a_o");
  if($arr_session!=0 && $arr_session!=false){
    ///---INICIAMOS SESION
    session_start();
    $_SESSION['user']=$arr_session[0]['user'];
    $_SESSION['perfil']=$arr_session[0]['user'];
    // $data['user']=$user;
    // $data['perfil']=''
    // $data['login']=true;
  }
  else {
    $session['login']=false;
    echo "datos de inicio de session incorrectos";
  }
  // echo json_encode($data);
  echo json_encode($_SESSION);
}
if($_POST['session_end']){
}
