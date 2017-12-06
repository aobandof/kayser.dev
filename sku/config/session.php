<?php
require_once "require.php";
require_once "sku_db_mysqli.php";
$data=[];

if($_POST['option']=='session_start'){
  
  $user=$_POST['user'];
  $pass=$_POST['pass'];
  $query_login="SELECT user, perfil FROM usuario WHERE user='$user' AND password='$pass'";
  $arr_session=$mysqli->select($query_login,"mysqli_a_o");
  if($arr_session!==0 && $arr_session!==false){
    ///---INICIAMOS SESION
    // if( $arr_session[0]['user']=='admin' || $arr_session[0]['user']=='reviser'){ 
      session_start();
      $_SESSION['user']=$arr_session[0]['user'];
      $_SESSION['perfil']=$arr_session[0]['perfil'];
      $data['user']=$_SESSION['user'];
      $data['perfil']=$_SESSION['perfil'];
      $data['login']=true;
    // }else{
      // session_start();
      // $_SESSION['user']=$arr_session[0]['user'];
      // $_SESSION['perfil']=$arr_session[0]['perfil'];
      // $data['user']=$_SESSION['user'];
      // $data['perfil']=$_SESSION['perfil'];
      // $data['login']=false;//true;
    //}
  }
  else {
     $data['login']=false;
  }
  echo json_encode($data);
}
if($_GET['option']=='session_end'){
  session_start();
  $_SESSION['user']='';
  $_SESSION['perfil']='';
  unset($_SESSION);
  session_destroy();
  // echo "destrullo la sesion";
  header("Location: ../index.php");
}
