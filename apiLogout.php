<?php 
include "db.php";
if (isset($_POST['token'])) {
  $token = sha1($_POST['token']);
  $sql = "SELECT token FROM login_tokens WHERE token = :token";
  $query = $pdo->prepare($sql);
  $query->bindValue(':token', $token, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch();
  if ($result) {
    $sql2 = 'DELETE FROM login_tokens WHERE token = :token';
    $query = $pdo->prepare($sql);
    $query->bindValue(':token', $token, PDO::PARAM_STR);
    $query->execute();
    setcookie("LOGINTOKEN", 0, time()-3600, "/", NULL, NULL, TRUE);
    echo '{ "Status": "Success" }';
    http_response_code(200);
  } else {
    echo '{ "Error": "Invalid token" }';
    http_response_code(400);
  }
} else {
  echo '{ "Error": "Malformed request" }';
  http_response_code(400);
}
?>