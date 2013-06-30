<?php

class Post extends Base {

	public static $table = 'posts';

	public static function slug($slug) {
		return static::left_join('users', 'users.id', '=', 'posts.author')
			->where('posts.slug', '=', $slug)
			->fetch(array(
				'posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'
			));
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
		$posts = $query->sort('posts.created', 'desc')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(array(
				'posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'
			));

		return array($total, $posts);
	}

	public static function search($term, $page = 1, $per_page = 10) {
		$query = static::left_join('users', 'users.id', '=', 'posts.author')
			->left_join('post_meta', 'post_meta.post', '=', 'posts.id')
			->where('posts.status', '=', 'published')
			->where('posts.title', 'like', '%' . $term . '%')
			->or_where('posts.description', 'like', '%' . $term . '%')
			->or_where('posts.html', 'like', '%' . $term . '%')
			->or_where('post_meta.data', 'like', '%' . $term . '%');

		$total = $query->count();

		$posts = $query->take($per_page)
			->skip(--$page * $per_page)
			->group('posts.id')
			->get(array(
				'posts.*',
				'users.id as author_id',
				'users.bio as author_bio',
				'users.real_name as author_name'
			));

		return array($total, $posts);
	}

	public function total_comments() {
		return Comment::where('status', '=', 'approved')->where('post', '=', $this->id)->count();
	}

}