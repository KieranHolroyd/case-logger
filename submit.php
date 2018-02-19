<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($logged_in['permissions']['submitReport']==1){
  $ls = $_POST['lead_staff'];
  $os = $_POST['other_staff'];
  $doe = $_POST['description_of_events'];
  $guid = $_POST['player_guid'];
  $ltpr = $_POST['link_to_player_report'];
  $oc = $_POST['offence_committed'];
  $pa = $_POST['points_awarded'];
  $aop = $_POST['ammount_of_points'];
  $es = $_POST['evidence_supplied'];
  $ba = $_POST['ban_awarded'];
  $bl = $_POST['ban_length'];
  $bm = $_POST['ban_message'];
  $ts = $_POST['ts_ban'];
  $ig = $_POST['ingame_ban'];
  $wb = $_POST['website_ban'];
  $perm = $_POST['ban_perm'];
  $players = $_POST['players'];
  $playersArray = array();
  $i=0;
    foreach($players as $players){
      $i++;
      $playersArray[$i]['type'] .= $players['type'];
      $playersArray[$i]['name'] .= $players['name'];
      $playersArray[$i]['guid'] .= $players['guid'];
    }
  $torep = $_POST['type_of_report'];
    $sql = "INSERT INTO case_logs (`lead_staff`, `other_staff`, `description_of_events`, `player_guid`, `link_to_player_report`, `offence_committed`, `points_awarded`, `amount_of_points`, `evidence_supplied`, `ban_awarded`, `ban_length`, `ban_message`, `ts_ban`, `ingame_ban`, `website_ban`, `ban_perm`, `players`, `type_of_report`) VALUES (:ls, :os, :doe, :guid, :ltpr, :oc, :pa, :aop, :es, :ba, :bl, :bm, :ts, :ig, :wb, :perm, :playersArray, :torep)";
    $query=$pdo->prepare($sql);
    $query->bindValue(':ls', $ls, PDO::PARAM_STR);
    $query->bindValue(':os', $os, PDO::PARAM_STR);
    $query->bindValue(':doe', $doe, PDO::PARAM_STR);
    $query->bindValue(':guid', $guid, PDO::PARAM_STR);
    $query->bindValue(':ltpr', $ltpr, PDO::PARAM_STR);
    $query->bindValue(':oc', $oc, PDO::PARAM_STR);
    $query->bindValue(':pa', $pa, PDO::PARAM_STR);
    $query->bindValue(':aop', $aop, PDO::PARAM_STR);
    $query->bindValue(':es', $es, PDO::PARAM_STR);
    $query->bindValue(':ba', $ba, PDO::PARAM_STR);
    $query->bindValue(':bl', $bl, PDO::PARAM_STR);
    $query->bindValue(':bm', $bm, PDO::PARAM_STR);
    $query->bindValue(':ts', $ts, PDO::PARAM_STR);
    $query->bindValue(':ig', $ig, PDO::PARAM_STR);
    $query->bindValue(':wb', $wb, PDO::PARAM_STR);
    $query->bindValue(':perm', $perm, PDO::PARAM_STR);
    $query->bindValue(':playersArray', json_encode($playersArray), PDO::PARAM_STR);
    $query->bindValue(':torep', $torep, PDO::PARAM_STR);
    $query->execute();
  	print_r($query->errorinfo());
  	print_r($query);
  } else {
    echo "Insufficient Permissions";
  }
?>