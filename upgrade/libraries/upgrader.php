<?php

class Upgrader {

	public static $version, $ext = '.tar.gz';

	public static function import() {
		// Store the old config data in the session

		// 0.7 and lower
		if(is_readable($path = PATH . 'config.php')) {
			$config = require $path;

			$config['database']['user'] = $config['database']['username'];
			unset($config['database']['username']);

			$config['database']['pass'] = $config['database']['password'];
			unset($config['database']['password']);

			$config['application']['url'] = $config['application']['base_url'];
			unset($config['application']['base_url']);

			$config['application']['index'] = $config['application']['index_page'];
			unset($config['application']['index_page']);

			if( ! isset($config['database']['port'])) {
				$config['database']['port'] = 3306;
			}
		}
		// 0.8 and above
		else if(is_readable($path = PATH . 'anchor/database.php')) {
			$config['database'] = require $path;

			if(is_readable($path = PATH . 'anchor/application.php')) {
				$config['application'] = require $path;
			}
		}

		Session::put('config', $config);
	}

	public static function backup() {
		$sql = APP . 'storage/backup.sql';
		$backup = APP . 'storage/backup.tar';

		// backup database
		Dump::create($sql);

		// backup anchor
		Archive::create(PATH, $backup, array('upgrade'));

		// append sql file
		Archive::append($sql, $backup);

		// remove sql file
		unlink($sql);

		// compress archive
		Archive::compress($backup);
	}

	public static function download() {
		static::$version = file_get_contents('http://anchorcms.com/version');

		$latest = APP . 'storage/latest' . static::$ext;
		$archive = static::$version . static::$ext;

		if( ! is_readable($latest)) {
			$archive = 'https://github.com/anchorcms/anchor-cms/archive/' . $archive;

			file_put_contents($latest, file_get_contents($archive));
		}
	}

	public static function deploy($path) {
		// extract
		$latest = APP . 'storage/latest' . static::$ext;

		$command = 'tar --extract --directory=' . APP . 'storage --file=' . $latest;
		Os::exec($command);

		// clean
		/*
		$if = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

		foreach($if as $file) {
			if($file->getBasename() == 'themes' or $file->getBasename() == 'upgrade') {
				continue;
			}

			if($file->isDir()) {
				$command = 'rm -rf ' . $file->getPathname();
				Os::exec($command);
			}
			else {
				unlink($file->getPathname());
			}
		}
		*/

		// copy
		$command = 'cp --recursive ' . APP . 'storage/anchor-cms-' . static::$version . '/* ' . $path;
		Os::exec($command);

		// clean up
		$command = 'rm -rf ' . APP . 'storage/anchor-cms-' . static::$version;
		Os::exec($command);
	}

	public static function database() {
		$database = Session::get('config.database');

		$connection = DB::connect(array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'port' => $database['port'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'charset' => 'utf8'
		));

		$schema = new Schema($connection);

		/**
			0.4 --> 0.5
		*/

		if( ! $schema->has('users', 'email')) {
			$sql = "alter table `users` add `email` varchar(140) not null after `password`";
			$connection->query($sql);
		}

		if( ! $schema->has('posts', 'comments')) {
			$sql = "alter table `posts` add `comments` tinyint( 1 ) not null";
			$connection->query($sql);
		}

		if( ! $schema->has('posts', 'custom_fields') === false) {
			$sql = "alter table `posts` add `custom_fields` text not null after `js`";
			$connection->query($sql);
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
		$connection->query($sql);

		// rename show_posts
		$sql = "update `meta` set `value` = 'posts_page' where `value` = 'show_posts'";
		$connection->query($sql);

		// make posts_page the home page
		if( ! $schema->has('meta', 'key', 'home_page') === false) {
			$posts_page = $connection->column("select `value` from meta where `key` = 'show_posts'");

			$sql = "insert into `meta` (`key`, `value`) values ('home_page', '" . $posts_page . "')";
			$connection->query($sql);
		}

		// [BUGFIX] make sure the password field is big enough
		$sql = "alter table `users` change `password` `password` text character set utf8 COLLATE utf8_general_ci not null";
		$connection->query($sql);

		/**
			0.5 --> 0.6
		*/
		$sql = "create table if not exists `sessions` (
			`id` char( 32 ) not null ,
			`date` datetime not null ,
			`data` text not null
		) engine=innodb charset=utf8 collate=utf8_general_ci;";
		$connection->query($sql);

		// comments auto published option
		if( ! $schema->has('meta', 'key', 'auto_published_comments') === false) {
			$sql = "insert into `meta` (`key`, `value`) values ('auto_published_comments', '0')";
			$connection->query($sql);
		}

		// pagination
		if($schema->has('meta', 'key', 'posts_per_page') === false) {
			$sql = "insert into `meta` (`key`, `value`) values ('posts_per_page', '10')";
			$connection->query($sql);
		}

		/**
			0.6 --> 0.7
		*/
		if( ! $schema->has('pages', 'redirect') === false) {
			$sql = "alter table `pages` add `redirect` varchar( 150 ) not null";
			$connection->query($sql);
		}
	}

	public static function config_database() {
		$database = Session::get('config.database');
		$distro = file_get_contents(APP . 'storage/database.distro.php');

		$data = array(
			"'hostname' => 'localhost'" => "'hostname' => '" . $database['host'] . "'",
			"'username' => 'root'" => "'username' => '" . $database['user'] . "'",
			"'password' => ''" => "'password' => '" . $database['pass'] . "'",
			"'database' => ''" => "'database' => '" . $database['name'] . "'"
		);

		foreach($data as $search => $replace) {
			$distro = str_replace($search, $replace, $distro);
		}

		file_put_contents(PATH . 'anchor/config/database.php', $distro);
	}

	public static function config_application() {
		$application = Session::get('config.application');
		$distro = file_get_contents(APP . 'storage/application.distro.php');

		$data = array(
			"'url' => ''" => "'url' => '" . $application['url'] . "'",
			"'index' => ''" => "'index' => '" . $application['index'] . "'",
			"'key' => 'YourSecretKeyGoesHere'" => "'key' => '" . Str::random(40) . "'",
			"'language' => 'en_GB'" => "'language' => '" . $application['language'] . "'"
		);

		foreach($data as $search => $replace) {
			$distro = str_replace($search, $replace, $distro);
		}

		file_put_contents(PATH . 'anchor/config/application.php', $distro);
	}

}