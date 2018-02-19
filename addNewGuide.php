<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($logged_in['info']['slt']==1){
  $title = $_POST['title'];
  $body = $_POST['body'];
  $user = $logged_in['info']['firstname']." ".$logged_in['info']['lastname'];
  $sql="INSERT INTO guides (`title`, `body`, `author`) VALUES ( :title , :body , :user)";
  $query = $pdo->prepare($sql);
  $query->bindValue(':title', $title, PDO::PARAM_STR);
  $query->bindValue(':body', $body, PDO::PARAM_STR);
  $query->bindValue(':user', $user, PDO::PARAM_STR);
  $query->execute();
  echo "Guide Added Successfully.";
} else {
  echo "Insufficient Permissions.";
}
?>