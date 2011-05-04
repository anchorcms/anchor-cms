<?php

/******************************************************
 *
 *		loader.php						by @visualidiot
 *
 ******************************************************
 *
 *		Load Anchor up.
 */
 
//	Set the version
	$version = '0.1.3';
 
//	Include the paths
	include('paths.php');
	include('check.php');

//	Get the user's settings
	include($path . '/config/settings.php');
	include($path . '/config/database.php');
	
	if(!empty($sitename) && !empty($theme) && !empty($host) && !empty($user) && !empty($pass) && !empty($name)) {	
		//	Get the theming functions
		include('themes.php');
		include('posts.php');
		include('connect.php');
		include('stats.php');
		
		//	And decide what page to load
		if(!isset($_GET['page'])) {
			$index = $path . '/themes/' . $theme . '/index.php';
			include($index);
		} else {
			$check = $db->prepare('select title from posts where slug = :slug');
			$check->execute(array(':slug' => $_GET['page']));
			
			$post = $check->fetch(PDO::FETCH_OBJ);
			
			if($post->title) {
				$sub = $path . '/themes/' . $theme . '/single.php';
				if(file_exists($sub)) { include($sub); } else { include($index); }	
			} else {
				$error = $path . '/themes/' . $theme . '/error.php';
				if(file_exists($error)) { include($error); } else { include($index); }	
			}
		}
	
		//	And load it all
		load();
	} else {
		//	Do not pass go, do not collect $200.
		//	include('install.php');
		echo 'Please fill in the install files, located in the <code>config/</code> directory, and reload this page.';
	}
	
?>