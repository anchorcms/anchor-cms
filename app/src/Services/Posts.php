<?php

namespace Services;

class Posts {

	public function __construct($posts, $postmeta, $extend) {
		$this->posts = $posts;
		$this->postmeta = $postmeta;
		$this->extend = $extend;
	}

	public function getPostsMapper() {
		return $this->posts;
	}

	public function getKeys(array $posts) {
		return array_map(function($post) { return $post->id; }, $posts);
	}

	public function hydrate(array $keys, array $posts) {
		$meta = $this->postmeta->join('anchor_extend', 'anchor_extend.id', '=', 'anchor_post_meta.extend')
			->whereIn('post', $keys)->get();

		array_walk($meta, function($row) {
			$row->data = json_decode($row->data);
		});

		foreach($posts as $post) {
			$filtered = array_filter($meta, function($row) use($post) {
				return $row->post == $post->id;
			});
			$post->setMeta($filtered);
		}
	}

	public function getPosts(array $params = []) {
		$defaults = [
			'page' => 1,
			'perpage' => 10,
			'status' => 'published',
		];

		$params = array_merge($defaults, $params);

		$offset = ($params['page'] - 1) * $params['perpage'];

		$query = $this->posts->select(['anchor_posts.*'])
			->where('status', '=', $params['status'])
			->sort('created', 'desc')
			->take($params['perpage'])
			->skip($offset);

		if(isset($params['category'])) {
			$query->join('anchor_categories', 'anchor_categories.id', '=', 'anchor_posts.category')
				->where('anchor_categories.slug', '=', $params['category']);
		}

		$posts = $query->get();

		$keys = $this->getKeys($posts);

		$this->hydrate($keys, $posts);

		return $posts;
	}

}
