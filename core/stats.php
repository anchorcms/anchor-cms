<?php

/******************************************************
 *
 *		stats.php						by @visualidiot
 *
 ******************************************************
 *
 *		A varied assortment of functions to count,
 *		record, and digest various information.
 */
 
//	Count the number of currently installed themes
	function count_themes($display = true) {
		include('paths.php');

		$return = count(scandir($path . '/themes/')) - 2;
		
		if($display) echo $return;
		return $return;
	}
	
//	Check for the latest version
	function latest_version() {
		$url = file_get_contents('http://anchorcms.com/port/starboard');
		
		if($url >= $version) {
			return false;
		} else {
			return true;
		}
	}

//	Add the latest version to the list
//	This is for stats usage only. Feel free to get rid of this.
	function load() {
		global $version;
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'http://anchorcms.com/port/count');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'siteurl=' . $_SERVER['HTTP_HOST'] . '&version=' . $version);
		
		$result = @curl_exec($ch);
		
		curl_close($ch);
	}
	
?>