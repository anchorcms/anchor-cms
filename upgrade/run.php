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

Db::update('meta', array('value' => 'posts_page'), array('value' => 'show_posts'));

// make posts_page the home page
$sql = "select `key` from `meta` where `value` = 'posts_page'";
$row = Db::row($sql);

Db::insert('meta', array('key' => 'home_page', 'value' => $row->key));

// add current version
Db::insert('meta', array('key' => 'version', 'value' => '0.5'));

// database update are done lets redirect to the complete page
header('Location: complete.php');
