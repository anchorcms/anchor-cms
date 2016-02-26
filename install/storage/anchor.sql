CREATE TABLE `{{prefix}}categories` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(40) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post` int(6) NOT NULL,
  `status` enum('pending','approved','spam') NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(140) NOT NULL,
  `email` varchar(140) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `status` (`status`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}extend` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `type` enum('post','page','user') NOT NULL,
  `field` enum('text','html','image','file') NOT NULL,
  `key` varchar(160) NOT NULL,
  `label` varchar(160) NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}meta` (
  `key` varchar(140) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}page_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `page` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page` (`page`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}pages` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `status` enum('draft','published','archived') NOT NULL,
  `redirect` text NOT NULL,
  `show_in_menu` tinyint(1) NOT NULL,
  `menu_order` int(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}post_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}posts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `html` text NOT NULL,
  `css` text NOT NULL,
  `js` text NOT NULL,
  `created` datetime NOT NULL,
  `author` int(6) NOT NULL,
  `category` int(6) NOT NULL,
  `status` enum('draft','published','archived') NOT NULL,
  `comments` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}sessions` (
  `id` char(32) NOT NULL,
  `expire` int(10) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(140) NOT NULL,
  `real_name` varchar(140) NOT NULL,
  `bio` text NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `role` enum('administrator','editor','user') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

INSERT INTO `{{prefix}}categories` (`title`, `slug`, `description`) VALUES
('Uncategorised', 'uncategorised', 'Ain\'t no category here.');

INSERT INTO `{{prefix}}meta` (`key`, `value`) VALUES
('auto_published_comments', '0'),
('comment_moderation_keys', ''),
('comment_notifications', '0'),
('date_format', 'jS M, Y'),
('home_page', '1'),
('posts_page',  '1'),
('posts_per_page',  '6');

INSERT INTO `{{prefix}}pages` (`slug`, `name`, `title`, `content`, `status`, `redirect`, `show_in_menu`, `menu_order`) VALUES
('posts', 'Posts', 'My posts and thoughts', 'Welcome!', 'published', '', '1', '0');

INSERT INTO `{{prefix}}posts` (`title`, `slug`, `description`, `html`, `css`, `js`, `created`, `author`, `category`, `status`, `comments`) VALUES
('Hello World', 'hello-world', 'This is the first post.', 'Hello World!\r\n\r\nThis is the first post.', '', '', '{{now}}', '1', '1', 'published', '0');
