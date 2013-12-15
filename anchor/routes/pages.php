<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Pages
	*/
	Route::get(array('admin/pages', 'admin/pages/(:num)'), function($page = 1) {
		$perpage = Config::meta('posts_per_page');
		$total = Page::count();
		$pages = Page::sort('title')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/pages');

		$pagination = new Paginator($pages, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['pages'] = $pagination;
		$vars['status'] = 'all';

		return View::create('pages/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List pages by status and paginate through them
	*/
	Route::get(array(
		'admin/pages/status/(:any)',
		'admin/pages/status/(:any)/(:num)'), function($status, $page = 1) {

		$query = Page::where('status', '=', $status);

		$perpage = Config::meta('posts_per_page');
		$total = $query->count();
		$pages = $query->sort('title')->take($perpage)->skip(($page - 1) * $perpage)->get();
		$url = Uri::to('admin/pages/status');

		$pagination = new Paginator($pages, $total, $page, $perpage, $url);

		$vars['messages'] = Notify::read();
		$vars['pages'] = $pagination;
		$vars['status'] = $status;

		return View::create('pages/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Page
	*/
	Route::get('admin/pages/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['page'] = Page::find($id);
		$vars['pages'] = Page::dropdown(array('exclude' => array($id), 'show_empty_option' => true));

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		// extended fields
		$vars['fields'] = Extend::fields('page', $id);

		return View::create('pages/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/pages/edit/(:num)', function($id) {
		$input = Input::get(array('parent', 'name', 'title', 'slug', 'content', 'status', 'redirect', 'show_in_menu'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);

		// encode title
		$input['title'] = htmlspecialchars($input['title'], ENT_QUOTES, Config::app('encoding'), false);

		$validator = new Validator($input);

		$validator->add('duplicate', function($str) use($id) {
			return Page::where('slug', '=', $str)->where('id', '<>', $id)->count() == 0;
		});

		$validator->check('title')
			->is_max(3, __('pages.title_missing'));

		$validator->check('slug')
			->is_max(3, __('pages.slug_missing'))
			->is_duplicate(__('pages.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('pages.slug_invalid'));

		if($input['redirect']) {
			$validator->check('redirect')
				->is_url( __('pages.redirect_missing'));
		}

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/pages/edit/' . $id);
		}

		if(empty($input['name'])) {
			$input['name'] = $input['title'];
		}

		// encode title
		$input['title'] = e($input['title'], ENT_COMPAT);

		$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

		Page::update($id, $input);

		Extend::process('page', $id);

		Notify::success(__('pages.updated'));

		return Response::redirect('admin/pages/edit/' . $id);
	});

	/*
		Add Page
	*/
	Route::get('admin/pages/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['pages'] = Page::dropdown(array('exclude' => array(), 'show_empty_option' => true));

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		// extended fields
		$vars['fields'] = Extend::fields('page');

		return View::create('pages/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/pages/add', function() {
		$input = Input::get(array('parent', 'name', 'title', 'slug', 'content',
			'status', 'redirect', 'show_in_menu'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);

		// encode title
		$input['title'] = e($input['title'], ENT_COMPAT);

		$validator = new Validator($input);

		$validator->add('duplicate', function($str) {
			return Page::where('slug', '=', $str)->count() == 0;
		});

		$validator->check('title')
			->is_max(3, __('pages.title_missing'));

		$validator->check('slug')
			->is_max(3, __('pages.slug_missing'))
			->is_duplicate(__('pages.slug_duplicate'))
			->not_regex('#^[0-9_-]+$#', __('pages.slug_invalid'));

		if($input['redirect']) {
			$validator->check('redirect')
				->is_url(__('pages.redirect_missing'));
		}

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/pages/add');
		}

		if(empty($input['name'])) {
			$input['name'] = $input['title'];
		}

		$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

		$page = Page::create($input);

		Extend::process('page', $page->id);

		Notify::success(__('pages.created'));

		return Response::redirect('admin/pages');
	});

	/*
		Delete Page
	*/
	Route::get('admin/pages/delete/(:num)', function($id) {
		Page::find($id)->delete();

		Query::table(Base::table('page_meta'))->where('page', '=', $id)->delete();

		Notify::success(__('pages.deleted'));

		return Response::redirect('admin/pages');
	});

});