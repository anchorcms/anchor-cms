<?php

class Category extends Base {

	public static $table = 'categories';

	public static function dropdown() {
		$items = array();
		$query = Query::table(static::table());
		foreach($query->sort('title')->get() as $item) {
			$items[$item->id] = $item->title;
		}

		return $items;
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('title')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/categories'));
	}

}
