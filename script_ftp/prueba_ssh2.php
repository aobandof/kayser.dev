<?php
$server="45.56.75.110";
$port=22;
$username="kayser";
$passwd="ey%T;WWv=9ko*;R541ay";
$ruta="/kayser/upload";

$connection = ssh2_connect($server, $port);
if (ssh2_auth_password($connection, $username, $passwd)) {
  $sftp = ssh2_sftp($connection);
  echo "Connection successful, uploading file now..."."\n";
  $file = 'prueba.txt';
  $contents = file_get_contents($file);
  // file_put_contents("ssh2.sftp://{$sftp}/{$file}", $contents);
file_put_contents("ssh2.sftp://$sftp/kayser/upload/$file"/*, $contents*/);
} 
else {
    echo "Unable to authenticate with server"."n";
}
?>