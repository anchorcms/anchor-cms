<?php

class Post extends Base {

	public static $table = 'posts';

	public static function input() {
		return filter_var_array(Input::get(), array(
			'title' => FILTER_SANITIZE_STRING,
			'slug' => FILTER_SANITIZE_URL,
			'description' => FILTER_SANITIZE_STRING,
			'created' => FILTER_SANITIZE_STRING,
			'html' => FILTER_UNSAFE_RAW,
			'css' => FILTER_UNSAFE_RAW,
			'js' => FILTER_UNSAFE_RAW,
			'category' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
			'comments' => FILTER_SANITIZE_NUMBER_INT
		));
	}

	public static function validate(&$input, $existing_id = 0) {
		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);

		$validator = new Validator($input);

		$validator->add('duplicate', function($str) use($existing_id) {
			return Post::where('slug', '=', $str)
				->where(Post::$primary, '<>', $existing_id)->count() == 0;
		});

		$validator->check('title')
			->is_max(2, __('posts.title_missing'));

		$validator->check('slug')
			->is_max(2, __('posts.slug_missing'))
			->is_duplicate(__('posts.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('posts.slug_invalid'));

		return $validator->errors();
	}

	public static function create($input) {
		if(empty($input['created'])) {
			$input['created'] = Date::mysql('now');
		}

		$user = Auth::user();

		$input['author'] = $user->id;

		if(is_null($input['comments'])) {
			$input['comments'] = 0;
		}

		if(empty($input['html'])) {
			$input['status'] = 'draft';
		}

		$post = parent::create($input);

		Extend::save_custom_fields('post', $post->id);

		return $post;
	}

	public static function update($id, $input) {
		if($input['created']) {
			$input['created'] = Date::mysql($input['created']);
		}
		else {
			unset($input['created']);
		}

		if(is_null($input['comments'])) {
			$input['comments'] = 0;
		}

		if(empty($input['html'])) {
			$input['status'] = 'draft';
		}

		Extend::save_custom_fields('post', $id);

		return parent::update($id, $input);
	}

	public function delete() {
		Comment::where('post', '=', $this->id)->delete();
		Query::table('post_meta')->where('post', '=', $this->id)->delete();
		return parent::delete();
	}

	/**
	 * Find a post via the slug
	 *
	 * @param string
	 * @return false|object
	 */
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
			->skip(($page - 1) * $per_page)
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
			->skip(($page - 1) * $per_page)
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
		return Comment::where('status', '=', 'approved')
			->where('post', '=', $this->id)->count();
	}

	public function content() {
		// swap out shortcodes {{meta_key}}
		$parsed = parse($this->html);

		return Markdown::defaultTransform($parsed);
	}

	public function custom_field($key, $default = '') {
		$custom_field = Extend::where('data_type', '=', 'post')->where('key', '=', $key)->fetch();

		if( ! $custom_field) return $default;

		// get custom field data
		$data = Query::table('post_meta')->where('post', '=', $this->id)
			->where('extend', '=', $custom_field->id)->fetch();

		// only set the value if we have data
		if($data) $custom_field->value = Json::decode($data);

		$custom_field_type = Type::create($custom_field->field_type, $custom_field);

		return $custom_field_type->value($default);
	}

}