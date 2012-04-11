<?php

/**
	0.4 --> 0.5
*/

if(Schema::has('users', 'email') === false) {
	$sql = "alter table `users` add `email` varchar( 140 ) not null after `password`";
	Migrations::query($sql);
}

if(Schema::has('posts', 'comments') === false) {
	$sql = "alter table `posts` add `comments` tinyint( 1 ) not null";
	Migrations::query($sql);
}

if(Schema::has('posts', 'custom_fields') === false) {
	$sql = "alter table `posts` add `custom_fields` text not null after `js`";
	Migrations::query($sql);
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
Migrations::query($sql);

// rename show_posts
$sql = "update `meta` set `value` = 'posts_page' where `value` = 'show_posts'";
Migrations::query($sql);

// make posts_page the home page
if(Schema::has('meta', 'key', 'home_page') === false) {
	$posts_page = Db::column("select `value` from meta where `key` = 'show_posts'");

	$sql = "insert into `meta` (`key`, `value`) values ('home_page', '" . $posts_page . "')";
	Migrations::query($sql);
}

// [BUGFIX] make sure the password field is big enough
$sql = "alter table `users` change `password` `password` text character set utf8 COLLATE utf8_general_ci not null";
Migrations::query($sql);

// update config
if(Config::get('application.admin_folder') === false) {
	Config::set('application.admin_folder', 'admin');
}

if(Config::get('application.key') === false) {
	Config::set('application.key', Str::random(40));
}

/**
	0.5 --> 0.6
*/
$sql = "create table if not exists `sessions` (
	`id` char( 32 ) not null ,
	`date` datetime not null ,
	`ip` varchar( 15 ) not null ,
	`ua` varchar( 140 ) not null ,
	`data` text not null
) engine=innodb charset=utf8 collate=utf8_general_ci;";
Migrations::query($sql);

// comments auto published option
if(Schema::has('meta', 'key', 'auto_published_comments') === false) {
	$sql = "insert into `meta` (`key`, `value`) values ('auto_published_comments', '0')";
	Migrations::query($sql);
}

// pagination
if(Schema::has('meta', 'key', 'posts_per_page') === false) {
	$sql = "insert into `meta` (`key`, `value`) values ('posts_per_page', '10')";
	Migrations::query($sql);
}

// update config
if(Config::get('session') === false) {
	Config::set('session.name', 'anchorcms');
	Config::set('session.expire', 3600);
	Config::set('session.path', '/');
	Config::set('session.domain', '');
}

if(Config::get('error') === false) {
	Config::set('error.ignore', array(E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED));
	Config::set('error.detail', true);
	Config::set('error.log', false);
}

/**
	0.6 --> 0.7
*/
if(Schema::has('pages', 'redirect') === false) {
	$sql = "alter table `pages` add `redirect` varchar( 150 ) not null";
	Migrations::query($sql);
}

Config::set('foreign_characters', array(
	'/æ|ǽ/' => 'ae',
	'/œ/' => 'oe',
	'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|А/' => 'A',
	'/à|á|â|ã|ä|å|ǻ|ā|ă|ą|ǎ|ª|а/' => 'a',
	'/Б/' => 'B',
	'/б/' => 'b',
	'/Ç|Ć|Ĉ|Ċ|Č|Ц/' => 'C',
	'/ç|ć|ĉ|ċ|č|ц/' => 'c',
	'/Ð|Ď|Đ|Д/' => 'Dj',
	'/ð|ď|đ|д/' => 'dj',
	'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Ё|Э/' => 'E',
	'/è|é|ê|ë|ē|ĕ|ė|ę|ě|е|ё|э/' => 'e',
	'/Ф/' => 'F',
	'/ƒ|ф/' => 'f',
	'/Ĝ|Ğ|Ġ|Ģ|Г/' => 'G',
	'/ĝ|ğ|ġ|ģ|г/' => 'g',
	'/Ĥ|Ħ|Х/' => 'H',
	'/ĥ|ħ|х/' => 'h',
	'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|И/' => 'I',
	'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|и/' => 'i',
	'/Ĵ|Й/' => 'J',
	'/ĵ|й/' => 'j',
	'/Ķ|К/' => 'K',
	'/ķ|к/' => 'k',
	'/Ĺ|Ļ|Ľ|Ŀ|Ł|Л/' => 'L',
	'/ĺ|ļ|ľ|ŀ|ł|л/' => 'l',
	'/М/' => 'M',
	'/м/' => 'm',
	'/Ñ|Ń|Ņ|Ň|Н/' => 'N',
	'/ñ|ń|ņ|ň|ŉ|н/' => 'n',
	'/Ö|Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|О/' => 'O',
	'/ö|ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|о/' => 'o',
	'/П/' => 'P',
	'/п/' => 'p',
	'/Ŕ|Ŗ|Ř|Р/' => 'R',
	'/ŕ|ŗ|ř|р/' => 'r',
	'/Ś|Ŝ|Ş|Ș|Š|С/' => 'S',
	'/ś|ŝ|ş|ș|š|ſ|с/' => 's',
	'/Ţ|Ț|Ť|Ŧ|Т/' => 'T',
	'/ţ|ț|ť|ŧ|т/' => 't',
	'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ü|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|У/' => 'U',
	'/ù|ú|û|ũ|ū|ŭ|ů|ü|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|у/' => 'u',
	'/В/' => 'V',
	'/в/' => 'v',
	'/Ý|Ÿ|Ŷ|Ы/' => 'Y',
	'/ý|ÿ|ŷ|ы/' => 'y',
	'/Ŵ/' => 'W',
	'/ŵ/' => 'w',
	'/Ź|Ż|Ž|З/' => 'Z',
	'/ź|ż|ž|з/' => 'z',
	'/Æ|Ǽ/' => 'AE',
	'/ß/'=> 'ss',
	'/Ĳ/' => 'IJ',
	'/ĳ/' => 'ij',
	'/Œ/' => 'OE',
	'/Ч/' => 'Ch',
	'/ч/' => 'ch',
	'/Ю/' => 'Ju',
	'/ю/' => 'ju',
	'/Я/' => 'Ja',
	'/я/' => 'ja',
	'/Ш/' => 'Sh',
	'/ш/' => 'sh',
	'/Щ/' => 'Shch',
	'/щ/' => 'shch',
	'/Ж/' => 'Zh',
	'/ж/' => 'zh',
));

if(Config::get('debug', null) === null) {
	Config::set('debug', false);
}

if(Config::get('database.collation', null) === null) {
	Config::set('database.collation', 'utf8_bin');
}