<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
  $sql="UPDATE users SET rank = :rank , rank_lvl = :rank_lvl , sRep = 1 , SLT = :slt WHERE id = :id";
	$id=$_POST['id'];
  $rank=$_POST['rank'];
  $slt = 0;
  if($rank==9){
  	$rankname="Trial Staff";
  } elseif($rank==8){
  	$rankname="Moderator";
  } elseif($rank==7){
  	$rankname="Administrator";
  } elseif($rank==6){
  	$rankname="Senior Administrator";
    $slt = 1;
  }
  $exec = $pdo->prepare($sql);
  $exec->execute(['rank' => $rankname, 'rank_lvl' => $rank, 'slt' => $slt, 'id' => $id]);
}
?>