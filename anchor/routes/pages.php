<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List Pages
	*/
	Route::get(array('admin/pages', 'admin/pages/(:num)'), function($page = 1) {
		$perpage = Config::get('admin.posts_per_page');
		$total = Page::where(Base::table('pages.parent'), '=', '0')->count();
		$pages = Page::sort('title')->where(Base::table('pages.parent'), '=', '0')->take($perpage)->skip(($page - 1) * $perpage)->get();
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

		$perpage = Config::get('admin.posts_per_page');
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
		$vars['deletable'] = (Page::count() > 1) && (Page::home()->id != $id) && (Page::posts()->id != $id);
		$vars['page'] = Page::find($id);
		$vars['pages'] = Page::dropdown(array('exclude' => array($id), 'show_empty_option' => true));

		$vars['pagetypes'] = Query::table(Base::table('pagetypes'))->sort('key')->get();

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		// extended fields
		$vars['fields'] = Extend::fields('page', $id, $vars['page']->pagetype);

		return View::create('pages/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/pages/edit/(:num)', function($id) {
		$input = Input::get(array('parent', 'name', 'title', 'slug', 'markdown', 'status', 'redirect', 'show_in_menu', 'pagetype'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);
		
		// an array of items that we shouldn't encode - they're no XSS threat
		$dont_encode = array('markdown');
		
		foreach($input as $key => &$value) {
			if(in_array($key, $dont_encode)) continue;
			$value = eq($value);
		}
		
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

		$input['html'] = parse($input['markdown']);

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

		$vars['pagetypes'] = Query::table(Base::table('pagetypes'))->sort('key')->get();

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
		$input = Input::get(array('parent', 'name', 'title', 'slug', 'markdown', 'status', 'redirect', 'show_in_menu', 'pagetype'));

		// if there is no slug try and create one from the title
		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// convert to ascii
		$input['slug'] = slug($input['slug']);
		
		// an array of items that we shouldn't encode - they're no XSS threat
		$dont_encode = array('markdown');
		
		foreach($input as $key => &$value) {
			if(in_array($key, $dont_encode)) continue;
			$value = eq($value);
		}
		
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

		$input['html'] = parse($input['markdown']);

		$page = Page::create($input);

		Extend::process('page', $page->id);

		Notify::success(__('pages.created'));
		
		return Response::redirect('admin/pages/edit/' . $page->id);
	});

	/*
		Delete Page
	*/
	Route::get('admin/pages/delete/(:num)', function($id) {
		if(Page::count() > 1) {
			Page::find($id)->delete();
			Query::table(Base::table('page_meta'))->where('page', '=', $id)->delete();
			Notify::success(__('pages.deleted'));
		} else {
			Notify::error('Unable to delete page, you must have at least 1 page.');
		}

		return Response::redirect('admin/pages');
	});
});
