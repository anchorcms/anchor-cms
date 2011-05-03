<?php

/******************************************************
 *
 *		themes.php						by @visualidiot
 *
 ******************************************************
 *
 *		Get the current theme, and turn it into the
 *		working design.
 */
 
//	Get the current theme.
	require($path . '/config/settings.php');
	
	if(!$theme) $theme = 'default';
	//echo $theme;
	
//	Echo the sitename	
	function sitename($display = true) {
		global $sitename;
		if($display) echo $sitename;
		return $sitename;
	}
	
//	Get the relevant page's title
	function title() {
		global $db;
		if(is_home()) {
			echo 'Homepage';
		} else {
			include('connect.php');
			$url = $_GET['page'];
			$get = $db->prepare('SELECT title FROM posts WHERE slug = :slug');

				   $get->execute(array(':slug' => $url));
			
			//	Return the post
			$return = $get->fetch(PDO::FETCH_OBJ);
			
			if($return)	{
				echo $return->title;
			} else {
				echo 'Post not found!';
			}
		}
	}
	
//	Is it the homepage?
	function is_home() {
		if(!isset($_GET['page'])) { return true; } else { return false; };
	}
	
//	Get the header
//	If it doesn't exist in the current theme, grab the default theme's
	function get_header() {
		global $path;
		if(isset($theme) && file_exists($path . '/themes/' . $theme . '/header.php')) {
			include($path . '/themes/' . $theme . '/header.php');
		} else {
			include($path . '/themes/default/header.php');
		}
	}

//	Get the header
//	If it doesn't exist in the current theme, grab the default theme's
	function get_footer() {
		global $path;
		if(isset($theme) && file_exists($path . '/themes/' . $theme . '/footer.php')) {
			include($path . '/themes/' . $theme . '/footer.php');
		} else {
			include($path . '/themes/default/footer.php');
		}
	}
	
//	Get the current theme's directory
	function theme_directory($display = true) {
		global $urlpath, $theme;
		if($display) echo $urlpath . 'themes/' . $theme;
		return $urlpath . 'themes/' . $theme;
	}
	
//	Has CSS? (Hides by default)
	function has_css($display = false) {
		include('connect.php');		
		
		$get = $db->prepare('SELECT css FROM posts WHERE slug = :slug');
		$get->execute(array(':slug' => isset($_GET['page']) ? $_GET['page'] : ''));
		
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		if($display) echo $return;
		return $return;		
	}

//	Has JS? (Hides by default)
	function has_js($display = false) {
		include('connect.php');		
		
		$get = $db->prepare('SELECT js FROM posts WHERE slug = :slug');
		$get->execute(array(':slug' => isset($_GET['page']) ? $_GET['page'] : ''));
		
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		if($display) echo $return;
		return $return;		
	}

//	Get the post's custom CSS (if any)
	function css_link($display = true) {
		global $db;

		include('connect.php');
		$url = $_GET['page'];
		$get = $db->prepare('SELECT css FROM posts WHERE slug = :slug');

		$get->execute(array(':slug' => $url));
		
		//	Return the post
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		if($display) echo $return->css;
		return $return->css;
	}

//	Get the post's custom JS (if any)
	function js_link($display = true) {
		global $db;

		include('connect.php');
		$url = $_GET['page'];
		$get = $db->prepare('SELECT js FROM posts WHERE slug = :slug');

		$get->execute(array(':slug' => $url));
		
		//	Return the post
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		if($display) echo $return->js;
		return $return->js;
	}

//	Get the date, and format it, using PHP's date() function	
	function format_date($format, $slug = '', $display = true) {
		global $db;

		include('connect.php');
		$slug = $_GET['page'];
		$get = $db->prepare('SELECT date FROM posts WHERE slug = :slug');

		$get->execute(array(':slug' => $slug));
		
		//	Return the post
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		$date = date($format, strtotime($return->date));
		
		if($display) echo $date;
		return $date;
	}
	
//	Get the date of a post, but don't format it
	function get_date($slug = '', $display = true) {
		global $db;

		include('connect.php');
		if($slug == '') $slug = $_GET['page'];

		$get = $db->prepare('SELECT date FROM posts WHERE slug = :slug');

		$get->execute(array(':slug' => $slug));
		
		//	Return the post
		$return = $get->fetch(PDO::FETCH_OBJ);
		
		if($display) echo $return->date;
		return $return->date;
	}
	
//	Time ago function
//	use time_ago(get_date()) 
	function time_ago($time) {
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	
	   $now = time();
	
	       $difference     = $now - $time;
	       $tense         = "ago";
	
	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }
	
	   $difference = round($difference);
	
	   if($difference != 1) {
	       $periods[$j].= "s";
	   }
	
	   return "$difference $periods[$j] ago";
	}
?>