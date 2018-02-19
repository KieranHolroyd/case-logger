<?php 
session_start();
include "db.php";
$cookietoken=sha1($_COOKIE['LOGINTOKEN']);
//Get User ID From Login Tokens
$sql = "SELECT * FROM login_tokens WHERE token = :token";
$query = $pdo->prepare($sql);
$query->bindValue(':token', $cookietoken, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch();
//Get Logged In User's Information
$sql2 = "SELECT * FROM users WHERE id = :id";
$query2 = $pdo->prepare($sql2);
$query2->bindValue(':id', $result->user_id, PDO::PARAM_STR);
$query2->execute();
$user = $query2->fetch();
//Assign Values To An Array.
$arr=array();
$arr['info']['id'].=$user->id;
$arr['info']['username'].=$user->username;
$arr['info']['firstname'].=$user->first_name;
$arr['info']['lastname'].=$user->last_name;
$arr['info']['profile_picture'].=$user->profile_picture;
$arr['info']['email'].=$user->email;
$arr['info']['suspended'].=$user->suspended;
$arr['info']['slt'].=$user->SLT;
$arr['info']['dev'].=$user->Developer;
$arr['info']['rank'].=$user->rank;
$arr['info']['team'].=$user->staff_team;
$arr['permissions']['submitReport'].=$user->sRep;
setcookie('userArrayPHP', serialize($arr), time()+60*60*24*30, '/');
echo json_encode($arr);
?>