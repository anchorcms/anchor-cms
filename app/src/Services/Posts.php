<?php

namespace Services;

class Posts {

	public function __construct($posts, $postmeta, $extend, $users, $categories) {
		$this->posts = $posts;
		$this->postmeta = $postmeta;
		$this->extend = $extend;
		$this->users = $users;
		$this->categories = $categories;
	}

	public function getMapper() {
		return $this->posts;
	}

	protected function getKeys(array $posts, $property = 'id') {
		return array_map(function($post) use($property) { return $post->getAttribute($property); }, $posts);
	}

	public function hydrate(array $posts) {
		if(empty($posts)) {
			// nothing to do.
			return;
		}

		$keys = $this->getKeys($posts);

		$prefix = $this->posts->getTablePrefix();

		$meta = $this->postmeta->join($prefix . 'custom_fields', $prefix . 'custom_fields.id', '=', $prefix . 'post_meta.custom_field')
			->whereIn('post', $keys)->get();

		array_walk($meta, function($row) {
			$row->data = json_decode($row->data);
		});

		$keys = $this->getKeys($posts, 'author');

		$users = $this->users->whereIn('id', $keys)->get();

		$keys = $this->getKeys($posts, 'category');

		$categories = $this->categories->whereIn('id', $keys)->get();

		foreach($posts as $post) {
			$filtered = array_filter($meta, function($row) use($post) {
				return $row->post == $post->id;
			});

			$post->setMeta($filtered);

			$author = array_reduce($users, function($carry, $row) use($post) {
				return $row->id == $post->author ? $row : $carry;
			});

			$post->setAuthor($author);

			$category = array_reduce($categories, function($carry, $row) use($post) {
				return $row->id == $post->category ? $row : $carry;
			});

			$post->setCategory($category);
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

		$query = $this->posts->where('status', '=', $params['status'])
			->sort('created', 'desc')
			->take($params['perpage'])
			->skip($offset);

		if(isset($params['category'])) {
			$query->where('category', '=', $params['category']);
		}

		$posts = $query->get();

		$this->hydrate($posts);

		return $posts;
	}

}
