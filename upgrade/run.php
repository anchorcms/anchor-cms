<?php

// report all errors
error_reporting(E_ALL);

// show all error uncaught
ini_set('display_errors', true);

/*
	Define some paths and get current config
*/
define('IN_CMS', true);
define('PATH', pathinfo(dirname(__FILE__), PATHINFO_DIRNAME) . '/');

/*
	Helper functions
*/
function random($length = 16) {
	$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
	$value = '';

	for ($i = 0; $i < $length; $i++)  {
		$value .= $pool[mt_rand(0, 61)];
	}

	return $value;
}

/*
	Include some files.
*/
require PATH . 'system/classes/autoload.php';
require PATH . 'system/classes/config.php';
require PATH . 'system/classes/error.php';

// map classes
Autoloader::map(array(
	'Schema' => PATH . 'upgrade/classes/schema.php',
	'Migrations' => PATH . 'upgrade/classes/migrations.php'
));

// register the auto loader
Autoloader::register();

// Query current metadata and store into our config
foreach(Db::results("select `key`, `value` from meta") as $row) {
	$meta[$row->key] = $row->value;
}

Config::set('metadata', $meta);

// add and apply migrations
require PATH . 'upgrade/migrations.php';

// redirect
header('Location: complete.php');