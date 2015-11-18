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

	public function children() {
		$query = static::where(Base::table('pages.parent'), '=', $this->data['id'])->sort(Base::table('pages.title'));
		return $query->get(array(Base::table('pages.*')));
	}
	
	public static function search($term, $pageNum = 1, $per_page = 10) {
		$query = static::where(Base::table('pages.status'), '=', 'published')
			->where(Base::table('pages.name'), 'like', '%' . $term . '%');
			//->or_where(Base::table('pages.content'), 'like', '%' . $term . '%'); // This could cause problems?
		
		$total =$query->count();
		
		$pages = $query->take($per_page)
			->skip(--$pageNum * $per_page)
			->get(array(Base::table('pages.*')));
		
		foreach($pages as $key => $page) {
			if($page->data['status'] !== 'published') {
				unset($pages[$key]);
			}
		}
		
		if(count($pages) < 1) $total = 0;
		
		return array($total, $pages);
	}
}
