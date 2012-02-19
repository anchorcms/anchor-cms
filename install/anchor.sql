
DROP TABLE IF EXISTS `comments`;

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
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `meta`;

CREATE TABLE `meta` (
	`key` varchar(140) NOT NULL,
	`value` text NOT NULL,
	PRIMARY KEY (`key`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `meta` (`key`, `value`) VALUES ('posts_page', '1'), ('home_page', '1'), ('twitter', ''), ('date_format', 'jS M, Y'), ('auto_published_comments', '0'), ('posts_per_page', '10');

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`slug` varchar(150) NOT NULL,
	`name` varchar(64) NOT NULL,
	`title` varchar(150) NOT NULL,
	`content` text NOT NULL,
	`status` enum('draft','published','archived') NOT NULL,
	PRIMARY KEY (`id`),
	KEY `status` (`status`),
	KEY `slug` (`slug`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `pages` (`slug`, `name`, `title`, `content`, `status`) VALUES
('posts', 'Posts', 'My posts and thoughts', '<p>Welcome!</p>', 'published'),
('about', 'About', 'A little bit about me', '<p>This is a little bit of text about me.</p>', 'published');

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`title` varchar(150) NOT NULL,
	`slug` varchar(150) NOT NULL,
	`description` text NOT NULL,
	`html` text NOT NULL,
	`css` text NOT NULL,
	`js` text NOT NULL,
	`custom_fields` text,
	`created` int(11) NOT NULL,
	`author` int(6) NOT NULL,
	`status` enum('draft','published','archived') NOT NULL,
	`comments` TINYINT( 1 ) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `status` (`status`),
	KEY `slug` (`slug`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `posts` (`title`, `slug`, `description`, `html`, `css`, `js`, `created`, `author`, `status`) VALUES
('Hello World', 'hello', 'Hello World.', '<p>My first post.</p>', '', '', '[[now]]', 1, 'published');

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) NOT NULL,
	`password` varchar(140) NOT NULL,
	`email` varchar(140) NOT NULL,
	`real_name` varchar(140) NOT NULL,
	`bio` text NOT NULL,
	`status` enum('inactive','active') NOT NULL,
	`role` enum('administrator','editor','user') NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `users` (`username`, `password`, `email`, `real_name`, `bio`, `status`, `role`) VALUES
('admin', '[[password]]', '[[email]]', 'Administrator', 'Default account for Anchor.', 'active', 'administrator');

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE IF NOT EXISTS `sessions` (
	`id` CHAR( 32 ) NOT NULL ,
	`date` DATETIME NOT NULL ,
	`ip` VARCHAR( 15 ) NOT NULL ,
	`ua` TEXT NOT NULL ,
	`data` TEXT NOT NULL
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;
