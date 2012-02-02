<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Check our installation
*/
if(Config::load() === false) {
	// looks like we are missing a config file
	Response::send(PATH . 'system/admin/theme/missing.php', 503);
	
	exit(1);
}

/*
	Start session handler
*/
Session::start();

/*
	Load any typekit fonts
*/
Typekit::load();

/*
	Handle routing
*/
Anchor::run();

/*
	Output awesomeness!
*/
Response::send();
