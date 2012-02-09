<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme helpers functions
*/


// Url helpers
function base_url($url = '') {
    return Url::make($url);
}

function theme_url($file = '') {
	return Config::get('application.base_url') . 'themes/' . Config::get('metadata.theme') . '/' . ltrim($file, '/');
}

function current_url() {
	return Url::make(Request::uri());
}

function admin_url() {
    return Url::make(Config::get('application.admin_folder'));
}

function search_url() {
	return Url::make('search');
}

function rss_url() {
    return Url::make('rss');
}

//  Custom function helpers
$return = array();
function bind($page, $fn, $area = '') {

	global $return;
	
	$url = explode('/', current_url());
	
	if($url[1] === $page && is_callable($fn)) {
		$return[($area != '' $area ? $page] = $fn();		
	}
	
	return false;
}

function recieve($area) {

	global $return;

	if(isset($return[$area])) {
		return $return[$area];
	}
	
	return '';
}
