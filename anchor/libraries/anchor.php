<?php

class Anchor {

	public static function setup() {
		// check installation and show intro
		static::installation();

		// load meta data from the dtabaase to the config
		static::meta();

		// import theming functions
		static::functions();

		// populate registry with globals
		static::register();

		// check mirgrations are up to date
		static::migrations();

		// load plugins
		static::plugins();
	}

	public static function installation() {
		if( ! is_installed()) {
			echo View::create('intro')->yield();

			exit(0);
		}
	}

	public static function meta() {
		// load database metadata
		foreach(Query::table('meta')->get() as $item) {
			$meta[$item->key] = $item->value;
		}

		Config::set('meta', $meta);
	}

	public static function functions() {
		if( ! is_admin()) {
			$fi = new FilesystemIterator(APP . 'functions', FilesystemIterator::SKIP_DOTS);

			foreach($fi as $file) {
				if($file->isFile() and $file->isReadable() and '.' . $file->getExtension() == EXT) {
					require $file->getPathname();
				}
			}

			// include theme functions
			if(is_readable($path = PATH . 'themes' . DS . Config::meta('theme') . DS . 'functions.php')) {
				require $path;
			}
		}
	}

	public static function register() {
		// register home page
		Registry::set('home_page', Page::home());

		// register posts page
		Registry::set('posts_page', Page::posts());

		if( ! is_admin()) {
			// register categories
			foreach(Category::get() as $itm) {
				$categories[$itm->id] = $itm;
			}

			Registry::set('all_categories', $categories);

			// register menu items
			$pages = Page::where('status', '=', 'published')
				->where('show_in_menu', '=', '1')
				->sort('menu_order')
				->get();

			$pages = new Items($pages);

			Registry::set('menu', $pages);
			Registry::set('total_menu_items', $pages->length());
		}
	}

	public static function migrations() {
		$current = Config::meta('current_migration');
		$migrate_to = Config::migrations('current');
		$migrations = new Migrations($current);

		if(is_null($current)) {
			$number = $migrations->up($migrate_to);

			Query::table('meta')->insert(array(
				'key' => 'current_migration',
				'value' => $number
			));
		}
		else if($current < $migrate_to) {
			$number = $migrations->up($migrate_to);
			Query::table('meta')->where('key', '=', 'current_migration')->update(array('value' => $number));
		}
	}

	public static function plugins() {
		$active = Plugin::installed();

		foreach($active as $item) {
			if($plugin = $item->instance()) {
				// call parent hooks
				$plugin->apply_routes();

				// call admin only hooks
				$plugin->apply_protected_routes();

				// content filters
				$plugin->apply_filters();

				// include theme functions
				$plugin->include_functions();

				unset($plugin);
			}
		}

		unset($active);
	}

	public static function check_version() {
		// first time
		if( ! $last = Config::meta('last_update_check')) {
			$last = static::setup_version_check();
		}

		$today = new DateTime('now', new DateTimeZone('GMT'));
		$last = new DateTime($last, new DateTimeZone('GMT'));

		$interval = $today->diff($last)->format('%d');

		// was update in the last 30 days
		if($interval > 30) {
			static::renew_version();
		}
	}

	public static function setup_version_check() {
		$version = static::get_latest_version();
		$today = gmdate('Y-m-d');

		Query::table('meta')->insert(array('key' => 'last_update_check', 'value' => $today));
		Query::table('meta')->insert(array('key' => 'update_version', 'value' => $version));

		// reload database metadata
		foreach(Query::table('meta')->get() as $item) {
			$meta[$item->key] = $item->value;
		}

		Config::set('meta', $meta);

		return $today;
	}

	public static function renew_version() {
		$version = static::get_latest_version();
		$today = gmdate('Y-m-d');

		Query::table('meta')->where('key', '=', 'last_update_check')->update(array('value' => $today));
		Query::table('meta')->where('key', '=', 'update_version')->update(array('value' => $version));

		// reload database metadata
		static::meta();
	}

	public static function get_latest_version() {
		$url = 'http://anchorcms.com/version';

		if(in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
			try {
				$context = stream_context_create(array('http' => array('timeout' => 2)));
				$result = file_get_contents($url, false, $context);
			} catch(Exception $e) {}
		}
		else if(function_exists('curl_init')) {
			$session = curl_init();

			curl_setopt_array($session, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true
			));

			$result = curl_exec($session);

			curl_close($session);
		}
		else {
			$result = false;
		}

		return $result;
	}

}