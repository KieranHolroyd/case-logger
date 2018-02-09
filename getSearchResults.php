<?php 
include "db.php";
$searchquery=$_POST['query'];
$sql = "SELECT * FROM `case_logs` WHERE `lead_staff` LIKE :query OR `other_staff` LIKE :query OR `description_of_events` LIKE :query OR `player_guid` LIKE :query OR `offence_committed` LIKE :query OR `amount_of_points` LIKE :query OR `evidence_supplied` LIKE :query ORDER BY id DESC";
$query = $pdo->prepare($sql);
$query->bindValue(':query', '%'.$searchquery.'%', PDO::PARAM_STR);
$query->execute();
$rf = $query->fetchAll();
$staffinfo=array();
$i=1;
foreach($rf as $r){
  $reporting_player=$r->players;
  $staffinfo['log'][$i]['id'] .= $r->id;
  $staffinfo['log'][$i]['doe'] .= $r->description_of_events;
  $staffinfo['log'][$i]['reporting_player']=$reporting_player;
  $i+=1;
}
echo json_encode($staffinfo);
?>