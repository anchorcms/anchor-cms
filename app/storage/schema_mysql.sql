CREATE TABLE `{prefix}categories` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR NOT NULL,
	`slug` VARCHAR NOT NULL,
	`description` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}extend` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`type` TEXT NOT NULL,
	`field` TEXT NOT NULL,
	`key` VARCHAR NOT NULL,
	`label` VARCHAR NOT NULL,
	`attributes` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}meta` (
	`key` VARCHAR NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}page_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`page` INT NOT NULL,
	`extend` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}page_meta_extend` (`extend`),
	KEY `{prefix}page_meta_page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}pages` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`parent` INT NOT NULL,
	`slug` VARCHAR NOT NULL,
	`name` VARCHAR NOT NULL,
	`title` VARCHAR NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`status` TEXT NOT NULL,
	`redirect` TEXT NOT NULL,
	`show_in_menu` INT NOT NULL,
	`menu_order` INT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}pages_slug` (`slug`),
	KEY `{prefix}pages_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}posts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR NOT NULL,
	`slug` VARCHAR NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	`author` INT NOT NULL,
	`category` INT NOT NULL,
	`status` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}posts_slug` (`slug`),
	KEY `{prefix}posts_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}post_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`post` INT NOT NULL,
	`extend` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `{prefix}post_meta_extend` (`extend`),
	KEY `{prefix}post_meta_post` (`post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{prefix}users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR NOT NULL,
	`password` TEXT NOT NULL,
	`email` VARCHAR NOT NULL,
	`real_name` VARCHAR NOT NULL,
	`bio` TEXT NOT NULL,
	`status` TEXT NOT NULL,
	`role` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
