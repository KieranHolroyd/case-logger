<?php 
include "db.php";
$guides=array();
$i=1;
foreach($pdo->query('SELECT * FROM guides ORDER BY title') as $r){
  $title=$r->title;
  $author=$r->author;
  $body=$r->body;
	$guides[$i]['id'] .= $r->id;
  $guides[$i]['title'] .= $title;
  $guides[$i]['author'] .= $author;
  $guides[$i]['body'] .= $body;
  $guides[$i]['time'] .= $r->timestamp;
  $i+=1;
}
echo json_encode($guides);
?>