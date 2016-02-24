<?php

class Post extends Base {

	public static $table = 'posts';

	public static function id($id) {
		return static::get('id', $id);
	}

	public static function slug($slug) {
		$post = static::get('slug', $slug);
		$post->total_comments = static::getCommentCount($post);
		return $post;
	}

	private static function getCommentCount($post) {
		return (int)static::left_join(Base::table('comments'), Base::table('comments.post'), '=', Base::table('posts.id'))
			->where(Base::table('posts.id'), '=', $post->id)
			->count();
	}

	private static function get($row, $val) {
		return static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('posts.author'))
			->where(Base::table('posts.'.$row), '=', $val)
			->fetch(array(Base::table('posts.*'),
				Base::table('users.id as author_id'),
				Base::table('users.bio as author_bio'),
				Base::table('users.email as author_email'),
				Base::table('users.real_name as author_name')));
	}

	public static function listing($category = null, $page = 1, $per_page = 10) {
		// get total
		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('posts.author'))
			->where(Base::table('posts.status'), '=', 'published');

		if($category) {
			$query->where(Base::table('posts.category'), '=', $category->id);
		}

		$total = $query->count();

		// get posts
		$posts = $query->sort(Base::table('posts.created'), 'desc')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(array(Base::table('posts.*'),
				Base::table('users.id as author_id'),
				Base::table('users.bio as author_bio'),
				Base::table('users.real_name as author_name')));

		return array($total, $posts);
	}

	public static function search($term, $page = 1, $per_page = 10) {
		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('posts.author'))
			->where(Base::table('posts.status'), '=', 'published')
			->where(Base::table('posts.title'), 'like', '%' . $term . '%')
			->or_where(Base::table('posts.html'), 'like', '%' . $term . '%');

		$total = $query->count();

		$posts = $query->take($per_page)
			->skip(--$page * $per_page)
			->get(array(Base::table('posts.*'),
				Base::table('users.id as author_id'),
				Base::table('users.bio as author_bio'),
				Base::table('users.real_name as author_name')));

		foreach ($posts as $key => $post) {
			if ($post->data['status'] !== 'published') {
				unset($posts[$key]);
			}
		}
		if (count($posts) < 1) {
			$total = 0;
		}

		return array($total, $posts);
	}

	public static function perPage() {
		return (Config::meta('show_all_posts') ? self::count() + 1 : Config::meta('posts_per_page'));
	}

}
