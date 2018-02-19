<?php 
include "db.php";
$email=$_POST['email'];
$password=$_POST['password'];
$sql = "SELECT * FROM users WHERE email = :email";
$query = $pdo->prepare($sql);
$query->bindValue(':email', $email, PDO::PARAM_STR);
$query->execute();
$selected_user = $query->fetch();
if (password_verify($password, $selected_user->password)) {
  $userid=$selected_user->id;
  $cstrong=true;
  $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
  $stoken = sha1($token);
  $sql2 = "INSERT INTO login_tokens (`token`, `user_id`) VALUES (:stoken , :userid )";
  $query2 = $pdo->prepare($sql2);
  $query2->bindValue(':stoken', $stoken, PDO::PARAM_STR);
  $query2->bindValue(':userid', $userid, PDO::PARAM_STR);
  $query2->execute();
  setcookie("LOGINTOKEN", $token, time()+60*60*24*365, "/", NULL, NULL, TRUE);
  $arr['token'] .= $token;
  $arr['uid'] .= $userid;
  $json=json_encode($arr);
  echo $json;
} else {
  $arr['token'] .= "Failed";
  $json=json_encode($arr);
  echo $json;
}
?>