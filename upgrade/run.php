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

// check we 0.5 downloaded 
$index = file_get_contents(PATH . 'index.php');

if(strpos($index, "0.5") !== false) {
	// this upgrade is for 0.4 -> 0.5 only
	header('Location: index.php');
}

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
	Include files we are going 
	to need for every request.
*/
require PATH . 'system/classes/autoload.php';
require PATH . 'system/classes/config.php';
require PATH . 'system/classes/error.php';

// register the auto loader
Autoloader::register();

/*
	Check our installation
*/
if(Config::load() === false) {
	// looks like we are missing a config file
	echo file_get_contents(PATH . 'system/admin/theme/error_config.php');
	exit(1);
}

// Query metadata and store into our config
$sql = "select `key`, `value` from meta";
$meta = array();

foreach(Db::results($sql) as $row) {
	$meta[$row->key] = $row->value;
}

Config::set('metadata', $meta);

// make database changes
$sql = "ALTER TABLE `users` ADD `email` VARCHAR( 140 ) NOT NULL AFTER `password`";
Db::query($sql);

$sql = "ALTER TABLE `posts` ADD `comments` TINYINT( 1 ) NOT NULL";
Db::query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post` int(6) NOT NULL,
  `status` enum('pending','published','spam') NOT NULL,
  `date` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `email` varchar(140) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `status` (`status`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci";
Db::query($sql);

// rename show_posts
Db::update('meta', array('value' => 'posts_page'), array('value' => 'show_posts'));

// make posts_page the home page
Db::insert('meta', array('key' => 'home_page', 'value' => Config::get('metadata.show_posts')));

// add current version
Db::insert('meta', array('key' => 'version', 'value' => '0.5'));

// create new config file
$template = file_get_contents('../config.default.php');

$search = array(
	"'host' => 'localhost'",
	"'username' => 'root'",
	"'password' => ''",
	"'name' => 'anchorcms'",
	
	// apllication paths
	"'base_url' => '/'",
	"'index_page' => 'index.php'",
	"'key' => ''"
);
$replace = array(
	"'host' => '" . Config::get('database.host') . "'",
	"'username' => '" . Config::get('database.username') . "'",
	"'password' => '" . Config::get('database.password') . "'",
	"'name' => '" . Config::get('database.name') . "'",

	// apllication paths
	"'base_url' => '" . Config::get('application.base_url') . "'",
	"'index_page' => '" . Config::get('application.index_page') . "'",
	"'key' => '" . radnom() . "'"
);
$config = str_replace($search, $replace, $template);

if(file_put_contents('../config.php', $config) === false) {
	$errors[] = 'Failed to create config file';
}

// database update are done lets redirect to the complete page
header('Location: complete.php');
