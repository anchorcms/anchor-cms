<?php

class Post extends Record {

	public static $table = 'posts';

	public static function paginate($page = 1, $perpage = 10) {
		$query = query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, admin_url('posts'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

}