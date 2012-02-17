<?php

/*
	0.4 --> 0.5
*/
$migration = new Migration;

if(Schema::has('users', 'email') === false) {
	$sql = "alter table `users` add `email` varchar( 140 ) not null after `password`";
	$migration->query($sql);
}

if(Schema::has('posts', 'comments') === false) {
	$sql = "alter table `posts` add `comments` tinyint( 1 ) not null";
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
$sql = "insert into `meta` (`key`, `value`) values ('home_page', '" . Config::get('metadata.show_posts') . "')";
$migration->query($sql);

// add current version
$sql = "insert into `meta` (`key`, `value`) values ('version', '0.5')";
$migration->query($sql);

// [BUGFIX] make sure the password field is big enough
$sql = "alter table `users` change `password` `password` varchar( 140 ) character set utf8 COLLATE utf8_general_ci not null";
$migration->query($sql);

// apply changes
$migration->apply();

/*
	0.5 --> 0.6
*/
$migration = new Migration;

$sql = "create table if not exists `sessions` (
	`id` char( 32 ) not null ,
	`date` datetime not null ,
	`ip` varchar( 15 ) not null ,
	`ua` varchar( 140 ) not null ,
	`data` text not null
) engine=innodb charset=utf8 collate=utf8_general_ci;";
$migration->query($sql);

$sq = "create table if not exists `tags` (
	`post` int( 6 ) not null ,
	`tag` varchar( 140 ) not null ,
	key `post` (`post`),
	key `tag` (`tag`)
) engine=myisam charset=utf8 collate=utf8_general_ci;";
$migration->query($sql);

// comments auto published option
$sql = "insert into `meta` (`key`, `value`) values ('auto_published_comments', '0')";
$migration->query($sql);

// apply changes
$migration->apply();
