<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
  $sql="DELETE FROM users WHERE id=?";
  $exec = $pdo->prepare($sql);
  $exec->execute([$_POST['id']]);
}
?>