<?php

use System\Database\Connection;

class Installer {

	/*
		Install
	*/

	public static function run() {
		// create database connection
		static::connect();

		// install tables
		static::schema();

		// insert metadata
		static::metadata();

		// create user account
		static::account();

		// write database config
		static::database();

		// write application config
		static::application();

		// install htaccess file
		static::rewrite();
	}

	private static function connect() {
		$database = Session::get('install.database');

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

	private static function schema() {
		$sql = file_get_contents(APP . 'storage/anchor.sql');

		// swap place holders
		$data = array(
			'[[now]]' => date('Y-m-d H:i:s')
		);

		foreach($data as $search => $replace) {
			$sql = str_replace($search, $replace, $sql);
		}

		DB::connection('install')->query($sql);
	}

	private static function metadata($metadata) {
		$metadata = Session::get('install.metadata');

		$query = new Query('meta', 'install');

		foreach(array(
			'sitename' => $metadata['site_name'],
			'description' => $metadata['site_description'],
			'theme' => $metadata['theme']
		) as $key => $value) {
			$query->insert(array('key' => $key, 'value' => $value));
		}
	}

	private static function account($account) {
		$account = Session::get('install.account');

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
		$database = Session::get('install.database');

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

	private static function application($settings) {
		$settings = Session::get('install');

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

	private static function rewrite() {
		if(is_apache()) {
			$htpath = PATH . '.htaccess';
			$ht = file_get_contents(PATH . 'htaccess.txt');

			if(is_cgi()) {
				$ht = str_replace('index.php/$1', 'index.php?/$1', $ht);
			}

			file_put_contents($htpath, $ht);
		}
	}

}