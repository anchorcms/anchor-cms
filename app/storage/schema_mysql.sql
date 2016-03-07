CREATE TABLE `{prefix}categories` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`title` varchar(150) NOT NULL,
	`slug` varchar(40) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}extend` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`type` enum('post','page','user') NOT NULL,
	`field` enum('text','html','image','file') NOT NULL,
	`key` varchar(160) NOT NULL,
	`label` varchar(160) NOT NULL,
	`attributes` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}extend_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}meta` (
	`key` varchar(140) NOT NULL,
	`value` text NOT NULL,
	PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}page_meta` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`page` int(6) NOT NULL,
	`extend` int(6) NOT NULL,
	`data` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}page_meta_page` (`page`),
	KEY `{prefix}page_meta_extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}pages` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`parent` int(6) NOT NULL DEFAULT '0',
	`slug` varchar(150) NOT NULL,
	`name` varchar(64) NOT NULL,
	`title` varchar(150) NOT NULL,
	`content` text NOT NULL,
	`html` text NOT NULL,
	`status` enum('draft','published','archived') NOT NULL,
	`redirect` text NOT NULL,
	`show_in_menu` tinyint(1) NOT NULL,
	`menu_order` int(4) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	KEY `{prefix}pages_status` (`status`),
	KEY `{prefix}pages_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}post_meta` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`post` int(6) NOT NULL,
	`extend` int(6) NOT NULL,
	`data` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}post_meta_post` (`post`),
	KEY `{prefix}post_meta_extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}posts` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`title` varchar(150) NOT NULL,
	`slug` varchar(150) NOT NULL,
	`content` text NOT NULL,
	`html` text NOT NULL,
	`created` datetime NOT NULL,
	`modified` datetime NOT NULL,
	`author` int(6) NOT NULL,
	`category` int(6) NOT NULL,
	`status` enum('draft','published','archived') NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}posts_slug` (`slug`),
	KEY `{prefix}posts_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}users` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) NOT NULL,
	`password` text NOT NULL,
	`email` varchar(140) NOT NULL,
	`real_name` varchar(140) NOT NULL,
	`bio` text NOT NULL,
	`status` enum('inactive','active') NOT NULL,
	`role` enum('admin','editor','user') NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}users_status` (`status`),
	KEY `{prefix}users_usernames` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
