<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Custom theme functions
	
	Note: we recommend you prefix all your functions to avoid any naming 
	collisions or wrap your functions with if function_exists braces.
*/

function get_last_tweet(){
	//use the twitter api to get tweet data for the user
	$json = file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".twitter_account()."&count=1"); 
	//Decode it with JSON
	$decode = json_decode($json, true);
	//return tweet text
	return '<p>My Latest Tweet: ' . $decode[0]['text'] . '</p>';
}

function numeral($number) {
	$test = abs($number) % 10;
	$ext = ((abs($number) % 100 < 21 and abs($number) % 100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
	return $number . $ext; 
}

function count_words($str) {
	return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}

function pluralise($amount, $str, $alt = '') {
    return intval($amount) === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function relative_time($date) {
    $elapsed = time() - $date;
    
    if($elapsed <= 1) {
        return 'Just now';
    }
    
    $times = array(
        31104000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    
    foreach($times as $seconds => $title) {
        $rounded = $elapsed / $seconds;
        
        if($rounded > 1) {
            $rounded = round($rounded);
            return $rounded . ' ' . pluralise($rounded, $title) . ' ago';
        }
    }
}
