<?php

/******************************************************
 *
 *		users.php						by @visualidiot
 *
 ******************************************************
 *
 *		Retrieve information about users 
 */
 

//	Check if the user is logged in
  session_start();
	function logged_in() {
		if($_SESSION['username'] || $_COOKIE['username']) {
			return true;
		} else {
			return false;
		}
	}
	
//	Check if there's an existing user
	function user_exists($input) {
		//	I'll rewrite this with PDO at some point.
		//	For now, though, it's just good old MySQL.
		include('paths.php');
		include($path . '/config/database.php');
		$link = @mysql_connect($host, $user, $pass);
				@mysql_select_db($name);
		
		return @mysql_num_rows(@mysql_query("SELECT * FROM `users` WHERE `username` = '$input'", $link));
	}
	
?>