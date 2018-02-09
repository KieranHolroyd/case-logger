<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
  $sql="UPDATE users SET staff_team = :team WHERE id = :id";
	$id=$_POST['id'];
  $team=$_POST['team'];
  $exec = $pdo->prepare($sql);
  $exec->execute(['team' => $team, 'id' => $id]);
}
?>