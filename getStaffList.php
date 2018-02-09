<?php 
include "db.php";
$staff=array();
$i=1;
foreach($pdo->query('SELECT * FROM users ORDER BY username') as $r){
  $staffname=$r->username;
	$staff[$i]['name'] .= $staffname;
  $i+=1;
}
echo json_encode($staff);
?>