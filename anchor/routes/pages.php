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

		// custom fields
		$vars['fields'] = Extend::fields('page');

		return View::create('pages/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer')
			->partial('editor', 'partials/editor');
	});

	Route::post('admin/pages/edit/(:num)', function($id) {
		$input = Page::input();

		if($errors = Page::validate($input, $id)) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/pages/edit/' . $id);
		}

		Page::update($id, $input);

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
		$input = Page::input();

		if($errors = Page::validate($input)) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/pages/add');
		}

		Page::create($input);

		Notify::success(__('pages.created'));

		return Response::redirect('admin/pages');
	});

	/*
		Delete Page
	*/
	Route::get('admin/pages/delete/(:num)', function($id) {
		$page = Page::find($id);

		// dont delete pages that are set as the posts page or home page
		if($page->id == Config::meta('home_page')) {
			Notify::success(__('pages.cannot_delete_home_page'));

			return Response::redirect('admin/pages');
		}

		if($page->id == Config::meta('posts_page')) {
			Notify::success(__('pages.cannot_delete_posts_page'));

			return Response::redirect('admin/pages');
		}

		$page->delete();

		Notify::success(__('pages.deleted'));

		return Response::redirect('admin/pages');
	});

	/*
		Upload a image
	*/
	Route::post('admin/pages/upload', function() {
		$uploader = new Uploader(PATH . 'content', array('png', 'jpg', 'bmp', 'gif'));
		$filepath = $uploader->upload($_FILES['file']);

		$uri = Config::app('url', '/') . '/content/' . basename($filepath);
		$output = array('uri' => $uri);

		return Response::json($output);
	});

});