<?php

class Post extends Model {

	public static $table = 'posts';

	public static function paginate($page = 1, $perpage = 10) {
		$query = query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->order_by('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, url('posts'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function parse($str) {
		// process tags
		if(preg_match_all('/[\{\{|\[\[]+([a-z]+)[\}\}|\]\]]+/i', $str, $matches)) {
			list($search, $replace) = $matches;

			foreach($replace as $index => $key) {
				$replace[$index] = Config::get('meta.' . $key);
			}

			$str = str_replace($search, $replace, $str);
		}

		return $str;
	}

}