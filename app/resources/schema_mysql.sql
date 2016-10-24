
CREATE TABLE `{prefix}categories` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `slug` (`slug`)
);

CREATE TABLE `{prefix}custom_fields` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(255) NOT NULL,
	`field` VARCHAR(255) NOT NULL,
	`key` VARCHAR(255) NOT NULL,
	`label` TEXT NOT NULL,
	`attributes` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `type` (`type`),
	INDEX `field` (`field`),
	INDEX `key` (`key`)
);

CREATE TABLE `{prefix}meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`key` VARCHAR(255) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `key` (`key`)
);

CREATE TABLE `{prefix}page_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`page` INT NOT NULL,
	`custom_field` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `page` (`page`),
	INDEX `custom_field` (`custom_field`)
);

CREATE TABLE `{prefix}pages` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`parent` INT NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`status` VARCHAR(255) NOT NULL,
	`redirect` TEXT NOT NULL,
	`show_in_menu` INT NOT NULL,
	`menu_order` INT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `parent` (`parent`),
	UNIQUE INDEX `slug` (`slug`),
	INDEX `status` (`status`),
	INDEX `show_in_menu` (`show_in_menu`),
	INDEX `menu_order` (`menu_order`)
);

CREATE TABLE `{prefix}post_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`post` INT NOT NULL,
	`custom_field` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `post` (`post`),
	INDEX `custom_field` (`custom_field`)
);

CREATE TABLE `{prefix}posts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	`published` DATETIME NOT NULL,
	`author` INT NOT NULL,
	`category` INT NOT NULL,
	`status` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `slug` (`slug`),
	INDEX `published` (`published`),
	INDEX `author` (`author`),
	INDEX `category` (`category`),
	INDEX `status` (`status`)
);

CREATE TABLE `{prefix}users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` TEXT NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`name` TEXT NOT NULL,
	`bio` TEXT NOT NULL,
	`status` VARCHAR(255) NOT NULL,
	`role` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `username` (`username`),
	UNIQUE INDEX `email` (`email`),
	INDEX `status` (`status`),
	INDEX `role` (`role`)
);

CREATE TABLE `{prefix}user_tokens` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`user` INT NOT NULL,
	`expires` DATETIME NOT NULL,
	`token` VARCHAR(255) NOT NULL,
	`signature` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `user` (`user`),
	INDEX `expires` (`expires`),
	INDEX `token` (`token`),
	INDEX `signature` (`signature`)
);
