<?php

class Page extends Base {

	public static $table = 'pages';

	public static function input() {
		return filter_var_array(Input::get(), array(
			'parent' => FILTER_SANITIZE_NUMBER_INT,
			'name' => FILTER_SANITIZE_STRING,
			'title' => FILTER_SANITIZE_STRING,
			'slug' => FILTER_SANITIZE_URL,
			'content' => FILTER_UNSAFE_RAW,
			'status' => FILTER_SANITIZE_STRING,
			'redirect' => FILTER_SANITIZE_URL,
			'show_in_menu' => FILTER_SANITIZE_NUMBER_INT
		));
	}

	public static function validate(&$input, $existing_id = 0) {
		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);

		// make menu order a int
		$input['menu_order'] = 0;

		$validator = new Validator($input);

		$validator->add('duplicate', function($str) use($existing_id) {
			return Page::where('slug', '=', $str)->where('id', '<>', $existing_id)->count() == 0;
		});

		$validator->check('title')
			->is_max(2, __('pages.title_missing'));

		$validator->check('slug')
			->is_max(2, __('pages.slug_missing'))
			->is_duplicate(__('pages.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('pages.slug_invalid'));

		if($input['redirect']) {
			$validator->check('redirect')
				->is_url( __('pages.redirect_missing'));
		}

		return $validator->errors();
	}

	public static function create($input) {
		if(empty($input['name'])) {
			$input['name'] = $input['title'];
		}

		$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

		$page = parent::create($input);

		Extend::save_custom_fields('page', $page->id);

		return $page;
	}

	public static function update($id, $input) {
		if(empty($input['name'])) {
			$input['name'] = $input['title'];
		}

		$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

		Extend::save_custom_fields('page', $id);

		return parent::update($id, $input);
	}

	public static function slug($slug) {
		return static::where('slug', '=', $slug)->fetch();
	}

	public static function dropdown($params = array()) {
		$items = array();
		$exclude = array();

		if(isset($params['show_empty_option']) and $params['show_empty_option']) {
			$items[0] = 'None';
		}

		if(isset($params['exclude'])) {
			$exclude = (array) $params['exclude'];
		}

		foreach(static::get() as $page) {
			if(in_array($page->id, $exclude)) continue;

			$items[$page->id] = $page->name;
		}

		return $items;
	}

	public static function home() {
		return static::find(Config::meta('home_page'));
	}

	public static function posts() {
		return static::find(Config::meta('posts_page'));
	}

	public function uri() {
		return Uri::to($this->relative_uri());
	}

	public function relative_uri() {
		$segments = array($this->slug);
		$parent = $this->parent;

		while($parent) {
			$page = static::find($parent);
			$segments[] = $page->slug;
			$parent = $page->parent;
		}

		return implode('/', array_reverse($segments));
	}

	public function active() {
		return (Registry::prop('page', 'slug') == $this->slug or
			Registry::prop('page', 'parent') == $this->id);
	}

	public function delete() {
		Query::table('page_meta')->where('page', '=', $this->id)->delete();
		parent::delete();
	}

	public function content() {
		// swap out shortcodes {{meta_key}}
		$parsed = parse($this->content);

		return Markdown::defaultTransform($parsed);
	}

	public function custom_field($key, $default = '') {
		$custom_field = Extend::where('data_type', '=', 'page')->where('key', '=', $key)->fetch();

		if( ! $custom_field) return $default;

		// get custom field data
		$data = Query::table('page_meta')->where('page', '=', $this->id)
			->where('extend', '=', $custom_field->id)->fetch();

		// only set the value if we have data
		if($data) $custom_field->value = Json::decode($data);

		$custom_field_type = Type::create($custom_field->field_type, $custom_field);

		return $custom_field_type->value($default);
	}

}