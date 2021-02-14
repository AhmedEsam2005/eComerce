<?php

	$dsn 	  = 'mysql:host=localhost;dbname=shop';
	$username = 'Ahmed';
	$password =  '123123';
	$option   =  array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	);
	try {
		$con = new PDO($dsn, $username, $password, $option);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connect";
	}
	catch(PDOException $e) {
		echo "Failed Message : " . $e;
	}
?>