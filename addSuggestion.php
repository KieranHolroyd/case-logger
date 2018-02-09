<?php 
session_start();
include "db.php";
$logged_in=unserialize($_COOKIE['userArrayPHP']);
if($_SESSION['csrf']==$_POST['csrf']){
  if($logged_in['permissions']['submitReport']==1){
    $name = $_POST['name'];
    $suggestion = $_POST['suggestion'];
    $sql="INSERT INTO suggestions (`name`, `suggestion`) VALUES ( :name , :suggestion )";
    $exec = $pdo->prepare($sql);
    $exec->execute(['name' => $name, 'suggestion' => $suggestion]);
    echo "Report Added";
  } else {
    echo "Insufficient Permissions";
  }
} else {
	echo "CSRF Failed";
}
?>