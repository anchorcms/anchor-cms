DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text,
  `visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

LOCK TABLES `pages` WRITE;

INSERT INTO `pages` (`id`, `slug`, `name`, `title`, `content`, `visible`)
VALUES
	(1,'posts','Posts','My posts and thoughts',NULL,1),
	(2,'search','Search','Search my site',NULL,0),
	(3,'about','About','A little bit about me','<p>This is a little bit of text about me. It doesn\'t have to be very long, but it would sure help if there was some, you know?</p>',1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `description` text,
  `html` text,
  `css` varchar(200) DEFAULT NULL,
  `js` varchar(200) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `author` int(2) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

LOCK TABLES `posts` WRITE;

INSERT INTO `posts` (`id`, `title`, `slug`, `description`, `html`, `css`, `js`, `date`, `author`, `published`)
VALUES
	(1,'My First Anchor Post','hello-world','This is my first Anchor post.','<div>\n<h3>Hello!</h3>\n</div>','http://visualidiot.com/test.css','//visualidiot.com/test.js',1320156306,1,1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;