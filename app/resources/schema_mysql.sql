
CREATE TABLE `{prefix}categories` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(191) NOT NULL,
	`slug` VARCHAR(191) NOT NULL,
	`description` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `slug` (`slug`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}custom_fields` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`content_type` VARCHAR(191) NOT NULL,
	`input_type` VARCHAR(191) NOT NULL,
	`field_key` VARCHAR(191) NOT NULL,
	`label` TEXT NOT NULL,
	`attributes` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `content_type` (`content_type`),
	INDEX `input_type` (`input_type`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`meta_key` VARCHAR(191) NOT NULL,
	`meta_value` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `meta_key` (`meta_key`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}page_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`page` INT NOT NULL,
	`custom_field` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `page` (`page`),
	INDEX `custom_field` (`custom_field`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}pages` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`parent` INT NOT NULL,
	`slug` VARCHAR(191) NOT NULL,
	`name` VARCHAR(191) NOT NULL,
	`title` VARCHAR(191) NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`status` VARCHAR(191) NOT NULL,
	`redirect` TEXT NOT NULL,
	`show_in_menu` INT NOT NULL,
	`menu_order` INT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `parent` (`parent`),
	UNIQUE INDEX `slug` (`slug`),
	INDEX `status` (`status`),
	INDEX `show_in_menu` (`show_in_menu`),
	INDEX `menu_order` (`menu_order`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}post_meta` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`post` INT NOT NULL,
	`custom_field` INT NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `post` (`post`),
	INDEX `custom_field` (`custom_field`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}posts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(191) NOT NULL,
	`slug` VARCHAR(191) NOT NULL,
	`content` TEXT NOT NULL,
	`html` TEXT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	`published` DATETIME NOT NULL,
	`author` INT NOT NULL,
	`category` INT NOT NULL,
	`status` VARCHAR(191) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `slug` (`slug`),
	INDEX `published` (`published`),
	INDEX `author` (`author`),
	INDEX `category` (`category`),
	INDEX `status` (`status`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(191) NOT NULL,
	`password` TEXT NOT NULL,
	`email` VARCHAR(191) NOT NULL,
	`name` TEXT NOT NULL,
	`bio` TEXT NOT NULL,
	`status` VARCHAR(191) NOT NULL,
	`user_role` VARCHAR(191) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `username` (`username`),
	UNIQUE INDEX `email` (`email`),
	INDEX `status` (`status`),
	INDEX `user_role` (`user_role`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `{prefix}user_tokens` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`user` INT NOT NULL,
	`expires` DATETIME NOT NULL,
	`token` VARCHAR(191) NOT NULL,
	`signature` VARCHAR(191) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `user` (`user`),
	INDEX `expires` (`expires`),
	INDEX `token` (`token`),
	INDEX `signature` (`signature`)
) ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
