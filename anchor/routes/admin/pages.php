<?php

/*
	List all
*/
Route::get(array('admin/pages', 'admin/pages/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['pages'] = Page::paginate($page, Config::get('meta.posts_per_page'));

	return View::make('pages/index', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

/*
	Edit
*/
Route::get('admin/pages/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['page'] = Page::find($id);
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page', $id);

	return View::make('pages/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/pages/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('name', 'title', 'slug', 'content', 'status', 'redirect'));

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

	$input['slug'] = Str::slug($input['slug']);

	Page::update($id, $input);

	Extend::process('page', $id);

	Notify::success(__('pages.page_success_updated'));

	return Response::redirect('admin/pages/edit/' . $id);
}));

/*
	Add
*/
Route::get('admin/pages/add', array('before' => 'auth', 'do' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['statuses'] = array(
		'published' => __('pages.published'),
		'draft' => __('pages.draft'),
		'archived' => __('pages.archived')
	);

	// extended fields
	$vars['fields'] = Extend::fields('page');

	return View::make('pages/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/pages/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('name', 'title', 'slug', 'content', 'status', 'redirect'));

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

	$input['slug'] = Str::slug($input['slug']);

	$id = Page::create($input);

	Extend::process('page', $id);

	Notify::success(__('pages.page_success_created'));

	return Response::redirect('admin/pages');
}));

/*
	Delete
*/
Route::get('admin/pages/delete/(:num)', array('before' => 'auth', 'do' => function($id) {
	Page::find($id)->delete();

	Notify::success(__('pages.page_success_delete'));

	return Response::redirect('admin/pages');
}));