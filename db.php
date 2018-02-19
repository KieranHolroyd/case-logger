<?php 
	$host = 'localhost';
	$user = 'psisyn_caselogger';
	$password = 'Ih0DzqPjcng7Koll!';
	$dbname = 'Psisyn_caselogger';
	$dsn = 'mysql:host='.$host.';dbname='.$dbname;
	$pdo = new PDO($dsn, $user, $password);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
?>