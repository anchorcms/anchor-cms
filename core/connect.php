<?php

/******************************************************
 *
 *		connect.php						by @visualidiot
 *
 ******************************************************
 *
 *		Connects to MySQL database (requires PDO).
 */


//	Get the database values
	include('paths.php');
	require($path . '/config/database.php');
	
//	Try to connect, but catch the exceptions, and die() with the results.	
	try {
		$db = new PDO("mysql:host=$host;dbname=$name", $user, $pass, array(PDO::ATTR_PERSISTENT => true));
	} catch(PDOException $e) {
		die($e->getMessage());
	}		
?>