<?php

class Post extends Base {

	public static $table = 'posts';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, admin_url('posts'));
	}

	public static function slug($slug) {
		return static::where('slug', 'like', $slug)->fetch();
	}

	public static function listing($category = null, $page = 1, $per_page = 10) {
		// get total
		$query = static::where('status', '=', 'published');

		if($category) $query->where('category', '=', $category->id);

		$total = $query->count();

		// get posts
		$query = static::where('status', '=', 'published');

		if($category) $query->where('category', '=', $category->id);

		$posts = $query->sort('created', 'desc')
			->take($per_page)
			->skip(--$page * $per_page)->get();

		return array($total, $posts);
	}

	public static function search($term, $page = 1, $per_page = 10) {
		$total = static::where('status', '=', 'published')
			->where('title', 'like', '%' . $term . '%')
			->count();

		$posts = static::where('status', '=', 'published')
			->where('title', 'like', '%' . $term . '%')
			->take($per_page)
			->skip(--$page * $per_page)
			->get();

		return array($total, $posts);
	}

}