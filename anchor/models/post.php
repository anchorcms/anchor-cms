<?php

class Post extends Base {

	public static $table = 'posts';

	private static function query() {
		return static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('posts.author'));
	}

	private static function select() {
		return array(
			Base::table('posts.*'),
			Base::table('users.id as author_id'),
			Base::table('users.bio as author_bio'),
			Base::table('users.real_name as author_name')
		);
	}

	public static function slug($slug) {
		return static::query()
			->where(Base::table('posts.slug'), '=', $slug)
			->fetch(static::select());
	}

	public static function listing($category = null, $page = 1, $per_page = 10) {
		// get total
		$query = static::query()
			->where(Base::table('posts.status'), '=', 'published');

		if($category) {
			$query->where(Base::table('posts.category'), '=', $category->id);
		}

		$total = $query->count();

		// get posts
		$posts = $query->sort(Base::table('posts.created'), 'desc')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(static::select());

		return array($total, $posts);
	}

	public static function search($term, $page = 1, $per_page = 10) {
		$query = static::query()
			->where(Base::table('posts.status'), '=', 'published')
			->where(Base::table('posts.title'), 'like', '%' . $term . '%');

		$total = $query->count();

		$posts = $query->take($per_page)
			->skip(--$page * $per_page)
			->get(static::select());

		return array($total, $posts);
	}

	public function total_comments() {
		return Comment::where('status', '=', 'approved')->where('post', '=', $this->id)->count();
	}

}