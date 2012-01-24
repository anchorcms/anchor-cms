
DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `pages` (`slug`, `name`, `title`, `content`, `visible`) VALUES
('posts','Posts','My posts and thoughts','',1),
('search','Search','Search my site','',0),
('about','About','A little bit about me','<p>This is a little bit of text about me. It doesn\'t have to be very long, but it would sure help if there was some, you know?</p>',1);


DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `html` text NOT NULL,
  `css` varchar(200) NOT NULL,
  `js` varchar(200) NOT NULL,
  `date` int(11) NOT NULL,
  `author` int(2) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `posts` (`title`, `slug`, `description`, `html`, `css`, `js`, `date`, `author`, `published`) VALUES
('My First Anchor Post','hello-world','This is my first Anchor post.','<div>\n<h3>Hello!</h3>\n</div>','','',1320156306,1,1);


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `real_name` varchar(140) NOT NULL,
  `bio` text NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `users` (`username`, `password`, `real_name`, `bio`, `status`) VALUES 
('admin', 'password', 'Administrator', 'I&rsquo;m the default account for Anchor. If you own the site, make sure to change this!', 2);

