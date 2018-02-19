<?php 
include "db.php";
$sql="INSERT INTO `rollcall` (`name`, `rank`, `team`) VALUES ( :name, :rank, :team)";
$query = $pdo->prepare($sql);
$query->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
$query->bindValue(':rank', $_POST['rank'], PDO::PARAM_STR);
$query->bindValue(':team', $_POST['team'], PDO::PARAM_STR);
$query->execute();
print_r($query->errorinfo());
?>