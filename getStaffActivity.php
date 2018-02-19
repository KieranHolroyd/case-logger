<?php 
include "db.php";
$sql="SELECT * FROM case_logs WHERE lead_staff LIKE :name OR other_staff LIKE :name ORDER BY id DESC";
$query = $pdo->prepare($sql);
$query->bindValue(':name', '%'.$_POST['id'].'%', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll();
$staffinfo=array();
$i=1;
foreach($rows as $r){
  if (strpos($r->other_staff, $_POST['id']) !== false) {
    $staffinfo['log'][$i]['other_staff'] = true;
  }
  $reporting_player=$r->players;
  $staffinfo['log'][$i]['id'] .= $r->id;
  $staffinfo['log'][$i]['doe'] .= $r->description_of_events;
  $staffinfo['log'][$i]['reporting_player']=$reporting_player;
  $i+=1;
}
echo json_encode($staffinfo);
?>