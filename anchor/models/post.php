<?php

class Post extends Model {

	public static $table = 'posts';

	public static function paginate($page = 1, $perpage = 10) {
		$query = query::table(static::$table);

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->order_by('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, admin_url('posts'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function parse($str) {
		// process tags
		//$pattern = '/[\{\{|\[\[]+([a-z]+)[\}\}|\]\]]+/i';
		$pattern = '/[\{\{]{1}([a-z]+)[\}\}]{1}/i';

		if(preg_match_all($pattern, $str, $matches)) {
			list($search, $replace) = $matches;

			foreach($replace as $index => $key) {
				$replace[$index] = Config::get('meta.' . $key);
			}

			$str = str_replace($search, $replace, $str);
		}

		$md = new Markdown;

		return $md->transform($str);
	}

}