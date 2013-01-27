<?php

class Page extends Model {

	public static $table = 'pages';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->order_by('title')->get();

		return new Paginator($results, $count, $page, $perpage, admin_url('pages'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function home() {
		return static::find(Config::get('meta.home_page'));
	}

	public static function posts() {
		return static::find(Config::get('meta.posts_page'));
	}

	public static function dropdown() {
		$items = array();

		foreach(static::all() as $page) {
			$items[$page->id] = $page->name;
		}

		return $items;
	}

}