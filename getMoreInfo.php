<?php 
include "db.php";
$id=$_POST['id'];
$sql = "SELECT * FROM case_logs WHERE id = :id";
$query = $pdo->prepare($sql);
$query->bindValue(':id', $id, PDO::PARAM_STR);
$query->execute();
$r = $query->fetch();
$report=array();
if($r->points_awarded==1){$points="Yes";} else {$points="No";}
if($r->ban_awarded==1){$ban="Yes";} else {$ban="No";}
if($r->ts_ban==2){$ts="Yes";} else {$ts="No";}
if($r->ingame_ban==2){$ig="Yes";} else {$ig="No";}
if($r->website_ban==2){$wb="Yes";} else {$wb="No";}
if($r->ban_perm==2){$perm="Yes";} else {$perm="No";}
$report['report']['id'] .= $r->id;
$report['report']['lead_staff'] .= $r->lead_staff;
$report['report']['other_staff'] .= $r->other_staff;
$report['report']['typeofreport'] .= $r->type_of_report;
$report['report']['players'] .= $r->players;
$report['report']['player_guid'] .= $r->player_guid;
$report['report']['ltpr'] .= $r->link_to_player_report;
$report['report']['doe'] .= $r->description_of_events;
$report['report']['aop'] .= $r->amount_of_points;
$report['report']['evidence'] .= $r->evidence_supplied;
$report['report']['oc'] .= $r->offence_committed;
$report['report']['bm'] .= $r->ban_message;
$report['report']['points'] .= $points;
$report['report']['banned'] .= $ban;
$report['report']['ban_length'] .= $r->ban_length;
$report['report']['ts'] .= $ts;
$report['report']['ig'] .= $ig;
$report['report']['wb'] .= $wb;
$report['report']['perm'] .= $perm;
$report['report']['timestamp'] .= $r->timestamp;
echo json_encode($report);
?>