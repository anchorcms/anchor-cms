<?php

class Upgrader {

	public static $version, $ext = '.tar.gz';

	public static function import() {
		// Store the old config data in the session

		// 0.7 and lower
		if(is_readable($path = PATH . 'config.php')) {
			define('IN_CMS', true);

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
			else {
				$config['database']['port'] = $config['database']['port'];
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
/*
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

		// copy
		$command = 'cp --recursive ' . APP . 'storage/anchor-cms-' . static::$version . '/* ' . $path;
		Os::exec($command);

		// clean up
		$command = 'rm -rf ' . APP . 'storage/anchor-cms-' . static::$version;
		Os::exec($command);
	}
*/
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

		/*
			Categories
		*/

		$sql = "CREATE TABLE `categories` (
			`id` int(6) NOT NULL AUTO_INCREMENT,
			`title` varchar(150) NOT NULL,
			`slug` varchar(40) NOT NULL,
			`description` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB CHARSET=utf8";

		$connection->query($sql);

		$sql = "INSERT INTO `categories` (`title`, `slug`, `description`) VALUES
			('Uncategorised', 'uncategorised', 'Ain\'t no category here.');";
		$connection->query($sql);

		/*
			Comments
		*/
		$sql = "ALTER TABLE `comments`
		CHANGE `status` `status` enum('pending','approved','spam') NOT NULL AFTER `post`,
		CHANGE `date` `date` datetime NOT NULL AFTER `status`,
		COMMENT='';";
		$connection->query($sql);

		/*
			Extend
		*/
		$sql = "CREATE TABLE `extend` (
			`id` int(6) NOT NULL AUTO_INCREMENT,
			`type` enum('post','page') NOT NULL,
			`field` enum('text','html','image','file') NOT NULL,
			`key` varchar(160) NOT NULL,
			`label` varchar(160) NOT NULL,
			`attributes` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB CHARSET=utf8;";
		$connection->query($sql);

		/*
			Page metadata
		*/
		$sql = "CREATE TABLE `page_meta` (
			`id` int(6) NOT NULL AUTO_INCREMENT,
			`page` int(6) NOT NULL,
			`extend` int(6) NOT NULL,
			`data` text NOT NULL,
			PRIMARY KEY (`id`),
			KEY `page` (`page`),
			KEY `extend` (`extend`)
		) ENGINE=InnoDB CHARSET=utf8;";
		$connection->query($sql);

		/*
			Post metadata
		*/
		$sql = "CREATE TABLE `post_meta` (
			`id` int(6) NOT NULL AUTO_INCREMENT,
			`post` int(6) NOT NULL,
			`extend` int(6) NOT NULL,
			`data` text NOT NULL,
			PRIMARY KEY (`id`),
			KEY `item` (`post`),
			KEY `extend` (`extend`)
		) ENGINE=InnoDB CHARSET=utf8;";
		$connection->query($sql);

		/*
			Posts
		*/
		$sql = "ALTER TABLE `posts`
		DROP `custom_fields`,
		CHANGE `created` `created` datetime NOT NULL AFTER `js`,
		ADD `category` int(6) NOT NULL AFTER `author`,
		COMMENT='';";
		$connection->query($sql);

		/*
			Sessions
		*/
		$sql = "ALTER TABLE `sessions`
		DROP `ip`,
		DROP `ua`,
		COMMENT='';";
		$connection->query($sql);

		/*
			Users
		*/
		$sql = "ALTER TABLE `users`
		CHANGE `password` `password` text NOT NULL AFTER `username`,
		COMMENT='';";
		$connection->query($sql);
	}

	public static function config_database() {
		$database = Session::get('config.database');
		$distro = file_get_contents(APP . 'storage/database.distro.php');

		$data = array(
			"'hostname' => 'localhost'" => "'hostname' => '" . $database['host'] . "'",
			"'port' => 3306" => "'port' => '" . $database['port'] . "'",
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