<?php

/*
	List all
*/
Route::get(array('admin/pages', 'admin/pages/(:num)'), array('before' => 'auth', 'do' => function($page = 1) {
	$vars['messages'] = Notify::read();
	$vars['pages'] = Page::paginate($page);

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
	$vars['statuses'] = array('draft' => __('pages.draft'), 'archived' => __('pages.archived'), 'published' => __('pages.published'));
	$vars['templates'] = array('page' => 'Page');

	return View::make('pages/edit', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/pages/edit/(:num)', array('before' => 'auth', 'do' => function($id) {
	$input = Input::get_array(array('name', 'title', 'slug', 'content', 'status'));

	$validator = new Validator($input);

	$validator->check('name')
		->is_max(3, __('pages.missing_name'));

	$validator->check('title')
		->is_max(3, __('pages.missing_title'));

	if($errors = $validator->errors()) {
		Input::flash();
		
		Notify::error($errors);

		return Response::redirect('admin/pages/edit/' . $id);
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	Page::update($id, $input);

	Notify::success(__('pages.page_success_updated'));

	return Response::redirect('admin/pages/edit/' . $id);
}));

/*
	Add
*/
Route::get('admin/pages/add', array('before' => 'auth', 'do' => function() {
	$vars['messages'] = Notify::read();
	$vars['token'] = Csrf::token();
	$vars['statuses'] = array('draft' => __('pages.draft'), 'archived' => __('pages.archived'), 'published' => __('pages.published'));
	$vars['templates'] = array('page' => 'Page');

	return View::make('pages/add', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');
}));

Route::post('admin/pages/add', array('before' => 'auth', 'do' => function() {
	$input = Input::get_array(array('name', 'title', 'slug', 'content', 'status'));

	$validator = new Validator($input);

	$validator->check('name')
		->is_max(3, __('pages.missing_name'));

	$validator->check('title')
		->is_max(3, __('pages.missing_title'));

	if($errors = $validator->errors()) {
		Input::flash();
		
		Notify::error($errors);

		return Response::redirect('admin/pages/add');
	}

	if(empty($input['slug'])) {
		$input['slug'] = $input['title'];
	}

	$input['slug'] = Str::slug($input['slug']);

	Page::create($input);

	Notify::success(__('pages.page_success_created'));

	return Response::redirect('admin/pages');
}));
