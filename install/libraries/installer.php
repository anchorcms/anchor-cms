<?php

class Installer {

	// database connection
	public static $connection;

	/*
		Install
	*/

	public static function run() {
		// session data
		$settings = Session::get('install');

		// create database connection
		static::connect($settings);

		// install tables
		static::schema($settings);

		// insert metadata
		static::metadata($settings);

		// create user account
		static::account($settings);

		// write database config
		static::database($settings);

		// write application config
		static::application($settings);

		// write session config
		static::session($settings);

		// install htaccess file
		static::rewrite($settings);
	}

	private static function connect($settings) {
		$database = $settings['database'];

		$config = array(
			'driver' => 'mysql',
			'database' => $database['name'],
			'hostname' => $database['host'],
			'port' => $database['port'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'charset' => 'utf8',
			'prefix' => $database['prefix']
		);

		static::$connection = DB::factory($config);
	}

	private static function schema($settings) {
		$database = $settings['database'];

		$sql = Braces::compile(APP . 'storage/anchor.sql', array(
			'now' => gmdate('Y-m-d H:i:s'),
			'charset' => 'utf8',
			'prefix' => $database['prefix']
		));

		static::$connection->instance()->exec($sql);
	}

	private static function metadata($settings) {
		$metadata = $settings['metadata'];

		// insert basic meta data
		$meta = array(
			'auto_published_comments' => 0,
			'comment_moderation_keys' => '',
			'comment_notifications' => 0,
			'date_format' => 'jS M, Y',
			'home_page' => '1',
			'posts_page' => '1',
			'posts_per_page' => '10',
			'admin_posts_per_page' => '6',

			'sitename' => $metadata['site_name'],
			'description' => $metadata['site_description'],
			'theme' => $metadata['theme']
		);

		foreach($meta as $key => $value) {
			$query = Query::table('meta', static::$connection)->where('key', '=', $key);

			if($query->count() == 0) {
				$query->insert(compact('key', 'value'));
			}
		}

		// create the first category
		$query = Query::table('categories', static::$connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Uncategorised',
				'slug' => 'uncategorised',
				'description' => 'Ain\'t no category here.'
			));
		}

		// create the first page
		$query = Query::table('pages', static::$connection);

		if($query->count() == 0) {
			$query->insert(array(
				'slug' => 'posts',
				'name' => 'Posts',
				'title' => 'My posts and thoughts',
				'content' => 'Welcome!',
				'status' => 'published',
				'redirect' => '',
				'show_in_menu' => 1,
				'menu_order' => 0
			));
		}

		// create the first post
		$query = Query::table('posts', static::$connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Hello World',
				'slug' => 'hello-world',
				'description' => 'This is the first post.',
				'html' => 'Hello World!\r\n\r\nThis is the first post.',
				'css' => '',
				'js' => '',
				'created' => gmdate('Y-m-d H:i:s'),
				'author' => 1,
				'category' => 1,
				'status' => 'published',
				'comments' => 0
			));
		}
	}

	private static function account($settings) {
		$account = $settings['account'];

		$query = Query::table('users', static::$connection);

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

	private static function database($settings) {
		$database = $settings['database'];

		$distro = Braces::compile(APP . 'storage/database.distro.php', array(
			'hostname' => $database['host'],
			'port' => $database['port'],
			'username' => $database['user'],
			'password' => $database['pass'],
			'database' => $database['name'],
			'prefix' => $database['prefix']
		));

		file_put_contents(PATH . 'anchor/config/db.php', $distro);
	}

	private static function application($settings) {
		$distro = Braces::compile(APP . 'storage/application.distro.php', array(
			'url' => $settings['metadata']['site_path'],
			'index' => (mod_rewrite() ? '' : 'index.php'),
			'key' => noise(),
			'language' => $settings['i18n']['language'],
			'timezone' => $settings['i18n']['timezone']
		));

		file_put_contents(PATH . 'anchor/config/app.php', $distro);
	}

	private static function session($settings) {
		$distro = Braces::compile(APP . 'storage/session.distro.php', array(
			'table' => 'sessions'
		));

		file_put_contents(PATH . 'anchor/config/session.php', $distro);
	}

	private static function rewrite($settings) {
		if(mod_rewrite() or (is_apache() and $settings['metadata']['rewrite'])) {
			$htaccess = Braces::compile(APP . 'storage/htaccess.distro', array(
				'base' => $settings['metadata']['site_path'],
				'index' => (is_cgi() ? 'index.php?/$1' : 'index.php/$1')
			));

			if(isset($htaccess) and is_writable($filepath = PATH . '.htaccess')) {
				file_put_contents($filepath, $htaccess);
			}
			else {
				Session::put('htaccess', $htaccess);
			}
		}
	}

}