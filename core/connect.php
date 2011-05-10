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
	require_once $path . 'lib/ActiveRecord/ActiveRecord.php';
	
//	Try to connect, but catch the exceptions, and die() with the results.	
	try {
		$db = new PDO("mysql:host=$host;dbname=$name", $user, $pass, array(PDO::ATTR_PERSISTENT => true));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		
		$connections = array(
			'development' => "mysql://$user:$pass@$host/$name"
		);
		// initialize ActiveRecord
		ActiveRecord\Config::initialize(function($cfg) use ($connections)
		{
		  global $path;
	    $cfg->set_model_directory($path . 'models');
	    $cfg->set_connections($connections);
		});
	} catch(PDOException $e) {
		die($e->getMessage());
	}		
?>