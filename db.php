<?php 
	//Include Config
	include "config.php";
	if($dbHost == ""){ $dbHost = "localhost"; }
	$host = $dbHost;
	$user = $dbUser;
	$password = $dbPassword;
	$dbname = $dbName;
	$dsn = 'mysql:host='.$host.';dbname='.$dbname;
	$pdo = new PDO($dsn, $user, $password);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
?>