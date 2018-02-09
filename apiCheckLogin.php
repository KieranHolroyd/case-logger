<?php 
include "db.php";
if (isset($_COOKIE['LOGINTOKEN'])) {
  $token=sha1($_COOKIE['LOGINTOKEN']);
  	$sql = "SELECT * FROM login_tokens WHERE token = :token";
    $query = $pdo->prepare($sql);
    $query->bindValue(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();
  if ($result) {
    echo true;
  }
} else {
  echo false;
}
?>