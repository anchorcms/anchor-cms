
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

CREATE TABLE `posts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `html` text NOT NULL,
  `css` text NOT NULL,
  `js` text NOT NULL,
  `created` date NOT NULL,
  `author` int(6) NOT NULL,
  `status` enum('draft','published','archived') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`),
  KEY `created` (`created`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `real_name` varchar(140) NOT NULL,
  `bio` text NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `pages` (`slug`, `name`, `title`, `content`, `status`) VALUES
('posts', 'Posts', 'My posts and thoughts', '', 'published'),
('about', 'About', 'A little bit about me', '<p>This is a little bit of text about me.</p>', 'published');

INSERT INTO `posts` (`title`, `slug`, `description`, `html`, `css`, `js`, `created`, `author`, `status`) VALUES
('Hellow World', 'hello', 'Hello World.', '<p>My first post.</p>', '', '', '2012-01-03', 1, 'published');

INSERT INTO `users` (`username`, `password`, `real_name`, `bio`, `status`) VALUES
('admin', '$1$zWh0Xu7w$hmt6j9VIwzPvRFwhKXj8G.', 'Administrator', 'Default account for Anchor.', 2);

