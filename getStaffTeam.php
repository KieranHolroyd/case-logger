<?php 
include "db.php";
$staff=array();
$i=1;
foreach($pdo->query('SELECT * FROM users ORDER BY rank_lvl, staff_team, username ASC') as $r){
  $staffname=$r->username;
  $staff[$i]['id'] .= $r->id;
	$staff[$i]['name'] .= $staffname;
  $staff[$i]['rank'] .= $r->rank;
  $staff[$i]['team'] .= $r->staff_team;
  $i+=1;
}
echo json_encode($staff);
?>