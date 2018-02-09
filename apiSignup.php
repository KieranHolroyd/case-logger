<?php 
include "db.php";
$password=$_POST['password'];
$cpassword=$_POST['cpassword'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$username=$first_name.$last_name;
$email=$_POST['email'];
if (!empty($username) && !empty($password) && !empty($cpassword) && !empty($first_name) && !empty($last_name) && !empty($email)) {
  if ($password==$cpassword) {
    $password=password_hash($password, PASSWORD_DEFAULT);
    $username=preg_replace('/[^A-Za-z0-9\-]/', '', $username);
    $first_name=preg_replace('/[^A-Za-z0-9\-]/', '', $first_name);
    $last_name=preg_replace('/[^A-Za-z0-9\-]/', '', $last_name);
    $uniqid=bin2hex(openssl_random_pseudo_bytes(256));
    $sql = "SELECT * FROM users WHERE email = :email";
    $query = $pdo->prepare($sql);
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();
    if ($result->email == "") {
      $sql2 = "SELECT username FROM users WHERE username = :username";
      $query2 = $pdo->prepare($sql2);
      $query2->bindValue(':username', $username, PDO::PARAM_STR);
      $query2->execute();
      $result2 = $query2->fetch();
      if ($result->username == "") {
        $sql3 = "INSERT INTO users (`username`, `first_name`, `last_name`, `email`, `password`, `unique_id`) VALUES (:username , :firstname , :lastname , :email , :password , :uniqid)";
        $query3 = $pdo->prepare($sql3);
        $query3->bindValue(':username', $username, PDO::PARAM_STR);
        $query3->bindValue(':firstname', $first_name, PDO::PARAM_STR);
        $query3->bindValue(':lastname', $last_name, PDO::PARAM_STR);
        $query3->bindValue(':email', $email, PDO::PARAM_STR);
        $query3->bindValue(':password', $password, PDO::PARAM_STR);
        $query3->bindValue(':uniqid', $uniqid, PDO::PARAM_STR);
        $query3->execute();
        echo "Account Created.";
      }	else {
        echo "Username Already Used.";
      }
    } else {
      echo "Email Already Used.";
    }
  } else {
    echo "Passwords Must Match.";
  }
} else {
  echo "All Fields Are Required To Sign Up.";
}
?>