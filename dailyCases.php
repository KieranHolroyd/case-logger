<?php 
include "db.php";
$today=0;$yesterday=0;$twodays=0;$threedays=0;$fourdays=0;
foreach($pdo->query('SELECT * FROM case_logs') as $r){
  $timeinseconds=strtotime($r->timestamp)-time();
  if($timeinseconds > -86400){
    $today++;
  }
  if($timeinseconds > -172800 && $timeinseconds < -86400){
    $yesterday++;
  }
  if($timeinseconds > -259200 && $timeinseconds < -172800){
    $twodays++;
  }
  if($timeinseconds > -345600 && $timeinseconds < -259200){
    $threedays++;
  }
  if($timeinseconds > -432000 && $timeinseconds < -345600){
    $fourdays++;
  }
}
$arr=array();
$arr['today'] .= $today;
$arr['yesterday'] .= $yesterday;
$arr['twodays'] .= $twodays;
$arr['threedays'] .= $threedays;
$arr['fourdays'] .= $fourdays;
echo json_encode($arr);
?>