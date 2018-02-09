<?php 
include "db.php";
$id=$_POST['id'];
$sql = "SELECT * FROM guides WHERE id = :id";
$query = $pdo->prepare($sql);
$query->bindValue(':id', $id, PDO::PARAM_STR);
$query->execute();
$r = $query->fetch();
$arr=array();
$arr['title'] = $r->title;
$arr['body'] = $r->body;
$arr['author'] = $r->author;
$arr['time'] = $r->timestamp;
echo json_encode($arr);
?>