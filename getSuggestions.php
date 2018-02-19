<?php 
include "db.php";
$arr=array();
$i=1;
foreach($pdo->query('SELECT * FROM suggestions ORDER BY id DESC') as $r){
  $arr[$i]['id'] .= $r->id;
  $arr[$i]['name'] .= $r->name;
  $arr[$i]['suggestion'] .= $r->suggestion;
	$i++;
}
echo json_encode($arr);
?>