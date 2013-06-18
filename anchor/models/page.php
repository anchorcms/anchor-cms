<?php

class Page extends Base {

	public static $table = 'pages';

	public static function slug($slug) {
		return static::where('slug', '=', $slug)->fetch();
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

	public static function home() {
		return static::find(Config::meta('home_page'));
	}

	public static function posts() {
		return static::find(Config::meta('posts_page'));
	}

	public function uri() {
		return Uri::to($this->relative_uri());
	}

	public function relative_uri() {
		$segments = array($this->slug);
		$parent = $this->parent;

		while($parent) {
			$page = static::find($parent);
			$segments[] = $page->slug;
			$parent = $page->parent;
		}

		return implode('/', array_reverse($segments));
	}

	public function active() {
		if (Registry::prop('page', 'slug') == $this->slug || Registry::prop('page', 'parent') == $this->id) {
			return true;
		}
	}

}