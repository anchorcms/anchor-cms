<?php

/*
	List Pages
*/
Route::get(array('admin/pages', 'admin/pages/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['pages'] = Page::paginate($page, Config::get('meta.posts_per_page'));

	return View::create('pages/index', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer');
}));

/*
	Edit Page
*/
Route::get('admin/pages/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['page'] = Page::find($id);
	$vars['pages'] = Page::dropdown(array('exclude' => array($id), 'show_empty_option' => true));
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page', $id);

	return View::create('pages/edit', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer')
		->partial('editor', 'partials/editor');
}));

Route::post('admin/pages/edit/(:num)', array('before' => 'auth', 'main' => function($id) {
	$input = Input::get(array('parent', 'name', 'title', 'slug', 'content', 'status', 'redirect', 'show_in_menu'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('pages.missing_title'));

	if($input['redirect']) {
		$validator->check('redirect')
			->is_url( __('pages.missing_redirect', 'Please enter a valid url'));
	}

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/pages/edit/' . $id);
	}

	if(empty($input['name'])) {
		$input['name'] = $input['title'];
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	// convert html to entities
	//$input['content'] = e($input['content']);

	$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

	Page::update($id, $input);

	Extend::process('page', $id);

	Notify::success(__('pages.page_success_updated'));

	return Response::redirect('admin/pages/edit/' . $id);
}));

/*
	Add Page
*/
Route::get('admin/pages/add', array('before' => 'auth', 'main' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['pages'] = Page::dropdown(array('exclude' => array(), 'show_empty_option' => true));
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page');

	return View::create('pages/add', $vars)
		->partial('header', 'partials/header')
		->partial('footer', 'partials/footer')
		->partial('editor', 'partials/editor');
}));

Route::post('admin/pages/add', array('before' => 'auth', 'main' => function() {
	$input = Input::get(array('parent', 'name', 'title', 'slug', 'content', 'status', 'redirect', 'show_in_menu'));

	$validator = new Validator($input);

	$validator->check('title')
		->is_max(3, __('pages.missing_title', ''));

	if($input['redirect']) {
		$validator->check('redirect')
			->is_url( __('pages.missing_redirect', 'Please enter a valid url'));
	}

	if($errors = $validator->errors()) {
		Input::flash();

		Notify::error($errors);

		return Response::redirect('admin/pages/add');
	}

	if(empty($input['name'])) {
		$input['name'] = $input['title'];
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = slug($input['slug']);

	// convert html to entities
	//$input['content'] = e($input['content']);

	$input['show_in_menu'] = is_null($input['show_in_menu']) ? 0 : 1;

	$page = Page::create($input);

	Extend::process('page', $page->id);

	Notify::success(__('pages.page_success_created'));

	return Response::redirect('admin/pages');
}));

/*
	Delete Page
*/
Route::get('admin/pages/delete/(:num)', array('before' => 'auth', 'main' => function($id) {
	Page::find($id)->delete();

	Notify::success(__('pages.page_success_delete'));

	return Response::redirect('admin/pages');
}));