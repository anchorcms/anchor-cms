<?php

use System\Database\Connection;

class Installer {

	public static function run($settings) {
		// create database connection
		static::connect($settings['database']);

		// install tables
		static::schema($settings['database']);

		// insert metadata
		static::metadata($settings['metadata']);

		// create user account
		static::account($settings['account']);

		// write database config
		static::database($settings['database']);

		// write application config
		static::application($settings);
	}

	private static function connect($database) {
		$config = array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'charset' => 'utf8'
		);

		$connection = new Connection(DB::connect($config), $config);

		$connection->query('SET NAMES `utf8` COLLATE `' . $database['collation'] . '`');

		DB::$connections['install'] = $connection;
	}

	private static function schema($database) {
		$sql = file_get_contents(APP . 'storage/anchor.sql');

		// swap place holders
		$data = array(
			'[[now]]' => date('c'),
			'[[collate]]' => $database['collation']
		);

		foreach($data as $search => $replace) {
			$sql = str_replace($search, $replace, $sql);
		}

		DB::connection('install')->query($sql);
	}

	private static function metadata($metadata) {
		$query = new Query('meta', 'install');

		foreach($metadata as $key => $value) {
			$query->insert(array('key' => $key, 'value' => $value));
		}
	}

	private static function account($account) {
		$query = new Query('users', 'install');

		$query->insert(array(
			'username' => $account['username'],
			'password' => Hash::make($account['password']),
			'email' => $account['email'],
			'real_name' => 'Administrator',
			'bio' => 'The bouse',
			'status' => 'active',
			'role' => 'administrator'
		));
	}

	private static function database($database) {
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

	private static function application($settings) {
		$distro = file_get_contents(APP . 'storage/application.distro.php');

		$data = array(
			"'url' => ''" => "'url' => '" . $settings['metadata']['site_path'] . "'",
			"'key' => 'YourSecretKeyGoesHere'" => "'key' => '" . Str::random(40) . "'",
			"'language' => 'en_GB'" => "'language' => '" . $settings['language'] . "'"
		);

		foreach($data as $search => $replace) {
			$distro = str_replace($search, $replace, $distro);
		}

		file_put_contents(PATH . 'anchor/config/application.php', $distro);
	}

}