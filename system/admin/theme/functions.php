<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Main menu
*/
function admin_menu() {

    $prefix = Config::get('application.admin_folder');

	$pages = array(
		'Posts' => $prefix . '/posts',
		'Pages' => $prefix . '/pages',
		'Users' => $prefix . '/users',
		'Metadata' => $prefix . '/metadata'
	);

	return $pages;
}

/**
	Custom fields
*/
function parse_fields($str) {
	$data = json_decode($str, true);
	return is_array($data) ? $data : array();
}

/**
	Url helpers
*/
function theme_url($file = '') {
	return Config::get('application.base_url') . 'system/admin/theme/' . ltrim($file, '/');
}

function admin_url($url = '') {
	return Url::make(Config::get('application.admin_folder') . '/' . ltrim($url, '/'));
}

/**
	String helpers
*/
function pluralise($amount, $str, $alt = '') {
    return $amount === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function truncate($str, $limit = 10, $elipse = ' [...]') {
	$words = preg_split('/\s+/', $str);

	if(count($words) <= $limit) {
		return $str;
	}

	return implode(' ', array_slice($words, 0, $limit)) . $elipse;
}
	
/**
    Error checking
*/
function latest_version() {
	// only run the version check once per session
	if(($version = Session::get('latest_version')) === false) {
		// returns plain text string with version number or 0 on failure.
		$version = floatval(Curl::get('http://anchorcms.com/version'));
		Session::set('latest_version', $version);
	}

	return $version;
}

function error_check() {
    $errors = array();

    //  Check for older versions
    if(version_compare(ANCHOR_VERSION, ($version = latest_version()), '<')) {
        $errors[] = 'Your version of Anchor is out of date. Please <a href="http://anchorcms.com">download the latest version</a>.';
    }

    // do something useful with it
    return count($errors) ? $errors : false;
}
