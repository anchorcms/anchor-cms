<?php

class Anchor {

	public static function setup() {
		// check installation and show intro
		static::installation();

		// load meta data from the database to the config
		static::meta();

		// import theming functions
		static::functions();

		// populate registry with globals
		static::register();

		// check migrations are up to date
		static::migrations();

		// load plugins
		static::plugins();
	}

	public static function installation() {
		if( ! is_installed()) {
			echo View::create('intro')->render();

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
			$fi = new Filesystem(APP . 'functions', Filesystem::SKIP_DOTS);

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
		Registry::set(array(
			'home_page' => Page::home(),
			'posts_page' => Page::posts()));

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

			Registry::set(array(
				'menu' => $pages,
				'total_menu_items' => $pages->length()));
		}
	}

	public static function migrations() {
		$current = Config::meta('current_migration');
		$migrate_to = Config::migrations('current');
		$migrations = new Migrations($current);
		$query = Query::table('meta');

		if(is_null($current)) {
			$number = $migrations->up($migrate_to);

			$query->insert(array(
				'key' => 'current_migration',
				'value' => $number
			));
		}
		else if($current < $migrate_to) {
			$number = $migrations->up($migrate_to);
			$query->where('key', '=', 'current_migration')->update(array('value' => $number));
		}
	}

	public static function plugins() {
		$active = Plugin::installed();

		foreach($active as $item) {
			if($plugin = $item->instance()) {
				$plugin->apply_routes()
					->apply_protected_routes()
					->apply_filters()
					->include_functions();

				unset($plugin);
			}
		}

		unset($active);
	}

	public static function page_not_found() {
		$template = new Template('404');

		return Response::create($template->render(), 404);
	}

}