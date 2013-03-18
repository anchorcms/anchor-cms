<?php

class Post extends Base {

	public static $table = 'posts';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/posts'));
	}

	public static function slug($slug) {
		return static::left_join('users', 'users.id', '=', 'posts.author')
			->where('posts.slug', '=', $slug)
			->fetch(array('posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'));
	}

	public static function listing($category = null, $page = 1, $per_page = 10) {
		// get total
		$query = static::left_join('users', 'users.id', '=', 'posts.author')
			->where('posts.status', '=', 'published');

		if($category) {
			$query->where('posts.category', '=', $category->id);
		}

		$total = $query->count();

		// get posts
		$posts = $query->sort('created', 'desc')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(array('posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'));

		return array($total, $posts);
	}

	public static function search($term, $page = 1, $per_page = 10) {
		$query = static::left_join('users', 'users.id', '=', 'posts.author')
			->where('posts.status', '=', 'published')
			->where('posts.title', 'like', '%' . $term . '%');

		$total = $query->count();

		$posts = $query->take($per_page)
			->skip(--$page * $per_page)
			->get(array('posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'));

		return array($total, $posts);
	}

}