<?php

class Page extends Base {

	public static $table = 'pages';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('title')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/pages'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function dropdown($params = array()) {
		$items = array();
		$exclude = array();

		if(isset($params['show_empty_option']) and $params['show_empty_option']) {
			$items[0] = 'None';
		}

		if(isset($params['exclude'])) {
			$exclude = (array) $params['exclude'];
		}

		foreach(static::get() as $page) {
			if(in_array($page->id, $exclude)) continue;

			$items[$page->id] = $page->name;
		}

		return $items;
	}

	public function uri($relative = false) {
		$segments = array($this->slug);
		$parent = $this->parent;

		while($parent) {
			$page = static::find($parent);
			$segments[] = $page->slug;
			$parent = $page->parent;
		}

		$uri = implode('/', array_reverse($segments));

		return $relative ? $uri : Uri::to($uri);
	}

}