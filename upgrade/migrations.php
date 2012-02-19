<?php

/*
	0.4 --> 0.5
*/
$migration = new Migrations;

if(Schema::has('users', 'email') === false) {
	$sql = "alter table `users` add `email` varchar( 140 ) not null after `password`";
	$migration->query($sql);
}

if(Schema::has('posts', 'comments') === false) {
	$sql = "alter table `posts` add `comments` tinyint( 1 ) not null";
	$migration->query($sql);
}

if(Schema::has('posts', 'custom_fields') === false) {
	$sql = "alter table `posts` add `custom_fields` text not null after `js`";
	$migration->query($sql);
}

$sql = "create table if not exists `comments` (
	`id` int(6) not null auto_increment,
	`post` int(6) not null,
	`status` enum('pending','published','spam') not null,
	`date` int(11) not null,
	`name` varchar(140) not null,
	`email` varchar(140) not null,
	`text` text not null,
	primary key (`id`),
	key `post` (`post`),
	key `status` (`status`)
) engine=myisam charset=utf8 collate=utf8_general_ci";
$migration->query($sql);

// rename show_posts
$sql = "update `meta` set `value` = 'posts_page' where `value` = 'show_posts'";
$migration->query($sql);

// make posts_page the home page
if(Schema::has('meta', 'key', 'home_page') === false) {
	$posts_page = Db::query("select `value` from meta where `key` = 'show_posts'")->fetchColumn();

	$sql = "insert into `meta` (`key`, `value`) values ('home_page', '" . $posts_page . "')";
	$migration->query($sql);
}

// [BUGFIX] make sure the password field is big enough
$sql = "alter table `users` change `password` `password` text character set utf8 COLLATE utf8_general_ci not null";
$migration->query($sql);

// apply changes
$migration->apply();

// update config
Config::set('application.admin_folder', 'admin');
Config::set('application.key', random(32));

/*
	0.5 --> 0.6
*/
$migration = new Migrations;

$sql = "create table if not exists `sessions` (
	`id` char( 32 ) not null ,
	`date` datetime not null ,
	`ip` varchar( 15 ) not null ,
	`ua` varchar( 140 ) not null ,
	`data` text not null
) engine=innodb charset=utf8 collate=utf8_general_ci;";
$migration->query($sql);

// comments auto published option
if(Schema::has('meta', 'key', 'auto_published_comments') === false) {
	$sql = "insert into `meta` (`key`, `value`) values ('auto_published_comments', '0')";
	$migration->query($sql);
}

// pagination
if(Schema::has('meta', 'key', 'posts_per_page') === false) {
	$sql = "insert into `meta` (`key`, `value`) values ('posts_per_page', '10')";
	$migration->query($sql);
}

// apply changes
$migration->apply();

// update config
Config::set('session.name', 'anchorcms');
Config::set('session.expire', 3600);
Config::set('session.path', '/');
Config::set('session.domain', '');

Config::set('error.ignore', array(E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED));
Config::set('error.detail', true);
Config::set('error.log', false);
