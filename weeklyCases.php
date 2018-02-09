<?php 
include "db.php";
$thisweek=0;$lastweek=0;$twoweeks=0;$threeweeks=0;$onemonth=0;
foreach($pdo->query('SELECT * FROM case_logs') as $r){
  $timeinseconds=strtotime($r->timestamp)-time();
  if($timeinseconds > -604800){
    $thisweek++;
  }
  if($timeinseconds > -1209600 && $timeinseconds < -604800){
    $lastweek++;
  }
  if($timeinseconds > -1814400 && $timeinseconds < -1209600){
    $twoweeks++;
  }
  if($timeinseconds > -2419200 && $timeinseconds < -1814400){
    $threeweeks++;
  }
  if($timeinseconds > -3024000 && $timeinseconds < -2419200){
    $onemonth++;
  }
}
$arr=array();
$arr['thisweek'] .= $thisweek;
$arr['lastweek'] .= $lastweek;
$arr['twoweeks'] .= $twoweeks;
$arr['threeweeks'] .= $threeweeks;
$arr['onemonth'] .= $onemonth;
echo json_encode($arr);
?>