<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Custom theme functions - This file will be included for each templae to use
*/

// counts the number of words that do not contain a vowel
function count_voweless($str) {
	$words = preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY);
	$total = 0;
	
	foreach($words as $word) {
		if(strlen($word) < 3) {
			continue;
		}
		
		$letters = str_split(strtolower($word));
		
		foreach(array('a','e','i','o','u') as $vowel) {
			if(in_array($vowel, $letters)) {
				continue 2;
			}
		}
		
		$total++;
	}
	
	return $total;
}
