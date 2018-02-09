<?php 
include "db.php";
$offset=$_POST['offset'];
if($offset <= 0){
	$offset=0;
}
$sql = "SELECT * FROM case_logs ORDER BY id DESC LIMIT 100 OFFSET $offset";
$query = $pdo->prepare($sql);
$query->execute();
$rows = $query->fetchAll();
$row_count=count($rows);
$reports=array();
$reports['info']['count'] .= $row_count;
$reports['info']['offset'] .= $offset;
$i=1;
foreach($rows as $row){
  $reporting_player=$row->players;
  $reports['caseno'][$i]['id'] .= $row->id;
	$reports['caseno'][$i]['lead_staff'] .= $row->lead_staff;
  $reports['caseno'][$i]['typeofreport'] .= $row->type_of_report;
  $reports['caseno'][$i]['ltpr'] .= $row->link_to_player_report;
  $reports['caseno'][$i]['pa'] .= $row->points_awarded;
  $reports['caseno'][$i]['ba'] .= $row->ban_awarded;
  $reports['caseno'][$i]['timestamp'] .= $row->timestamp;
  $reports['caseno'][$i]['reporting_player']=$reporting_player;
  $i+=1;
}
echo json_encode($reports);
?>