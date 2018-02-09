<?php 
include "db.php";
$id=$_POST['id'];
$sql="SELECT * FROM users WHERE id = :name";
$query = $pdo->prepare($sql);
$query->bindValue(':name', $_POST['id'], PDO::PARAM_STR);
$query->execute();
$r = $query->fetch();
$staffname=$r->username;
$sql2="SELECT * FROM case_logs WHERE lead_staff LIKE :name OR other_staff LIKE :name ORDER BY id DESC";
$query2 = $pdo->prepare($sql2);
$query2->bindValue(':name', '%'.$staffname.'%', PDO::PARAM_STR);
$query2->execute();
$cases = $query2->fetchAll();
$cases_count=count($cases);
$i=0;
foreach($cases as $case){
  if((strtotime($case->timestamp)-time())>(-604800)){
    $i++;
	}
}
$staffinfo=array();
//Check for activity warnings based on current weekly case count.
if($r->rank_lvl >= 7){
	if($i<10){
  	$staffinfo['activity_warning'] .= true;
  }
}
$staffinfo['id'] .= $r->id;
$staffinfo['name'] .= $staffname;
$staffinfo['rank'] .= $r->rank;
$staffinfo['rank_lvl'] .= $r->rank_lvl;
$staffinfo['team'] .= $r->staff_team;
$staffinfo['casecount'] .= $cases_count;
$staffinfo['casecount_week'] .= $i;
echo json_encode($staffinfo);
?>